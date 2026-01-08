<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'full_name',
        'phone_number',
        'address',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function cashierOrders()
    {
        return $this->hasMany(Order::class, 'cashier_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'cashier_id');
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    // Scopes for 3 Actors
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeCashiers($query)
    {
        return $query->where('role', 'cashier');
    }

    public function scopeCustomers($query)
    {
        return $query->where('role', 'customer');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Check roles
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCashier()
    {
        return $this->role === 'cashier';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }
}