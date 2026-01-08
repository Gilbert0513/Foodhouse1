@extends('layouts.customer')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <!-- Profile Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light-blue text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-circle fa-lg me-3"></i>
                        <h5 class="mb-0 fw-bold">My Profile</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row align-items-center mb-4">
                        <div class="col-md-4 text-center mb-4 mb-md-0">
                            <div class="avatar-container mb-3">
                                <div class="avatar-circle bg-light-blue text-white">
                                    <i class="fas fa-user fa-3x"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold text-dark">{{ auth()->user()->name }}</h5>
                            <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
                            <div class="mt-2">
                                <span class="badge bg-light-blue-light text-blue">
                                    <i class="fas fa-user-check me-1"></i>Customer
                                </span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="profile-details">
                                <h6 class="section-title mb-3">
                                    <i class="fas fa-info-circle me-2 text-blue"></i>
                                    Account Information
                                </h6>
                                
                                <div class="info-list">
                                    <div class="info-item d-flex align-items-center mb-3 p-3 rounded border">
                                        <div class="info-icon me-3">
                                            <div class="icon-circle bg-blue text-white">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="text-muted d-block">Full Name</small>
                                            <h6 class="mb-0 fw-bold">{{ auth()->user()->name }}</h6>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item d-flex align-items-center mb-3 p-3 rounded border">
                                        <div class="info-icon me-3">
                                            <div class="icon-circle bg-green text-white">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="text-muted d-block">Email Address</small>
                                            <h6 class="mb-0 fw-bold">{{ auth()->user()->email }}</h6>
                                        </div>
                                        <div class="ms-2">
                                            <span class="badge bg-green-light text-green">
                                                <i class="fas fa-check-circle me-1"></i>Verified
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item d-flex align-items-center mb-3 p-3 rounded border">
                                        <div class="info-icon me-3">
                                            <div class="icon-circle bg-purple text-white">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="text-muted d-block">Phone Number</small>
                                            <h6 class="mb-0 fw-bold">{{ auth()->user()->phone ?? 'Not set' }}</h6>
                                        </div>
                                        @if(!auth()->user()->phone)
                                        <div class="ms-2">
                                            <span class="badge bg-warning-light text-warning">
                                                <i class="fas fa-exclamation-circle me-1"></i>Add phone
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="info-item d-flex align-items-center mb-3 p-3 rounded border">
                                        <div class="info-icon me-3">
                                            <div class="icon-circle bg-orange text-white">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="text-muted d-block">Address</small>
                                            <h6 class="mb-0 fw-bold">{{ auth()->user()->address ?? 'Not set' }}</h6>
                                        </div>
                                        @if(!auth()->user()->address)
                                        <div class="ms-2">
                                            <span class="badge bg-warning-light text-warning">
                                                <i class="fas fa-exclamation-circle me-1"></i>Add address
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="info-item d-flex align-items-center p-3 rounded border">
                                        <div class="info-icon me-3">
                                            <div class="icon-circle bg-teal text-white">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="text-muted d-block">Member Since</small>
                                            <h6 class="mb-0 fw-bold">{{ auth()->user()->created_at->format('F d, Y') }}</h6>
                                        </div>
                                        <div class="ms-2">
                                            <span class="badge bg-teal-light text-teal">
                                                {{ \Carbon\Carbon::parse(auth()->user()->created_at)->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="border-top pt-4">
                        <div class="d-flex justify-content-between">
                            <a href="#" class="btn btn-outline-secondary">
                                <i class="fas fa-history me-2"></i>Order History
                            </a>
                            <a href="#" class="btn btn-blue">
                                <i class="fas fa-edit me-2"></i>Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="icon-circle-md bg-blue text-white">
                                        <i class="fas fa-shopping-bag"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Total Orders</h6>
                                    <h4 class="mb-0 fw-bold text-blue">12</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="icon-circle-md bg-green text-white">
                                        <i class="fas fa-peso-sign"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Total Spent</h6>
                                    <h4 class="mb-0 fw-bold text-green">₱3,450</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Light Color Scheme */
    .bg-light-blue {
        background-color: #4a6bff !important;
    }
    
    .bg-blue {
        background-color: #4a6bff !important;
    }
    
    .text-blue {
        color: #4a6bff !important;
    }
    
    .bg-green {
        background-color: #10b981 !important;
    }
    
    .text-green {
        color: #10b981 !important;
    }
    
    .bg-purple {
        background-color: #8b5cf6 !important;
    }
    
    .text-purple {
        color: #8b5cf6 !important;
    }
    
    .bg-orange {
        background-color: #f59e0b !important;
    }
    
    .text-orange {
        color: #f59e0b !important;
    }
    
    .bg-teal {
        background-color: #06b6d4 !important;
    }
    
    .text-teal {
        color: #06b6d4 !important;
    }
    
    /* Light Backgrounds */
    .bg-blue-light {
        background-color: rgba(74, 107, 255, 0.1) !important;
    }
    
    .bg-green-light {
        background-color: rgba(16, 185, 129, 0.1) !important;
    }
    
    .bg-warning-light {
        background-color: rgba(245, 158, 11, 0.1) !important;
    }
    
    .bg-teal-light {
        background-color: rgba(6, 182, 212, 0.1) !important;
    }
    
    /* Card Styling */
    .card {
        border-radius: 10px;
        transition: transform 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    /* Avatar */
    .avatar-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .icon-circle-md {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Section Title */
    .section-title {
        position: relative;
        padding-bottom: 10px;
        color: #333;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 2px;
        background-color: #4a6bff;
    }
    
    /* Info Items */
    .info-item {
        transition: all 0.3s ease;
        background: #fff;
    }
    
    .info-item:hover {
        border-color: #4a6bff !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    /* Buttons */
    .btn-blue {
        background-color: #4a6bff;
        border-color: #4a6bff;
        color: white;
    }
    
    .btn-blue:hover {
        background-color: #3a5bef;
        border-color: #3a5bef;
        color: white;
    }
    
    /* Badges */
    .badge {
        font-weight: 500;
        padding: 4px 8px;
        border-radius: 6px;
    }
    
    /* Borders */
    .border {
        border-color: #e9ecef !important;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .avatar-circle {
            width: 80px;
            height: 80px;
        }
        
        .avatar-circle i {
            font-size: 2rem !important;
        }
    }
</style>
@endpush