@extends('layouts.cashier')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-receipt text-primary mr-2"></i>Order #{{ $order->order_number }}
        </h1>
        <div>
            <a href="{{ route('cashier.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Orders
            </a>
            @if($order->payment_status != 'paid')
                <a href="{{ route('cashier.orders.payment', $order) }}" class="btn btn-success">
                    <i class="fas fa-credit-card mr-2"></i>Process Payment
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Main Order Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Order Details</h6>
                    <div>
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
                            $paymentColors = [
                                'paid' => 'success',
                                'pending' => 'warning',
                                'partial' => 'info',
                                'unpaid' => 'danger'
                            ];
                            $color = $statusColors[$order->status] ?? 'secondary';
                            $paymentColor = $paymentColors[$order->payment_status] ?? 'secondary';
                        @endphp
                        <span class="badge badge-{{ $color }} px-3 py-2">{{ ucfirst($order->status) }}</span>
                        <span class="badge badge-{{ $paymentColor }} px-3 py-2">{{ ucfirst($order->payment_status) }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Order Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-primary mb-3">
                                <i class="fas fa-info-circle mr-2"></i>Order Information
                            </h6>
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
                                            <span class="badge badge-info">Dine In</span>
                                            @if($order->table_number)
                                                (Table {{ $order->table_number }})
                                            @endif
                                        @elseif($order->order_type == 'takeaway')
                                            <span class="badge badge-warning">Takeaway</span>
                                        @else
                                            {{ ucfirst($order->order_type) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Pax:</th>
                                    <td>{{ $order->pax ?? 1 }} person(s)</td>
                                </tr>
                                @if($order->waiter_name)
                                <tr>
                                    <th>Waiter:</th>
                                    <td>{{ $order->waiter_name }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-primary mb-3">
                                <i class="fas fa-user mr-2"></i>Customer Information
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%">Name:</th>
                                    <td>{{ $order->customer->name ?? $order->customer_name ?? 'Walk-in Customer' }}</td>
                                </tr>
                                @if($order->customer)
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $order->customer->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $order->customer->phone ?? 'N/A' }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <h6 class="font-weight-bold text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-utensils mr-2"></i>Order Items
                    </h6>

                    @if($order->items && $order->items->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-right">Price</th>
                                    <th class="text-right">Subtotal</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                @php
                                    $itemStatus = $item->item_status ?? 'pending';
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
                                        <strong>{{ $item->menuItem->item_name ?? 'Item' }}</strong>
                                        @if($item->special_instructions)
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-sticky-note"></i> {{ $item->special_instructions }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-right">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="text-right">₱{{ number_format($item->total ?? ($item->quantity * $item->price), 2) }}</td>
                                    <td class="text-center">
                                        @if(auth()->user()->isAdmin() || auth()->user()->isCashier())
                                            <span class="badge badge-{{ $itemColor }}">
                                                {{ ucfirst($itemStatus) }}
                                            </span>
                                        @else
                                            <span class="badge badge-{{ $itemColor }}">
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
                        <i class="fas fa-info-circle mr-2"></i>No order items found.
                    </div>
                    @endif

                    <!-- Order Totals -->
                    <div class="row justify-content-end mt-4">
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>₱{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                                @if($order->tax_amount > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tax ({{ $order->tax_rate ?? 12 }}%):</span>
                                    <span>₱{{ number_format($order->tax_amount, 2) }}</span>
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
                                    <strong class="text-primary">₱{{ number_format($order->grand_total, 2) }}</strong>
                                </div>

                                @if($order->payment_status == 'paid')
                                <hr>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Amount Paid:</span>
                                    <span class="text-success">₱{{ number_format($order->amount_tendered ?? $order->grand_total, 2) }}</span>
                                </div>
                                @if($order->change_amount > 0)
                                <div class="d-flex justify-content-between">
                                    <span>Change:</span>
                                    <span>₱{{ number_format($order->change_amount, 2) }}</span>
                                </div>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Special Instructions -->
                    @if($order->special_instructions)
                    <div class="mt-4">
                        <h6 class="font-weight-bold text-primary">
                            <i class="fas fa-sticky-note mr-2"></i>Special Instructions
                        </h6>
                        <div class="border rounded p-3 bg-light">
                            {{ $order->special_instructions }}
                        </div>
                    </div>
                    @endif

                    <!-- Staff Actions -->
                    @if(auth()->user()->isAdmin() || auth()->user()->isCashier())
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-primary mb-3">
                                <i class="fas fa-cogs mr-2"></i>Staff Actions
                            </h6>

                            <!-- Update Order Status -->
                            <form action="{{ route('cashier.orders.update-status', $order) }}" method="POST" class="mb-3">
                                @csrf
                                @method('PATCH')
                                <div class="form-group">
                                    <label class="font-weight-bold">Update Order Status</label>
                                    <div class="input-group">
                                        <select name="status" class="form-control" required>
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                            <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                            <option value="served" {{ $order->status == 'served' ? 'selected' : '' }}>Served</option>
                                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Quick Actions -->
                            <div class="btn-group mb-3">
                                @if($order->status != 'completed')
                                <form action="{{ route('cashier.orders.complete', $order) }}" method="POST" class="mr-2">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check mr-1"></i>Mark as Complete
                                    </button>
                                </form>
                                @endif
                                @if($order->status == 'ready')
                                <form action="{{ route('cashier.orders.serve', $order) }}" method="POST" class="mr-2">
                                    @csrf
                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-concierge-bell mr-1"></i>Mark as Served
                                    </button>
                                </form>
                                @endif
                                @if(in_array($order->status, ['pending', 'confirmed', 'preparing']))
                                <form action="{{ route('cashier.orders.cancel', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Are you sure you want to cancel this order?')">
                                        <i class="fas fa-times mr-1"></i>Cancel Order
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>

                        <!-- Print Actions -->
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-primary mb-3">
                                <i class="fas fa-print mr-2"></i>Print Options
                            </h6>
                            <div class="d-flex flex-column">
                                <a href="{{ route('cashier.orders.print-receipt', $order) }}" 
                                   class="btn btn-outline-dark mb-2" target="_blank">
                                    <i class="fas fa-receipt mr-2"></i>Print Receipt
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Payment Status -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-credit-card mr-2"></i>Payment Status
                    </h6>
                </div>
                <div class="card-body">
                    @if($order->payment_status == 'paid')
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle mr-2"></i>
                        <strong>Paid</strong>
                        @if($order->paid_at)
                            <br>
                            <small>Paid on: {{ $order->paid_at->format('M d, Y h:i A') }}</small>
                        @endif
                        @if($order->payment_method)
                            <br>
                            <small>Method: {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</small>
                        @endif
                    </div>
                    
                    @if($order->payments && $order->payments->count() > 0)
                    <h6 class="font-weight-bold mt-3">Payment Details</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->payments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('h:i A') }}</td>
                                    <td>{{ ucfirst($payment->payment_method) }}</td>
                                    <td class="text-right">₱{{ number_format($payment->amount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    @else
                    <div class="alert alert-warning">
                        <i class="fas fa-clock mr-2"></i>
                        <strong>Pending Payment</strong>
                        <br>
                        <small>Amount Due: ₱{{ number_format($order->grand_total, 2) }}</small>
                        <div class="mt-3">
                            <a href="{{ route('cashier.orders.payment', $order) }}" class="btn btn-success btn-block">
                                <i class="fas fa-credit-card mr-2"></i>Process Payment
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history mr-2"></i>Order Timeline
                    </h6>
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
</style>
@endsection
