@extends('layouts.customer')

@section('title', 'Help & FAQs')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Help & Frequently Asked Questions</h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="helpAccordion">
                    <!-- FAQ 1 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                How do I place an order?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                To place an order:
                                <ol>
                                    <li>Go to the <a href="{{ route('customer.menu') }}">Menu</a> page</li>
                                    <li>Select the items you want to order</li>
                                    <li>Click "Add to Cart" for each item</li>
                                    <li>Go to your <a href="{{ route('customer.cart') }}">Cart</a></li>
                                    <li>Review your order and click "Checkout"</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ 2 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                How can I cancel my order?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                You can cancel your order from the "My Orders" page if it hasn't been prepared yet. Look for the cancel button next to your pending orders.
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ 3 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                What payment methods do you accept?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                We accept cash payments, credit/debit cards, and digital wallets (GCash, PayMaya) for orders.
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ 4 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                How long does food preparation take?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                Preparation time varies by dish but typically takes 15-30 minutes. You can check the estimated preparation time on each menu item.
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ 5 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                Can I modify my order after placing it?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                Orders can only be modified before preparation begins. Please contact our staff immediately if you need to make changes.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6><i class="fas fa-headset text-primary me-2"></i>Need More Help?</h6>
                    <p>If you have additional questions, please <a href="{{ route('customer.contact') }}">contact us</a> directly.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection