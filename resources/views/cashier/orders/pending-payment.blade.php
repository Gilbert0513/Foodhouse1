<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Pending | Foodhouse</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #e63946;
            --primary-dark: #d62839;
            --warning: #ffc107;
            --warning-dark: #e0a800;
        }
        
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        
        .logo-circle {
            width: 40px;
            height: 40px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 1.2rem;
        }
        
        .brand-text {
            display: flex;
            flex-direction: column;
        }
        
        .brand-main {
            font-weight: 700;
            font-size: 1.4rem;
            color: var(--primary);
        }
        
        .brand-sub {
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 400;
            margin-top: -2px;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 12px 24px;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(230, 57, 70, 0.3);
        }
        
        .btn-warning {
            background: var(--warning);
            color: #212529;
        }
        
        .btn-warning:hover {
            background: var(--warning-dark);
            transform: translateY(-2px);
        }
        
        .btn-outline-primary {
            border: 1px solid var(--primary);
            color: var(--primary);
        }
        
        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-outline-secondary {
            border: 1px solid #6c757d;
            color: #6c757d;
        }
        
        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-outline-dark {
            border: 1px solid #343a40;
            color: #343a40;
        }
        
        .btn-outline-dark:hover {
            background: #343a40;
            color: white;
            transform: translateY(-2px);
        }
        
        .payment-header {
            text-align: center;
            padding: 2rem;
            border-radius: 12px;
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            margin-bottom: 2rem;
            border: 1px solid #ffecb5;
        }
        
        .payment-icon {
            font-size: 4rem;
            color: var(--warning);
            margin-bottom: 1rem;
        }
        
        .badge-warning {
            background: var(--warning);
            color: #212529;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            border: none;
        }
        
        .amount-display {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin: 1rem 0;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }
        
        .table td {
            border-color: #eee;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(230, 57, 70, 0.05);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid #eee;
        }
        
        .user-info i {
            color: var(--primary);
        }
        
        @media (max-width: 768px) {
            .btn {
                padding: 10px 20px;
            }
            
            .payment-icon {
                font-size: 3rem;
            }
            
            .amount-display {
                font-size: 2rem;
            }
        }
        
        .badge {
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-weight: 500;
        }
        
        .badge.bg-warning {
            background: var(--warning) !important;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <!-- Logo and Brand -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('cashier.dashboard') }}">
                <div class="logo-circle">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="brand-text">
                    <span class="brand-main">Foodhouse</span>
                    <span class="brand-sub">POS System</span>
                </div>
            </a>
            
            <!-- User Info and Back Button -->
            <div class="d-flex align-items-center gap-3">
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <span class="small">{{ Auth::user()->name ?? 'Cashier' }}</span>
                </div>
                
                <a href="{{ route('cashier.orders.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back to Orders
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">
                    <i class="fas fa-clock me-2" style="color: var(--warning);"></i>
                    Payment Pending
                </h2>
                <p class="text-muted mb-0">Order #{{ $order->order_number ?? $order->id }} is awaiting payment</p>
            </div>
            <div class="badge-warning">
                <i class="fas fa-exclamation-circle me-1"></i> PENDING PAYMENT
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column - Order Details -->
            <div class="col-lg-8">
                <!-- Order Information Card -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>Order Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="text-muted small">Order Number</div>
                                <div class="fw-bold">#{{ $order->order_number ?? $order->id }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="text-muted small">Order Date</div>
                                <div class="fw-bold">{{ $order->created_at->format('M d, Y h:i A') }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="text-muted small">Customer</div>
                                <div class="fw-bold">{{ $order->customer_name ?? 'Walk-in Customer' }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="text-muted small">Order Type</div>
                                <div class="fw-bold text-capitalize">{{ str_replace('_', ' ', $order->order_type) }}</div>
                            </div>
                            @if($order->table_number)
                            <div class="col-md-6 mb-3">
                                <div class="text-muted small">Table Number</div>
                                <div class="fw-bold">{{ $order->table_number }}</div>
                            </div>
                            @endif
                            <div class="col-md-6 mb-3">
                                <div class="text-muted small">Pax</div>
                                <div class="fw-bold">{{ $order->pax ?? 1 }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items Card -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-utensils me-2"></i>Order Items
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->item_name }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">₱{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-end fw-bold">₱{{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                        <td class="text-end">₱{{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Tax (12%):</strong></td>
                                        <td class="text-end">₱{{ number_format($order->tax, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td class="text-end fw-bold fs-5" style="color: var(--primary);">
                                            ₱{{ number_format($order->total_amount, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Payment Actions -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-credit-card me-2"></i>Payment Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Amount Due -->
                        <div class="text-center mb-4">
                            <div class="text-muted small">Amount Due</div>
                            <div class="amount-display">₱{{ number_format($order->total_amount, 2) }}</div>
                            <div class="text-muted small">to be paid</div>
                        </div>

                        <!-- Status Badges -->
                        <div class="d-flex justify-content-between mb-4">
                            <div class="text-center">
                                <div class="text-muted small mb-1">Payment</div>
                                <span class="badge bg-warning">
                                    {{ strtoupper($order->payment_status) }}
                                </span>
                            </div>
                            <div class="text-center">
                                <div class="text-muted small mb-1">Order</div>
                                <span class="badge 
                                    @if($order->status == 'pending') bg-warning
                                    @elseif($order->status == 'preparing') bg-info
                                    @elseif($order->status == 'ready') bg-success
                                    @else bg-secondary @endif">
                                    {{ strtoupper($order->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-3">
                            <!-- Process Payment Button -->
                            <a href="{{ route('cashier.orders.payment', $order) }}" class="btn btn-warning">
                                <i class="fas fa-credit-card me-2"></i> Process Payment
                            </a>
                            
                            <!-- View Order Details -->
                            <a href="{{ route('cashier.orders.show', $order) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-2"></i> View Full Details
                            </a>
                            
                            <!-- Back to Orders -->
                            <a href="{{ route('cashier.orders.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i> Back to All Orders
                            </a>
                            
                            <!-- Print Button -->
                            <a href="{{ route('cashier.orders.print', $order) }}" class="btn btn-outline-dark" target="_blank">
                                <i class="fas fa-print me-2"></i> Print Order
                            </a>
                        </div>

                        <!-- Info Message -->
                        <div class="alert alert-light border mt-4">
                            <div class="d-flex">
                                <i class="fas fa-info-circle me-2 mt-1" style="color: var(--warning);"></i>
                                <div class="small">
                                    <strong>Note:</strong> This order is waiting for payment. 
                                    Process payment when the customer is ready to pay.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto-refresh page every 30 seconds to check for payment updates
        setTimeout(function() {
            window.location.reload();
        }, 30000);

        // Keyboard shortcut for processing payment
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + Enter to process payment
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                window.location.href = "{{ route('cashier.orders.payment', $order) }}";
            }
            
            // Escape to go back
            if (e.key === 'Escape') {
                window.history.back();
            }
            
            // P key for print
            if (e.key === 'p' && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                window.open("{{ route('cashier.orders.print', $order) }}", '_blank');
            }
        });
    </script>
</body>
</html>