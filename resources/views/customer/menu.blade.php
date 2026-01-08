@extends('layouts.customer')

@section('title', 'Foodhouse Menu')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3">
            <!-- Categories Sidebar -->
            <div class="card shadow-lg mb-4 border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Categories</h5>
                </div>
                <div class="list-group list-group-flush rounded-bottom">
                    <a href="#" class="list-group-item list-group-item-action active category-filter d-flex align-items-center" 
                       data-category="all" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="fas fa-layer-group me-3"></i>
                        <span>All Items</span>
                        <span class="badge bg-white text-primary ms-auto">{{ $categories->sum(function($cat) { return $cat->menuItems->count(); }) }}</span>
                    </a>
                    @foreach($categories as $category)
                    <a href="#" class="list-group-item list-group-item-action category-filter d-flex align-items-center" 
                       data-category="{{ $category->id }}">
                        <i class="fas fa-utensils me-3 text-primary"></i>
                        <span>{{ $category->name }}</span>
                        <span class="badge bg-primary rounded-pill ms-auto">{{ $category->menuItems->count() }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="card shadow-lg border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-gradient-success text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Your Order</h5>
                </div>
                <div class="card-body">
                    <div id="cartItems" style="max-height: 300px; overflow-y: auto;">
                        <!-- Cart items will be displayed here -->
                    </div>
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal:</span>
                            <span id="cartSubtotal" class="fw-bold">₱0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tax (12%):</span>
                            <span id="cartTax" class="fw-bold">₱0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <strong class="text-success">Total:</strong>
                            <strong id="cartTotal" class="text-success fs-5">₱0.00</strong>
                        </div>
                        <button class="btn btn-lg btn-gradient-primary btn-block shadow-sm" id="checkoutBtn" disabled>
                            <i class="fas fa-credit-card me-2"></i> Checkout Now
                        </button>
                        <button class="btn btn-outline-danger btn-block mt-2" id="clearCartBtn">
                            <i class="fas fa-trash-alt me-2"></i> Clear Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <!-- Welcome Banner -->
            <div class="card shadow-lg mb-4 border-0 bg-gradient-info text-white">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-2"><i class="fas fa-utensils me-3"></i>Welcome to Foodhouse!</h2>
                            <p class="mb-0">Browse our delicious menu and place your order</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <i class="fas fa-hamburger fa-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Items Grid -->
            <div class="row" id="menuItemsGrid">
                @foreach($categories as $category)
                    @foreach($category->menuItems as $item)
                    <div class="col-md-4 mb-4 menu-item" data-category="{{ $category->id }}">
                        <div class="card h-100 shadow-lg border-0 hover-effect">
                            <!-- Image Section -->
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
                                <div class="image-container" style="height: 200px; overflow: hidden;">
                                    <img src="{{ asset($imagePath) }}" 
                                         class="card-img-top" 
                                         alt="{{ $item->item_name }}"
                                         style="height: 100%; width: 100%; object-fit: cover; transition: transform 0.5s ease;">
                                </div>
                                
                                <!-- Category Badge -->
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-primary-gradient px-3 py-2">
                                        <i class="fas fa-tag me-1"></i>{{ $category->name }}
                                    </span>
                                </div>
                                
                                <!-- Price Tag -->
                                <div class="position-absolute top-0 end-0 m-3">
                                    <div class="price-tag bg-success text-white px-3 py-2 rounded-pill shadow">
                                        <i class="fas fa-peso-sign me-1"></i>{{ number_format($item->price, 2) }}
                                    </div>
                                </div>
                                
                                <!-- Overlay Effect -->
                                <div class="image-overlay"></div>
                            </div>
                            
                            <div class="card-body">
                                <h5 class="card-title text-primary fw-bold">{{ $item->item_name }}</h5>
                                <p class="card-text text-muted mb-3">
                                    <i class="fas fa-info-circle me-2"></i>{{ Str::limit($item->description, 80) }}
                                </p>
                                
                                <!-- Item Details -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="text-muted small">
                                        @if($item->preparation_time)
                                        <span class="me-3">
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
                                        <span class="badge bg-success-gradient px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i> {{ $item->stock_quantity }} in stock
                                        </span>
                                        @else
                                        <span class="badge bg-danger-gradient px-3 py-2">
                                            <i class="fas fa-times-circle me-1"></i> Out of Stock
                                        </span>
                                        @endif
                                    @else
                                        <span class="badge bg-success-gradient px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i> Available
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Card Footer -->
                            <div class="card-footer bg-white border-top-0 pt-0">
                                @if(!$item->track_inventory || $item->stock_quantity > 0)
                                <button class="btn btn-gradient-primary btn-block add-to-cart d-flex align-items-center justify-content-center py-3"
                                        data-id="{{ $item->id }}"
                                        data-name="{{ $item->item_name }}"
                                        data-price="{{ $item->price }}"
                                        data-stock="{{ $item->stock_quantity }}"
                                        data-image="{{ asset($imagePath) }}">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    <span>Add to Order</span>
                                    <span class="ms-auto">
                                        <i class="fas fa-peso-sign me-1"></i>{{ number_format($item->price, 2) }}
                                    </span>
                                </button>
                                @else
                                <button class="btn btn-outline-secondary btn-block d-flex align-items-center justify-content-center py-3" disabled>
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
            <div class="text-center py-5 d-none" id="noItemsFound">
                <div class="empty-state">
                    <i class="fas fa-utensils fa-4x text-muted mb-4"></i>
                    <h3 class="text-muted">No items found</h3>
                    <p class="text-muted">Try selecting a different category</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white">
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
                                        <div class="icon-circle bg-primary text-white me-3">
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
                                        <div class="icon-circle bg-success text-white me-3">
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
                                    <h5 class="text-success mb-0">Total</h5>
                                    <h3 class="text-success fw-bold" id="checkoutTotal">₱0.00</h3>
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
                    <button type="submit" class="btn btn-gradient-primary" id="placeOrderBtn">
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
    /* Custom Styles */
    :root {
        --primary-color: #667eea;
        --secondary-color: #764ba2;
        --success-color: #38c172;
        --warning-color: #f6993f;
        --danger-color: #e3342f;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%) !important;
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #38c1a2 100%) !important;
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%) !important;
    }
    
    .bg-primary-gradient {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
    }
    
    .bg-success-gradient {
        background: linear-gradient(135deg, var(--success-color), #38c1a2) !important;
    }
    
    .bg-danger-gradient {
        background: linear-gradient(135deg, var(--danger-color), #f65) !important;
    }
    
    .btn-gradient-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }
    
    .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-gradient-primary:disabled {
        background: linear-gradient(135deg, #ccc 0%, #999 100%);
    }
    
    .hover-effect {
        transition: all 0.3s ease;
    }
    
    .hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    }
    
    .card.hover-effect:hover .card-img-top {
        transform: scale(1.1);
    }
    
    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 200px;
        background: linear-gradient(to bottom, transparent 70%, rgba(0,0,0,0.7) 100%);
        pointer-events: none;
    }
    
    .price-tag {
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .cart-item-quantity {
        width: 60px;
        text-align: center;
        border: 1px solid #dee2e6;
    }
    
    .quantity-btn {
        width: 30px;
        height: 30px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dee2e6;
        background: white;
    }
    
    .quantity-btn:hover {
        background: #f8f9fa;
    }
    
    .empty-state {
        padding: 4rem;
        background: #f8f9fa;
        border-radius: 15px;
        border: 2px dashed #dee2e6;
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: var(--secondary-color);
    }
    
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .menu-item {
        animation: fadeIn 0.5s ease forwards;
    }
    
    .menu-item:nth-child(1) { animation-delay: 0.1s; }
    .menu-item:nth-child(2) { animation-delay: 0.2s; }
    .menu-item:nth-child(3) { animation-delay: 0.3s; }
    .menu-item:nth-child(4) { animation-delay: 0.4s; }
    .menu-item:nth-child(5) { animation-delay: 0.5s; }
    .menu-item:nth-child(6) { animation-delay: 0.6s; }
</style>
@endpush

@push('scripts')
<script>
let cart = JSON.parse(localStorage.getItem('foodhouse_cart') || '[]');

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
    $btn.removeClass('btn-gradient-primary').addClass('btn-success');
    
    setTimeout(() => {
        $btn.html('<i class="fas fa-plus-circle me-2"></i>Add to Order');
        $btn.removeClass('btn-success').addClass('btn-gradient-primary');
    }, 1000);
    
    updateCartDisplay();
    saveCart();
    showNotification(`${item.name} added to cart!`, 'success');
});

