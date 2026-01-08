<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::with('category')->orderBy('item_name')->get();
        $categories = Category::all();
        return view('admin.menu.index', compact('menuItems', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.menu.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'unit_of_measurement' => 'required|string|max:50',
        ]);

        // Generate item code
        $itemCode = 'FH' . Str::upper(Str::random(6));

        $menuItem = MenuItem::create([
            'item_code' => $itemCode,
            'item_name' => $request->item_name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'cost' => $request->cost,
            'stock_quantity' => $request->stock_quantity,
            'reorder_level' => $request->reorder_level,
            'unit_of_measurement' => $request->unit_of_measurement,
            'status' => $request->stock_quantity > 0 ? 'available' : 'out_of_stock',
        ]);

        // Log initial inventory
        if ($request->stock_quantity > 0) {
            $menuItem->inventoryLogs()->create([
                'transaction_type' => 'in',
                'quantity' => $request->stock_quantity,
                'previous_stock' => 0,
                'new_stock' => $request->stock_quantity,
                'user_id' => auth()->id(),
                'reason' => 'Initial stock'
            ]);
        }

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu item created successfully.');
    }

    public function edit(MenuItem $menuItem)
    {
        $categories = Category::all();
        return view('admin.menu.edit', compact('menuItem', 'categories'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'reorder_level' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'unit_of_measurement' => 'required|string|max:50',
            'status' => 'required|in:available,out_of_stock,discontinued',
        ]);

        $menuItem->update($request->all());

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu item updated successfully.');
    }

    public function updateStock(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'transaction_type' => 'required|in:in,out,adjustment,waste,damaged',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:500',
        ]);

        $menuItem->updateStock(
            $request->quantity,
            $request->transaction_type,
            $request->reason,
            auth()->id()
        );

        return back()->with('success', 'Stock updated successfully.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();
        
        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu item deleted successfully.');
    }
}