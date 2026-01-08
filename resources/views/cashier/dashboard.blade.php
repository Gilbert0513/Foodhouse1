<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Foodhouse</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #e63946;
            --primary-dark: #d62839;
            --secondary-color: #457b9d;
            --light-gray: #f8f9fa;
            --dark-gray: #343a40;
            --border-radius: 12px;
            --box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            --transition: all 0.3s ease;
        }

        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', system-ui, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.5rem;
            text-decoration: none;
        }

        .logo-circle {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
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
        }

        .brand-sub {
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 400;
            margin-top: -2px;
        }

        .main-container {
            padding: 2rem 0;
            min-height: calc(100vh - 80px);
        }

        .card {
            background: white;
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--box-shadow);
            overflow: hidden;
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            padding: 1.5rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Stats Cards */
        .stat-card {
            border-radius: var(--border-radius);
            padding: 1.5rem;
            text-align: center;
            transition: var(--transition);
            border: 1px solid #eee;
            background: white;
        }

        .stat-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-3px);
        }

        .stat-card h6 {
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card h4 {
            color: #333;
            font-weight: 700;
            margin: 0;
            font-size: 2rem;
        }

        /* Action Buttons */
        .action-card {
            text-align: center;
            padding: 2rem 1rem;
            border-radius: var(--border-radius);
            color: white;
            text-decoration: none;
            display: block;
            transition: var(--transition);
            border: none;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .action-card:hover {
            transform: translateY(-10px) scale(1.02);
            text-decoration: none;
            color: white;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .action-card i {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }

        .action-card h5 {
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
        }

        .action-card small {
            opacity: 0.85;
            font-size: 0.9rem;
        }

        .btn-primary-action {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }

        .btn-success-action {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        }

        .btn-info-action {
            background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);
        }

        .btn-warning-action {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
        }

        .btn-warning-action small {
            color: rgba(33, 37, 41, 0.85);
        }

        /* Quick Access Buttons */
        .quick-btn {
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 500;
            transition: var(--transition);
            border: 1px solid #dee2e6;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #333;
        }

        .quick-btn:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(230, 57, 70, 0.2);
            text-decoration: none;
        }

        .quick-btn i {
            margin-right: 8px;
            font-size: 1.1rem;
        }

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .welcome-banner h2 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-banner p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        /* Time Display */
        .time-display {
            font-size: 0.9rem;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid #eee;
        }

        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid #eee;
        }

        .user-info i {
            color: var(--primary-color);
        }

        /* Print Button */
        .print-btn {
            background: #6c757d;
            border: none;
            color: white;
        }

        .print-btn:hover {
            background: #5a6268;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .action-card {
                min-height: 180px;
                padding: 1.5rem 1rem;
            }
            
            .action-card i {
                font-size: 2.5rem;
            }
            
            .navbar-brand {
                font-size: 1.2rem;
            }
            
            .logo-circle {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .navbar .d-flex {
                flex-direction: column;
                align-items: flex-end;
                gap: 10px;
            }
            
            .time-display, .user-info {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }
        }

        /* Animation for stats */
        @keyframes countUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card h4 {
            animation: countUp 0.5s ease-out;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Logo and Brand -->
            <a class="navbar-brand" href="{{ route('cashier.dashboard') }}">
                <div class="logo-circle">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="brand-text">
                    <span class="brand-main">Foodhouse</span>
                    <span class="brand-sub">POS System</span>
                </div>
            </a>

            <!-- User Info and Logout -->
            <div class="d-flex align-items-center">
                <div class="time-display me-3">
                    <i class="fas fa-clock"></i>
                    <span id="current-time"></span>
                </div>
                
                <div class="user-info me-3">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ Auth::user()->name ?? 'Cashier' }}</span>
                </div>
                
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        <div class="container">
            <!-- Welcome Banner -->
            <div class="welcome-banner">
                <h2>Welcome to Foodhouse POS System</h2>
                <p>Select an action below to get started</p>
                
                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <h6>Today's Sales</h6>
                        <h4 id="today-sales">₱{{ number_format($todaySales ?? 0, 2) }}</h4>
                    </div>
                    <div class="stat-card">
                        <h6>Today's Orders</h6>
                        <h4 id="today-orders">{{ $todayOrders ?? 0 }}</h4>
                    </div>
                    <div class="stat-card">
                        <h6>Pending Orders</h6>
                        <h4 id="pending-orders">{{ $pendingOrders ?? 0 }}</h4>
                    </div>
                    <div class="stat-card">
                        <h6>Ready Orders</h6>
                        <h4 id="ready-orders">{{ $readyOrders ?? 0 }}</h4>
                    </div>
                </div>
            </div>

            <!-- Main Action Cards -->
            <div class="row g-4 mb-5">
                <div class="col-lg-3 col-md-6">
                    <a href="{{ route('cashier.pos') }}" class="action-card btn-primary-action">
                        <i class="fas fa-cash-register"></i>
                        <h5>Take Orders</h5>
                        <small>Create new orders</small>
                    </a>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <a href="{{ route('cashier.orders.unpaid') }}" class="action-card btn-success-action">
                        <i class="fas fa-credit-card"></i>
                        <h5>Process Payments</h5>
                        <small>Accept payments</small>
                    </a>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <a href="{{ route('cashier.orders.index') }}" class="action-card btn-info-action">
                        <i class="fas fa-list"></i>
                        <h5>View Orders</h5>
                        <small>Order history</small>
                    </a>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <a href="{{ route('cashier.orders.active') }}" class="action-card btn-warning-action">
                        <i class="fas fa-sync-alt"></i>
                        <h5>Update Status</h5>
                        <small>Order status updates</small>
                    </a>
                </div>
            </div>

            <!-- Quick Access -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Access</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('cashier.pos') }}" class="quick-btn">
                                <i class="fas fa-plus-circle"></i>
                                <span>New Order</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('cashier.orders.today') }}" class="quick-btn">
                                <i class="fas fa-calendar-day"></i>
                                <span>Today's Orders</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('cashier.orders.unpaid') }}" class="quick-btn">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Unpaid Orders</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('cashier.orders.preparing') }}" class="quick-btn">
                                <i class="fas fa-utensils"></i>
                                <span>Preparing</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('cashier.orders.ready') }}" class="quick-btn">
                                <i class="fas fa-check-circle"></i>
                                <span>Ready</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <button onclick="window.print()" class="quick-btn print-btn">
                                <i class="fas fa-print"></i>
                                <span>Print</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            @if(isset($recentOrders) && $recentOrders->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Orders</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('cashier.orders.show', $order) }}" class="text-decoration-none fw-bold">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->customer_name ?: 'Walk-in' }}</td>
                                    <td class="fw-bold">₱{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($order->status == 'pending') bg-warning
                                            @elseif($order->status == 'preparing') bg-info
                                            @elseif($order->status == 'ready') bg-success
                                            @else bg-secondary @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Update current time
        function updateTime() {
            const now = new Date();
            const dateOptions = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };
            
            const dateString = now.toLocaleDateString('en-US', dateOptions);
            const timeString = now.toLocaleTimeString('en-US', timeOptions);
            
            document.getElementById('current-time').innerHTML = 
                `<span class="fw-bold">${dateString}</span><br><small>${timeString}</small>`;
        }
        
        // Update time immediately and every second
        updateTime();
        setInterval(updateTime, 1000);

        // Animate number counting
        function animateValue(element, start, end, duration) {
            if (start === end) return;
            
            const range = end - start;
            const increment = end > start ? 1 : -1;
            const stepTime = Math.abs(Math.floor(duration / range));
            let current = start;
            
            const timer = setInterval(function() {
                current += increment;
                element.textContent = element.id.includes('sales') 
                    ? '₱' + current.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                    : current.toLocaleString();
                
                if (current == end) {
                    clearInterval(timer);
                }
            }, stepTime);
        }

        // Animate stats on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Get current values
            const salesElement = document.getElementById('today-sales');
            const ordersElement = document.getElementById('today-orders');
            const pendingElement = document.getElementById('pending-orders');
            const readyElement = document.getElementById('ready-orders');
            
            // Extract numeric values
const salesValue = parseFloat('{{ number_format($todaySales ?? 0, 2) }}'.replace(/,/g, '')) || 0;

            const ordersValue = parseInt('{{ $todayOrders ?? 0 }}');
            const pendingValue = parseInt('{{ $pendingOrders ?? 0 }}');
            const readyValue = parseInt('{{ $readyOrders ?? 0 }}');
            
            // Animate each value
            setTimeout(() => animateValue(salesElement, 0, salesValue, 1000), 300);
            setTimeout(() => animateValue(ordersElement, 0, ordersValue, 1000), 600);
            setTimeout(() => animateValue(pendingElement, 0, pendingValue, 1000), 900);
            setTimeout(() => animateValue(readyElement, 0, readyValue, 1000), 1200);
        });

        // Add hover effects
        document.addEventListener('DOMContentLoaded', function() {
            const actionCards = document.querySelectorAll('.action-card');
            actionCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px) scale(1.02)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        });
    </script>
</body>
</html>