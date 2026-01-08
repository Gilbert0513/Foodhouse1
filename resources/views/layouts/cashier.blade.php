<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pay Order #{{ $order->id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background: #f5f5f5;
            font-family: system-ui, -apple-system, sans-serif;
            padding-top: 20px;
        }
        
        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .btn {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="mb-0" style="color: #e63946;">Foodhouse - Payment</h3>
                        <small class="text-muted">Order #{{ $order->id }}</small>
                    </div>
                    <a href="{{ route('cashier.orders.unpaid') }}" class="btn btn-outline-secondary btn-sm">
                        ← Back
                    </a>
                </div>

                <div class="row">
                    <!-- Order Summary -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Order Summary</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Customer:</strong><br>
                                {{ $order->customer->name ?? ($order->customer_name ?? 'Walk-in') }}</p>
                                
                                <p><strong>Items:</strong></p>
                                <div style="max-height: 200px; overflow-y: auto; font-size: 0.9rem;">
                                    @foreach($order->items as $item)
                                    <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                        <span>{{ $item->quantity }}x {{ $item->item_name }}</span>
                                        <span>₱{{ number_format($item->total_price, 2) }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-3 pt-2 border-top">
                                    <div class="d-flex justify-content-between">
                                        <strong>Total Amount:</strong>
                                        <strong class="text-success">₱{{ number_format($order->total_amount, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Payment Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-success mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong>Amount Due:</strong>
                                        <h4 class="mb-0">₱{{ number_format($order->total_amount, 2) }}</h4>
                                    </div>
                                </div>

                                <form id="paymentForm">
                                    @csrf
                                    
                                    <!-- Payment Method -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Payment Method *</label>
                                        <select name="payment_method" class="form-control" required>
                                            <option value="cash">Cash</option>
                                            <option value="gcash">GCash</option>
                                            <option value="card">Card</option>
                                            <option value="maya">Maya</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Amount Paid by Customer -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Amount Paid *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" 
                                                   name="amount" 
                                                   id="amount_paid"
                                                   class="form-control" 
                                                   value="{{ $order->total_amount }}"
                                                   min="{{ $order->total_amount }}"
                                                   step="0.01"
                                                   required
                                                   oninput="calculateChange(this.value)">
                                        </div>
                                    </div>
                                    
                                    <!-- Change -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Change</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="text" 
                                                   name="change_amount" 
                                                   id="change_amount"
                                                   class="form-control bg-light" 
                                                   readonly
                                                   value="0.00">
                                        </div>
                                    </div>
                                    
                                    <!-- Reference Number -->
                                    <div class="mb-3">
                                        <label class="form-label">Reference Number (Optional)</label>
                                        <input type="text" 
                                               name="reference_number" 
                                               class="form-control" 
                                               placeholder="For GCash/Card payments">
                                    </div>
                                    
                                    <!-- Notes -->
                                    <div class="mb-3">
                                        <label class="form-label">Notes (Optional)</label>
                                        <textarea name="notes" 
                                                  class="form-control" 
                                                  rows="2" 
                                                  placeholder="Additional notes..."></textarea>
                                    </div>
                                    
                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn-success w-100 btn-lg" id="submitBtn">
                                        Complete Payment
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#28a745" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                        </svg>
                    </div>
                    <h4 class="text-success mb-3">Payment Successful!</h4>
                    <p class="mb-4">Order #{{ $order->id }} has been paid.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('cashier.orders.unpaid') }}" class="btn btn-success">
                            Continue
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const totalAmount = {{ $order->total_amount }};
        
        // Calculate change
        function calculateChange(amountPaid) {
            const paid = parseFloat(amountPaid) || 0;
            const change = paid - totalAmount;
            document.getElementById('change_amount').value = change > 0 ? change.toFixed(2) : '0.00';
            
            // Update button state
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = paid < totalAmount;
        }
        
        // Handle form submission
        document.getElementById('paymentForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const amountPaid = parseFloat(document.getElementById('amount_paid').value);
            
            // Quick validation
            if (amountPaid < totalAmount) {
                alert('Amount paid must be at least ₱' + totalAmount.toFixed(2));
                return;
            }
            
            if (!confirm('Confirm payment for Order #{{ $order->id }}?')) {
                return;
            }
            
            // Show loading
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';
            
            try {
                // Send request using FormData
                const response = await fetch('{{ route("cashier.orders.process-payment", $order) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: new FormData(this)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Show success modal
                    const modal = new bootstrap.Modal(document.getElementById('successModal'));
                    modal.show();
                } else {
                    // Show error message
                    alert(data.message || 'Payment failed. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Complete Payment';
                }
                
            } catch (error) {
                alert('Network error. Please try again.');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Complete Payment';
            }
        });
        
        // Enter key to submit
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.ctrlKey && !e.shiftKey) {
                if (e.target.tagName !== 'TEXTAREA') {
                    e.preventDefault();
                    document.getElementById('paymentForm').requestSubmit();
                }
            }
        });
        
        // Initialize change calculation
        calculateChange(totalAmount);
    </script>
</body>
</html>