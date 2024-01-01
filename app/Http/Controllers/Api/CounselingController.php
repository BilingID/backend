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
        $counselings = Counseling::with('psychologist')
            ->with('result')
            ->with('user')
            ->where(function ($query) {
                $userId = Auth::user()->id;
                $query->where('user_id', $userId)
                    ->orWhere('psikolog_id', $userId);
            })
            ->whereHas('invoice', function ($query) {
                $query->where('status', 'paid');
            })
            ->orderByDesc('created_at')
            ->get();

        return $this->success($counselings, 'Counseling data retrieved successfully');
    }

    public function getMeetInfo(String $id)
    {
        $counseling = Counseling::where('id', $id)->first();
        $meetURL = $counseling->meet_url;
        $psychologist = User::where('id', $counseling->psikolog_id)->first();
        $user = User::where('id', $counseling->user_id)->first();

        return $this->success([
            'meet_url' => $meetURL,
            'psychologist_name' => $psychologist->fullname,
            'user_name' => $user->fullname,
        ], 'Meet Info successfully retrieved');
    }

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
            'psychologist_id' => 'required|exists:users,id',
            'meet_date' => 'required|date',
            'meet_time' => 'required|date_format:H:i',
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
        // $createdAt = now(); // Use Laravel's now() function to get the current timestamp
        // $updatedAt = now(); // Use Laravel's now() function to get the current timestamp
        $counseling = Counseling::create([
            'user_id' => Auth::user()->id,
            'invoice_id' => $invoice->id,
            'result_id' => $result->id,
            'psikolog_id' => $request->psychologist_id,
            'meet_date' => $request->meet_date,
            'meet_time' => $request->meet_time,
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

    public function updateStatus(String $id)
    {
        $counseling = Counseling::with('result')->where('id', $id)->first();

        $counseling->result->status = 'finish';

        if (!$counseling->result->save())
            return $this->error([], "Update status failed.");

        return $this->success($counseling->result, "Update status successfully");
    }

    public function getResult($id) {
        $counseling = Counseling::with('result')->where('id', $id)->first();

        if (!$counseling)
            return $this->error([], "Counseling not found.");

        return $this->success($counseling->result, "Get result successfully");
    }

 
    public function update(Request $request)
    {
        $counseling = Counseling::where('id', $request->id)->first();

        $counseling->updated_at = now();
        $result = Result::where('id', $counseling->result_id)->first();
        
        if ($request->file !== null) {
            $result->attachment_path = config("app.url") . 'storage/' . $request->file->store('pdf', 'public');
            $result->updated_at = now();
            $result->status = 'finish';
        }

        if (!$result->save())
            return $this->error([], "Result upload failed.");

        return $this->success($result, "Result uploaded successfully.");
    }

    public function processPayment(string $id)
    {
        $counseling = Counseling::with('invoice')->with('result')->where('id', $id)->first();

        $invoice = $counseling->invoice;
        $result = $counseling->result;

        if (!$invoice)
            return $this->error([], 'Counseling not found', 404);

        if ($invoice->status === 'expired')
            return $this->error([], 'Counseling payment expired', 400);

        if ($invoice->status === 'paid')
            return $this->error([], 'Counseling already processed', 400);

        date_default_timezone_set("Asia/Jakarta");

        $currentDatetime = date('Y-m-d H:i:s');
        $paymentDatetime = date('Y-m-d H:i:s', strtotime($invoice->created_at . ' + 15 minutes'));

        if ($currentDatetime > $paymentDatetime) {
            $invoice->status = 'expired';
            $invoice->save();

            return $this->error([], 'Counseling payment expired', 400);
        }

        $invoice->status = 'paid';
        $invoice->save();

        $result->status = 'wait';
        $result->save();
        
        return $this->success([], 'Counseling payment successfully processed');
    }

    public function getPayment(string $id)
    {
        $session = Auth::user();
        $invoice = Counseling::with('invoice')
            ->where('id', $id)
            ->where('user_id', $session->id)
            ->first()
            ->invoice;

        if (!$invoice)
            return $this->error([], 'Counseling not found', 404);

        date_default_timezone_set("Asia/Jakarta");

        $now = date('Y-m-d H:i:s');
        $countdown = strtotime($now) - strtotime($invoice->created_at);

        return $this->success([
            'status' => $invoice->status,
            'amount' => $invoice->amount,
            'payment_code' => $invoice->qr_code,
            'countdown' => $countdown,
        ], 'Counseling status retrieved successfully');
    }
}
