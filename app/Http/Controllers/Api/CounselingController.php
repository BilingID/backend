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
use Illuminate\Support\Facades\Log;

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

    private function generateUniqueID($length = 3)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';

        $randomString = '';

        // Append random letters
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Append a hyphen
        $randomString .= '-';

        // Append random letters
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Append a hyphen
        $randomString .= '-';

        // Append random letters
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }


    public function store(Request $request)
    {

        // Validate the incoming request data
        $request->validate([
            // Add validation rules for your request data
        ]);

        // Check if the user is authenticated
        if (!Auth::check()) {
            return $this->error([], 'User not authenticated', 401);
        }

        $counselingAmount = 300000;
        $roomID = $this->generateUniqueID();
        $meetURL = "https://meet.google.com/{$roomID}";

        // Create an Invoice
        $invoice = Invoice::create([
            'amount' => $counselingAmount,
            'qr_code' => Str::random(32),
        ]);


        // Create a Result
        $result = Result::create();

        // Create Counseling
        $createdAt = now(); // Use Laravel's now() function to get the current timestamp
        $counseling = Counseling::create([
            'user_id' => Auth::user()->id,
            'invoice_id' => $invoice->id,
            'result_id' => $result->id,
            'psikolog_id' => $request->psychologist_id,
            'created_at' => $createdAt,
            'meet_url' => $meetURL,
        ]);

        if (!$counseling) {
            // Log the error for troubleshooting
            Log::error('Failed to create counseling data');
            return $this->error([], 'Failed to create counseling data', 500);
        }

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
