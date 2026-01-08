@extends('layouts.admin')

@section('title', 'Inventory Reports')
@section('page-title', 'Inventory Reports')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Inventory Status</h5>
    </div>
    <div class="card-body">
        @if($inventoryStats && ($inventoryStats['total_items'] ?? 0) > 0)
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h6>In Stock</h6>
                            <h3>{{ $inventoryStats['in_stock'] ?? 0 }} items</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h6>Low Stock</h6>
                            <h3>{{ $inventoryStats['low_stock'] ?? 0 }} items</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h6>Out of Stock</h6>
                            <h3>{{ $inventoryStats['out_of_stock'] ?? 0 }} items</h3>
                        </div>
                    </div>
                </div>
            </div>

            @if($inventoryItems && count($inventoryItems) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Current Stock</th>
                                <th>Min. Stock</th>
                                <th>Status</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventoryItems as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category ?? 'Uncategorized' }}</td>
                                    <td>{{ $item->stock ?? 0 }}</td>
                                    <td>{{ $item->min_stock ?? 5 }}</td>
                                    <td>
                                        @if(($item->stock ?? 0) <= 0)
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @elseif(($item->stock ?? 0) <= ($item->min_stock ?? 5))
                                            <span class="badge bg-warning">Low Stock</span>
                                        @else
                                            <span class="badge bg-success">In Stock</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->updated_at ? $item->updated_at->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Display pagination links if using paginate() -->
                @if($inventoryItems instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="d-flex justify-content-center mt-4">
                        {{ $inventoryItems->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No inventory data available</h5>
                    <p class="text-muted">Add menu items to track inventory.</p>
                </div>
            @endif

            <div class="mt-4">
                <button class="btn btn-primary">
                    <i class="fas fa-print me-2"></i> Print Inventory Report
                </button>
                <button class="btn btn-success">
                    <i class="fas fa-download me-2"></i> Export to Excel
                </button>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No inventory items found</h5>
                <p class="text-muted">Add menu items with stock tracking to see inventory reports here.</p>
                <a href="{{ route('admin.menu.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Add Menu Item
                </a>
            </div>
        @endif
    </div>
</div>
@endsection