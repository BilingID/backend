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

class PsychotestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
        $invoice = Psychotest::with('invoice')->where('code', $code)->first()->invoice;

        if (!$invoice)
            return $this->error([], 'Psychotest not found', 404);

        if ($invoice->status == 'paid')
            return $this->error([], 'Psychotest already processed', 400);

        $invoice->status = 'paid';
        $invoice->save();

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

        return $this->success([
            'status' => $invoice->status,
            'amount' => $invoice->amount,
            'payment_code' => $invoice->qr_code,
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