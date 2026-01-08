<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Order | Foodhouse</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #e63946;
            --primary-dark: #d62839;
        }
        
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        
        .logo-circle {
            width: 40px;
            height: 40px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 1.2rem;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        
        .btn-primary {
            background: var(--primary);
            border: none;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .menu-item {
            border: 1px solid #dee2e6;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border-color: var(--primary);
        }
        
        .order-summary {
            height: calc(100vh - 200px);
            position: sticky;
            top: 20px;
        }
        
        @media (max-width: 992px) {
            .order-summary {
                height: auto;
                position: static;
            }
        }
        
        .object-fit-cover {
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('cashier.dashboard') }}">
                <div class="logo-circle">
                    <i class="fas fa-utensils"></i>
                </div>
                <div>
                    <div class="fw-bold" style="color: var(--primary);">Foodhouse</div>
                    <div class="small text-muted">POS System</div>
                </div>
            </a>
            
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small">
                    <i class="fas fa-user-circle me-1"></i>
                    {{ Auth::user()->name ?? 'Cashier' }}
                </span>
                <a href="{{ route('cashier.dashboard') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">
                    <i class="fas fa-cash-register me-2" style="color: var(--primary);"></i>
                    New Order
                </h2>
                <p class="text-muted mb-0">Create a new customer order</p>
            </div>
            <div class="badge bg-primary p-2">
                <i class="fas fa-shopping-cart me-1"></i>
                <span id="itemCount">0 items</span>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column - Menu -->
            <div class="col-lg-8">
                <!-- Order Info -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Order Type</label>
                                <select class="form-select" id="orderType">
                                    <option value="dine_in">Dine In</option>
                                    <option value="takeaway">Takeaway</option>
                                </select>
                            </div>
                            <div class="col-md-4" id="tableSection">
                                <label class="form-label fw-bold">Table Number</label>
                                <select class="form-select" id="tableSelect">
                                    @for($i = 1; $i <= 10; $i++)
                                    <option value="T{{ $i }}">Table {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Number of Persons</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="paxInput" value="1" min="1">
                                    <span class="input-group-text">pax</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <button class="btn btn-outline-primary btn-sm active" data-category="all">All Items</button>
                    @foreach($categories as $category)
                    <button class="btn btn-outline-primary btn-sm" data-category="{{ $category->id }}">
                        {{ $category->name }}
                    </button>
                    @endforeach
                </div>

                <!-- Menu Items -->
                <div class="row g-3" id="menuItemsGrid">
                    @foreach($categories as $category)
                        @foreach($category->menuItems as $item)
                            @php
                                // Use the original image handling from your code
                                if ($item->image_url) {
                                    $imagePath = $item->image_url;
                                } else {
                                    // Keep your original image mapping
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
                        
                        <div class="col-6 col-md-4 col-lg-3 menu-item" 
                             data-category="{{ $category->id }}"
                             onclick="addToCart({{ $item->id }}, '{{ addslashes($item->item_name) }}', {{ $item->price }}, '{{ $imagePath }}')">
                            <div style="height: 140px; overflow: hidden;">
                                <img src="{{ asset($imagePath) }}" 
                                     alt="{{ $item->item_name }}"
                                     class="w-100 h-100 object-fit-cover"
                                     onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                            </div>
                            <div class="p-3">
                                <div class="fw-bold small mb-1">{{ $item->item_name }}</div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="fw-bold" style="color: var(--primary);">
                                        ₱{{ number_format($item->price, 2) }}
                                    </div>
                                    @if($item->track_inventory && $item->stock_quantity > 0)
                                        <span class="badge bg-success bg-opacity-10 text-success small">
                                            {{ $item->stock_quantity }}
                                        </span>
                                    @elseif(!$item->track_inventory)
                                        <span class="badge bg-success bg-opacity-10 text-success small">
                                            Available
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger small">
                                            Out of Stock
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="col-lg-4">
                <div class="card order-summary">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>Order Summary
                        </h5>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <!-- Items List -->
                        <div class="flex-grow-1 overflow-auto mb-3" id="orderItems">
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-shopping-cart fa-2x mb-3"></i>
                                <p class="mb-0">Cart is empty</p>
                                <small>Select items from the menu</small>
                            </div>
                        </div>

                        <!-- Totals -->
                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal:</span>
                                <span id="subtotal">₱0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Tax (12%):</span>
                                <span id="tax">₱0.00</span>
                            </div>
                            <div class="d-flex justify-content-between border-top pt-3">
                                <h5 class="mb-0">Total:</h5>
                                <h4 class="mb-0" style="color: var(--primary);" id="total">₱0.00</h4>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-grid gap-2 mt-3">
                            <button class="btn btn-danger" id="clearCartBtn">
                                <i class="fas fa-trash-alt me-2"></i>Clear Cart
                            </button>
                            <button class="btn btn-primary" id="placeOrderBtn">
                                <i class="fas fa-check-circle me-2"></i>Place Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let cart = [];
        
        // Simple notification
        function showNotification(message, type = 'info') {
            // Remove existing notifications first
            document.querySelectorAll('.alert.position-fixed').forEach(el => el.remove());
            
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);';
            alert.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 3000);
        }
        
        // Add to cart
        function addToCart(itemId, name, price, image) {
            const existing = cart.find(item => item.menu_item_id == itemId);
            if (existing) {
                existing.quantity++;
            } else {
                cart.push({ 
                    menu_item_id: itemId, 
                    name: name, 
                    price: parseFloat(price), 
                    image: image, 
                    quantity: 1 
                });
            }
            updateCart();
            showNotification(`✓ Added ${name} to cart`, 'success');
        }
        
        // Update cart display
        function updateCart() {
            const itemCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * 0.12;
            const total = subtotal + tax;
            
            document.getElementById('itemCount').textContent = itemCount + ' item' + (itemCount !== 1 ? 's' : '');
            document.getElementById('subtotal').textContent = '₱' + subtotal.toFixed(2);
            document.getElementById('tax').textContent = '₱' + tax.toFixed(2);
            document.getElementById('total').textContent = '₱' + total.toFixed(2);
            
            const container = document.getElementById('orderItems');
            if (cart.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-shopping-cart fa-2x mb-3"></i>
                        <p class="mb-0">Cart is empty</p>
                        <small>Select items from the menu</small>
                    </div>
                `;
                return;
            }
            
            let html = '';
            cart.forEach((item, index) => {
                const itemTotal = item.price * item.quantity;
                html += `
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <div style="width: 60px; height: 60px; border-radius: 8px; overflow: hidden; flex-shrink: 0;">
                            <img src="{{ asset('') }}${item.image}" 
                                 alt="${item.name}" 
                                 class="w-100 h-100 object-fit-cover"
                                 onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold small">${item.name}</div>
                            <div class="text-muted small">₱${item.price.toFixed(2)} each</div>
                        </div>
                        <div class="d-flex align-items-center gap-2" style="flex-shrink: 0;">
                            <button class="btn btn-sm btn-outline-secondary" onclick="updateQty(${index}, -1)">
                                <i class="fas fa-minus"></i>
                            </button>
                            <span class="fw-bold">${item.quantity}</span>
                            <button class="btn btn-sm btn-outline-secondary" onclick="updateQty(${index}, 1)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="fw-bold ms-3" style="flex-shrink: 0;">₱${itemTotal.toFixed(2)}</div>
                        <button class="btn btn-sm btn-link text-danger ms-2" onclick="removeFromCart(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            });
            container.innerHTML = html;
        }
        
        // Quantity functions
        function updateQty(index, change) {
            const item = cart[index];
            const newQty = item.quantity + change;
            if (newQty < 1) {
                removeFromCart(index);
                return;
            }
            item.quantity = newQty;
            updateCart();
        }
        
        function removeFromCart(index) {
            const itemName = cart[index].name;
            cart.splice(index, 1);
            updateCart();
            showNotification(`Removed ${itemName} from cart`, 'warning');
        }
        
        // Clear cart
        document.getElementById('clearCartBtn').onclick = function() {
            if (cart.length === 0) {
                showNotification('Cart is already empty', 'info');
                return;
            }
            if (confirm('Are you sure you want to clear the cart?')) {
                cart = [];
                updateCart();
                showNotification('Cart cleared', 'info');
            }
        };
        
        // Place order
        document.getElementById('placeOrderBtn').onclick = function() {
            if (cart.length === 0) {
                showNotification('Please add items to cart first', 'warning');
                return;
            }
            
            const orderType = document.getElementById('orderType').value;
            const tableNumber = orderType === 'dine_in' ? document.getElementById('tableSelect').value : null;
            const pax = document.getElementById('paxInput').value;
            
            if (!pax || pax < 1) {
                showNotification('Please enter a valid number of pax', 'warning');
                return;
            }
            
            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('order_type', orderType);
            formData.append('table_number', tableNumber || '');
            formData.append('pax', pax);
            formData.append('items', JSON.stringify(cart.map(item => ({
                menu_item_id: item.menu_item_id,
                quantity: item.quantity
            }))));
            
            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            btn.disabled = true;
            
            $.ajax({
                url: '{{ route("cashier.pos.place-order") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function(response) {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    if (response.success) {
                        showNotification('Order placed successfully!', 'success');
                        cart = [];
                        updateCart();
                        setTimeout(() => {
                            window.location.href = response.redirect || '{{ route("cashier.dashboard") }}';
                        }, 1500);
                    } else {
                        showNotification('Error: ' + response.message, 'error');
                    }
                },
                error: function(xhr) {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    
                    let errorMessage = 'Failed to place order. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join(', ');
                    }
                    
                    showNotification('Error: ' + errorMessage, 'error');
                }
            });
        };
        
        // Category filtering
        document.querySelectorAll('[data-category]').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('[data-category]').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const category = this.dataset.category;
                document.querySelectorAll('.menu-item').forEach(item => {
                    item.style.display = (category === 'all' || item.dataset.category === category) ? 'block' : 'none';
                });
            });
        });
        
        // Show/hide table section
        document.getElementById('orderType').addEventListener('change', function() {
            document.getElementById('tableSection').style.display = this.value === 'dine_in' ? 'block' : 'none';
        });
        
        // Initialize
        updateCart();
        if (document.getElementById('orderType').value !== 'dine_in') {
            document.getElementById('tableSection').style.display = 'none';
        }
    </script>
</body>
</html>