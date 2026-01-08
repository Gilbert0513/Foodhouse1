@extends('layouts.admin')

@section('title', 'Add Ingredient')
@section('page-title', 'Add New Ingredient')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.inventory.index') }}">Inventory</a></li>
    <li class="breadcrumb-item active">Add Ingredient</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ingredient Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.inventory.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Ingredient Name *</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name') }}" required placeholder="e.g., Chicken Breast, Rice, Cooking Oil">
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="unit" class="form-label">Unit of Measurement *</label>
                            <select class="form-control" id="unit" name="unit" required>
                                <option value="">Select Unit</option>
                                <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>Gram (g)</option>
                                <option value="l" {{ old('unit') == 'l' ? 'selected' : '' }}>Liter (l)</option>
                                <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Milliliter (ml)</option>
                                <option value="piece" {{ old('unit') == 'piece' ? 'selected' : '' }}>Piece</option>
                                <option value="pack" {{ old('unit') == 'pack' ? 'selected' : '' }}>Pack</option>
                                <option value="bottle" {{ old('unit') == 'bottle' ? 'selected' : '' }}>Bottle</option>
                                <option value="can" {{ old('unit') == 'can' ? 'selected' : '' }}>Can</option>
                                <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box</option>
                                <option value="dozen" {{ old('unit') == 'dozen' ? 'selected' : '' }}>Dozen</option>
                                <option value="bundle" {{ old('unit') == 'bundle' ? 'selected' : '' }}>Bundle</option>
                            </select>
                            @error('unit')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cost_per_unit" class="form-label">Cost per Unit (₱) *</label>
                            <input type="number" step="0.01" class="form-control" id="cost_per_unit" name="cost_per_unit"
                                   value="{{ old('cost_per_unit', 0) }}" required min="0">
                            @error('cost_per_unit')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="current_stock" class="form-label">Current Stock *</label>
                            <input type="number" step="0.01" class="form-control" id="current_stock" name="current_stock"
                                   value="{{ old('current_stock', 0) }}" required min="0">
                            @error('current_stock')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="minimum_stock" class="form-label">Minimum Stock (Alert Level) *</label>
                            <input type="number" step="0.01" class="form-control" id="minimum_stock" name="minimum_stock"
                                   value="{{ old('minimum_stock', 0) }}" required min="0">
                            @error('minimum_stock')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="supplier" class="form-label">Supplier</label>
                        <input type="text" class="form-control" id="supplier" name="supplier"
                               value="{{ old('supplier') }}" placeholder="e.g., Supplier Name, Market, Local Store">
                        @error('supplier')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                                  placeholder="Additional details about the ingredient">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Save Ingredient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Tips</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i> <strong>Unit:</strong> Choose the most appropriate unit for this ingredient</li>
                    <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i> <strong>Cost per Unit:</strong> Enter the purchase price for one unit</li>
                    <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i> <strong>Current Stock:</strong> Enter the actual quantity available</li>
                    <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i> <strong>Minimum Stock:</strong> System will alert when stock reaches this level</li>
                    <li><i class="fas fa-lightbulb text-warning me-2"></i> <strong>Supplier:</strong> Helps in reordering when stock is low</li>
                </ul>
                
                <div class="alert alert-info mt-3">
                    <h6><i class="fas fa-info-circle me-2"></i>Inventory Status</h6>
                    <ul class="mb-0 small">
                        <li><span class="badge bg-success">In Stock</span> - Stock > Minimum Stock</li>
                        <li><span class="badge bg-warning">Low Stock</span> - Stock ≤ Minimum Stock</li>
                        <li><span class="badge bg-danger">Out of Stock</span> - Stock = 0</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-update status based on current and minimum stock
        const currentStockInput = document.getElementById('current_stock');
        const minStockInput = document.getElementById('minimum_stock');
        
        function updateStatusPreview() {
            const currentStock = parseFloat(currentStockInput.value) || 0;
            const minStock = parseFloat(minStockInput.value) || 0;
            
            let status = '';
            if (currentStock <= 0) {
                status = '<span class="badge bg-danger">Out of Stock</span>';
            } else if (currentStock <= minStock) {
                status = '<span class="badge bg-warning">Low Stock</span>';
            } else {
                status = '<span class="badge bg-success">In Stock</span>';
            }
            
            document.getElementById('statusPreview').innerHTML = 'Initial Status: ' + status;
        }
        
        currentStockInput.addEventListener('input', updateStatusPreview);
        minStockInput.addEventListener('input', updateStatusPreview);
        
        // Initialize preview
        updateStatusPreview();
    });
</script>
@endpush