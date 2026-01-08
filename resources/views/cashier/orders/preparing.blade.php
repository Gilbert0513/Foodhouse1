<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Preparing Orders | Foodhouse</title>
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
        
        .btn-outline-warning {
            border: 1px solid var(--warning);
            color: var(--warning);
        }
        
        .btn-outline-warning:hover {
            background: var(--warning);
            color: #212529;
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
        
        .badge-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .badge-info {
            background: #cff4fc;
            color: #055160;
            border: 1px solid #9eeaf9;
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
        
        .progress-container {
            background: #e9ecef;
            border-radius: 50px;
            height: 12px;
            margin: 15px 0;
            overflow: hidden;
        }
        
        .progress-bar {
            background: linear-gradient(90deg, var(--warning), #ff9500);
            height: 100%;
            border-radius: 50px;
            transition: width 2s ease-in-out;
        }
        
        .order-card {
            border-left: 4px solid var(--warning);
            transition: all 0.3s ease;
        }
        
        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 193, 7, 0.15);
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
                    <i class="fas fa-clock me-2" style="color: var(--warning);"></i>
                    Preparing Orders
                </h2>
                <p class="text-muted mb-0">Orders currently being prepared in the kitchen</p>
            </div>
            <div class="badge bg-warning text-dark">
                {{ $orders->count() ?? 0 }} orders
            </div>
        </div>

        <!-- Single Order View -->
        @if(isset($order))
        <div class="card order-card mb-4">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">
                    <i class="fas fa-concierge-bell me-2"></i>
                    Order #{{ $order->id }} - In Progress
                </h4>
            </div>
            
            <div class="card-body">
                <!-- Progress Section -->
                <div class="text-center mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <small>Order Placed</small>
                        <small>Being Prepared</small>
                        <small>Ready Soon</small>
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: 75%"></div>
                    </div>
                    <p class="mt-2 text-muted"><i class="fas fa-spinner fa-spin me-1"></i>75% Complete - Preparation stage</p>
                </div>
                
                <!-- Order Details -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="mb-3">
                            <i class="fas fa-receipt me-2"></i>Order Details
                        </h5>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Order Number:</span>
                                <strong class="text-primary">#{{ $order->id }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Placed On:</span>
                                <span>{{ $order->created_at->format('M d, Y h:i A') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Customer:</span>
                                <span>{{ $order->customer_name ?? 'Walk-in Customer' }}</span>
                            </li>
                            @if($order->table_number)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Table Number:</span>
                                <span class="badge bg-info">Table {{ $order->table_number }}</span>
                            </li>
                            @endif
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total Amount:</span>
                                <strong class="text-success">₱{{ number_format($order->total_amount, 2) }}</strong>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="col-md-6">
                        <h5 class="mb-3">
                            <i class="fas fa-clock me-2"></i>Preparation Info
                        </h5>
                        <div class="alert alert-warning">
                            <div class="d-flex">
                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                <div>
                                    <strong>Estimated Ready Time:</strong><br>
                                    {{ $estimatedTime ?? '15-20 minutes' }} from order time
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <div class="d-flex">
                                <i class="fas fa-utensils fa-2x me-3"></i>
                                <div>
                                    <strong>Kitchen Status:</strong><br>
                                    All items are being prepared. The chef has been notified.
                                </div>
                            </div>
                        </div>
                        
                        <!-- Items List -->
                        <h6 class="mt-3">Order Items:</h6>
                        @if($order->items && $order->items->count() > 0)
                        <div class="list-group">
                            @foreach($order->items as $item)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <span>{{ $item->quantity }}x {{ $item->menu_item_name ?? $item->name }}</span>
                                    <span>₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Payment Status -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6>Payment Status:</h6>
                                @if($order->payment_status == 'paid')
                                    <span class="badge badge-paid">PAID</span>
                                    <small class="text-muted ms-2">{{ $order->payments->first()->payment_method ?? 'Cash' }}</small>
                                @else
                                    <span class="badge badge-unpaid">UNPAID</span>
                                @endif
                            </div>
                            
                            <div class="text-end">
                                <small class="text-muted">Order Status:</small><br>
                                <span class="badge bg-warning text-dark">{{ strtoupper($order->status) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="text-center mt-4">
                    <a href="{{ route('cashier.orders.index') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-list me-1"></i> View All Orders
                    </a>
                    
                    @if($order->payment_status != 'paid')
                    <a href="{{ route('cashier.orders.payment', $order) }}" class="btn btn-warning me-2">
                        <i class="fas fa-credit-card me-1"></i> Process Payment
                    </a>
                    @endif
                    
                    <button type="button" class="btn btn-primary mark-ready-btn" data-order-id="{{ $order->id }}">
                        <i class="fas fa-check-circle me-1"></i> Mark as Ready
                    </button>
                </div>
            </div>
        </div>
        
        @else
        <!-- Multiple Orders View (if showing list) -->
        <div class="card">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Preparing Orders List</h5>
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
                @if(isset($orders) && $orders->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-clock fa-3x text-warning mb-3"></i>
                    <h5 class="text-warning">No orders being prepared</h5>
                    <p class="text-muted">All orders are either pending or ready.</p>
                </div>
                @elseif(!isset($orders))
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-primary mb-3"></i>
                    <h5 class="text-primary">Single Order View</h5>
                    <p class="text-muted">Showing details for a specific order.</p>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th class="text-end">Total</th>
                                <th>Progress</th>
                                <th>Payment</th>
                                <th>Time Started</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $orderItem)
                            <tr>
                                <td>
                                    <div class="fw-bold">#{{ $orderItem->id }}</div>
                                    @if($orderItem->table_number)
                                    <small class="text-muted">Table {{ $orderItem->table_number }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $orderItem->customer_name ?? 'Walk-in Customer' }}</div>
                                    @if($orderItem->customer_phone)
                                    <small class="text-muted">{{ $orderItem->customer_phone }}</small>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">
                                    ₱{{ number_format($orderItem->total_amount, 2) }}
                                </td>
                                <td>
                                    <div class="progress-container">
                                        <div class="progress-bar" style="width: 75%"></div>
                                    </div>
                                    <small class="text-muted">75% complete</small>
                                </td>
                                <td>
                                    @if($orderItem->payment_status == 'paid')
                                        <span class="badge badge-paid">{{ ucfirst($orderItem->payment_status) }}</span>
                                        <br>
                                        <small class="text-muted">{{ $orderItem->payments->first()->payment_method ?? 'Cash' }}</small>
                                    @else
                                        <span class="badge badge-unpaid">{{ ucfirst($orderItem->payment_status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $orderItem->updated_at->format('h:i A') }}</div>
                                    <small class="text-muted">{{ $orderItem->updated_at->diffForHumans() }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <!-- View Details -->
                                        <a href="{{ route('cashier.orders.show', $orderItem) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <!-- Mark as Ready -->
                                        <button type="button" 
                                                class="btn btn-sm btn-warning mark-ready-btn" 
                                                data-order-id="{{ $orderItem->id }}"
                                                title="Mark as Ready">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        
                                        @if($orderItem->payment_status != 'paid')
                                        <!-- Process Payment -->
                                        <a href="{{ route('cashier.orders.payment', $orderItem) }}" 
                                           class="btn btn-sm btn-primary" 
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
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        
        // Mark as Ready functionality
        document.querySelectorAll('.mark-ready-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const orderId = this.dataset.orderId;
                
                if (!confirm('Mark this order as READY for pickup/delivery?')) {
                    return;
                }
                
                const originalText = this.innerHTML;
                const originalBg = this.classList.contains('btn-primary') ? 'btn-primary' : 'btn-warning';
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                this.classList.remove(originalBg);
                this.classList.add('btn-secondary');
                this.disabled = true;
                
                fetch(`/cashier/orders/${orderId}/ready`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: 'ready' })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Order marked as READY!');
                        // Redirect to ready orders page
                        window.location.href = "{{ route('cashier.orders.ready') }}";
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error'));
                        resetButton(this, originalText, originalBg);
                    }
                })
                .catch(error => {
                    alert('Failed to update order status: ' + error.message);
                    resetButton(this, originalText, originalBg);
                });
            });
        });
        
        function resetButton(button, originalText, originalBg) {
            button.innerHTML = originalText;
            button.classList.remove('btn-secondary');
            button.classList.add(originalBg);
            button.disabled = false;
        }
        
        // Auto-refresh every 30 seconds (for list view)
        @if(!isset($order) || (isset($orders) && $orders->count() > 0))
        setTimeout(function() {
            window.location.reload();
        }, 30000);
        @endif
        
        // Progress bar animation
        document.querySelectorAll('.progress-bar').forEach(bar => {
            let width = 0;
            const targetWidth = parseInt(bar.style.width);
            const interval = setInterval(() => {
                if (width >= targetWidth) {
                    clearInterval(interval);
                } else {
                    width++;
                    bar.style.width = width + '%';
                }
            }, 20);
        });
    </script>
</body>
</html>