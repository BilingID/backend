<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Psychotest;
use App\Models\Invoice;
use App\Http\Requests\UpdatePsychotestRequest;
use App\Models\Answer;
use App\Models\Result;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Events\PaymentTest;
use App\Models\Choice;
use App\Models\QuestionBank;
use Carbon\Carbon;
use Google\Service\CloudSourceRepositories\Repo;

class PsychotestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $psychotests = DB::table('psychotests')
                        ->join('invoices', 'psychotests.invoice_id', '=', 'invoices.id')
                        ->join('results', 'psychotests.result_id', '=', 'results.id')
                        ->where('psychotests.user_id', Auth::user()->id)
                        ->where('invoices.status', 'paid')
                        ->select('psychotests.code', 'psychotests.attempt_date', 'invoices.status',  'invoices.qr_code', 'results.status', 'results.attachment_path', 'results.updated_at')
                        ->get();

        return $this->success($psychotests, 'Psychotests retrieved successfully');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getResult(string $code)
    {
        $result = Psychotest::with('result')->where('code', $code)->first()->result;

        if (!$result)
            return $this->error([], 'Psychotest not found', 404);

        if ($result->status == 'wait')
            return $this->error([], 'Psychotest result not ready', 400);
        
        return $this->success([
            'personality' => $result->personality,
            'attachment_path' => $result->attachment_path,
        ], 'Psychotest result retrieved successfully');
    }


    public function updateResult(Request $request, string $code)
    {
        $request->validate([
            'personality' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $result = Psychotest::with('result')->where('code', $code)->first()->result;
        if (!$result)
            return $this->error([], 'Psychotest not found', 404);
        
        if ($request->personality)
            $result->personality = $request->personality;
    
        if ($request->attachment !== null) {
            $result->attachment_path = '/storage/' . $request->attachment->store('attachments', 'public');
        }

        $result->status = 'ready';
        $result->save();

        return $this->success([], 'Psychotest result updated successfully');
    }

    public function getQuestions(string $code) {
        $psychotest = Psychotest::where('code', $code)->first();

        if (!$psychotest)
            return $this->error([], 'Psychotest not found', 404);

        if ($psychotest->answer_id !== null)
            return $this->error([], 'Psychotest answers already stored', 400);

        $questions = QuestionBank::with('choices')
            ->limit(30)
            ->get(['id', 'question_text']);

        $transformedQuestions = $questions->map(function ($question) {
            return [
                'id' => $question->id,
                'question_text' => $question->question_text,
                'choices' => $question->choices->map(function ($choice) {
                    return [
                        'id' => $choice->id,
                        'text' => $choice->choice_text,
                    ];
                }),
            ];
        });

        return $this->success($transformedQuestions, 'Psychotest questions retrieved successfully');
    }

    public function storeAnswer(Request $request, string $code)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*.question_bank_id' => 'required|integer',
            'answers.*.choice_id' => 'required|integer',
        ]);

        $answers = $request->answers;

        $psychotest = Psychotest::where('code', $code)->first();

        if (!$psychotest)
            return $this->error([], 'Psychotest not found', 404);

        if ($psychotest->answer_id !== null)
            return $this->error([], 'Psychotest answers already stored', 400);

        $answerUuid = Str::uuid();
        
        for ($i = 0; $i < count($answers); $i++) {
            $answers[$i]['group_id'] = $answerUuid;
        }

        Answer::insert($answers);
        
        $psychotest->answer_id = $answerUuid;
        $psychotest->attempt_date = now('Asia/Jakarta');

        $psychotest->save();
        
        return $this->success([], 'Psychotest answers stored successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: 
        $psychotestAmount = 50000;
        
        $invoice = Invoice::create([
            'amount' => $psychotestAmount,
            'qr_code' => Str::random(32),
        ]);
        $result = Result::create();
        
        $psychotest = Psychotest::create([
            'user_id' => Auth::user()->id,
            'invoice_id' => $invoice->id,
            'result_id' => $result->id,
        ]);
        
        if (!$psychotest)
            return $this->error([], 'Failed to create psychotest', 500);

        return $this->success([
            'code' => $psychotest->code,
        ], 'Psychotest created successfully');
    }

    public function processPayment(string $code)
    {
        $psychotest = Psychotest::with('invoice')->with('result')->where('code', $code)->first();
        
        $invoice = $psychotest->invoice;
        $result = $psychotest->result;

        if (!$invoice)
            return $this->error([], 'Psychotest not found', 404);

        if ($invoice->status === 'expired') 
            return $this->error([], 'Psychotest payment expired', 400);

        if ($invoice->status === 'paid')
            return $this->error([], 'Psychotest already processed', 400);
        
        date_default_timezone_set("Asia/Jakarta");

        $currentDatetime = date('Y-m-d H:i:s');
        $paymentDatetime = date('Y-m-d H:i:s', strtotime($invoice->created_at . ' + 15 minutes'));

        if ($currentDatetime > $paymentDatetime) {
            $invoice->status = 'expired';
            $invoice->save();
            
            return $this->error([], 'Psychotest payment expired', 400);
        }

        $invoice->status = 'paid';
        $invoice->save();

        $result->status = 'wait';
        $result->save();

        // event(new PaymentTest($code));

        return $this->success([], 'Psychotest payment successfully processed');
    }

    public function getPayment(string $code)
    {
        $session = Auth::user();
        $invoice = Psychotest::with('invoice')
                        ->where('code', $code)
                        ->where('user_id', $session->id)
                        ->first()
                        ->invoice;

        if (!$invoice)
            return $this->error([], 'Psychotest not found', 404);

        date_default_timezone_set("Asia/Jakarta");

        $now = date('Y-m-d H:i:s');
        $countdown = strtotime($now) - strtotime($invoice->created_at);
        
        return $this->success([
            'status' => $invoice->status,
            'amount' => $invoice->amount,
            'payment_code' => $invoice->qr_code,
            'countdown' => $countdown,
        ], 'Psychotest status retrieved successfully');
    }
 
}