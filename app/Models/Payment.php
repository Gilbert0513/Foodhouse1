<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'cashier_id',
        'payment_reference',
        'payment_method',
        'amount_paid',
        'change_amount',
        'transaction_id',
        'status',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'change_amount' => 'decimal:2'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function markAsCompleted()
    {
        $this->update(['status' => 'completed']);
    }

    public function markAsFailed()
    {
        $this->update(['status' => 'failed']);
    }

    public function markAsRefunded()
    {
        $this->update(['status' => 'refunded']);
    }

    public function getPaymentMethodTextAttribute()
    {
        $methods = [
            'cash' => 'Cash',
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'gcash' => 'GCash',
            'paymaya' => 'PayMaya',
            'bank_transfer' => 'Bank Transfer',
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }
}