@extends('layouts.admin')

@section('title', 'Orders Management')
@section('page-title', 'Orders')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Orders</h5>
        <div class="btn-group">
            <button class="btn btn-outline-primary btn-sm">Today</button>
            <button class="btn btn-outline-secondary btn-sm">This Week</button>
            <button class="btn btn-outline-secondary btn-sm">This Month</button>
        </div>
    </div>
    <div class="card-body">
        <div class="text-center py-5">
            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No orders yet</h5>
            <p class="text-muted">Orders will appear here once they are created through the POS system.</p>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h6 class="card-title">Total Orders</h6>
                        <h3 class="mb-0">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h6 class="card-title">Completed</h6>
                        <h3 class="mb-0">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h6 class="card-title">Pending</h6>
                        <h3 class="mb-0">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h6 class="card-title">Cancelled</h6>
                        <h3 class="mb-0">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection