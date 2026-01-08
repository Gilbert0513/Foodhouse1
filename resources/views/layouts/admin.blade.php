<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Foodhouse')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            border-radius: 5px;
            margin: 5px 0;
        }
        .sidebar a:hover {
            background: rgba(255,255,255,0.1);
        }
        .sidebar a.active {
            background: rgba(255,255,255,0.2);
        }
        .main-content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 p-0 sidebar">
                <div class="p-3">
                    <h4 class="text-center mb-4">
                        <i class="fas fa-utensils"></i> Foodhouse
                    </h4>
                    <h6 class="text-muted">ADMIN PANEL</h6>
                    <nav class="mt-4">
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users me-2"></i> Users
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="fas fa-list me-2"></i> Categories
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="fas fa-receipt me-2"></i> Orders
                        </a>
                        <a href="{{ route('admin.reports.sales') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar me-2"></i> Reports
                        </a>
                         <a href="{{ route('admin.inventory.index') }}" class="{{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                            <i class="fas fa-box me-2"></i> Inventory
                        </a>
                    </nav>
                    
                    <div class="mt-5">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>@yield('page-title')</h3>
                    <div class="text-end">
                        <span class="badge bg-primary p-2">
                            <i class="fas fa-user me-1"></i> {{ auth()->user()->name }}
                        </span>
                    </div>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>