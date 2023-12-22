<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counseling extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_id',
        'result_id',
        'psikolog_id',
        'created_at',
        'updated_at',
        'meet_url',
        // Add other fields as needed
    ];

    // Rest of your model code...
}
