@extends('layouts.admin')

@section('title', 'Inventory Management')
@section('page-title', 'Stock Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Inventory</li>
@endsection

@section('content')
<div class="row">
    <!-- Quick Stats -->
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h6>Total Items</h6>
                <h3>{{ $stats['total_items'] }}</h3>
                <small>Ingredients</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h6>In Stock</h6>
                <h3>{{ $stats['in_stock'] }}</h3>
                <small>Available</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h6>Low Stock</h6>
                <h3>{{ $stats['low_stock'] }}</h3>
                <small>Needs Reorder</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h6>Out of Stock</h6>
                <h3>{{ $stats['out_of_stock'] }}</h3>
                <small>Unavailable</small>
            </div>
        </div>
    </div>
</div>

<!-- Suggested Ingredients Card -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-lightbulb me-2"></i>Suggested Ingredients for Your Menu
        </h5>
    </div>
    <div class="card-body">
        <p class="text-muted">Based on your 10 menu items, here are the essential ingredients you should track:</p>
        
        <div class="row">
            <!-- Meat & Poultry -->
            <div class="col-md-3 mb-3">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white py-2">
                        <h6 class="mb-0"><i class="fas fa-drumstick-bite me-2"></i>Meat & Poultry</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-unstyled mb-0 small">
                            <li>✓ Beef (for Beef Steak)</li>
                            <li>✓ Pork (Sinigang, Pork Chop)</li>
                            <li>✓ Chicken (for Adobo)</li>
                            <li>✓ Squid (for Calamares)</li>
                            <li>✓ Ground Beef (for Nachos)</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Vegetables -->
            <div class="col-md-3 mb-3">
                <div class="card border-success">
                    <div class="card-header bg-success text-white py-2">
                        <h6 class="mb-0"><i class="fas fa-carrot me-2"></i>Vegetables</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-unstyled mb-0 small">
                            <li>✓ Onions</li>
                            <li>✓ Garlic</li>
                            <li>✓ Ginger</li>
                            <li>✓ Tomatoes</li>
                            <li>✓ Tamarind (Sinigang)</li>
                            <li>✓ String Beans</li>
                            <li>✓ Eggplant</li>
                            <li>✓ Okra</li>
                            <li>✓ Kangkong</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Dairy & Eggs -->
            <div class="col-md-3 mb-3">
                <div class="card border-info">
                    <div class="card-header bg-info text-white py-2">
                        <h6 class="mb-0"><i class="fas fa-egg me-2"></i>Dairy & Eggs</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-unstyled mb-0 small">
                            <li>✓ Eggs (Leche Flan)</li>
                            <li>✓ Condensed Milk</li>
                            <li>✓ Evaporated Milk</li>
                            <li>✓ Butter</li>
                            <li>✓ Cheese (Nachos)</li>
                            <li>✓ Ice Cream (Halo-Halo)</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Beverages -->
            <div class="col-md-3 mb-3">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-white py-2">
                        <h6 class="mb-0"><i class="fas fa-glass-whiskey me-2"></i>Beverages</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-unstyled mb-0 small">
                            <li>✓ Lemons (Lemonade)</li>
                            <li>✓ Sugar</li>
                            <li>✓ Iced Tea Powder</li>
                            <li>✓ Water</li>
                            <li>✓ Ice</li>
                            <li>✓ Mint Leaves</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Desserts -->
            <div class="col-md-3 mb-3">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white py-2">
                        <h6 class="mb-0"><i class="fas fa-ice-cream me-2"></i>Desserts</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-unstyled mb-0 small">
                            <li>✓ Shaved Ice (Halo-Halo)</li>
                            <li>✓ Sweet Beans</li>
                            <li>✓ Nata de Coco</li>
                            <li>✓ Kaong</li>
                            <li>✓ Sweet Corn</li>
                            <li>✓ Ube Halaya</li>
                            <li>✓ Pinipig</li>
                            <li>✓ Leche Flan</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Snacks & Appetizers -->
            <div class="col-md-3 mb-3">
                <div class="card border-secondary">
                    <div class="card-header bg-secondary text-white py-2">
                        <h6 class="mb-0"><i class="fas fa-cookie me-2"></i>Snacks & Appetizers</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-unstyled mb-0 small">
                            <li>✓ Tortilla Chips</li>
                            <li>✓ Refried Beans</li>
                            <li>✓ Jalapeños</li>
                            <li>✓ Sour Cream</li>
                            <li>✓ Guacamole</li>
                            <li>✓ Bread Crumbs</li>
                            <li>✓ Flour</li>
                            <li>✓ Cooking Oil</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Condiments & Sauces -->
            <div class="col-md-3 mb-3">
                <div class="card border-dark">
                    <div class="card-header bg-dark text-white py-2">
                        <h6 class="mb-0"><i class="fas fa-wine-bottle me-2"></i>Sauces & Condiments</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-unstyled mb-0 small">
                            <li>✓ Soy Sauce</li>
                            <li>✓ Vinegar</li>
                            <li>✓ Fish Sauce</li>
                            <li>✓ Oyster Sauce</li>
                            <li>✓ Calamansi</li>
                            <li>✓ Salsa</li>
                            <li>✓ Tartar Sauce</li>
                            <li>✓ Mayonnaise</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Spices & Herbs -->
            <div class="col-md-3 mb-3">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white py-2">
                        <h6 class="mb-0"><i class="fas fa-mortar-pestle me-2"></i>Spices & Herbs</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-unstyled mb-0 small">
                            <li>✓ Salt</li>
                            <li>✓ Black Pepper</li>
                            <li>✓ Bay Leaves</li>
                            <li>✓ MSG</li>
                            <li>✓ Garlic Powder</li>
                            <li>✓ Onion Powder</li>
                            <li>✓ Paprika</li>
                            <li>✓ Cumin</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-3 text-center">
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Total of 50+ ingredients needed for your 10 menu items. Track them all for better inventory management.
            </small>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Ingredients Inventory</h5>
        <div>
            <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Add Ingredient
            </a>
            <a href="{{ route('admin.inventory.logs') }}" class="btn btn-info">
                <i class="fas fa-history me-2"></i> View Logs
            </a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#quickAddModal">
                <i class="fas fa-bolt me-2"></i> Quick Add
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" class="row mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search ingredients..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                    <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="low_stock" value="1" 
                           id="lowStockCheck" {{ request('low_stock') ? 'checked' : '' }}>
                    <label class="form-check-label" for="lowStockCheck">
                        Low Stock Only
                    </label>
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <!-- Inventory Table -->
        @if($ingredients->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                            <th>Min. Stock</th>
                            <th>Unit Cost</th>
                            <th>Total Value</th>
                            <th>Status</th>
                            <th>Supplier</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ingredients as $ingredient)
                            @php
                                $statusClass = '';
                                if($ingredient->current_stock > $ingredient->minimum_stock) {
                                    $statusClass = 'table-success';
                                } elseif($ingredient->current_stock > 0) {
                                    $statusClass = 'table-warning';
                                } else {
                                    $statusClass = 'table-danger';
                                }
                            @endphp
                            <tr class="{{ $statusClass }}">
                                <td>
                                    <strong>{{ $ingredient->name }}</strong>
                                    @if($ingredient->description)
                                        <small class="d-block text-muted">{{ Str::limit($ingredient->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($ingredient->category)
                                    <span class="badge bg-light text-dark">
                                        {{ ucfirst(str_replace('_', ' ', $ingredient->category)) }}
                                    </span>
                                    @else
                                    <span class="badge bg-secondary">Uncategorized</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $ingredient->current_stock }} {{ $ingredient->unit }}</span>
                                </td>
                                <td>
                                    <small>{{ $ingredient->minimum_stock }} {{ $ingredient->unit }}</small>
                                </td>
                                <td>₱{{ number_format($ingredient->cost_per_unit, 2) }}</td>
                                <td>
                                    <strong>₱{{ number_format($ingredient->current_stock * $ingredient->cost_per_unit, 2) }}</strong>
                                </td>
                                <td>
                                    @if($ingredient->current_stock > $ingredient->minimum_stock)
                                        <span class="badge bg-success">In Stock</span>
                                    @elseif($ingredient->current_stock > 0)
                                        <span class="badge bg-warning">Low Stock</span>
                                        <small class="d-block text-muted">
                                            {{ round(($ingredient->current_stock / $ingredient->minimum_stock) * 100) }}%
                                        </small>
                                    @else
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @endif
                                </td>
                                <td>{{ $ingredient->supplier ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.inventory.show', $ingredient) }}" 
                                           class="btn btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.inventory.edit', $ingredient) }}" 
                                           class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.inventory.adjust-stock', $ingredient) }}" 
                                           class="btn btn-primary" title="Adjust Stock">
                                            <i class="fas fa-exchange-alt"></i>
                                        </a>
                                        <form action="{{ route('admin.inventory.destroy', $ingredient) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Delete this ingredient?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-active">
                            <td colspan="5" class="text-end"><strong>Total Inventory Value:</strong></td>
                            <td><strong>₱{{ number_format($stats['total_inventory_value'], 2) }}</strong></td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $ingredients->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No ingredients found</h5>
                <p class="text-muted">Add your first ingredient to start tracking inventory.</p>
                <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Add First Ingredient
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Low Stock Warning -->
@if($stats['low_stock'] > 0 || $stats['out_of_stock'] > 0)
<div class="alert alert-warning mt-4">
    <h6><i class="fas fa-exclamation-triangle me-2"></i>Inventory Alerts</h6>
    <ul class="mb-0">
        @if($stats['low_stock'] > 0)
            <li>{{ $stats['low_stock'] }} ingredient(s) are running low on stock</li>
        @endif
        @if($stats['out_of_stock'] > 0)
            <li>{{ $stats['out_of_stock'] }} ingredient(s) are out of stock</li>
        @endif
    </ul>
</div>
@endif

<!-- Quick Add Modal -->
<div class="modal fade" id="quickAddModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-bolt me-2"></i>Quick Add Common Ingredients</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Select common ingredients to add quickly to your inventory:</p>
                
                <div class="row">
                    @php
                        $commonIngredients = [
                            ['name' => 'Beef (for Beef Steak)', 'category' => 'meat', 'unit' => 'kg', 'default_cost' => 350],
                            ['name' => 'Pork (for Sinigang)', 'category' => 'meat', 'unit' => 'kg', 'default_cost' => 280],
                            ['name' => 'Chicken (for Adobo)', 'category' => 'meat', 'unit' => 'kg', 'default_cost' => 180],
                            ['name' => 'Squid (Calamares)', 'category' => 'seafood', 'unit' => 'kg', 'default_cost' => 320],
                            ['name' => 'Onions', 'category' => 'vegetables', 'unit' => 'kg', 'default_cost' => 60],
                            ['name' => 'Garlic', 'category' => 'vegetables', 'unit' => 'kg', 'default_cost' => 120],
                            ['name' => 'Soy Sauce', 'category' => 'condiments', 'unit' => 'bottle', 'default_cost' => 45],
                            ['name' => 'Vinegar', 'category' => 'condiments', 'unit' => 'bottle', 'default_cost' => 35],
                            ['name' => 'Cooking Oil', 'category' => 'oils', 'unit' => 'l', 'default_cost' => 120],
                            ['name' => 'Rice', 'category' => 'grains', 'unit' => 'kg', 'default_cost' => 55],
                            ['name' => 'Sugar', 'category' => 'others', 'unit' => 'kg', 'default_cost' => 65],
                            ['name' => 'Salt', 'category' => 'spices', 'unit' => 'kg', 'default_cost' => 30],
                            ['name' => 'Eggs', 'category' => 'dairy', 'unit' => 'dozen', 'default_cost' => 120],
                            ['name' => 'Lemons', 'category' => 'fruits', 'unit' => 'dozen', 'default_cost' => 80],
                            ['name' => 'Ice Tea Powder', 'category' => 'beverages', 'unit' => 'pack', 'default_cost' => 150],
                        ];
                    @endphp
                    
                    @foreach($commonIngredients as $ingredient)
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input ingredient-checkbox" type="checkbox" 
                                   value="{{ $ingredient['name'] }}" 
                                   data-category="{{ $ingredient['category'] }}"
                                   data-unit="{{ $ingredient['unit'] }}"
                                   data-cost="{{ $ingredient['default_cost'] }}"
                                   id="ingredient{{ $loop->index }}">
                            <label class="form-check-label" for="ingredient{{ $loop->index }}">
                                {{ $ingredient['name'] }}
                                <span class="text-muted small">({{ $ingredient['unit'] }})</span>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addSelectedIngredients()">
                    <i class="fas fa-plus me-2"></i> Add Selected
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.table-success {
    background-color: rgba(25, 135, 84, 0.05);
}
.table-warning {
    background-color: rgba(255, 193, 7, 0.05);
}
.table-danger {
    background-color: rgba(220, 53, 69, 0.05);
}
.card-header h6 {
    font-size: 0.9rem;
    margin-bottom: 0;
}
</style>
@endpush

@push('scripts')
<script>
function addSelectedIngredients() {
    const selected = document.querySelectorAll('.ingredient-checkbox:checked');
    if (selected.length === 0) {
        alert('Please select at least one ingredient');
        return;
    }
    
    // In a real application, you would make an AJAX call to add these ingredients
    // For now, we'll redirect to the create page with pre-filled data
    const ingredients = Array.from(selected).map(cb => {
        return {
            name: cb.value,
            category: cb.dataset.category,
            unit: cb.dataset.unit,
            cost: cb.dataset.cost
        };
    });
    
    // Store in sessionStorage to pre-fill the create form
    sessionStorage.setItem('quick_add_ingredients', JSON.stringify(ingredients));
    
    // Redirect to create page
    window.location.href = "{{ route('admin.inventory.create') }}";
}

// Initialize tooltips
$(document).ready(function() {
    $('[title]').tooltip();
});
</script>
@endpush