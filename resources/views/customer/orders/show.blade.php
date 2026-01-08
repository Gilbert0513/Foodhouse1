@extends('layouts.customer')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Order #{{ $order->order_number }}
                    </h5>
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'confirmed' => 'primary',
                            'preparing' => 'info',
                            'ready' => 'success',
                            'served' => 'secondary',
                            'completed' => 'dark',
                            'cancelled' => 'danger'
                        ];
                        $color = $statusColors[$order->status] ?? 'secondary';
                    @endphp
                    <span class="badge bg-{{ $color }} fs-6">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <!-- Order Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><i class="fas fa-info-circle text-primary me-2"></i>Order Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="40%">Order Number:</th>
                                <td>{{ $order->order_number }}</td>
                            </tr>
                            <tr>
                                <th>Date & Time:</th>
                                <td>{{ $order->created_at->format('F d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Order Type:</th>
                                <td>
                                    @if($order->order_type == 'dine_in')
                                        <span class="badge bg-info">Dine In</span>
                                        @if($order->table_number)
                                            (Table {{ $order->table_number }})
                                        @endif
                                    @elseif($order->order_type == 'takeaway')
                                        <span class="badge bg-warning">Takeaway</span>
                                    @else
                                        {{ ucfirst($order->order_type) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Pax:</th>
                                <td>{{ $order->pax ?? 1 }} person(s)</td>
                            </tr>
                            <tr>
                                <th>Service Staff:</th>
                                <td>{{ $order->waiter_name ?? 'Not assigned' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-user text-primary me-2"></i>Customer Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="40%">Name:</th>
                                <td>{{ $order->customer->full_name ?? ($order->customer_name ?? 'Walk-in Customer') }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $order->customer->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ $order->customer_phone ?? 'N/A' }}</td>
                            </tr>
                            @if($order->order_type == 'delivery')
                            <tr>
                                <th>Address:</th>
                                <td>{{ $order->delivery_address ?? 'N/A' }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- Order Items -->
                <h6 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-utensils text-primary me-2"></i>Order Items
                </h6>

                @if($order->orderDetails && $order->orderDetails->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderDetails as $detail)
                            @php
                                $itemStatus = $detail->item_status ?? 'pending';
                                $itemStatusColors = [
                                    'pending' => 'warning',
                                    'preparing' => 'info',
                                    'ready' => 'success',
                                    'served' => 'secondary'
                                ];
                                $itemColor = $itemStatusColors[$itemStatus] ?? 'secondary';
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $detail->menuItem->item_name ?? 'Item' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $detail->menuItem->description ?? '' }}</small>
                                    @if($detail->special_instructions)
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-sticky-note"></i> {{ $detail->special_instructions }}
                                        </small>
                                    @endif
                                </td>
                                <td class="text-center">{{ $detail->quantity }}</td>
                                <td class="text-end">₱{{ number_format($detail->unit_price ?? $detail->price, 2) }}</td>
                                <td class="text-end">₱{{ number_format($detail->subtotal ?? ($detail->quantity * $detail->unit_price), 2) }}</td>
                                <td class="text-center">
                                    @if(auth()->user()->isAdmin() || auth()->user()->isCashier())
                                        <select class="form-select form-select-sm item-status-select" 
                                                data-item-id="{{ $detail->id }}"
                                                style="width: 100px;">
                                            <option value="pending" {{ $itemStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="preparing" {{ $itemStatus == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                            <option value="ready" {{ $itemStatus == 'ready' ? 'selected' : '' }}>Ready</option>
                                            <option value="served" {{ $itemStatus == 'served' ? 'selected' : '' }}>Served</option>
                                        </select>
                                    @else
                                        <span class="badge bg-{{ $itemColor }}">
                                            {{ ucfirst($itemStatus) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>No order items found.
                </div>
                @endif

                <!-- Order Totals -->
                <div class="row justify-content-end mt-4">
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>₱{{ number_format($order->total_amount ?? 0, 2) }}</span>
                            </div>
                            @if($order->tax_amount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax ({{ $order->tax_rate ?? 12 }}%):</span>
                                <span>₱{{ number_format($order->tax_amount ?? 0, 2) }}</span>
                            </div>
                            @endif
                            @if($order->service_charge > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Service Charge:</span>
                                <span>₱{{ number_format($order->service_charge, 2) }}</span>
                            </div>
                            @endif
                            @if($order->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount:</span>
                                <span class="text-danger">-₱{{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Grand Total:</strong>
                                <strong class="text-primary">₱{{ number_format($order->grand_total ?? $order->total_amount, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Special Instructions -->
                @if($order->special_instructions)
                <div class="mt-4">
                    <h6><i class="fas fa-sticky-note text-primary me-2"></i>Special Instructions</h6>
                    <div class="border rounded p-3 bg-light">
                        {{ $order->special_instructions }}
                    </div>
                </div>
                @endif

                <!-- Customer Actions -->
                @if(auth()->user()->isCustomer())
                <hr>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Orders
                    </a>
                    
                    @if(in_array($order->status, ['pending', 'confirmed']))
                    <form action="{{ route('customer.orders.cancel', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger" 
                                onclick="return confirm('Are you sure you want to cancel this order?')">
                            <i class="fas fa-times me-2"></i>Cancel Order
                        </button>
                    </form>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment & Timeline Sidebar -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payment Status</h6>
            </div>
            <div class="card-body">
                @if($order->payment_status == 'paid')
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Paid</strong>
                    @if($order->paid_at)
                        <br>
                        <small>Paid on: {{ $order->paid_at->format('M d, Y h:i A') }}</small>
                    @endif
                    @if($order->payment_method)
                        <br>
                        <small>Method: {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</small>
                    @endif
                    @if($order->change_amount > 0)
                        <br>
                        <small>Change: ₱{{ number_format($order->change_amount, 2) }}</small>
                    @endif
                </div>
                @else
                <div class="alert alert-warning">
                    <i class="fas fa-clock me-2"></i>
                    <strong>Pending Payment</strong>
                    <br>
                    <small>Amount Due: ₱{{ number_format($order->grand_total, 2) }}</small>
                </div>
                @endif
            </div>
        </div>

        <!-- Timeline -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i>Order Timeline</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item {{ $order->created_at ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Order Placed</h6>
                            <p class="text-muted mb-0">{{ $order->created_at->format('M d, h:i A') }}</p>
                        </div>
                    </div>

                    @if(in_array($order->status, ['confirmed', 'preparing', 'ready', 'served', 'completed']))
                    <div class="timeline-item active">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Order Confirmed</h6>
                            <p class="text-muted mb-0">Preparing your order</p>
                        </div>
                    </div>
                    @endif

                    @if(in_array($order->status, ['ready', 'served', 'completed']))
                    <div class="timeline-item active">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Ready for Pickup/Serving</h6>
                            <p class="text-muted mb-0">Your order is ready</p>
                        </div>
                    </div>
                    @endif

                    @if($order->status == 'completed')
                    <div class="timeline-item active">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Order Completed</h6>
                            <p class="text-muted mb-0">Thank you for your order!</p>
                        </div>
                    </div>
                    @endif

                    @if($order->status == 'cancelled')
                    <div class="timeline-item">
                        <div class="timeline-marker bg-danger"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Order Cancelled</h6>
                            <p class="text-muted mb-0">Order was cancelled</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
}
.timeline-item.active .timeline-marker {
    background-color: #4e73df;
    border-color: #4e73df;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 12px;
    height: 12px;
    border: 2px solid #e3e6f0;
    border-radius: 50%;
    background-color: #fff;
}
.timeline-content {
    padding-bottom: 10px;
}
.item-status-select {
    max-width: 120px;
}
</style>

@push('scripts')
<script>
    // Change calculation
    document.querySelector('input[name="amount_tendered"]')?.addEventListener('input', function() {
        const total = {{ $order->grand_total }};
        const tendered = parseFloat(this.value) || 0;
        const change = tendered - total;
        const changeField = document.getElementById('changeDue');
        if (change >= 0) {
            changeField.value = '₱' + change.toFixed(2);
            changeField.style.color = '#28a745';
        } else {
            changeField.value = '₱' + Math.abs(change).toFixed(2) + ' (Short)';
            changeField.style.color = '#dc3545';
        }
    });

    function setAmount(amount) {
        const input = document.querySelector('input[name="amount_tendered"]');
        input.value = amount.toFixed(2);
        input.dispatchEvent(new Event('input'));
    }
</script>
@endpush
@endsection
