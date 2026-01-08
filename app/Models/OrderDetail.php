<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'menu_item_id',
        'quantity',
        'unit_price',
        'subtotal',
        'special_instructions',
        'status'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function markAsPreparing()
    {
        $this->update(['status' => 'preparing']);
    }

    public function markAsReady()
    {
        $this->update(['status' => 'ready']);
    }

    public function markAsServed()
    {
        $this->update(['status' => 'served']);
    }
}