<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_global',
        'user_id',
        'per_transaction_limit',
        'daily_limit',
        'monthly_limit',
        'currency',
    ];
}