// Update cart display with enhanced design
function updateCartDisplay() {
    const cartDiv = $('#cartItems');
    let subtotal = 0;
    
    if (cart.length === 0) {
        cartDiv.html(`
            <div class="text-center py-4">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-0">Your cart is empty</p>
                <small class="text-muted">Add items from the menu</small>
            </div>
        `);
        $('#checkoutBtn').prop('disabled', true).removeClass('shadow-sm');
    } else {
        let cartHtml = '<div class="list-group border-0">';
        
        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            
            cartHtml += `
                <div class="list-group-item border-0 mb-2 bg-light rounded-lg">
                    <div class="d-flex align-items-center">
                        <!-- Item Image -->
                        <div class="me-3" style="width: 50px; height: 50px; overflow: hidden; border-radius: 8px; border: 2px solid white;">
                            <img src="${item.image}" 
                                 alt="${item.name}" 
                                 style="width: 100%; height: 100%; object-fit: cover;"
                                 onerror="this.onerror=null; this.src='{{ asset("images/placeholder.jpg") }}';">
                        </div>
                        
                        <!-- Item Details -->
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">${item.name}</h6>
                            <small class="text-muted">₱${item.price.toFixed(2)} each</small>
                        </div>
                        
                        <!-- Quantity Controls -->
                        <div class="d-flex align-items-center">
                            <div class="input-group input-group-sm me-2" style="width: 110px;">
                                <button class="btn btn-outline-secondary quantity-btn decrease-quantity" 
                                        data-index="${index}" type="button">-</button>
                                <input type="number" class="form-control text-center cart-item-quantity" 
                                       value="${item.quantity}" min="1" max="${item.stock}" 
                                       data-index="${index}">
                                <button class="btn btn-outline-secondary quantity-btn increase-quantity" 
                                        data-index="${index}" type="button">+</button>
                            </div>
                            <span class="me-3 fw-bold text-primary">₱${itemTotal.toFixed(2)}</span>
                            <button class="btn btn-sm btn-outline-danger remove-from-cart" 
                                    data-index="${index}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        cartHtml += '</div>';
        cartDiv.html(cartHtml);
        $('#checkoutBtn').prop('disabled', false).addClass('shadow-sm');
    }
    
    const tax = subtotal * 0.12;
    const total = subtotal + tax;
    
    $('#cartSubtotal').text('₱' + subtotal.toFixed(2));
    $('#cartTax').text('₱' + tax.toFixed(2));
    $('#cartTotal').text('₱' + total.toFixed(2));
}

// Update quantity with notification
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

// Update quantity via input
$(document).on('change', '.cart-item-quantity', function() {
    const index = $(this).data('index');
    const newQuantity = parseInt($(this).val());
    const item = cart[index];
    
    if (newQuantity < 1) {
        $(this).val(1);
        item.quantity = 1;
    } else if (item.stock > 0 && newQuantity > item.stock) {
        showNotification(`Cannot add more than ${item.stock} items (available stock)`, 'warning');
        $(this).val(item.quantity);
        return;
    } else {
        item.quantity = newQuantity;
    }
    
    updateCartDisplay();
    saveCart();
});

// Remove from cart
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

// Category filter with animation
$(document).on('click', '.category-filter', function(e) {
    e.preventDefault();
    const category = $(this).data('category');
    
    $('.category-filter').removeClass('active');
    $(this).addClass('active');
    
    // Add animation to items
    $('.menu-item').hide().removeClass('d-block');
    
    setTimeout(() => {
        if (category === 'all') {
            $('.menu-item').addClass('d-block').show();
        } else {
            $(`.menu-item[data-category="${category}"]`).addClass('d-block').show();
        }
        
        // Show/hide empty state
        const visibleItems = $('.menu-item:visible').length;
        if (visibleItems === 0) {
            $('#noItemsFound').removeClass('d-none');
            $('#menuItemsGrid').addClass('d-none');
        } else {
            $('#noItemsFound').addClass('d-none');
            $('#menuItemsGrid').removeClass('d-none');
        }
    }, 300);
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
    
    // Prepare checkout items display
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
                    <strong class="text-primary">₱${itemTotal.toFixed(2)}</strong>
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
    
    // Show modal with animation
    const checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
    checkoutModal.show();
});

// Form submission
$('#checkoutForm').submit(function(e) {
    e.preventDefault();
    
    if (cart.length === 0) {
        showNotification('Your cart is empty!', 'warning');
        return;
    }
    
    // Show loading
    $('#placeOrderBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...');
    
    // Submit form via AJAX
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                // Clear cart
                cart = [];
                localStorage.removeItem('foodhouse_cart');
                updateCartDisplay();
                
                // Close modal
                $('#checkoutModal').modal('hide');
                
                // Show success message
                showNotification('Order placed successfully! Order #: ' + response.order_number, 'success');
                
                // Redirect to orders page after 2 seconds
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
    // Create toast
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : type === 'danger' ? 'times-circle' : 'info-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    // Add to toast container
    $('.toast-container').append(toastHtml);
    
    // Show toast
    const toast = new bootstrap.Toast($('.toast').last()[0]);
    toast.show();
    
    // Remove after 3 seconds
    setTimeout(() => {
        $('.toast').last().remove();
    }, 3000);
}

// Initialize on page load
$(document).ready(function() {
    updateCartDisplay();
    
    // Create toast container if it doesn't exist
    if ($('.toast-container').length === 0) {
        $('body').append('<div class="toast-container position-fixed bottom-0 end-0 p-3"></div>');
    }
    
    // Add hover effect to cards
    $('.card.hover-effect').hover(
        function() {
            $(this).find('.card-img-top').css('transform', 'scale(1.1)');
        },
        function() {
            $(this).find('.card-img-top').css('transform', 'scale(1)');
        }
    );
});
</script>
@endpush