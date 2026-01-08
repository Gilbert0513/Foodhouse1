<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'unit',
        'current_stock',
        'minimum_stock',
        'cost_per_unit',
        'supplier',
        'description',
        'status'
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'cost_per_unit' => 'decimal:2',
    ];

    // Relationships
    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_ingredients')
                    ->withPivot('quantity_required', 'unit')
                    ->withTimestamps();
    }

    public function logs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    // Methods
    public function updateStatus()
    {
        if ($this->current_stock <= 0) {
            $this->status = 'out_of_stock';
        } elseif ($this->current_stock <= $this->minimum_stock) {
            $this->status = 'low_stock';
        } else {
            $this->status = 'in_stock';
        }
        
        $this->save();
    }

    public function isAvailable($quantity)
    {
        return $this->current_stock >= $quantity;
    }

    public function deductStock($quantity, $orderId = null, $userId = null, $notes = null)
    {
        $previousStock = $this->current_stock;
        $this->current_stock -= $quantity;
        $this->save();
        
        // Log the deduction
        InventoryLog::create([
            'ingredient_id' => $this->id,
            'type' => 'usage',
            'quantity' => $quantity,
            'unit' => $this->unit,
            'previous_stock' => $previousStock,
            'new_stock' => $this->current_stock,
            'notes' => $notes,
            'order_id' => $orderId,
            'user_id' => $userId ?? auth()->id(),
        ]);
        
        $this->updateStatus();
    }

    public function addStock($quantity, $notes = null, $userId = null)
    {
        $previousStock = $this->current_stock;
        $this->current_stock += $quantity;
        $this->save();
        
        // Log the addition
        InventoryLog::create([
            'ingredient_id' => $this->id,
            'type' => 'purchase',
            'quantity' => $quantity,
            'unit' => $this->unit,
            'previous_stock' => $previousStock,
            'new_stock' => $this->current_stock,
            'notes' => $notes,
            'user_id' => $userId ?? auth()->id(),
        ]);
        
        $this->updateStatus();
    }
}