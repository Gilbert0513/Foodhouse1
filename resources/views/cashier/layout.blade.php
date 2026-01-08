<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Foodhouse POS')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-dark: #1a237e;
            --primary-light: #283593;
            --surface-light: #f8f9fa;
            --surface-white: #ffffff;
            --text-primary: #374151;
            --text-secondary: #6b7280;
            --success-color: #10b981;
            --error-color: #ef4444;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.08);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
            --border-radius: 8px;
            --transition: all 0.2s ease;
        }

        body {
            background: var(--surface-light);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--text-primary);
            line-height: 1.6;
        }

        .cashier-navbar {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-light) 100%);
            color: white;
            padding: 1rem 1.5rem;
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .navbar-brand i {
            font-size: 1.5rem;
            opacity: 0.9;
        }

        .navbar-brand h4 {
            font-weight: 600;
            font-size: 1.25rem;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .navbar-brand small {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        .main-content {
            padding: 2rem;
            min-height: calc(100vh - 80px);
        }

        .action-btn {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            text-decoration: none;
            color: white;
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-dark) 100%);
            transition: var(--transition);
            height: 120px;
            box-shadow: var(--shadow-sm);
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: white;
        }

        .dashboard-card {
            background: var(--surface-white);
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            overflow: hidden;
        }

        .dashboard-card:hover {
            box-shadow: var(--shadow-md);
        }

        .alert {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--error-color);
            border-left: 4px solid var(--error-color);
        }

        .btn-outline-light {
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            transition: var(--transition);
        }

        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .nav-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
        }

        .nav-info i {
            opacity: 0.7;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--surface-light);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* Focus styles */
        :focus {
            outline: 2px solid var(--primary-light);
            outline-offset: 2px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }
            
            .cashier-navbar {
                padding: 0.75rem 1rem;
            }
            
            .navbar-brand h4 {
                font-size: 1.125rem;
            }
            
            .action-btn {
                height: 100px;
                padding: 1rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar cashier-navbar">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="navbar-brand">
                <i class="fas fa-cash-register"></i>
                <div>
                    <h4>FOODHOUSE POS</h4>
                    <small>Cashier: {{ auth()->user()->name }}</small>
                </div>
            </div>
            
            <div class="nav-info">
                <span>
                    <i class="fas fa-clock me-1"></i>
                    {{ now()->format('h:i A') }}
                </span>
                <form method="POST" action="{{ route('logout') }}" id="logoutForm" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    
    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.remove();
            });
        }, 5000);
    </script>
</body>
</html>