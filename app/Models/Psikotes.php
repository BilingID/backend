<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Psikotes extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'psikotes';
    protected $primaryKey = 'psikotes_code';

    protected $fillable = [
        'attempt_date',
        'user_id',
        'answer_id',
        'invoice_id',
        'result_id',
    ];
}
