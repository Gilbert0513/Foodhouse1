<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'image',
        'is_available',
        'track_inventory',
        'inventory_status',
        'preparation_time'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'track_inventory' => 'boolean',
        'preparation_time' => 'decimal:2',
    ];

    // Relationships
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'menu_ingredients')
                    ->withPivot('quantity_required', 'unit')
                    ->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'menu_item_id');
    }

    // Methods
    public function checkInventoryAvailability($quantity = 1)
    {
        if (!$this->track_inventory) {
            return true;
        }

        foreach ($this->ingredients as $ingredient) {
            $requiredQuantity = $ingredient->pivot->quantity_required * $quantity;
            if (!$ingredient->isAvailable($requiredQuantity)) {
                return false;
            }
        }
        
        return true;
    }

    public function deductIngredients($quantity = 1, $orderId = null)
    {
        if (!$this->track_inventory) {
            return true;
        }

        foreach ($this->ingredients as $ingredient) {
            $requiredQuantity = $ingredient->pivot->quantity_required * $quantity;
            $ingredient->deductStock(
                $requiredQuantity, 
                $orderId, 
                auth()->id(), 
                "Deducted for menu item: {$this->name} (x{$quantity})"
            );
        }
        
        $this->updateInventoryStatus();
        
        return true;
    }

    public function updateInventoryStatus()
    {
        if (!$this->track_inventory) {
            return;
        }

        $status = 'in_stock';
        foreach ($this->ingredients as $ingredient) {
            if ($ingredient->status === 'out_of_stock') {
                $status = 'out_of_stock';
                break;
            } elseif ($ingredient->status === 'low_stock') {
                $status = 'low_stock';
            }
        }
        
        $this->inventory_status = $status;
        $this->save();
    }

    public function getIngredientCost()
    {
        $totalCost = 0;
        foreach ($this->ingredients as $ingredient) {
            $totalCost += $ingredient->pivot->quantity_required * $ingredient->cost_per_unit;
        }
        return $totalCost;
    }

    // Helper methods for statistics
    public function getTotalSoldAttribute()
    {
        return $this->orderItems()->sum('quantity');
    }

    public function getTotalRevenueAttribute()
    {
        return $this->orderItems()->sum(DB::raw('quantity * price'));
    }

    // Scope for popular items
    public function scopePopular($query, $days = 7)
    {
        return $query->withCount(['orderItems as total_sold' => function($query) use ($days) {
            $query->whereHas('order', function($q) use ($days) {
                $q->where('created_at', '>=', now()->subDays($days))
                  ->where('payment_status', 'paid');
            });
        }])->orderBy('total_sold', 'desc');
    }
}