@extends('layouts.admin')

@section('title', 'Order Details')
@section('page-title', 'Order #{{ $orderId }}')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order Details - #ORD-{{ str_pad($orderId, 4, '0', STR_PAD_LEFT) }}</h5>
                <div>
                    <span class="badge bg-success me-2">Completed</span>
                    <span class="badge bg-success">Paid</span>
                </div>
            </div>
            <div class="card-body">
                <!-- Customer Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <p class="mb-1"><strong>Name:</strong> John Doe</p>
                        <p class="mb-1"><strong>Email:</strong> john@example.com</p>
                        <p class="mb-1"><strong>Phone:</strong> +63 912 345 6789</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Order Information</h6>
                        <p class="mb-1"><strong>Order Date:</strong> 2024-01-15 14:30:00</p>
                        <p class="mb-1"><strong>Order Type:</strong> Dine-in</p>
                        <p class="mb-1"><strong>Table #:</strong> 05</p>
                    </div>
                </div>

                <!-- Order Items -->
                <h6>Order Items</h6>
                <div class="table-responsive mb-4">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Chicken Adobo</td>
                                <td>2</td>
                                <td>₱150.00</td>
                                <td>₱300.00</td>
                            </tr>
                            <tr>
                                <td>Halo-Halo</td>
                                <td>1</td>
                                <td>₱120.00</td>
                                <td>₱120.00</td>
                            </tr>
                            <tr>
                                <td>Coke (500ml)</td>
                                <td>2</td>
                                <td>₱45.00</td>
                                <td>₱90.00</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                <td><strong>₱510.00</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Tax (12%):</strong></td>
                                <td><strong>₱61.20</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>₱571.20</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Payment Information -->
                <h6>Payment Information</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Payment Method:</strong> Cash</p>
                        <p class="mb-1"><strong>Payment Status:</strong> Paid</p>
                        <p class="mb-1"><strong>Amount Paid:</strong> ₱600.00</p>
                        <p class="mb-1"><strong>Change:</strong> ₱28.80</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Cashier:</strong> Maria Santos</p>
                        <p class="mb-1"><strong>Order Taken By:</strong> POS Terminal #1</p>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Orders
                    </a>
                    <button class="btn btn-primary">
                        <i class="fas fa-print me-2"></i> Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Order Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-success">
                        <i class="fas fa-check me-2"></i> Mark as Completed
                    </button>
                    <button class="btn btn-warning">
                        <i class="fas fa-clock me-2"></i> Mark as Preparing
                    </button>
                    <button class="btn btn-danger">
                        <i class="fas fa-times me-2"></i> Cancel Order
                    </button>
                    <button class="btn btn-info">
                        <i class="fas fa-receipt me-2"></i> Generate Invoice
                    </button>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Order Timeline</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-circle text-success me-2"></i>
                        <strong>Order Placed</strong>
                        <small class="text-muted d-block">2024-01-15 14:30</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-circle text-warning me-2"></i>
                        <strong>Payment Received</strong>
                        <small class="text-muted d-block">2024-01-15 14:31</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-circle text-info me-2"></i>
                        <strong>Order Prepared</strong>
                        <small class="text-muted d-block">2024-01-15 14:45</small>
                    </li>
                    <li>
                        <i class="fas fa-circle text-success me-2"></i>
                        <strong>Order Served</strong>
                        <small class="text-muted d-block">2024-01-15 14:50</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection