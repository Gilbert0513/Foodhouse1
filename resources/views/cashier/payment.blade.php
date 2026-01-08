@extends('layouts.cashier')

@section('title', 'Process Payment - Order #' . $order->order_number)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-credit-card text-primary mr-2"></i>Process Payment
        </h1>
        <div>
            <a href="{{ route('cashier.orders.show', $order) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Order
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Order #:</strong> {{ $order->order_number }}</p>
                            <p><strong>Customer:</strong> {{ $order->customer->name ?? ($order->customer_name ?? 'Walk-in Customer') }}</p>
                            <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                            <p><strong>Order Type:</strong> 
                                <span class="badge badge-info">
                                    {{ ucfirst($order->order_type) }}
                                    @if($order->order_type == 'dine_in' && $order->table_number)
                                        (Table {{ $order->table_number }})
                                    @endif
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 text-right">
                            <h3 class="text-success">₱{{ number_format($order->grand_total, 2) }}</h3>
                            <span class="badge badge-warning">Pending Payment</span>
                            <div class="mt-2">
                                <span class="badge badge-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'confirmed' ? 'primary' : ($order->status == 'preparing' ? 'info' : ($order->status == 'ready' ? 'success' : 'secondary'))) }}">
                                    {{ strtoupper($order->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <form method="POST" action="{{ route('cashier.orders.process-payment', $order) }}" id="paymentForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_method" class="font-weight-bold">Payment Method *</label>
                                    <select name="payment_method" id="payment_method" class="form-control" required>
                                        <option value="">Select payment method</option>
                                        <option value="cash">Cash</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="debit_card">Debit Card</option>
                                        <option value="gcash">GCash</option>
                                        <option value="paymaya">Maya</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount" class="font-weight-bold">Amount Tendered *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₱</span>
                                        </div>
                                        <input type="number" name="amount" id="amount" 
                                               class="form-control" step="0.01" min="{{ $order->grand_total }}"
                                               value="{{ $order->grand_total }}" required
                                               oninput="calculateChange()">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_reference">Payment Reference</label>
                                    <input type="text" name="payment_reference" id="payment_reference" 
                                           class="form-control" placeholder="Transaction ID, Reference #">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Change Due</label>
                                    <div id="changeDisplay" class="form-control bg-light">
                                        ₱0.00
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Cash Denominations (shown only for cash payments) -->
                        <div id="cashDenominations" class="row mt-3" style="display: none;">
                            <div class="col-12">
                                <label class="font-weight-bold">Quick Add Cash</label>
                                <div class="d-flex flex-wrap">
                                    @php
                                        $denominations = [1000, 500, 200, 100, 50, 20, 10, 5, 1, 0.50, 0.25];
                                    @endphp
                                    @foreach($denominations as $denomination)
                                    <button type="button" class="btn btn-outline-primary btn-sm m-1" 
                                            onclick="addDenomination({{ $denomination }})">
                                        ₱{{ number_format($denomination, 2) }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="fas fa-check-circle mr-2"></i>Process Payment
                            </button>
                            <a href="{{ route('cashier.orders.pending-payment', $order) }}" class="btn btn-outline-secondary btn-lg ml-2">
                                <i class="fas fa-arrow-left mr-2"></i>Back to Pending Payment
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Items</h6>
                </div>
                <div class="card-body">
                    @if($order->items && $order->items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th class="text-right">Qty</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->menuItem->item_name ?? 'Item' }}</td>
                                        <td class="text-right">{{ $item->quantity }}</td>
                                        <td class="text-right">₱{{ number_format($item->total ?? ($item->quantity * $item->price), 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2">Subtotal</th>
                                        <th class="text-right">₱{{ number_format($order->total_amount, 2) }}</th>
                                    </tr>
                                    @if($order->tax_amount > 0)
                                    <tr>
                                        <th colspan="2">Tax</th>
                                        <th class="text-right">₱{{ number_format($order->tax_amount, 2) }}</th>
                                    </tr>
                                    @endif
                                    @if($order->discount_amount > 0)
                                    <tr>
                                        <th colspan="2">Discount</th>
                                        <th class="text-right">-₱{{ number_format($order->discount_amount, 2) }}</th>
                                    </tr>
                                    @endif
                                    <tr class="table-primary">
                                        <th colspan="2">Grand Total</th>
                                        <th class="text-right">₱{{ number_format($order->grand_total, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <p class="text-center">No items found</p>
                    @endif
                </div>
            </div>
            
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Summary</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h4 class="text-primary">Amount Due</h4>
                        <h2 class="text-success">₱{{ number_format($order->grand_total, 2) }}</h2>
                        
                        <div class="mt-4">
                            <h5 id="changeText">Change: ₱0.00</h5>
                            <div id="paymentStatus" class="mt-2">
                                <span class="badge badge-warning">Waiting for payment</span>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <p class="text-muted">
                                <i class="fas fa-info-circle mr-2"></i>
                                Enter the amount tendered above and select payment method
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Calculate change when amount changes
    function calculateChange() {
        const total = {{ $order->grand_total }};
        const tendered = parseFloat(document.getElementById('amount').value) || 0;
        const change = tendered - total;
        const changeDisplay = document.getElementById('changeDisplay');
        const changeText = document.getElementById('changeText');
        const paymentStatus = document.getElementById('paymentStatus');
        const submitBtn = document.getElementById('submitBtn');
        
        if (change >= 0) {
            changeDisplay.textContent = '₱' + change.toFixed(2);
            changeDisplay.className = 'form-control bg-success text-white';
            changeText.textContent = 'Change: ₱' + change.toFixed(2);
            changeText.className = 'text-success';
            paymentStatus.innerHTML = '<span class="badge badge-success">Ready to process</span>';
            submitBtn.disabled = false;
        } else {
            changeDisplay.textContent = '₱' + Math.abs(change).toFixed(2) + ' (Short)';
            changeDisplay.className = 'form-control bg-danger text-white';
            changeText.textContent = 'Short by: ₱' + Math.abs(change).toFixed(2);
            changeText.className = 'text-danger';
            paymentStatus.innerHTML = '<span class="badge badge-danger">Insufficient amount</span>';
            submitBtn.disabled = true;
        }
    }
    
    // Add denomination to amount tendered
    function addDenomination(amount) {
        const amountField = document.getElementById('amount');
        const currentValue = parseFloat(amountField.value) || 0;
        amountField.value = (currentValue + amount).toFixed(2);
        calculateChange();
        amountField.focus();
    }
    
    // Show/hide cash denominations based on payment method
    document.getElementById('payment_method').addEventListener('change', function() {
        const cashDenominations = document.getElementById('cashDenominations');
        if (this.value === 'cash') {
            cashDenominations.style.display = 'block';
        } else {
            cashDenominations.style.display = 'none';
        }
    });
    
    // Form validation
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        const total = {{ $order->grand_total }};
        const paymentMethod = document.getElementById('payment_method').value;
        
        if (!paymentMethod) {
            e.preventDefault();
            alert('Please select a payment method');
            return false;
        }
        
        if (amount < total) {
            e.preventDefault();
            alert('Amount tendered must be at least ₱' + total.toFixed(2));
            return false;
        }
        
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
        submitBtn.disabled = true;
        
        return confirm('Are you sure you want to process this payment?');
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+Enter to submit form
        if (e.ctrlKey && e.key === 'Enter') {
            document.getElementById('paymentForm').submit();
        }
        
        // Escape to clear amount
        if (e.key === 'Escape') {
            document.getElementById('amount').value = {{ $order->grand_total }};
            calculateChange();
        }
    });
    
    // Initialize change calculation
    calculateChange();
</script>
@endsection