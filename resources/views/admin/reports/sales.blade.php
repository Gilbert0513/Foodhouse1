@extends('layouts.admin')

@section('title', 'Sales Reports')
@section('page-title', 'Sales Reports')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Sales Reports</h5>
            </div>
            <div class="card-body">
                <!-- Date Range Selector -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label>Start Date</label>
                        <input type="date" class="form-control" id="startDate" value="{{ date('Y-m-d', strtotime('-7 days')) }}">
                    </div>
                    <div class="col-md-3">
                        <label>End Date</label>
                        <input type="date" class="form-control" id="endDate" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <button class="btn btn-primary form-control" id="generateReportBtn">
                            Generate Report
                        </button>
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <button class="btn btn-success form-control" id="exportReportBtn" disabled>
                            <i class="fas fa-download me-2"></i> Export
                        </button>
                    </div>
                </div>

                @if($hasData ?? false)
                    <!-- Sales Summary -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h6>Total Sales</h6>
                                    <h3>₱{{ number_format($salesSummary['total_sales'] ?? 0, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h6>Total Orders</h6>
                                    <h3>{{ $salesSummary['total_orders'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h6>Average Order</h6>
                                    <h3>₱{{ number_format($salesSummary['average_order'] ?? 0, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h6>Tax Collected</h6>
                                    <h3>₱{{ number_format($salesSummary['tax_collected'] ?? 0, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Chart (Placeholder) -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Sales Trend</h6>
                        </div>
                        <div class="card-body">
                            <div style="height: 300px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                <div class="text-center">
                                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Sales data will be displayed here once available</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Selling Items -->
                    @if($topSellingItems && count($topSellingItems) > 0)
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Top Selling Items</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Item Name</th>
                                                <th>Category</th>
                                                <th>Quantity Sold</th>
                                                <th>Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($topSellingItems as $index => $item)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $item->name ?? 'N/A' }}</td>
                                                    <td>{{ $item->category ?? 'Uncategorized' }}</td>
                                                    <td>{{ $item->quantity_sold ?? 0 }}</td>
                                                    <td>₱{{ number_format($item->revenue ?? 0, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-utensils fa-2x text-muted mb-3"></i>
                                <h6 class="text-muted">No sales data available</h6>
                                <p class="text-muted">Start selling items to see top selling products here.</p>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No Sales Data Available</h4>
                        <p class="text-muted mb-4">
                            Sales reports will appear here once you start processing orders through the POS system.
                        </p>
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>Get Started</h6>
                                        <p class="small text-muted">To see sales data:</p>
                                        <ol class="text-start small">
                                            <li>Add menu items to your inventory</li>
                                            <li>Process orders through the POS system</li>
                                            <li>Complete transactions with payments</li>
                                        </ol>
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('cashier.pos') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-cash-register me-2"></i> Open POS
                                            </a>
                                            <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-utensils me-2"></i> Manage Menu
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('generateReportBtn').addEventListener('click', function() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        if (!startDate || !endDate) {
            alert('Please select both start and end dates.');
            return;
        }
        
        if (new Date(startDate) > new Date(endDate)) {
            alert('Start date cannot be after end date.');
            return;
        }
        
        // Show loading state
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating...';
        this.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            this.innerHTML = originalText;
            this.disabled = false;
            alert('Report generated for ' + startDate + ' to ' + endDate + '. No data available yet.');
        }, 1500);
    });
</script>
@endpush
@endsection