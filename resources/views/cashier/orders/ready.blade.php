<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ready Orders | Foodhouse</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #e63946;
            --primary-dark: #d62839;
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
            padding: 8px 16px;
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
        
        .btn-outline-primary {
            border: 1px solid var(--primary);
            color: var(--primary);
        }
        
        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.875rem;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }
        
        .table td {
            border-color: #eee;
            vertical-align: middle;
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
        
        .badge {
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .badge-success {
            background: #d1e7dd;
            color: #0a3622;
            border: 1px solid #a3cfbb;
        }
        
        .badge-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .badge-unpaid {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .badge-paid {
            background: #d1e7dd;
            color: #0a3622;
            border: 1px solid #a3cfbb;
        }
        
        @media (max-width: 768px) {
            .table {
                font-size: 0.9rem;
            }
            
            .badge {
                padding: 0.35rem 0.75rem;
                font-size: 0.75rem;
            }
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
                    <i class="fas fa-check-circle me-2" style="color: #28a745;"></i>
                    Ready Orders
                </h2>
                <p class="text-muted mb-0">Orders ready for pickup or delivery</p>
            </div>
            <div class="badge bg-success">
                {{ $orders->count() }} orders
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ready Orders List</h5>
                    <div class="btn-group">
                        <a href="{{ route('cashier.orders.today') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-calendar-day me-1"></i>Today
                        </a>
                        <a href="{{ route('cashier.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-list me-1"></i>All Orders
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                @if($orders->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5 class="text-success">No orders ready</h5>
                    <p class="text-muted">All orders are being prepared or have been served.</p>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th class="text-end">Total</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Ready Time</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>
                                    <div class="fw-bold">#{{ $order->id }}</div>
                                    @if($order->table_number)
                                    <small class="text-muted">Table {{ $order->table_number }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $order->customer_name ?? 'Walk-in Customer' }}</div>
                                    @if($order->customer_phone)
                                    <small class="text-muted">{{ $order->customer_phone }}</small>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">
                                    ₱{{ number_format($order->total_amount, 2) }}
                                </td>
                                <td>
                                    <span class="badge badge-success">{{ ucfirst($order->status) }}</span>
                                    <br>
                                    <small class="text-muted">Pax: {{ $order->pax ?? 1 }}</small>
                                </td>
                                <td>
                                    @if($order->payment_status == 'paid')
                                        <span class="badge badge-paid">{{ ucfirst($order->payment_status) }}</span>
                                        <br>
                                        <small class="text-muted">{{ $order->payments->first()->payment_method ?? 'Cash' }}</small>
                                    @else
                                        <span class="badge badge-unpaid">{{ ucfirst($order->payment_status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $order->updated_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $order->updated_at->format('h:i A') }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <!-- View Details -->
                                        <a href="{{ route('cashier.orders.show', $order) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <!-- Mark as Completed -->
                                        @if($order->payment_status == 'paid')
                                        <a href="#" 
                                           class="btn btn-sm btn-success mark-completed" 
                                           data-order-id="{{ $order->id }}"
                                           title="Mark as Completed">
                                            <i class="fas fa-flag-checkered"></i>
                                        </a>
                                        @else
                                        <!-- Process Payment -->
                                        <a href="{{ route('cashier.orders.payment', $order) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Process Payment">
                                            <i class="fas fa-credit-card"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        // Mark as Completed
        document.querySelectorAll('.mark-completed').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const orderId = this.dataset.orderId;
                
                if (!confirm('Mark this order as completed (served/delivered)?')) {
                    return;
                }
                
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                this.disabled = true;
                
                fetch(`/cashier/orders/${orderId}/complete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Order marked as completed!');
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    alert('Failed to update order status');
                    this.innerHTML = originalText;
                    this.disabled = false;
                });
            });
        });
        
        // Auto-refresh every 30 seconds
        setTimeout(function() {
            window.location.reload();
        }, 30000);
    </script>
</body>
</html>