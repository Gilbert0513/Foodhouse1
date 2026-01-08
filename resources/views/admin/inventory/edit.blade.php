@extends('layouts.admin')

@section('title', 'Edit Ingredient')
@section('page-title', 'Edit Ingredient: ' . $ingredient->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.inventory.index') }}">Inventory</a></li>
    <li class="breadcrumb-item active">Edit Ingredient</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Ingredient Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.inventory.update', $ingredient) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Ingredient Name *</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name', $ingredient->name) }}" required>
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="unit" class="form-label">Unit of Measurement *</label>
                            <select class="form-control" id="unit" name="unit" required>
                                <option value="">Select Unit</option>
                                <optgroup label="Weight">
                                    <option value="kg" {{ old('unit', $ingredient->unit) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                    <option value="g" {{ old('unit', $ingredient->unit) == 'g' ? 'selected' : '' }}>Gram (g)</option>
                                </optgroup>
                                <optgroup label="Volume">
                                    <option value="l" {{ old('unit', $ingredient->unit) == 'l' ? 'selected' : '' }}>Liter (l)</option>
                                    <option value="ml" {{ old('unit', $ingredient->unit) == 'ml' ? 'selected' : '' }}>Milliliter (ml)</option>
                                </optgroup>
                                <optgroup label="Count">
                                    <option value="piece" {{ old('unit', $ingredient->unit) == 'piece' ? 'selected' : '' }}>Piece</option>
                                    <option value="pack" {{ old('unit', $ingredient->unit) == 'pack' ? 'selected' : '' }}>Pack</option>
                                    <option value="bottle" {{ old('unit', $ingredient->unit) == 'bottle' ? 'selected' : '' }}>Bottle</option>
                                    <option value="can" {{ old('unit', $ingredient->unit) == 'can' ? 'selected' : '' }}>Can</option>
                                    <option value="box" {{ old('unit', $ingredient->unit) == 'box' ? 'selected' : '' }}>Box</option>
                                    <option value="dozen" {{ old('unit', $ingredient->unit) == 'dozen' ? 'selected' : '' }}>Dozen</option>
                                    <option value="bundle" {{ old('unit', $ingredient->unit) == 'bundle' ? 'selected' : '' }}>Bundle</option>
                                    <option value="sachet" {{ old('unit', $ingredient->unit) == 'sachet' ? 'selected' : '' }}>Sachet</option>
                                    <option value="tray" {{ old('unit', $ingredient->unit) == 'tray' ? 'selected' : '' }}>Tray</option>
                                    <option value="packet" {{ old('unit', $ingredient->unit) == 'packet' ? 'selected' : '' }}>Packet</option>
                                </optgroup>
                            </select>
                            @error('unit')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cost_per_unit" class="form-label">Cost per Unit (₱) *</label>
                            <input type="number" step="0.01" class="form-control" id="cost_per_unit" name="cost_per_unit"
                                   value="{{ old('cost_per_unit', $ingredient->cost_per_unit) }}" required min="0">
                            @error('cost_per_unit')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="current_stock" class="form-label">Current Stock *</label>
                            <input type="number" step="0.01" class="form-control" id="current_stock" name="current_stock"
                                   value="{{ old('current_stock', $ingredient->current_stock) }}" required min="0">
                            @error('current_stock')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="minimum_stock" class="form-label">Minimum Stock (Alert Level) *</label>
                            <input type="number" step="0.01" class="form-control" id="minimum_stock" name="minimum_stock"
                                   value="{{ old('minimum_stock', $ingredient->minimum_stock) }}" required min="0">
                            @error('minimum_stock')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category" class="form-label">Ingredient Category</label>
                        <select class="form-control" id="category" name="category">
                            <option value="">Select Category</option>
                            <option value="meat" {{ old('category', $ingredient->category) == 'meat' ? 'selected' : '' }}>Meat & Poultry</option>
                            <option value="seafood" {{ old('category', $ingredient->category) == 'seafood' ? 'selected' : '' }}>Seafood</option>
                            <option value="vegetables" {{ old('category', $ingredient->category) == 'vegetables' ? 'selected' : '' }}>Vegetables</option>
                            <option value="fruits" {{ old('category', $ingredient->category) == 'fruits' ? 'selected' : '' }}>Fruits</option>
                            <option value="dairy" {{ old('category', $ingredient->category) == 'dairy' ? 'selected' : '' }}>Dairy & Eggs</option>
                            <option value="grains" {{ old('category', $ingredient->category) == 'grains' ? 'selected' : '' }}>Grains & Pasta</option>
                            <option value="spices" {{ old('category', $ingredient->category) == 'spices' ? 'selected' : '' }}>Spices & Herbs</option>
                            <option value="condiments" {{ old('category', $ingredient->category) == 'condiments' ? 'selected' : '' }}>Condiments & Sauces</option>
                            <option value="beverages" {{ old('category', $ingredient->category) == 'beverages' ? 'selected' : '' }}>Beverages</option>
                            <option value="dessert" {{ old('category', $ingredient->category) == 'dessert' ? 'selected' : '' }}>Dessert Items</option>
                            <option value="others" {{ old('category', $ingredient->category) == 'others' ? 'selected' : '' }}>Others</option>
                        </select>
                        @error('category')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="supplier" class="form-label">Supplier</label>
                        <input type="text" class="form-control" id="supplier" name="supplier"
                               value="{{ old('supplier', $ingredient->supplier) }}">
                        @error('supplier')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $ingredient->description) }}</textarea>
                        @error('description')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Related Menu Items -->
                    <div class="mb-3">
                        <label class="form-label">Related Menu Items</label>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info small">
                                    <i class="fas fa-info-circle me-2"></i>
                                    This ingredient is used in the following menu items:
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($ingredient->menuItems ?? [] as $menuItem)
                                    <span class="badge bg-primary">
                                        <i class="fas fa-utensils me-1"></i>{{ $menuItem->item_name }}
                                    </span>
                                    @endforeach
                                    @if(($ingredient->menuItems ?? [])->count() == 0)
                                    <span class="text-muted">Not assigned to any menu items yet</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6>Current Status</h6>
                                    @if($ingredient->current_stock > $ingredient->minimum_stock)
                                        <span class="badge bg-success">In Stock</span>
                                    @elseif($ingredient->current_stock > 0)
                                        <span class="badge bg-warning">Low Stock</span>
                                    @else
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @endif
                                    <p class="mt-2 mb-0 small">
                                        Stock Value: <strong>₱{{ number_format($ingredient->current_stock * $ingredient->cost_per_unit, 2) }}</strong>
                                    </p>
                                    <p class="mb-0 small">
                                        Stock Level: 
                                        @if($ingredient->minimum_stock > 0)
                                            {{ round(($ingredient->current_stock / $ingredient->minimum_stock) * 100) }}%
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Cancel
                        </a>
                        <div>
                            <a href="{{ route('admin.inventory.adjust-stock', $ingredient) }}" class="btn btn-info me-2">
                                <i class="fas fa-exchange-alt me-2"></i> Adjust Stock
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Update Ingredient
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Suggested Ingredients for Your Menu Items -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Suggested Ingredients for Menu</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info small">
                    <i class="fas fa-lightbulb me-2"></i>
                    Based on your 10 menu items, here are all possible ingredients:
                </div>
                
                <div class="accordion" id="ingredientsAccordion">
                    <!-- MEAT & POULTRY -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMeat">
                                <i class="fas fa-drumstick-bite me-2"></i>Meat & Poultry
                            </button>
                        </h2>
                        <div id="collapseMeat" class="accordion-collapse collapse" data-bs-parent="#ingredientsAccordion">
                            <div class="accordion-body">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-check text-success me-2"></i>Beef (for Beef Steak) - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Pork (for Pork Sinigang, Grilled Pork Chop) - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Chicken (for Chicken Adobo) - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Calamares/Squid (for Crispy Calamares) - kg</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- VEGETABLES -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVeg">
                                <i class="fas fa-carrot me-2"></i>Vegetables
                            </button>
                        </h2>
                        <div id="collapseVeg" class="accordion-collapse collapse" data-bs-parent="#ingredientsAccordion">
                            <div class="accordion-body">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-check text-success me-2"></i>Onions - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Garlic - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Ginger - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Tomatoes - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Tamarind (for Sinigang) - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>String Beans - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Eggplant - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Okra - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Kangkong - kg</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- DAIRY & EGGS -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDairy">
                                <i class="fas fa-egg me-2"></i>Dairy & Eggs
                            </button>
                        </h2>
                        <div id="collapseDairy" class="accordion-collapse collapse" data-bs-parent="#ingredientsAccordion">
                            <div class="accordion-body">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-check text-success me-2"></i>Eggs (for Leche Flan) - dozen</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Condensed Milk (for Leche Flan) - can</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Evaporated Milk (for Leche Flan) - can</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Butter - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Cheese (for Nachos) - kg</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- BEVERAGES -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBeverages">
                                <i class="fas fa-glass-whiskey me-2"></i>Beverages
                            </button>
                        </h2>
                        <div id="collapseBeverages" class="accordion-collapse collapse" data-bs-parent="#ingredientsAccordion">
                            <div class="accordion-body">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-check text-success me-2"></i>Lemon (for Fresh Lemonade) - dozen</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Sugar - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Iced Tea Powder (for Iced Tea) - pack</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Water - l</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Ice - kg</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- DESSERT -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDessert">
                                <i class="fas fa-ice-cream me-2"></i>Dessert Items
                            </button>
                        </h2>
                        <div id="collapseDessert" class="accordion-collapse collapse" data-bs-parent="#ingredientsAccordion">
                            <div class="accordion-body">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-check text-success me-2"></i>Halo-Halo Mix (beans, nata de coco, kaong) - pack</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Leche Flan Caramel - pack</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Shaved Ice - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Ube Halaya - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Sweet Corn - can</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Pinipig - kg</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- SNACKS & APPETIZERS -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSnacks">
                                <i class="fas fa-cookie me-2"></i>Snacks & Appetizers
                            </button>
                        </h2>
                        <div id="collapseSnacks" class="accordion-collapse collapse" data-bs-parent="#ingredientsAccordion">
                            <div class="accordion-body">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-check text-success me-2"></i>Tortilla Chips (for Nachos) - bag</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Ground Beef (for Nachos) - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Refried Beans - can</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Jalapeños - jar</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Sour Cream - bottle</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Guacamole - pack</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Bread Crumbs (for Calamares) - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Flour (for Calamares) - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Cooking Oil - l</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- CONDIMENTS & SAUCES -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCondiments">
                                <i class="fas fa-wine-bottle me-2"></i>Condiments & Sauces
                            </button>
                        </h2>
                        <div id="collapseCondiments" class="accordion-collapse collapse" data-bs-parent="#ingredientsAccordion">
                            <div class="accordion-body">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-check text-success me-2"></i>Soy Sauce - bottle</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Vinegar - bottle</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Fish Sauce (Patis) - bottle</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Oyster Sauce - bottle</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Calamansi - dozen</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Salsa - jar</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Tartar Sauce - bottle</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Mayonnaise - bottle</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Ketchup - bottle</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Hot Sauce - bottle</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- SPICES & HERBS -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSpices">
                                <i class="fas fa-mortar-pestle me-2"></i>Spices & Herbs
                            </button>
                        </h2>
                        <div id="collapseSpices" class="accordion-collapse collapse" data-bs-parent="#ingredientsAccordion">
                            <div class="accordion-body">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-check text-success me-2"></i>Salt - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Black Pepper - pack</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Bay Leaves - pack</li>
                                    <li><i class="fas fa-check text-success me-2"></i>MSG (Vetsin) - pack</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Garlic Powder - pack</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Onion Powder - pack</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Paprika - pack</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Cumin - pack</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Oregano - pack</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Thyme - pack</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- RICE & GRAINS -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGrains">
                                <i class="fas fa-seedling me-2"></i>Rice & Grains
                            </button>
                        </h2>
                        <div id="collapseGrains" class="accordion-collapse collapse" data-bs-parent="#ingredientsAccordion">
                            <div class="accordion-body">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-check text-success me-2"></i>Rice - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Corn Starch - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>All-purpose Flour - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Baking Soda - pack</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Baking Powder - pack</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- OILS & VINEGARS -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOils">
                                <i class="fas fa-oil-can me-2"></i>Oils & Vinegars
                            </button>
                        </h2>
                        <div id="collapseOils" class="accordion-collapse collapse" data-bs-parent="#ingredientsAccordion">
                            <div class="accordion-body">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-check text-success me-2"></i>Cooking Oil (Vegetable Oil) - l</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Olive Oil - bottle</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Sesame Oil - bottle</li>
                                    <li><i class="fas fa-check text-success me-2"></i>White Vinegar - bottle</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Cane Vinegar - bottle</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Apple Cider Vinegar - bottle</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- OTHERS -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOthers">
                                <i class="fas fa-ellipsis-h me-2"></i>Others
                            </button>
                        </h2>
                        <div id="collapseOthers" class="accordion-collapse collapse" data-bs-parent="#ingredientsAccordion">
                            <div class="accordion-body">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-check text-success me-2"></i>Sugar (White) - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Brown Sugar - kg</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Honey - bottle</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Corn Syrup - bottle</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Vanilla Extract - bottle</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Food Coloring - pack</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Plastic Cups - pack</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Paper Plates - pack</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Plastic Forks/Spoons - pack</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Paper Napkins - pack</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.inventory.adjust-stock', $ingredient) }}" class="btn btn-primary">
                        <i class="fas fa-exchange-alt me-2"></i> Adjust Stock
                    </a>
                    <a href="{{ route('admin.inventory.show', $ingredient) }}" class="btn btn-info">
                        <i class="fas fa-eye me-2"></i> View Details
                    </a>
                    <a href="{{ route('admin.inventory.menu-items', $ingredient) }}" class="btn btn-warning">
                        <i class="fas fa-utensils me-2"></i> View Menu Items
                    </a>
                    <form action="{{ route('admin.inventory.destroy', $ingredient) }}" method="POST" 
                          onsubmit="return confirm('Delete this ingredient? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i> Delete Ingredient
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.accordion-button:not(.collapsed) {
    background-color: rgba(230, 57, 70, 0.1);
    color: #e63946;
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(230, 57, 70, 0.25);
}

.accordion-body ul li {
    padding: 5px 0;
    border-bottom: 1px solid #eee;
}

.accordion-body ul li:last-child {
    border-bottom: none;
}
</style>
@endpush

@push('scripts')
<script>
// Auto-expand accordion based on ingredient category
$(document).ready(function() {
    const category = $('#category').val();
    if (category) {
        const collapseId = 'collapse' + category.charAt(0).toUpperCase() + category.slice(1);
        $('#' + collapseId).addClass('show');
        $('#' + collapseId).prev().find('.accordion-button').removeClass('collapsed');
    }
});
</script>
@endpush