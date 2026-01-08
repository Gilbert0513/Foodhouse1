<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    protected $fillable = [
        'ingredient_id',
        'type',
        'quantity',
        'unit',
        'previous_stock',
        'new_stock',
        'notes',
        'user_id',
        'order_id'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'previous_stock' => 'decimal:2',
        'new_stock' => 'decimal:2',
    ];

    // Relationships
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}