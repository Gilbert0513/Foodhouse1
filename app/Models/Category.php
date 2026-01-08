<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function activeMenuItems()
    {
        return $this->hasMany(MenuItem::class)->where('status', 'available');
    }

    public function getActiveItemsCountAttribute()
    {
        return $this->menuItems()->where('status', 'available')->count();
    }
}