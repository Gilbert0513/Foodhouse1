@extends('layouts.customer')

@section('title', 'My Orders')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light-blue text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shopping-cart fa-lg me-3"></i>
                            <div>
                                <h5 class="mb-0 fw-bold">My Orders</h5>
                                <p class="mb-0 opacity-75">Track and manage your food orders</p>
                            </div>
                        </div>
                        <a href="{{ route('customer.menu') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-2"></i>New Order
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <!-- Order Stats -->
            @if($orders->count() > 0)
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="icon-circle-md bg-blue text-white">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Total Orders</h6>
                                    <h4 class="mb-0 fw-bold text-blue">{{ $orders->total() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="icon-circle-md bg-green text-white">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Completed</h6>
                                    <h4 class="mb-0 fw-bold text-green">
                                        {{ $orders->where('status', 'completed')->count() }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="icon-circle-md bg-orange text-white">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Pending</h6>
                                    <h4 class="mb-0 fw-bold text-orange">
                                        {{ $orders->whereIn('status', ['pending', 'confirmed', 'preparing'])->count() }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="icon-circle-md bg-red text-white">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Cancelled</h6>
                                    <h4 class="mb-0 fw-bold text-red">
                                        {{ $orders->where('status', 'cancelled')->count() }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Orders Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0">Order #</th>
                                    <th class="border-0">Date</th>
                                    <th class="border-0">Type</th>
                                    <th class="border-0">Items</th>
                                    <th class="border-0">Total</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr class="order-row">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <i class="fas fa-receipt text-blue"></i>
                                            </div>
                                            <div>
                                                <strong class="d-block">{{ $order->order_number }}</strong>
                                                <small class="text-muted">#{{ $order->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-medium">{{ $order->created_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($order->order_type == 'dine_in')
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-blue-light text-blue me-2">
                                                    <i class="fas fa-utensils me-1"></i>Dine In
                                                </span>
                                                @if($order->table_number)
                                                <small class="text-muted">Table {{ $order->table_number }}</small>
                                                @endif
                                            </div>
                                        @elseif($order->order_type == 'takeaway')
                                            <span class="badge bg-orange-light text-orange">
                                                <i class="fas fa-shopping-bag me-1"></i>Takeaway
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                {{ ucfirst($order->order_type) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="fw-medium">{{ $order->items->sum('quantity') }}</span>
                                            <small class="text-muted ms-1">items</small>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-dark">₱{{ number_format($order->grand_total ?? $order->total_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            $statusConfig = [
                                                'pending' => ['color' => 'orange', 'icon' => 'clock'],
                                                'confirmed' => ['color' => 'blue', 'icon' => 'check'],
                                                'preparing' => ['color' => 'purple', 'icon' => 'utensils'],
                                                'ready' => ['color' => 'green', 'icon' => 'check-circle'],
                                                'served' => ['color' => 'teal', 'icon' => 'concierge-bell'],
                                                'completed' => ['color' => 'dark', 'icon' => 'flag-checkered'],
                                                'cancelled' => ['color' => 'red', 'icon' => 'times']
                                            ];
                                            $config = $statusConfig[$order->status] ?? ['color' => 'secondary', 'icon' => 'question'];
                                        @endphp
                                        <span class="badge bg-{{ $config['color'] }}-light text-{{ $config['color'] }}">
                                            <i class="fas fa-{{ $config['icon'] }} me-1"></i>
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('customer.orders.show', $order) }}" 
                                               class="btn btn-sm btn-blue-outline">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                            
                                            @if(in_array($order->status, ['pending', 'confirmed']))
                                            <form action="{{ route('customer.orders.cancel', $order) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-red-outline" 
                                                        onclick="return confirm('Are you sure you want to cancel this order?')">
                                                    <i class="fas fa-times me-1"></i> Cancel
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($orders->hasPages())
                    <div class="d-flex justify-content-center mt-4 pt-3 border-top">
                        <nav>
                            {{ $orders->links() }}
                        </nav>
                    </div>
                    @endif
                    
                    @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div class="empty-state-icon mb-4">
                            <i class="fas fa-shopping-cart fa-4x text-muted"></i>
                        </div>
                        <h5 class="text-muted mb-3">No Orders Yet</h5>
                        <p class="text-muted mb-4">You haven't placed any orders yet.</p>
                        <a href="{{ route('customer.menu') }}" class="btn btn-blue">
                            <i class="fas fa-utensils me-2"></i>Browse Menu
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Light Color Scheme */
    .bg-light-blue {
        background-color: #4a6bff !important;
    }
    
    .bg-blue {
        background-color: #4a6bff !important;
    }
    
    .text-blue {
        color: #4a6bff !important;
    }
    
    .bg-green {
        background-color: #10b981 !important;
    }
    
    .text-green {
        color: #10b981 !important;
    }
    
    .bg-orange {
        background-color: #f59e0b !important;
    }
    
    .text-orange {
        color: #f59e0b !important;
    }
    
    .bg-purple {
        background-color: #8b5cf6 !important;
    }
    
    .text-purple {
        color: #8b5cf6 !important;
    }
    
    .bg-red {
        background-color: #ef4444 !important;
    }
    
    .text-red {
        color: #ef4444 !important;
    }
    
    .bg-teal {
        background-color: #06b6d4 !important;
    }
    
    .text-teal {
        color: #06b6d4 !important;
    }
    
    .bg-dark {
        background-color: #374151 !important;
    }
    
    .text-dark {
        color: #374151 !important;
    }
    
    /* Light Backgrounds */
    .bg-blue-light {
        background-color: rgba(74, 107, 255, 0.1) !important;
    }
    
    .bg-green-light {
        background-color: rgba(16, 185, 129, 0.1) !important;
    }
    
    .bg-orange-light {
        background-color: rgba(245, 158, 11, 0.1) !important;
    }
    
    .bg-purple-light {
        background-color: rgba(139, 92, 246, 0.1) !important;
    }
    
    .bg-red-light {
        background-color: rgba(239, 68, 68, 0.1) !important;
    }
    
    .bg-teal-light {
        background-color: rgba(6, 182, 212, 0.1) !important;
    }
    
    .bg-dark-light {
        background-color: rgba(55, 65, 81, 0.1) !important;
    }
    
    /* Card Styling */
    .card {
        border-radius: 10px;
        transition: transform 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    /* Icon Circles */
    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .icon-circle-md {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Table Styling */
    .table {
        --bs-table-hover-bg: rgba(74, 107, 255, 0.02);
    }
    
    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        color: #6b7280;
        letter-spacing: 0.5px;
    }
    
    .order-row:hover {
        background-color: rgba(74, 107, 255, 0.03);
    }
    
    /* Badges */
    .badge {
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
    }
    
    /* Buttons */
    .btn-blue {
        background-color: #4a6bff;
        border-color: #4a6bff;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
    }
    
    .btn-blue:hover {
        background-color: #3a5bef;
        border-color: #3a5bef;
        color: white;
    }
    
    .btn-blue-outline {
        border: 1px solid #4a6bff;
        color: #4a6bff;
        background: transparent;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.875rem;
    }
    
    .btn-blue-outline:hover {
        background-color: rgba(74, 107, 255, 0.1);
        border-color: #3a5bef;
        color: #3a5bef;
    }
    
    .btn-red-outline {
        border: 1px solid #ef4444;
        color: #ef4444;
        background: transparent;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.875rem;
    }
    
    .btn-red-outline:hover {
        background-color: rgba(239, 68, 68, 0.1);
        border-color: #dc2626;
        color: #dc2626;
    }
    
    .btn-light {
        background-color: #f9fafb;
        border-color: #f9fafb;
        color: #374151;
    }
    
    /* Empty State */
    .empty-state-icon {
        opacity: 0.6;
    }
    
    /* Pagination */
    .pagination .page-link {
        color: #4a6bff;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        margin: 0 2px;
        padding: 8px 12px;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #4a6bff;
        border-color: #4a6bff;
        color: white;
    }
    
    .pagination .page-link:hover {
        background-color: rgba(74, 107, 255, 0.1);
        border-color: #4a6bff;
    }
    
    /* Alerts */
    .alert {
        border-radius: 8px;
        border: none;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .table-responsive {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        
        .icon-circle-md {
            width: 40px;
            height: 40px;
        }
    }
</style>
@endpush