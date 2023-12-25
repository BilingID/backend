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
        'meet_date', 
        'meet_time', 
        'meet_url', 
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function result()
    {
        return $this->belongsTo(Result::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function psychologist()
    {
        return $this->belongsTo(User::class, 'psikolog_id');
    }

    
}
