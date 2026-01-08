@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <p class="text-muted">Foodhouse Smart Ordering System</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Today's Sales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ₱{{ number_format($stats['total_sales_today'], 2) }}
                            </div>
                            @if($stats['yesterday_sales'] > 0)
                            <div class="mt-2 mb-0 text-muted text-xs">
                                @php
                                    $change = (($stats['total_sales_today'] - $stats['yesterday_sales']) / $stats['yesterday_sales']) * 100;
                                @endphp
                                <span class="{{ $change >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-arrow-{{ $change >= 0 ? 'up' : 'down' }} fa-xs"></i>
                                    {{ number_format(abs($change), 1) }}%
                                </span>
                                vs yesterday
                            </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Today's Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_orders_today'] }}
                            </div>
                            @if($stats['total_orders_today'] > 0)
                            <div class="mt-2 mb-0 text-muted text-xs">
                                @php
                                    $avgValue = $stats['total_sales_today'] / $stats['total_orders_today'];
                                @endphp
                                <span>Avg: ₱{{ number_format($avgValue, 2) }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Paid Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['paid_orders_today'] ?? 0 }}
                            </div>
                            @if($stats['total_orders_today'] > 0)
                            <div class="mt-2 mb-0 text-muted text-xs">
                                @php
                                    $conversionRate = ($stats['paid_orders_today'] / $stats['total_orders_today']) * 100;
                                @endphp
                                <span>{{ number_format($conversionRate, 1) }}% conversion</span>
                            </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['pending_orders'] }}
                            </div>
                            @if($stats['total_orders_today'] > 0)
                            <div class="mt-2 mb-0 text-muted text-xs">
                                @php
                                    $pendingRate = ($stats['pending_orders'] / $stats['total_orders_today']) * 100;
                                @endphp
                                <span>{{ number_format($pendingRate, 1) }}% of today</span>
                            </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row - Additional Analytics -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Low Stock Items</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['low_stock_items'] }}
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                @if($stats['low_stock_items'] > 0)
                                    <span class="text-danger">Needs attention</span>
                                @else
                                    <span class="text-success">All items in stock</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Weekly Sales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ₱{{ number_format($stats['weekly_sales'] ?? 0, 2) }}
                            </div>
                            @if($stats['weekly_sales'] > 0)
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span>{{ $stats['weekly_orders'] ?? 0 }} orders this week</span>
                            </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Customers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['active_customers'] ?? 0 }}
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span>Registered customers</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Menu Items</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_menu_items'] ?? 0 }}
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span>Active items in menu</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-utensils fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Recent Orders Table -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                    <span class="badge badge-primary">{{ $recentOrders->count() }} orders</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Type</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td class="font-weight-bold">{{ $order->order_number }}</td>
                                    <td>{{ $order->customer ? $order->customer->full_name : 'Walk-in Customer' }}</td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ ucfirst($order->order_type) }}
                                        </span>
                                    </td>
                                    <td class="font-weight-bold">₱{{ number_format($order->total_amount ?? $order->grand_total, 2) }}</td>
                                    <td>
                                        @if(isset($order->payment_status) && $order->payment_status === 'paid')
                                            <span class="badge badge-success">Paid</span>
                                        @else
                                            <span class="badge badge-warning">Unpaid</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge badge-warning">Pending</span>
                                                @break
                                            @case('confirmed')
                                                <span class="badge badge-primary">Confirmed</span>
                                                @break
                                            @case('preparing')
                                                <span class="badge badge-info">Preparing</span>
                                                @break
                                            @case('ready')
                                                <span class="badge badge-success">Ready</span>
                                                @break
                                            @case('served')
                                                <span class="badge badge-secondary">Served</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge badge-danger">Cancelled</span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary">{{ $order->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $order->created_at->format('h:i A') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($recentOrders->count() == 0)
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-3x text-gray-300 mb-3"></i>
                        <p class="text-muted">No recent orders found</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Selling Items & Quick Stats -->
        <div class="col-lg-4 mb-4">
            <!-- Top Selling Items -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Selling Items (7 Days)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="topItemsChart"></canvas>
                    </div>
                    <div class="mt-4">
                        @foreach($topItems as $index => $item)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                            <div>
                                <span class="font-weight-bold">{{ $index + 1 }}.</span>
                                <span>{{ $item->item_name }}</span>
                            </div>
                            <div>
                                <span class="badge badge-primary">{{ $item->total_sold }} sold</span>
                            </div>
                        </div>
                        @endforeach
                        @if($topItems->count() == 0)
                        <div class="text-center py-3">
                            <i class="fas fa-utensils fa-2x text-gray-300 mb-2"></i>
                            <p class="text-muted">No sales data available</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="p-3 border rounded">
                                <div class="text-primary font-weight-bold h5">
                                    {{ $stats['order_completion_rate'] ?? '0' }}%
                                </div>
                                <div class="text-muted small">Completion Rate</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 border rounded">
                                <div class="text-success font-weight-bold h5">
                                    {{ $stats['avg_daily_orders'] ?? '0' }}
                                </div>
                                <div class="text-muted small">Avg Daily Orders</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded">
                                <div class="text-info font-weight-bold h5">
                                    {{ $stats['peak_hour'] ?? 'N/A' }}
                                </div>
                                <div class="text-muted small">Peak Hour</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded">
                                <div class="text-warning font-weight-bold h5">
                                    {{ $stats['cancelled_orders'] ?? '0' }}
                                </div>
                                <div class="text-muted small">Cancelled Today</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
<script>
// Top Items Chart
var ctx = document.getElementById("topItemsChart");
@if($topItems->count() > 0)
var topItemsChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($topItems->pluck('item_name')) !!},
        datasets: [{
            data: {!! json_encode($topItems->pluck('total_sold')) !!},
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
            hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
    },
    options: {
        maintainAspectRatio: false,
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
        },
        legend: {
            display: false
        },
        cutoutPercentage: 80,
    },
});
@else
// If no data, hide the canvas
ctx.style.display = 'none';
@endif

// Auto-refresh dashboard every 60 seconds
setInterval(() => {
    location.reload();
}, 60000);
</script>
@endpush

@push('styles')
<style>
.card {
    border-radius: 10px;
    transition: transform 0.2s ease;
}
.card:hover {
    transform: translateY(-2px);
}
.badge {
    font-size: 0.8em;
    padding: 5px 10px;
}
.table-hover tbody tr:hover {
    background-color: rgba(78, 115, 223, 0.05);
}
</style>
@endpush