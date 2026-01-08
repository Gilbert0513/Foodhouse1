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
        
        .card {
            background: white;
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        
        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background: var(--warning);
            border: none;
            color: #212529;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 500;
        }
        
        .btn-warning:hover {
            background: var(--warning-dark);
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
            color: #ffc107;
            margin-bottom: 1rem;
        }
        
        .order-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--warning);
        }
        
        .badge-warning {
            background: var(--warning);
            color: #212529;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }
        
        .amount-display {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('cashier.dashboard') }}">
                <div class="logo-circle">
                    <i class="fas fa-utensils"></i>
                </div>
                <div>
                    <div class="fw-bold" style="color: var(--primary);">Foodhouse</div>
                    <div class="small text-muted">POS System</div>
                </div>
            </a>
            
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small">
                    <i class="fas fa-user-circle me-1"></i>
                    {{ Auth::user()->name ?? 'Cashier' }}
                </span>
                <a href="{{ route('cashier.orders.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back to Orders
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">
        <!-- Payment Status Header -->
        <div class="payment-header">
            <div class="payment-icon">
                <i class="fas fa-clock"></i>
            </div>
            <h2 class="text-warning fw-bold mb-2">Payment Pending</h2>
            <p class="text-muted mb-3">Order #{{ $order->order_number ?? $order->id }} is awaiting payment</p>
            <span class="badge-warning">
                <i class="fas fa-exclamation-circle me-1"></i> PENDING PAYMENT
            </span>
        </div>

        <div class="row g-4">
            <!-- Left Column - Order Details -->
            <div class="col-lg-8">
                <!-- Order Information -->
                <div class="card mb-4">
                    <div class="card-header">
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

                <!-- Order Items -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-utensils me-2"></i>Order Items
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="table-light">
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
                    <div class="card-header">
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
                            
                            <!-- Print Button - Removed until route is defined -->
                            <button onclick="window.print()" class="btn btn-outline-dark">
                                <i class="fas fa-print me-2"></i> Print This Page
                            </button>
                        </div>

                        <!-- Info Message -->
                        <div class="alert alert-light border mt-4">
                            <div class="d-flex">
                                <i class="fas fa-info-circle text-warning me-2 mt-1"></i>
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
                window.print();
            }
        });
        
        // Print function for better printing
        function printPage() {
            const printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Order #{{ $order->order_number ?? $order->id }} - Payment Pending</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #ccc; padding-bottom: 10px; }
                        .header h2 { color: #e63946; margin: 0; }
                        .info { margin-bottom: 15px; }
                        .info strong { display: inline-block; width: 120px; }
                        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                        th { background: #f8f9fa; text-align: left; padding: 8px; border: 1px solid #dee2e6; }
                        td { padding: 8px; border: 1px solid #dee2e6; }
                        .total-row { font-weight: bold; background: #f8f9fa; }
                        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h2>Foodhouse - Order #{{ $order->order_number ?? $order->id }}</h2>
                        <h3 style="color: #ffc107;">PAYMENT PENDING</h3>
                        <p>Generated: ${new Date().toLocaleString()}</p>
                    </div>
                    
                    <div class="info">
                        <p><strong>Customer:</strong> {{ $order->customer_name ?? 'Walk-in Customer' }}</p>
                        <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                        <p><strong>Order Type:</strong> {{ str_replace('_', ' ', $order->order_type) }}</p>
                        @if($order->table_number)
                        <p><strong>Table:</strong> {{ $order->table_number }}</p>
                        @endif
                        <p><strong>Pax:</strong> {{ $order->pax ?? 1 }}</p>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₱{{ number_format($item->unit_price, 2) }}</td>
                                <td>₱{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="3" align="right">Subtotal:</td>
                                <td>₱{{ number_format($order->subtotal, 2) }}</td>
                            </tr>
                            <tr class="total-row">
                                <td colspan="3" align="right">Tax (12%):</td>
                                <td>₱{{ number_format($order->tax, 2) }}</td>
                            </tr>
                            <tr style="background: #fff3cd; font-size: 1.2em;">
                                <td colspan="3" align="right"><strong>TOTAL DUE:</strong></td>
                                <td><strong>₱{{ number_format($order->total_amount, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <div class="footer">
                        <p>*** This order is pending payment ***</p>
                        <p>Foodhouse POS System</p>
                    </div>
                </body>
                </html>
            `;
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        }
        
        // Replace the print button functionality
        document.querySelector('.btn-outline-dark').addEventListener('click', printPage);
    </script>
</body>
</html>