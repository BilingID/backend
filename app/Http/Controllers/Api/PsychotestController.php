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
                        ->select('psychotests.code', 'invoices.status',  'invoices.qr_code', 'results.status', 'results.attachment_path', 'results.updated_at')
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
        $result = Psychotest::with('result')->where('code', $code)->first()->result;
        
        if (!$result)
            return $this->error([], 'Psychotest not found', 404);
        
        $result->personality = $request->personality;
        
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $attachmentName = Str::random(32) . '.' . $attachment->getClientOriginalExtension();
            $attachment->move(public_path('storage/attachments'), $attachmentName);
            $result->attachment_path = $attachmentName;
        }

        $result->status = 'ready';
        $result->save();

        return $this->success([], 'Psychotest result updated successfully');
    }

    public function getQuestions(string $code) {
        $psychotest = Psychotest::where('code', $code)->first();

        if (!$psychotest)
            return $this->error([], 'Psychotest not found', 404);

        $questions = QuestionBank::with('choices')
            ->limit(50)
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

    public function storeAnswer(Request $request)
    {
        //
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
        $answer = Answer::create();
        $result = Result::create();
        
        $psychotest = Psychotest::create([
            'user_id' => Auth::user()->id,
            'invoice_id' => $invoice->id,
            'answer_id' => $answer->id,
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

        if ($invoice->status == 'paid')
            return $this->error([], 'Psychotest already processed', 400);
        
        $currentDatetime = date('Y-m-d H:i:s');
        $paymentDatetime = date('Y-m-d H:i:s', strtotime($invoice->created_at . ' + 15 minutes'));

        if ($currentDatetime > $paymentDatetime)
            return $this->error([], 'Psychotest payment expired', 400);

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

        $expired_at = new Carbon($invoice->created_at, 'Asia/Jakarta');
        $expired_at->addMinutes(15);
        
        return $this->success([
            'status' => $invoice->status,
            'amount' => $invoice->amount,
            'payment_code' => $invoice->qr_code,
            'expired_at' => $expired_at,
        ], 'Psychotest status retrieved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Psychotest $psychotest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Psychotest $psychotest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePsychotestRequest $request, Psychotest $psychotest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Psychotest $psychotest)
    {
        //
    }
}