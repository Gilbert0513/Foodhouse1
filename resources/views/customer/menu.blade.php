@extends('layouts.customer')

@section('title', 'Foodhouse Menu')

@section('content')
<div class="container-fluid p-0">
    <!-- Premium Header with Logo -->
    <div class="premium-header py-3" style="background: linear-gradient(135deg, #e63946 0%, #d32f2f 100%);">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <!-- Awesome Logo Design -->
                    <div class="logo-container d-flex align-items-center">
                        <div class="logo-wrapper position-relative me-3">
                            <div class="logo-circle">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div class="logo-ring"></div>
                        </div>
                        <div class="logo-text">
                            <h1 class="brand-title mb-0 fw-bold">FOODHOUSE</h1>
                            <p class="brand-subtitle mb-0 text-light opacity-85">Premium Dining Experience</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <div class="header-info">
                        <div class="info-item d-inline-flex align-items-center me-4 me-lg-0 mb-2 mb-lg-0">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="ms-2">
                                <div class="info-label small">NOW SERVING</div>
                                <div class="info-value fw-bold" id="currentTime">{{ now()->format('h:i A') }}</div>
                            </div>
                        </div>
                        <div class="info-item d-inline-flex align-items-center">
                            <div class="info-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="ms-2">
                                <div class="info-label small">TODAY'S DATE</div>
                                <div class="info-value fw-bold" id="currentDate">{{ now()->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Full Width Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row g-0">
                <!-- Categories Sidebar - Full Width -->
                <div class="col-xl-3 col-lg-4">
                    <div class="sidebar-container vh-100">
                        <!-- Categories Section -->
                        <div class="sidebar-section">
                            <div class="section-header">
                                <h3 class="section-title">
                                    <i class="fas fa-layer-group me-2"></i>Categories
                                </h3>
                            </div>
                            <div class="category-list">
                                <a href="#" class="category-item active" data-category="all">
                                    <div class="category-icon">
                                        <i class="fas fa-bars"></i>
                                    </div>
                                    <div class="category-content">
                                        <div class="category-name">All Items</div>
                                        <div class="category-count">{{ $categories->sum(function($cat) { return $cat->menuItems->count(); }) }}</div>
                                    </div>
                                </a>
                                @foreach($categories as $category)
                                <a href="#" class="category-item" data-category="{{ $category->id }}">
                                    <div class="category-icon">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                    <div class="category-content">
                                        <div class="category-name">{{ $category->name }}</div>
                                        <div class="category-count">{{ $category->menuItems->count() }}</div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Cart Summary - Full Width -->
                        <div class="cart-summary mt-4">
                            <div class="section-header">
                                <h3 class="section-title">
                                    <i class="fas fa-shopping-basket me-2"></i>Your Order
                                </h3>
                            </div>
                            <div class="cart-content">
                                <div id="cartItems" class="cart-items-container">
                                    <!-- Cart items will be displayed here -->
                                </div>
                                <div class="cart-totals mt-4">
                                    <div class="total-row">
                                        <span class="total-label">Subtotal</span>
                                        <span id="cartSubtotal" class="total-value">₱0.00</span>
                                    </div>
                                    <div class="total-row">
                                        <span class="total-label">Tax (12%)</span>
                                        <span id="cartTax" class="total-value">₱0.00</span>
                                    </div>
                                    <div class="total-row grand-total">
                                        <span class="total-label">Total</span>
                                        <span id="cartTotal" class="total-value">₱0.00</span>
                                    </div>
                                </div>
                                <div class="cart-actions mt-4">
                                    <button class="btn btn-checkout w-100 mb-3" id="checkoutBtn" disabled>
                                        <i class="fas fa-credit-card me-2"></i>PROCEED TO CHECKOUT
                                    </button>
                                    <button class="btn btn-clear w-100" id="clearCartBtn">
                                        <i class="fas fa-trash-alt me-2"></i>CLEAR CART
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menu Items Grid - Full Width -->
                <div class="col-xl-9 col-lg-8">
                    <div class="menu-container vh-100">
                        <!-- Welcome Section -->
                        <div class="welcome-section mb-4">
                            <div class="welcome-card">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h2 class="welcome-title mb-2">Welcome to Foodhouse!</h2>
                                        <p class="welcome-text mb-0">Browse our delicious menu and place your order</p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <div class="welcome-icon">
                                            <i class="fas fa-hamburger fa-4x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Grid -->
                        <div class="row g-4" id="menuItemsGrid">
                            @foreach($categories as $category)
                                @foreach($category->menuItems as $item)
                                <div class="col-xxl-3 col-xl-4 col-lg-6 menu-item" data-category="{{ $category->id }}">
                                    <div class="menu-card hover-effect">
                                        <!-- Menu Item Image -->
                                        @php
                                            if ($item->image_url) {
                                                $imagePath = $item->image_url;
                                            } else {
                                                if ($item->item_name == 'Crispy Calamares') {
                                                    $imagePath = 'images/CALAMARES.jpg';
                                                }
                                                elseif ($item->item_name == 'Nachos Supreme') {
                                                    $imagePath = 'images/NACHOS.jpg';
                                                }
                                                elseif ($item->item_name == 'Fresh Lemonade') {
                                                    $imagePath = 'images/LEMONADE.jpg';
                                                }
                                                elseif ($item->item_name == 'Iced Tea') {
                                                    $imagePath = 'images/NESTEA.jpg';
                                                }
                                                elseif ($item->item_name == 'Halo-Halo Special') {
                                                    $imagePath = 'images/HALO HALO.jpg';
                                                }
                                                elseif ($item->item_name == 'Leche Flan') {
                                                    $imagePath = 'images/LETCHE FLAN.jpg';
                                                }
                                                elseif ($item->item_name == 'Beef Steak') {
                                                    $imagePath = 'images/BEEF STEAK.jpg';
                                                }
                                                elseif ($item->item_name == 'Grilled Pork Chop') {
                                                    $imagePath = 'images/GRILLED.JPG';
                                                }
                                                elseif ($item->item_name == 'Chicken Adobo') {
                                                    $imagePath = 'images/ADOBO.jpg';
                                                }
                                                elseif ($item->item_name == 'Pork Sinigang') {
                                                    $imagePath = 'images/SINIGANG.jpg';
                                                }
                                                else {
                                                    $imageName = Str::slug($item->item_name);
                                                    $autoImagePath = "images/{$imageName}.jpg";
                                                    $imagePath = file_exists(public_path($autoImagePath)) ? $autoImagePath : 'images/placeholder.jpg';
                                                }
                                            }
                                        @endphp
                                        
                                        <div class="card-image position-relative">
                                            <div class="image-container" style="height: 180px; overflow: hidden;">
                                                <img src="{{ asset($imagePath) }}" 
                                                     class="card-img-top" 
                                                     alt="{{ $item->item_name }}"
                                                     style="height: 100%; width: 100%; object-fit: cover; transition: transform 0.5s ease;">
                                            </div>
                                            
                                            <!-- Category Badge -->
                                            <div class="position-absolute top-0 start-0 m-2">
                                                <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #e63946, #d32f2f); color: white;">
                                                    <i class="fas fa-tag me-1"></i>{{ $category->name }}
                                                </span>
                                            </div>
                                            
                                            <!-- Price Tag -->
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <div class="price-tag px-3 py-2 rounded-pill shadow" style="background: #28a745; color: white;">
                                                    <i class="fas fa-peso-sign me-1"></i>{{ number_format($item->price, 2) }}
                                                </div>
                                            </div>
                                            
                                            <!-- Overlay Effect -->
                                            <div class="image-overlay"></div>
                                        </div>
                                        
                                        <div class="card-body">
                                            <h5 class="card-title fw-bold" style="color: #e63946;">{{ $item->item_name }}</h5>
                                            <p class="card-text text-muted mb-3">
                                                <i class="fas fa-info-circle me-2"></i>{{ Str::limit($item->description, 80) }}
                                            </p>
                                            
                                            <!-- Item Details -->
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div class="text-muted small">
                                                    @if($item->preparation_time)
                                                    <span class="me-2">
                                                        <i class="fas fa-clock me-1"></i> {{ $item->preparation_time }}min
                                                    </span>
                                                    @endif
                                                    @if($item->calories)
                                                    <span>
                                                        <i class="fas fa-fire me-1"></i> {{ $item->calories }} cal
                                                    </span>
                                                    @endif
                                                </div>
                                                
                                                @if($item->track_inventory)
                                                    @if($item->stock_quantity > 0)
                                                    <span class="badge px-3 py-2 small" style="background: linear-gradient(135deg, #28a745, #218838); color: white;">
                                                        <i class="fas fa-check-circle me-1"></i> {{ $item->stock_quantity }} in stock
                                                    </span>
                                                    @else
                                                    <span class="badge px-3 py-2 small" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white;">
                                                        <i class="fas fa-times-circle me-1"></i> Out of Stock
                                                    </span>
                                                    @endif
                                                @else
                                                    <span class="badge px-3 py-2 small" style="background: linear-gradient(135deg, #28a745, #218838); color: white;">
                                                        <i class="fas fa-check-circle me-1"></i> Available
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Card Footer -->
                                        <div class="card-footer bg-white border-top-0 pt-0">
                                            @if(!$item->track_inventory || $item->stock_quantity > 0)
                                            <button class="btn btn-block add-to-cart d-flex align-items-center justify-content-center py-2"
                                                    data-id="{{ $item->id }}"
                                                    data-name="{{ $item->item_name }}"
                                                    data-price="{{ $item->price }}"
                                                    data-stock="{{ $item->stock_quantity }}"
                                                    data-image="{{ asset($imagePath) }}"
                                                    style="background: linear-gradient(135deg, #e63946 0%, #d32f2f 100%); color: white; border: none;">
                                                <i class="fas fa-plus-circle me-2"></i>
                                                <span>Add to Order</span>
                                                <span class="ms-auto">
                                                    <i class="fas fa-peso-sign me-1"></i>{{ number_format($item->price, 2) }}
                                                </span>
                                            </button>
                                            @else
                                            <button class="btn btn-outline-secondary btn-block d-flex align-items-center justify-content-center py-2" disabled>
                                                <i class="fas fa-times-circle me-2"></i>
                                                <span>Out of Stock</span>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endforeach
                        </div>
                        
                        <!-- Empty State -->
                        <div class="empty-state text-center py-5 d-none" id="noItemsFound">
                            <div class="empty-icon mb-4">
                                <i class="fas fa-utensils fa-4x text-muted"></i>
                            </div>
                            <h3 class="empty-title mb-2">No items found</h3>
                            <p class="empty-text mb-0">Try selecting a different category</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Modal (from your original code - keeping it since you want functionality intact) -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background: linear-gradient(135deg, #e63946 0%, #d32f2f 100%); color: white;">
                <h5 class="modal-title"><i class="fas fa-shopping-bag me-2"></i>Place Your Order</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('customer.orders.store') }}" method="POST" id="checkoutForm">
                @csrf
                <div class="modal-body">
                    <!-- Order Type -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Order Type</label>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="form-check card border p-3 rounded-lg hover-effect">
                                    <input class="form-check-input" type="radio" name="order_type" 
                                           id="dineIn" value="dine_in" checked>
                                    <label class="form-check-label d-flex align-items-center" for="dineIn">
                                        <div class="icon-circle me-3" style="background: #e63946; color: white;">
                                            <i class="fas fa-utensils"></i>
                                        </div>
                                        <div>
                                            <strong>Dine In</strong>
                                            <p class="text-muted small mb-0">Eat at our restaurant</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-check card border p-3 rounded-lg hover-effect">
                                    <input class="form-check-input" type="radio" name="order_type" 
                                           id="takeaway" value="takeaway">
                                    <label class="form-check-label d-flex align-items-center" for="takeaway">
                                        <div class="icon-circle me-3" style="background: #28a745; color: white;">
                                            <i class="fas fa-shopping-bag"></i>
                                        </div>
                                        <div>
                                            <strong>Takeaway</strong>
                                            <p class="text-muted small mb-0">Take food to go</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Number (for Dine In) -->
                    <div class="mb-4" id="tableNumberGroup">
                        <label class="form-label fw-bold"><i class="fas fa-chair me-2"></i>Table Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-hashtag"></i></span>
                            <input type="text" class="form-control" name="table_number" 
                                   placeholder="Enter your table number (e.g., 12, A5)">
                        </div>
                    </div>

                    <!-- Pax -->
                    <div class="mb-4">
                        <label class="form-label fw-bold"><i class="fas fa-users me-2"></i>Number of Persons</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                            <input type="number" class="form-control" name="pax" 
                                   value="1" min="1" max="20" required>
                            <span class="input-group-text bg-light">Persons</span>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-4">
                        <label class="form-label fw-bold"><i class="fas fa-receipt me-2"></i>Order Summary</label>
                        <div class="card border p-3 mb-3">
                            <div id="checkoutItems" style="max-height: 250px; overflow-y: auto;">
                                <!-- Order items will be displayed here -->
                            </div>
                            <hr class="my-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Subtotal:</span>
                                        <span id="checkoutSubtotal" class="fw-bold">₱0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Tax (12%):</span>
                                        <span id="checkoutTax" class="fw-bold">₱0.00</span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h5 class="mb-0" style="color: #28a745;">Total</h5>
                                    <h3 class="fw-bold" style="color: #28a745;" id="checkoutTotal">₱0.00</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Special Instructions -->
                    <div class="mb-4">
                        <label class="form-label fw-bold"><i class="fas fa-sticky-note me-2"></i>Special Instructions</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-comment"></i></span>
                            <textarea class="form-control" name="special_instructions" 
                                      rows="3" placeholder="Any special requests, allergies, or preferences..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn" id="placeOrderBtn" style="background: linear-gradient(135deg, #e63946 0%, #d32f2f 100%); color: white; border: none;">
                        <i class="fas fa-paper-plane me-2"></i>Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Full Width Layout */
    .container-fluid {
        max-width: 100%;
        padding: 0;
    }

    /* Premium Header */
    .premium-header {
        background: linear-gradient(135deg, #e63946 0%, #d32f2f 100%);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }

    .premium-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #e63946, #ff6b6b, #ffd166);
    }

    /* Awesome Logo Design */
    .logo-wrapper {
        display: inline-block;
    }

    .logo-circle {
        width: 60px;
        height: 60px;
        background: white;
        color: #e63946;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        position: relative;
        z-index: 2;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(255, 255, 255, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
    }

    .logo-ring {
        position: absolute;
        top: -5px;
        left: -5px;
        right: -5px;
        bottom: -5px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .brand-title {
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: 1px;
        color: white;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }

    .brand-subtitle {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    /* Header Info */
    .info-item {
        padding: 8px 12px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .info-icon {
        width: 32px;
        height: 32px;
        background: white;
        color: #e63946;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .info-label {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        color: white;
        font-size: 0.9rem;
    }

    /* Full Height Sections */
    .vh-100 {
        height: calc(100vh - 92px);
    }

    .sidebar-container {
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        padding: 20px;
        overflow-y: auto;
        height: 100%;
    }

    .menu-container {
        padding: 20px;
        overflow-y: auto;
        height: 100%;
    }

    /* Category Items */
    .section-header {
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3436;
        display: flex;
        align-items: center;
    }

    .category-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .category-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        background: #f8f9fa;
        border-radius: 10px;
        text-decoration: none;
        color: #2d3436;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .category-item:hover,
    .category-item.active {
        background: white;
        border-color: #e63946;
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .category-item.active {
        background: linear-gradient(135deg, #e63946 15%, #d32f2f 100%);
        color: white;
    }

    .category-icon {
        width: 36px;
        height: 36px;
        background: rgba(230, 57, 70, 0.1);
        color: #e63946;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }

    .category-item.active .category-icon {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .category-content {
        flex: 1;
    }

    .category-name {
        font-weight: 500;
        font-size: 0.95rem;
    }

    .category-count {
        font-size: 0.85rem;
        opacity: 0.7;
        font-weight: 600;
    }

    .category-item.active .category-count {
        color: rgba(255, 255, 255, 0.9);
    }

    /* Cart Summary */
    .cart-summary {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border: 1px solid #e9ecef;
    }

    .cart-items-container {
        max-height: 200px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .total-row:last-child {
        border-bottom: none;
    }

    .total-row.grand-total {
        font-size: 1.1rem;
        font-weight: 700;
        color: #e63946;
        padding-top: 12px;
        margin-top: 8px;
        border-top: 2px solid #e9ecef;
    }

    /* Buttons */
    .btn-checkout {
        background: linear-gradient(135deg, #e63946, #d32f2f);
        color: white;
        border: none;
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-checkout:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(230, 57, 70, 0.3);
    }

    .btn-checkout:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-clear {
        background: transparent;
        color: #495057;
        border: 2px solid #e9ecef;
        padding: 10px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-clear:hover {
        background: #f8f9fa;
        border-color: #adb5bd;
    }

    /* Welcome Section */
    .welcome-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }

    .welcome-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .welcome-text {
        opacity: 0.9;
        font-size: 1rem;
    }

    .welcome-icon {
        font-size: 4rem;
        opacity: 0.3;
    }

    /* Menu Cards */
    .menu-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
        border: 1px solid #e9ecef;
    }

    .hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
        border-color: #ff6b6b;
    }

    .menu-card:hover .card-img-top {
        transform: scale(1.1);
    }

    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 180px;
        background: linear-gradient(to bottom, transparent 70%, rgba(0,0,0,0.7) 100%);
        pointer-events: none;
    }

    /* Empty State */
    .empty-state {
        padding: 60px 20px;
    }

    .empty-icon {
        font-size: 4rem;
        color: #adb5bd;
        margin-bottom: 20px;
    }

    .empty-title {
        color: #495057;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .empty-text {
        color: #adb5bd;
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb {
        background: #e63946;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #d32f2f;
    }

    /* Responsive */
    @media (max-width: 1199px) {
        .vh-100 {
            height: auto;
            min-height: calc(100vh - 92px);
        }
    }

    @media (max-width: 991px) {
        .sidebar-container,
        .menu-container {
            padding: 15px;
        }
        
        .brand-title {
            font-size: 1.5rem;
        }
        
        .welcome-title {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 767px) {
        .logo-circle {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }
        
        .brand-title {
            font-size: 1.3rem;
        }
        
        .info-item {
            margin-bottom: 10px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
let cart = JSON.parse(localStorage.getItem('foodhouse_cart') || '[]');

// Update current time and date
function updateDateTime() {
    const now = new Date();
    document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit',
        hour12: true 
    });
    document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric', 
        year: 'numeric' 
    });
}

// Add to cart with animation
$(document).on('click', '.add-to-cart', function() {
    const $btn = $(this);
    const item = {
        id: $btn.data('id'),
        name: $btn.data('name'),
        price: parseFloat($btn.data('price')),
        quantity: 1,
        stock: parseInt($btn.data('stock')),
        image: $btn.data('image') || '{{ asset("images/placeholder.jpg") }}'
    };
    
    const existingItem = cart.find(i => i.id === item.id);
    
    if (existingItem) {
        if (item.stock > 0 && existingItem.quantity >= item.stock) {
            showNotification(`Cannot add more than ${item.stock} items (available stock)`, 'warning');
            return;
        }
        existingItem.quantity++;
    } else {
        cart.push(item);
    }
    
    // Add animation
    $btn.html('<i class="fas fa-check me-2"></i>Added!');
    $btn.css('background', 'linear-gradient(135deg, #28a745, #218838)');
    
    setTimeout(() => {
        $btn.html('<i class="fas fa-plus-circle me-2"></i><span>Add to Order</span><span class="ms-auto"><i class="fas fa-peso-sign me-1"></i>' + item.price.toFixed(2) + '</span>');
        $btn.css('background', 'linear-gradient(135deg, #e63946, #d32f2f)');
    }, 1000);
    
    updateCartDisplay();
    saveCart();
    showNotification(`${item.name} added to cart!`, 'success');
});

// Update cart display
function updateCartDisplay() {
    const cartDiv = $('#cartItems');
    let subtotal = 0;
    
    if (cart.length === 0) {
        cartDiv.html(`
            <div class="text-center py-4">
                <i class="fas fa-shopping-basket fa-2x text-muted mb-3"></i>
                <p class="text-muted mb-1">Your cart is empty</p>
                <small class="text-muted">Add items from the menu</small>
            </div>
        `);
        $('#checkoutBtn').prop('disabled', true);
    } else {
        let cartHtml = '';
        
        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            
            cartHtml += `
                <div class="cart-item mb-3 pb-3 border-bottom" data-index="${index}">
                    <div class="d-flex align-items-center">
                        <div class="me-3" style="width: 40px; height: 40px; overflow: hidden; border-radius: 6px;">
                            <img src="${item.image}" alt="${item.name}" style="width: 100%; height: 100%; object-fit: cover;"
                                 onerror="this.onerror=null; this.src='{{ asset("images/placeholder.jpg") }}';">
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold small">${item.name}</div>
                            <div class="text-muted small">₱${item.price.toFixed(2)} each</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="quantity-controls me-3">
                                <button class="btn btn-sm btn-outline-secondary decrease-quantity" data-index="${index}">-</button>
                                <span class="mx-2 fw-bold">${item.quantity}</span>
                                <button class="btn btn-sm btn-outline-secondary increase-quantity" data-index="${index}">+</button>
                            </div>
                            <div class="me-3 fw-bold" style="color: #e63946;">₱${itemTotal.toFixed(2)}</div>
                            <button class="btn btn-sm btn-outline-danger remove-from-cart" data-index="${index}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        cartDiv.html(cartHtml);
        $('#checkoutBtn').prop('disabled', false);
    }
    
    const tax = subtotal * 0.12;
    const total = subtotal + tax;
    
    $('#cartSubtotal').text('₱' + subtotal.toFixed(2));
    $('#cartTax').text('₱' + tax.toFixed(2));
    $('#cartTotal').text('₱' + total.toFixed(2));
}

// Category filter
$(document).on('click', '.category-item', function(e) {
    e.preventDefault();
    const category = $(this).data('category');
    
    $('.category-item').removeClass('active');
    $(this).addClass('active');
    
    if (category === 'all') {
        $('.menu-item').show();
    } else {
        $('.menu-item').hide();
        $(`.menu-item[data-category="${category}"]`).show();
    }
    
    const visibleItems = $('.menu-item:visible').length;
    if (visibleItems === 0) {
        $('#noItemsFound').removeClass('d-none');
        $('#menuItemsGrid').addClass('d-none');
    } else {
        $('#noItemsFound').addClass('d-none');
        $('#menuItemsGrid').removeClass('d-none');
    }
});

// Quantity controls (using your original functions)
$(document).on('click', '.increase-quantity', function() {
    const index = $(this).data('index');
    const item = cart[index];
    
    if (item.stock > 0 && item.quantity >= item.stock) {
        showNotification(`Cannot add more than ${item.stock} items (available stock)`, 'warning');
        return;
    }
    
    item.quantity++;
    updateCartDisplay();
    saveCart();
    showNotification(`Increased ${item.name} quantity to ${item.quantity}`, 'info');
});

$(document).on('click', '.decrease-quantity', function() {
    const index = $(this).data('index');
    const item = cart[index];
    
    if (item.quantity > 1) {
        item.quantity--;
        updateCartDisplay();
        saveCart();
        showNotification(`Decreased ${item.name} quantity to ${item.quantity}`, 'info');
    }
});

$(document).on('click', '.remove-from-cart', function() {
    const index = $(this).data('index');
    const itemName = cart[index].name;
    cart.splice(index, 1);
    updateCartDisplay();
    saveCart();
    showNotification(`${itemName} removed from cart`, 'warning');
});

// Clear cart
$('#clearCartBtn').click(function() {
    if (cart.length > 0 && confirm('Clear all items from cart?')) {
        cart = [];
        updateCartDisplay();
        saveCart();
        showNotification('Cart cleared', 'info');
    }
});

// Show/hide table number
$('input[name="order_type"]').change(function() {
    if ($(this).val() === 'dine_in') {
        $('#tableNumberGroup').slideDown();
        $('#tableNumberGroup input').prop('required', true);
    } else {
        $('#tableNumberGroup').slideUp();
        $('#tableNumberGroup input').prop('required', false);
    }
});

// Checkout button
$('#checkoutBtn').click(function() {
    if (cart.length === 0) {
        showNotification('Your cart is empty!', 'warning');
        return;
    }
    
    let checkoutHtml = '';
    let subtotal = 0;
    
    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;
        
        checkoutHtml += `
            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                <div class="d-flex align-items-center">
                    <div style="width: 40px; height: 40px; overflow: hidden; border-radius: 6px; margin-right: 10px;">
                        <img src="${item.image}" alt="${item.name}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div>
                        <strong>${item.name}</strong>
                        <br>
                        <small class="text-muted">₱${item.price.toFixed(2)} × ${item.quantity}</small>
                    </div>
                </div>
                <div class="text-end">
                    <strong style="color: #e63946;">₱${itemTotal.toFixed(2)}</strong>
                </div>
            </div>
            <input type="hidden" name="items[${index}][menu_item_id]" value="${item.id}">
            <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
        `;
    });
    
    const tax = subtotal * 0.12;
    const total = subtotal + tax;
    
    $('#checkoutItems').html(checkoutHtml);
    $('#checkoutSubtotal').text('₱' + subtotal.toFixed(2));
    $('#checkoutTax').text('₱' + tax.toFixed(2));
    $('#checkoutTotal').text('₱' + total.toFixed(2));
    
    const checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
    checkoutModal.show();
});

// Form submission (using your original function)
$('#checkoutForm').submit(function(e) {
    e.preventDefault();
    
    if (cart.length === 0) {
        showNotification('Your cart is empty!', 'warning');
        return;
    }
    
    $('#placeOrderBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...');
    
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                cart = [];
                localStorage.removeItem('foodhouse_cart');
                updateCartDisplay();
                
                $('#checkoutModal').modal('hide');
                showNotification('Order placed successfully! Order #: ' + response.order_number, 'success');
                
                setTimeout(() => {
                    window.location.href = response.redirect;
                }, 2000);
            } else {
                showNotification('Error: ' + response.message, 'danger');
                $('#placeOrderBtn').prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i>Place Order');
            }
        },
        error: function(xhr) {
            let errorMessage = 'Failed to place order. Please try again.';
            
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                errorMessage = Object.values(errors).flat().join('\n');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            showNotification('Error: ' + errorMessage, 'danger');
            $('#placeOrderBtn').prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i>Place Order');
        }
    });
});

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('foodhouse_cart', JSON.stringify(cart));
}

// Notification function
function showNotification(message, type = 'info') {
    const colors = {
        success: '#06d6a0',
        warning: '#ffd166',
        danger: '#e63946',
        info: '#e63946'
    };
    
    const toastHtml = `
        <div class="toast align-items-center text-white border-0" role="alert" style="background: ${colors[type] || colors.info};">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : type === 'danger' ? 'times-circle' : 'info-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    $('.toast-container').append(toastHtml);
    const toast = new bootstrap.Toast($('.toast').last()[0]);
    toast.show();
    
    setTimeout(() => {
        $('.toast').last().remove();
    }, 3000);
}

// Initialize
$(document).ready(function() {
    updateDateTime();
    updateCartDisplay();
    setInterval(updateDateTime, 60000);
    
    if ($('.toast-container').length === 0) {
        $('body').append('<div class="toast-container position-fixed bottom-0 end-0 p-3"></div>');
    }
});
</script>
@endpush