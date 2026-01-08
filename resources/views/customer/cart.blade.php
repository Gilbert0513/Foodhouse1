@extends('layouts.customer')

@section('title', 'My Cart')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light-blue text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-shopping-cart fa-lg me-3"></i>
                        <div>
                            <h5 class="mb-0 fw-bold">My Cart</h5>
                            <p class="mb-0 opacity-75">Review your order items</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            @if(empty($cart))
                                <!-- Empty Cart State -->
                                <div class="text-center py-5">
                                    <div class="empty-state-icon mb-4">
                                        <i class="fas fa-shopping-cart fa-4x text-muted"></i>
                                    </div>
                                    <h5 class="text-muted mb-3">Your cart is empty</h5>
                                    <p class="text-muted mb-4">Add some delicious items from our menu!</p>
                                    <a href="{{ route('customer.menu') }}" class="btn btn-blue">
                                        <i class="fas fa-utensils me-2"></i>Browse Menu
                                    </a>
                                </div>
                            @else
                                <form action="{{ route('customer.cart.update') }}" method="POST" id="cart-form">
                                    @csrf
                                    
                                    <!-- Cart Items List -->
                                    <div class="cart-items">
                                        @foreach($cart as $item)
                                        <div class="cart-item border-bottom pb-3 mb-3">
                                            <div class="row align-items-center">
                                                <div class="col-md-2">
                                                    <!-- Item Image (Placeholder) -->
                                                    <div class="item-image bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-utensils text-muted"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <h6 class="mb-1 fw-bold">{{ $item['name'] }}</h6>
                                                    <p class="text-muted mb-0 small">
                                                        ₱{{ number_format($item['price'], 2) }} each
                                                    </p>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="quantity-control">
                                                        <div class="input-group input-group-sm">
                                                            <button type="button" class="btn btn-outline-secondary decrease-btn" data-id="{{ $item['id'] }}">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input type="number" 
                                                                   name="items[{{ $item['id'] }}][quantity]" 
                                                                   value="{{ $item['quantity'] }}" 
                                                                   min="1" 
                                                                   class="form-control text-center quantity-input"
                                                                   data-id="{{ $item['id'] }}"
                                                                   data-price="{{ $item['price'] }}">
                                                            <button type="button" class="btn btn-outline-secondary increase-btn" data-id="{{ $item['id'] }}">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        <input type="hidden" name="items[{{ $item['id'] }}][id]" value="{{ $item['id'] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 text-end">
                                                    <div class="item-total">
                                                        <strong class="text-dark">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</strong>
                                                    </div>
                                                    <button type="button" class="btn btn-link btn-sm text-danger remove-item" data-id="{{ $item['id'] }}">
                                                        <i class="fas fa-trash-alt"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- Cart Actions -->
                                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                        <a href="{{ route('customer.cart.clear') }}" class="btn btn-red-outline">
                                            <i class="fas fa-trash-alt me-2"></i>Clear Cart
                                        </a>
                                        <button type="submit" class="btn btn-blue">
                                            <i class="fas fa-sync me-2"></i>Update Cart
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-receipt me-2"></i>Order Summary</h6>
                        </div>
                        <div class="card-body p-4">
                            <!-- Summary Items -->
                            <div class="summary-item d-flex justify-content-between mb-3">
                                <span class="text-muted">Subtotal ({{ count($cart) ?? 0 }} items):</span>
                                <span class="fw-medium" id="subtotal">₱{{ number_format($total, 2) }}</span>
                            </div>
                            
                            <div class="summary-item d-flex justify-content-between mb-3">
                                <span class="text-muted">Tax (12%):</span>
                                <span class="fw-medium" id="tax">₱{{ number_format($total * 0.12, 2) }}</span>
                            </div>
                            
                            <div class="summary-item d-flex justify-content-between mb-3 pb-3 border-bottom">
                                <span class="text-muted">Service Charge:</span>
                                <span class="fw-medium">₱0.00</span>
                            </div>
                            
                            <!-- Total -->
                            <div class="summary-total d-flex justify-content-between mb-4">
                                <strong>Total Amount:</strong>
                                <strong class="text-green fs-5" id="grand-total">₱{{ number_format($total + ($total * 0.12), 2) }}</strong>
                            </div>
                            
                            @if(!empty($cart))
                                <!-- Checkout Button -->
                                <a href="{{ route('customer.checkout') }}" class="btn btn-green w-100 mb-3">
                                    <i class="fas fa-check-circle me-2"></i>Proceed to Checkout
                                </a>
                                
                                <!-- Continue Shopping -->
                                <a href="{{ route('customer.menu') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-plus me-2"></i>Add More Items
                                </a>
                            @endif
                            
                            <!-- Help Text -->
                            <div class="mt-4 pt-3 border-top">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    All prices include VAT. Free delivery on orders over ₱500.
                                </small>
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
    
    .bg-red {
        background-color: #ef4444 !important;
    }
    
    .text-red {
        color: #ef4444 !important;
    }
    
    /* Buttons */
    .btn-blue {
        background-color: #4a6bff;
        border-color: #4a6bff;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .btn-blue:hover {
        background-color: #3a5bef;
        border-color: #3a5bef;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(74, 107, 255, 0.2);
    }
    
    .btn-green {
        background-color: #10b981;
        border-color: #10b981;
        color: white;
        padding: 10px 16px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .btn-green:hover {
        background-color: #0da271;
        border-color: #0da271;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }
    
    .btn-red-outline {
        border: 1px solid #ef4444;
        color: #ef4444;
        background: transparent;
        padding: 8px 16px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .btn-red-outline:hover {
        background-color: rgba(239, 68, 68, 0.1);
        border-color: #dc2626;
        color: #dc2626;
    }
    
    .btn-outline-secondary {
        border: 1px solid #d1d5db;
        color: #6b7280;
    }
    
    .btn-outline-secondary:hover {
        background-color: #f9fafb;
        border-color: #9ca3af;
        color: #374151;
    }
    
    /* Card Styling */
    .card {
        border-radius: 10px;
        transition: transform 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    /* Cart Item Styling */
    .cart-item {
        transition: all 0.2s ease;
    }
    
    .cart-item:hover {
        background-color: rgba(74, 107, 255, 0.02);
        border-radius: 6px;
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .item-image {
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        transition: all 0.2s ease;
    }
    
    .cart-item:hover .item-image {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* Quantity Controls */
    .quantity-control .input-group {
        width: 120px;
    }
    
    .quantity-control .btn {
        width: 36px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .quantity-input {
        border-left: none;
        border-right: none;
        font-weight: 500;
    }
    
    .quantity-input:focus {
        box-shadow: none;
        border-color: #d1d5db;
    }
    
    /* Remove Item Button */
    .remove-item {
        text-decoration: none;
        font-size: 0.8rem;
        padding: 2px 0;
        transition: all 0.2s ease;
    }
    
    .remove-item:hover {
        text-decoration: underline;
    }
    
    /* Summary Styling */
    .summary-item {
        padding: 4px 0;
    }
    
    .summary-total {
        padding: 8px 0;
        border-top: 2px solid #e5e7eb;
        border-bottom: 2px solid #e5e7eb;
    }
    
    /* Empty State */
    .empty-state-icon {
        opacity: 0.6;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .cart-item .row > div {
            margin-bottom: 10px;
        }
        
        .cart-item .row > div:last-child {
            margin-bottom: 0;
            text-align: left !important;
        }
        
        .quantity-control .input-group {
            width: 140px;
        }
        
        .sticky-top {
            position: static !important;
        }
    }
    
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .cart-item {
        animation: fadeIn 0.3s ease;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Update item quantity with +/- buttons
    $('.increase-btn').click(function() {
        const id = $(this).data('id');
        const input = $(`.quantity-input[data-id="${id}"]`);
        const currentVal = parseInt(input.val());
        input.val(currentVal + 1);
        updateItemTotal(id);
        updateSummary();
    });
    
    $('.decrease-btn').click(function() {
        const id = $(this).data('id');
        const input = $(`.quantity-input[data-id="${id}"]`);
        const currentVal = parseInt(input.val());
        if (currentVal > 1) {
            input.val(currentVal - 1);
            updateItemTotal(id);
            updateSummary();
        }
    });
    
    // Update quantity via input
    $('.quantity-input').change(function() {
        const id = $(this).data('id');
        const value = parseInt($(this).val());
        if (value < 1) {
            $(this).val(1);
        }
        updateItemTotal(id);
        updateSummary();
    });
    
    // Remove item
    $('.remove-item').click(function() {
        const id = $(this).data('id');
        if (confirm('Remove this item from cart?')) {
            // Create a form to remove the item
            const form = $('#cart-form');
            const input = $(`.quantity-input[data-id="${id}"]`);
            input.val(0);
            form.submit();
        }
    });
    
    // Update item total
    function updateItemTotal(id) {
        const input = $(`.quantity-input[data-id="${id}"]`);
        const price = parseFloat(input.data('price'));
        const quantity = parseInt(input.val());
        const total = price * quantity;
        
        // Find the item total element and update it
        const cartItem = input.closest('.cart-item');
        cartItem.find('.item-total strong').text('₱' + total.toFixed(2));
    }
    
    // Update summary
    function updateSummary() {
        let subtotal = 0;
        
        // Calculate new subtotal
        $('.quantity-input').each(function() {
            const price = parseFloat($(this).data('price'));
            const quantity = parseInt($(this).val());
            subtotal += price * quantity;
        });
        
        const tax = subtotal * 0.12;
        const grandTotal = subtotal + tax;
        
        // Update summary display
        $('#subtotal').text('₱' + subtotal.toFixed(2));
        $('#tax').text('₱' + tax.toFixed(2));
        $('#grand-total').text('₱' + grandTotal.toFixed(2));
    }
    
    // Add hover effects
    $('.cart-item').hover(
        function() {
            $(this).addClass('hover');
        },
        function() {
            $(this).removeClass('hover');
        }
    );
});
</script>
@endpush