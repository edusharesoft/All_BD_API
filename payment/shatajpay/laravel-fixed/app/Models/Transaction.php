<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public const INITIALIZE = 'initialize';
    public const PENDING = 'pending';
    public const COMPLETED = 'completed';
    public const FAILED = 'failed';
    public const AWAITING_VERIFICATION = 'awaiting_verification';

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'method', 'amount', 'currency',
        'status', 'transaction_id', 'order_id', 'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'meta'   => 'array',
    ];
}
