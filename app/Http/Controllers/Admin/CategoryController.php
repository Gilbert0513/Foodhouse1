<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('menuItems')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string|max:500',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

// Show edit form
public function edit(Category $category)
{
    // Calculate total items sold in this category (optional)
    $totalItemsSold = 0;
    if ($category->menuItems->count() > 0) {
        $menuItemIds = $category->menuItems->pluck('id');
        $totalItemsSold = \App\Models\OrderItem::whereIn('menu_item_id', $menuItemIds)
            ->sum('quantity');
    }
    
    return view('admin.categories.edit', compact('category', 'totalItemsSold'));
}

// Update category
public function update(Request $request, Category $category)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        'description' => 'nullable|string',
        'status' => 'required|in:active,inactive',
    ]);
    
    $category->update($validated);
    
    return redirect()->route('admin.categories.index')
        ->with('success', 'Category updated successfully!');
}

// Delete category
public function destroy(Category $category)
{
    // Check if category has menu items
    if ($category->menuItems()->exists()) {
        // Option 1: Prevent deletion
        // return redirect()->back()->with('error', 'Cannot delete category with menu items.');
        
        // Option 2: Move items to "Uncategorized" or set category_id to null
        $uncategorized = Category::where('name', 'Uncategorized')->first();
        if ($uncategorized) {
            $category->menuItems()->update(['category_id' => $uncategorized->id]);
        } else {
            $category->menuItems()->update(['category_id' => null]);
        }
    }
    
    $category->delete();
    
    return redirect()->route('admin.categories.index')
        ->with('success', 'Category deleted successfully!');
}
}