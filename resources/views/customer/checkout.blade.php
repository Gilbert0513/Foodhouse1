@extends('layouts.customer')

@section('title', 'Checkout')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Checkout</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('customer.orders.store') }}" method="POST">
                    @csrf
                    
                    <h6 class="mb-3">Order Details</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Order Type</label>
                            <select name="order_type" id="order_type" class="form-control" required>
                                <option value="takeaway">Takeaway</option>
                                <option value="dine_in">Dine In</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Number of Pax</label>
                            <input type="number" name="pax" class="form-control" value="1" min="1">
                        </div>
                    </div>
                    
                    <div class="mb-3" id="table_number_section" style="display: none;">
                        <label class="form-label">Table Number</label>
                        <input type="text" name="table_number" class="form-control" placeholder="Enter table number">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Special Instructions</label>
                        <textarea name="special_instructions" class="form-control" rows="3" placeholder="Any special requests?"></textarea>
                    </div>
                    
                    <h6 class="mb-3">Order Items</h6>
                    @foreach($cart as $item)
                    <div class="border-bottom pb-2 mb-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $item['name'] }}</strong>
                                <br>
                                <small class="text-muted">₱{{ number_format($item['price'], 2) }} × {{ $item['quantity'] }}</small>
                            </div>
                            <strong>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</strong>
                        </div>
                        <input type="hidden" name="items[{{ $loop->index }}][menu_item_id]" value="{{ $item['id'] }}">
                        <input type="hidden" name="items[{{ $loop->index }}][quantity]" value="{{ $item['quantity'] }}">
                    </div>
                    @endforeach
                    
                    <div class="mt-4">
                        <a href="{{ route('customer.cart') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Cart
                        </a>
                        <button type="submit" class="btn btn-success float-end">
                            <i class="fas fa-paper-plane me-2"></i>Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Order Summary</h6>
            </div>
            <div class="card-body">
                @php
                    $subtotal = 0;
                    foreach($cart as $item) {
                        $subtotal += $item['price'] * $item['quantity'];
                    }
                    $tax = $subtotal * 0.12;
                    $total = $subtotal + $tax;
                @endphp
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>₱{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tax (12%):</span>
                    <span>₱{{ number_format($tax, 2) }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total:</strong>
                    <strong>₱{{ number_format($total, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('order_type').addEventListener('change', function() {
    var tableSection = document.getElementById('table_number_section');
    if (this.value === 'dine_in') {
        tableSection.style.display = 'block';
    } else {
        tableSection.style.display = 'none';
    }
});
</script>
@endsection