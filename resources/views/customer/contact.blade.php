@extends('layouts.customer')

@section('title', 'Contact Us')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-phone me-2"></i>Contact Us</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h6><i class="fas fa-map-marker-alt text-primary me-2"></i>Location</h6>
                        <p class="mb-1">123 Food Street</p>
                        <p class="mb-1">Makati City, Metro Manila</p>
                        <p>Philippines</p>
                    </div>
                    <div class="col-md-6 mb-4">
                        <h6><i class="fas fa-clock text-primary me-2"></i>Business Hours</h6>
                        <p class="mb-1">Monday - Friday: 10:00 AM - 10:00 PM</p>
                        <p class="mb-1">Saturday - Sunday: 9:00 AM - 11:00 PM</p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h6><i class="fas fa-phone text-primary me-2"></i>Phone Numbers</h6>
                        <p class="mb-1">Main: (02) 1234-5678</p>
                        <p>Mobile: 0917-123-4567</p>
                    </div>
                    <div class="col-md-6 mb-4">
                        <h6><i class="fas fa-envelope text-primary me-2"></i>Email</h6>
                        <p class="mb-1">General: info@foodhouse.com</p>
                        <p>Support: support@foodhouse.com</p>
                    </div>
                </div>
                
                <hr>
                
                <h6><i class="fas fa-comment-dots text-primary me-2"></i>Send us a Message</h6>
                <form>
                    <div class="mb-3">
                        <label class="form-label">Your Name</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Your Email</label>
                        <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" rows="4" placeholder="Type your message here..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection