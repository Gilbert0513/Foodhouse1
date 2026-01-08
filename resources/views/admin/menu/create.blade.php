@extends('layouts.admin')

@section('title', 'Add Menu Item')
@section('page-title', 'Add New Menu Item')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Menu Item Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Item Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-control" id="category" name="category_id">
                                <option value="">Select Category</option>
                                <option value="1">Main Course</option>
                                <option value="2">Appetizer</option>
                                <option value="3">Dessert</option>
                                <option value="4">Beverage</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price *</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">Initial Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" value="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="available">Available</option>
                                <option value="out_of_stock">Out of Stock</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Item Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Save Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Tips</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-info-circle text-primary me-2"></i> Required fields are marked with *</li>
                    <li class="mb-2"><i class="fas fa-info-circle text-primary me-2"></i> Set appropriate stock levels</li>
                    <li class="mb-2"><i class="fas fa-info-circle text-primary me-2"></i> Upload clear images for better presentation</li>
                    <li><i class="fas fa-info-circle text-primary me-2"></i> Set status to "Out of Stock" for unavailable items</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection