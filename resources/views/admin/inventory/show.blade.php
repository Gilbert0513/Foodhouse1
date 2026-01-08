@extends('layouts.admin')

@section('title', 'View Ingredient')
@section('page-title', 'Ingredient Details: ' . $ingredient->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.inventory.index') }}">Inventory</a></li>
    <li class="breadcrumb-item active">View Ingredient</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Ingredient Details Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ingredient Information</h5>
                <div>
                    @if($ingredient->status == 'in_stock')
                        <span class="badge bg-success">In Stock</span>
                    @elseif($ingredient->status == 'low_stock')
                        <span class="badge bg-warning">Low Stock</span>
                    @else
                        <span class="badge bg-danger">Out of Stock</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th>Name:</th>
                                <td>{{ $ingredient->name }}</td>
                            </tr>
                            <tr>
                                <th>Unit:</th>
                                <td>{{ $ingredient->unit }}</td>
                            </tr>
                            <tr>
                                <th>Current Stock:</th>
                                <td><strong>{{ $ingredient->current_stock }} {{ $ingredient->unit }}</strong></td>
                            </tr>
                            <tr>
                                <th>Minimum Stock:</th>
                                <td>{{ $ingredient->minimum_stock }} {{ $ingredient->unit }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th>Cost per Unit:</th>
                                <td>₱{{ number_format($ingredient->cost_per_unit, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Total Value:</th>
                                <td><strong>₱{{ number_format($ingredient->current_stock * $ingredient->cost_per_unit, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Supplier:</th>
                                <td>{{ $ingredient->supplier ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated:</th>
                                <td>{{ $ingredient->updated_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($ingredient->description)
                    <div class="mt-3">
                        <h6>Description:</h6>
                        <p class="mb-0">{{ $ingredient->description }}</p>
                    </div>
                @endif
                
                <div class="mt-4">
                    <a href="{{ route('admin.inventory.edit', $ingredient) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i> Edit
                    </a>
                    <a href="{{ route('admin.inventory.adjust-stock', $ingredient) }}" class="btn btn-primary">
                        <i class="fas fa-exchange-alt me-2"></i> Adjust Stock
                    </a>
                    <a href="{{ route('admin.inventory.menu-items', $ingredient) }}" class="btn btn-info">
                        <i class="fas fa-utensils me-2"></i> View Menu Items
                    </a>
                </div>
            </div>
        </div>

        <!-- Stock History Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Stock Movements</h5>
            </div>
            <div class="card-body">
                @if($logs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Previous</th>
                                    <th>New</th>
                                    <th>Notes</th>
                                    <th>By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            @if($log->type == 'purchase')
                                                <span class="badge bg-success">Purchase</span>
                                            @elseif($log->type == 'usage')
                                                <span class="badge bg-danger">Usage</span>
                                            @elseif($log->type == 'reservation')
                                                <span class="badge bg-info">Reservation</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($log->type) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $log->quantity }} {{ $log->unit }}</td>
                                        <td>{{ $log->previous_stock }} {{ $log->unit }}</td>
                                        <td>{{ $log->new_stock }} {{ $log->unit }}</td>
                                        <td>{{ $log->notes ?? 'N/A' }}</td>
                                        <td>{{ $log->user->name ?? 'System' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        {{ $logs->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-2x text-muted mb-3"></i>
                        <p class="text-muted">No stock movements recorded yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Stock Status Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Stock Status</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <h1 class="display-4">{{ $ingredient->current_stock }}</h1>
                    <h5 class="text-muted">{{ $ingredient->unit }}</h5>
                </div>
                
                <div class="progress mb-3" style="height: 25px;">
                    @php
                        $percentage = $ingredient->minimum_stock > 0 
                            ? min(100, ($ingredient->current_stock / $ingredient->minimum_stock) * 100)
                            : ($ingredient->current_stock > 0 ? 100 : 0);
                        
                        $progressClass = $ingredient->status == 'in_stock' ? 'bg-success' : 
                                        ($ingredient->status == 'low_stock' ? 'bg-warning' : 'bg-danger');
                    @endphp
                    <div class="progress-bar {{ $progressClass }}" role="progressbar" 
                         style="width: {{ $percentage }}%;" 
                         aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                        {{ round($percentage) }}%
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="card bg-light">
                            <div class="card-body p-2">
                                <small class="text-muted">Minimum Stock</small>
                                <h6 class="mb-0">{{ $ingredient->minimum_stock }} {{ $ingredient->unit }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-light">
                            <div class="card-body p-2">
                                <small class="text-muted">Stock Value</small>
                                <h6 class="mb-0">₱{{ number_format($ingredient->current_stock * $ingredient->cost_per_unit, 2) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($ingredient->status == 'low_stock')
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Stock is below minimum level! Consider reordering.
                    </div>
                @elseif($ingredient->status == 'out_of_stock')
                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-times-circle me-2"></i>
                        Item is out of stock! Immediate reorder required.
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Quick Actions Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.inventory.adjust-stock', $ingredient) }}" class="btn btn-primary">
                        <i class="fas fa-exchange-alt me-2"></i> Adjust Stock
                    </a>
                    <a href="{{ route('admin.inventory.edit', $ingredient) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i> Edit Details
                    </a>
                    <a href="{{ route('admin.inventory.menu-items', $ingredient) }}" class="btn btn-info">
                        <i class="fas fa-utensils me-2"></i> View Menu Items
                    </a>
                    <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Inventory
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection