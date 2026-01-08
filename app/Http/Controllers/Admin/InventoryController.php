<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\MenuItem;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventory items.
     */
    public function index(Request $request)
    {
        $query = Ingredient::with('menuItems');
        
        // Search
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter low stock
        if ($request->has('low_stock') && $request->low_stock) {
            $query->where('current_stock', '<=', DB::raw('minimum_stock'));
        }
        
        $ingredients = $query->orderBy('name')->paginate(20);
        
        // Stats
        $stats = [
            'total_items' => Ingredient::count(),
            'in_stock' => Ingredient::where('status', 'in_stock')->count(),
            'low_stock' => Ingredient::where('status', 'low_stock')->count(),
            'out_of_stock' => Ingredient::where('status', 'out_of_stock')->count(),
            'total_inventory_value' => Ingredient::sum(DB::raw('current_stock * cost_per_unit')),
        ];
        
        return view('admin.inventory.index', compact('ingredients', 'stats'));
    }

    /**
     * Show the form for creating a new ingredient.
     */
    public function create()
    {
        $units = ['kg', 'g', 'l', 'ml', 'piece', 'pack', 'bottle', 'can', 'box'];
        return view('admin.inventory.create', compact('units'));
    }

    /**
     * Store a newly created ingredient in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:20',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'cost_per_unit' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $ingredient = Ingredient::create($validated);
        $ingredient->updateStatus();
        
        return redirect()->route('admin.inventory.index')
            ->with('success', 'Ingredient added successfully!');
    }

    /**
     * Display the specified ingredient.
     */
    public function show(Ingredient $ingredient)
    {
        $logs = $ingredient->logs()->with('order', 'user')->latest()->paginate(10);
        $menuItems = $ingredient->menuItems()->paginate(10);
        
        return view('admin.inventory.show', compact('ingredient', 'logs', 'menuItems'));
    }

    /**
     * Show the form for editing the specified ingredient.
     */
    public function edit(Ingredient $ingredient)
    {
        $units = ['kg', 'g', 'l', 'ml', 'piece', 'pack', 'bottle', 'can', 'box'];
        return view('admin.inventory.edit', compact('ingredient', 'units'));
    }

    /**
     * Update the specified ingredient in storage.
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:20',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'cost_per_unit' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $ingredient->update($validated);
        $ingredient->updateStatus();
        
        return redirect()->route('admin.inventory.index')
            ->with('success', 'Ingredient updated successfully!');
    }

    /**
     * Remove the specified ingredient from storage.
     */
    public function destroy(Ingredient $ingredient)
    {
        if ($ingredient->menuItems()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete ingredient that is used in menu items!');
        }
        
        $ingredient->delete();
        
        return redirect()->route('admin.inventory.index')
            ->with('success', 'Ingredient deleted successfully!');
    }

    /**
     * Show stock adjustment form.
     */
    public function showAdjustStock(Ingredient $ingredient)
    {
        return view('admin.inventory.adjust-stock', compact('ingredient'));
    }

    /**
     * Adjust stock level.
     */
    public function adjustStock(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'adjustment_type' => 'required|in:add,deduct,set',
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);
        
        $quantity = $request->quantity;
        $notes = $request->notes;
        
        switch ($request->adjustment_type) {
            case 'add':
                $ingredient->addStock($quantity, $notes);
                $message = "Stock added successfully!";
                break;
                
            case 'deduct':
                if (!$ingredient->isAvailable($quantity)) {
                    return redirect()->back()
                        ->with('error', 'Insufficient stock to deduct!');
                }
                $ingredient->deductStock($quantity, null, null, $notes);
                $message = "Stock deducted successfully!";
                break;
                
            case 'set':
                $difference = $quantity - $ingredient->current_stock;
                if ($difference > 0) {
                    $ingredient->addStock($difference, $notes);
                } elseif ($difference < 0) {
                    $ingredient->deductStock(abs($difference), null, null, $notes);
                }
                $message = "Stock level set successfully!";
                break;
        }
        
        return redirect()->route('admin.inventory.show', $ingredient)
            ->with('success', $message);
    }

    /**
     * Show menu items that use this ingredient.
     */
    public function menuItems(Ingredient $ingredient)
    {
        $menuItems = $ingredient->menuItems()->paginate(20);
        return view('admin.inventory.menu-items', compact('ingredient', 'menuItems'));
    }

    /**
     * Show inventory logs.
     */
    public function logs(Request $request)
    {
        $query = InventoryLog::with(['ingredient', 'user', 'order']);
        
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('ingredient_id') && $request->ingredient_id) {
            $query->where('ingredient_id', $request->ingredient_id);
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $logs = $query->latest()->paginate(50);
        $ingredients = Ingredient::orderBy('name')->get();
        
        return view('admin.inventory.logs', compact('logs', 'ingredients'));
    }
}