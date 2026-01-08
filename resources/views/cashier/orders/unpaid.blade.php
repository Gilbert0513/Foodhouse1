<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unpaid Orders | Foodhouse</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #e63946;
            --danger: #dc3545;
        }
        
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
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
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
            border: none;
        }
        
        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        
        .order-card {
            border-left: 4px solid var(--danger);
            transition: all 0.3s ease;
        }
        
        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(220, 53, 69, 0.15);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('cashier.dashboard') }}">
                <div class="logo-circle">
                    <i class="fas fa-utensils"></i>
                </div>
                <div>
                    <span class="fw-bold fs-5" style="color: var(--primary);">Foodhouse</span><br>
                    <small class="text-muted">POS System</small>
                </div>
            </a>
            
            <!-- User Info -->
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2 bg-light px-3 py-2 rounded">
                    <i class="fas fa-user-circle text-primary"></i>
                    <span class="small">{{ Auth::user()->name ?? 'Cashier' }}</span>
                </div>
                
                <!-- Nav Buttons -->
                <div class="btn-group">
                    <a href="{{ route('cashier.orders.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-list"></i>
                    </a>
                    <a href="{{ route('cashier.orders.preparing') }}" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-clock"></i>
                    </a>
                    <a href="{{ route('cashier.orders.ready') }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-check"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">
                    <i class="fas fa-exclamation-circle me-2" style="color: var(--danger);"></i>
                    Unpaid Orders
                </h2>
                <p class="text-muted mb-0">Orders pending payment</p>
            </div>
            <span class="badge bg-danger fs-6">{{ $orders->count() }} unpaid</span>
        </div>

        @if($orders->isEmpty())
        <!-- Empty State -->
        <div class="card text-center py-5">
            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
            <h4 class="text-success">All orders are paid! 🎉</h4>
            <p class="text-muted">No pending payments at the moment.</p>
            <a href="{{ route('cashier.orders.index') }}" class="btn btn-primary mt-3">
                <i class="fas fa-arrow-left me-1"></i>Back to Orders
            </a>
        </div>
        @else
        <!-- Orders Grid -->
        <div class="row">
            @foreach($orders as $order)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card order-card h-100">
                    <div class="card-body">
                        <!-- Order Header -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-0">
                                    <i class="fas fa-receipt me-1 text-primary"></i>
                                    #{{ $order->id }}
                                </h5>
                                <small class="text-muted">
                                    {{ $order->created_at->format('h:i A') }}
                                </small>
                            </div>
                            <span class="badge bg-danger">
                                <i class="fas fa-exclamation-circle me-1"></i>UNPAID
                            </span>
                        </div>
                        
                        <!-- Customer -->
                        <div class="mb-3">
                            <h6 class="text-muted">
                                <i class="fas fa-user me-1"></i>
                                {{ $order->customer->name ?? ($order->customer_name ?? 'Walk-in Customer') }}
                            </h6>
                            @if($order->table_number)
                            <span class="badge bg-info">Table {{ $order->table_number }}</span>
                            @endif
                        </div>
                        
                        <!-- Status & Items -->
                        <div class="mb-3">
                            <span class="badge bg-warning">{{ ucfirst($order->status) }}</span>
                            @if($order->items && $order->items->count() > 0)
                            <small class="text-muted ms-2">
                                {{ $order->items->count() }} items
                            </small>
                            @endif
                        </div>
                        
                        <!-- Amount & Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <div class="fs-5 fw-bold text-danger">
                                ₱{{ number_format($order->total_amount ?? $order->total, 2) }}
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('cashier.orders.show', $order) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('cashier.orders.payment', $order) }}" 
                                   class="btn btn-sm btn-success">
                                    <i class="fas fa-credit-card"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-refresh every 30 seconds
        setTimeout(() => window.location.reload(), 30000);
    </script>
</body>
</html>