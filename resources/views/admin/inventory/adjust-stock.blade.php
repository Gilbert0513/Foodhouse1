@extends('layouts.admin')

@section('title', 'Adjust Stock')
@section('page-title', 'Adjust Stock: ' . $ingredient->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.inventory.index') }}">Inventory</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.inventory.show', $ingredient) }}">{{ $ingredient->name }}</a></li>
    <li class="breadcrumb-item active">Adjust Stock</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Stock Adjustment</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.inventory.adjust-stock', $ingredient) }}" method="POST">
                    @csrf
                    
                    <!-- Current Stock Info -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Current Stock Information</h6>
                        <div class="row">
                            <div class="col-6">
                                <small>Current Stock:</small>
                                <h5 class="mb-0">{{ $ingredient->current_stock }} {{ $ingredient->unit }}</h5>
                            </div>
                            <div class="col-6">
                                <small>Minimum Stock:</small>
                                <h5 class="mb-0">{{ $ingredient->minimum_stock }} {{ $ingredient->unit }}</h5>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small>Status:</small>
                            @if($ingredient->status == 'in_stock')
                                <span class="badge bg-success">In Stock</span>
                            @elseif($ingredient->status == 'low_stock')
                                <span class="badge bg-warning">Low Stock</span>
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Adjustment Type -->
                    <div class="mb-3">
                        <label class="form-label">Adjustment Type *</label>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="adjustment_type" 
                                           id="add" value="add" checked>
                                    <label class="form-check-label" for="add">
                                        <i class="fas fa-plus text-success me-1"></i> Add Stock
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="adjustment_type" 
                                           id="deduct" value="deduct">
                                    <label class="form-check-label" for="deduct">
                                        <i class="fas fa-minus text-danger me-1"></i> Deduct Stock
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="adjustment_type" 
                                           id="set" value="set">
                                    <label class="form-check-label" for="set">
                                        <i class="fas fa-equals text-primary me-1"></i> Set Stock
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quantity Input -->
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity *</label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control" id="quantity" 
                                   name="quantity" required min="0.01" placeholder="Enter quantity">
                            <span class="input-group-text">{{ $ingredient->unit }}</span>
                        </div>
                        <div class="form-text">Enter the quantity to adjust</div>
                    </div>
                    
                    <!-- Notes -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes / Reason</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Explain why you're adjusting the stock (e.g., 'Received delivery', 'Physical count discrepancy', 'Wastage')"></textarea>
                        <div class="form-text">Provide a reason for the stock adjustment</div>
                    </div>
                    
                    <!-- Preview -->
                    <div class="card mb-3" id="previewCard" style="display: none;">
                        <div class="card-body">
                            <h6>Adjustment Preview</h6>
                            <div id="previewContent"></div>
                        </div>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.inventory.show', $ingredient) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-2"></i> Apply Adjustment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Adjustment Types</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6><i class="fas fa-plus text-success me-2"></i> Add Stock</h6>
                    <p class="small text-muted">Use this when:</p>
                    <ul class="small">
                        <li>Receiving new delivery/purchase</li>
                        <li>Correcting undercount in physical inventory</li>
                        <li>Returning unused ingredients to stock</li>
                    </ul>
                </div>
                
                <div class="mb-4">
                    <h6><i class="fas fa-minus text-danger me-2"></i> Deduct Stock</h6>
                    <p class="small text-muted">Use this when:</p>
                    <ul class="small">
                        <li>Recording wastage/spoilage</li>
                        <li>Correcting overcount in physical inventory</li>
                        <li>Transferring to another location</li>
                        <li>Personal consumption/sample</li>
                    </ul>
                </div>
                
                <div class="mb-4">
                    <h6><i class="fas fa-equals text-primary me-2"></i> Set Stock</h6>
                    <p class="small text-muted">Use this when:</p>
                    <ul class="small">
                        <li>Conducting physical inventory count</li>
                        <li>Correcting system stock to match actual count</li>
                        <li>Initial setup of stock levels</li>
                    </ul>
                </div>
                
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Important Notes</h6>
                    <ul class="mb-0 small">
                        <li>All stock adjustments are logged for audit purposes</li>
                        <li>Provide clear notes for each adjustment</li>
                        <li>Regular physical counts are recommended</li>
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
        const currentStock = {{ $ingredient->current_stock }};
        const unit = '{{ $ingredient->unit }}';
        const quantityInput = document.getElementById('quantity');
        const previewCard = document.getElementById('previewCard');
        const previewContent = document.getElementById('previewContent');
        const adjustmentRadios = document.querySelectorAll('input[name="adjustment_type"]');
        
        function updatePreview() {
            const quantity = parseFloat(quantityInput.value);
            const adjustmentType = document.querySelector('input[name="adjustment_type"]:checked').value;
            
            if (!quantity || quantity <= 0) {
                previewCard.style.display = 'none';
                return;
            }
            
            let newStock = currentStock;
            let actionText = '';
            let badgeClass = '';
            
            switch (adjustmentType) {
                case 'add':
                    newStock = currentStock + quantity;
                    actionText = `<span class="badge bg-success">Adding</span> ${quantity} ${unit}`;
                    badgeClass = 'text-success';
                    break;
                case 'deduct':
                    if (quantity > currentStock) {
                        actionText = `<span class="badge bg-danger">Cannot deduct</span> ${quantity} ${unit} (exceeds current stock)`;
                        badgeClass = 'text-danger';
                        previewContent.innerHTML = `
                            <div class="text-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Error:</strong> Cannot deduct more than current stock!
                            </div>
                            <div class="mt-2">
                                <p class="mb-1"><strong>Current Stock:</strong> ${currentStock} ${unit}</p>
                                <p class="mb-1"><strong>Deduction Amount:</strong> ${quantity} ${unit}</p>
                                <p class="mb-0"><strong>Result:</strong> Insufficient stock</p>
                            </div>`;
                        previewCard.style.display = 'block';
                        return;
                    }
                    newStock = currentStock - quantity;
                    actionText = `<span class="badge bg-danger">Deducting</span> ${quantity} ${unit}`;
                    badgeClass = 'text-danger';
                    break;
                case 'set':
                    newStock = quantity;
                    actionText = `<span class="badge bg-primary">Setting to</span> ${quantity} ${unit}`;
                    badgeClass = 'text-primary';
                    break;
            }
            
            // Calculate status
            let status = '';
            let statusClass = '';
            if (newStock <= 0) {
                status = 'Out of Stock';
                statusClass = 'badge bg-danger';
            } else if (newStock <= {{ $ingredient->minimum_stock }}) {
                status = 'Low Stock';
                statusClass = 'badge bg-warning';
            } else {
                status = 'In Stock';
                statusClass = 'badge bg-success';
            }
            
            previewContent.innerHTML = `
                <div class="${badgeClass}">
                    ${actionText}
                </div>
                <div class="mt-3">
                    <p class="mb-1"><strong>Current Stock:</strong> ${currentStock} ${unit}</p>
                    <p class="mb-1"><strong>Adjustment:</strong> ${quantity} ${unit}</p>
                    <p class="mb-1"><strong>New Stock:</strong> ${newStock} ${unit}</p>
                    <p class="mb-0"><strong>New Status:</strong> <span class="${statusClass}">${status}</span></p>
                </div>`;
            
            previewCard.style.display = 'block';
        }
        
        // Event listeners
        quantityInput.addEventListener('input', updatePreview);
        adjustmentRadios.forEach(radio => {
            radio.addEventListener('change', updatePreview);
        });
        
        // Initial preview
        updatePreview();
    });
</script>
@endpush