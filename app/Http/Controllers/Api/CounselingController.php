<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Counseling;
use App\Models\Invoice;
use App\Models\Result;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CounselingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    public function getMeetInfo(String $id)
    {
        $counseling = Counseling::where('id', $id)->first();
        $meetURL = $counseling->meet_url;
        $psychologist = User::where('id', $counseling->psychologist_id)->first();

        return $this->success([
            'meet_url' => $meetURL,
            'psychologist_name' => $psychologist->fullname,
        ], 'Meet Info successfully retrieved');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $counselingAmount = 300000;

        // this should be meet API, temporary solution.
        $roomID = uniqid();

        // Construct the Google Meet URL
        $meetURL = "https://meet.google.com/{$roomID}";


        $invoice = Invoice::create([
            'amount' => $counselingAmount,
            'qr_code' => Str::random(32),
        ]);

        $result = Result::create();

        $createdAt = time();

        $counseling = Counseling::create([
            'user_id' => Auth::user()->id,
            'invoice_id' => $invoice->id,
            'result_id' => $result->id,
            'created_at' => $createdAt,
            'meet_url' => $meetURL,
        ]);

        if (!$counseling)
            return $this->error([], 'Failed to create counseling data', 500);

        return $this->success([
            'id' => $counseling->id,
        ], 'Counseling created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        //
    }
}
