<?php

namespace App\Services;

use App\Models\MenuItem;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function updateStock(MenuItem $menuItem, $data, $userId)
    {
        return DB::transaction(function () use ($menuItem, $data, $userId) {
            $previousStock = $menuItem->stock_quantity;
            $quantity = $data['quantity'];
            $type = $data['transaction_type'];
            $reason = $data['reason'] ?? 'Stock adjustment';

            switch ($type) {
                case 'in':
                    $newStock = $previousStock + $quantity;
                    break;
                case 'out':
                    if ($quantity > $previousStock) {
                        throw new \Exception("Insufficient stock. Available: {$previousStock}, Requested: {$quantity}");
                    }
                    $newStock = $previousStock - $quantity;
                    break;
                case 'adjustment':
                    $newStock = $quantity; // Direct set
                    break;
                case 'waste':
                case 'damaged':
                    if ($quantity > $previousStock) {
                        throw new \Exception("Cannot waste/damage more than available stock");
                    }
                    $newStock = $previousStock - $quantity;
                    break;
                default:
                    throw new \Exception("Invalid transaction type");
            }

            // Update stock
            $menuItem->update(['stock_quantity' => $newStock]);

            // Update status
            if ($newStock <= 0) {
                $menuItem->update(['status' => 'out_of_stock']);
            } elseif ($menuItem->status === 'out_of_stock' && $newStock > 0) {
                $menuItem->update(['status' => 'available']);
            }

            // Log transaction
            InventoryLog::create([
                'menu_item_id' => $menuItem->id,
                'transaction_type' => $type,
                'quantity' => $type === 'adjustment' ? abs($newStock - $previousStock) : $quantity,
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'user_id' => $userId,
                'reason' => $reason,
                'reference_number' => $data['reference_number'] ?? null,
            ]);

            return [
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'difference' => $newStock - $previousStock,
            ];
        });
    }

    public function checkLowStock()
    {
        return MenuItem::whereColumn('stock_quantity', '<=', 'reorder_level')
            ->where('status', 'available')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->item_name,
                    'current_stock' => $item->stock_quantity,
                    'reorder_level' => $item->reorder_level,
                    'category' => $item->category->name,
                    'status' => 'critical'
                ];
            });
    }

    public function getStockHistory(MenuItem $menuItem, $period = '30days')
    {
        $query = InventoryLog::where('menu_item_id', $menuItem->id)
            ->orderBy('created_at', 'desc');

        switch ($period) {
            case '7days':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case '30days':
                $query->where('created_at', '>=', now()->subDays(30));
                break;
            case '90days':
                $query->where('created_at', '>=', now()->subDays(90));
                break;
        }

        return $query->get();
    }

    public function generateInventoryReport($filters = [])
    {
        $query = MenuItem::with('category');

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['low_stock'])) {
            $query->whereColumn('stock_quantity', '<=', 'reorder_level');
        }

        $items = $query->get();

        $report = [
            'summary' => [
                'total_items' => $items->count(),
                'total_value' => $items->sum(function($item) {
                    return $item->stock_quantity * ($item->cost ?? 0);
                }),
                'low_stock_count' => $items->where('stock_quantity', '<=', 'reorder_level')->count(),
                'out_of_stock_count' => $items->where('stock_quantity', 0)->count(),
                'critical_items' => $items->where('stock_quantity', '<', 5)->count(),
            ],
            'items' => $items->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->item_name,
                    'category' => $item->category->name,
                    'stock' => $item->stock_quantity,
                    'reorder_level' => $item->reorder_level,
                    'status' => $item->status,
                    'value' => $item->stock_quantity * ($item->cost ?? 0),
                    'last_updated' => $item->updated_at->format('Y-m-d H:i'),
                ];
            })->sortByDesc('value')->values(),
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ];

        return $report;
    }
}