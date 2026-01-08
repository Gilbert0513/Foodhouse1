<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'cashier_id',
        'order_type',
        'table_number',
        'pax',
        'special_instructions',
        'status',
        'payment_status',
        
        // CORRECT COLUMN NAMES (from your database)
        'total_amount',     // This is the subtotal
        'tax_amount',       // This is the tax
        'discount_amount',  // Discount if any
        'grand_total',      // This is the total
        
        'paid_at',
        'order_time',
        'preparation_time',
        'ready_time',
        'served_time',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_at' => 'datetime',
        'order_time' => 'datetime',
        'preparation_time' => 'datetime',
        'ready_time' => 'datetime',
        'served_time' => 'datetime',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Status methods
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

    public function markAsCancelled()
    {
        $this->update(['status' => 'cancelled']);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['confirmed', 'preparing', 'ready']);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopePendingPayment($query)
    {
        return $query->where('payment_status', 'pending');
    }

    // Methods
    public function calculateTotals()
    {
        $subtotal = $this->items->sum('total');
        $tax = $subtotal * 0.12; // 12% tax
        $total = $subtotal + $tax;
        
        $this->update([
            'total_amount' => $subtotal,    // Store as total_amount
            'tax_amount' => $tax,           // Store as tax_amount
            'grand_total' => $total,        // Store as grand_total
        ]);
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function getPaymentMethodAttribute()
    {
        return $this->payments->first()->payment_method ?? null;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'preparing' => 'primary',
            'ready' => 'success',
            'served' => 'secondary',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            'pending' => 'warning',
            'partial' => 'info',
            'paid' => 'success',
            'refunded' => 'danger',
            default => 'secondary',
        };
    }

    // ACCESSORS - To make old code still work
    // These map the old names to the new column names
    
    // Get subtotal (maps to total_amount)
    public function getSubtotalAttribute()
    {
        return $this->total_amount;
    }

    // Set subtotal (maps to total_amount)
    public function setSubtotalAttribute($value)
    {
        $this->attributes['total_amount'] = $value;
    }

    // Get tax (maps to tax_amount)
    public function getTaxAttribute()
    {
        return $this->tax_amount;
    }

    // Set tax (maps to tax_amount)
    public function setTaxAttribute($value)
    {
        $this->attributes['tax_amount'] = $value;
    }

    // Get total (maps to grand_total)
    public function getTotalAttribute()
    {
        return $this->grand_total;
    }

    // Set total (maps to grand_total)
    public function setTotalAttribute($value)
    {
        $this->attributes['grand_total'] = $value;
    }

    // Add these to $appends if you want them available in JSON
    protected $appends = ['subtotal', 'tax', 'total'];
}