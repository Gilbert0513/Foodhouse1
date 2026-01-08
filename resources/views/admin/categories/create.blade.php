@extends('layouts.admin')

@section('title', 'Add Category')
@section('page-title', 'Add New Category')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Category Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               placeholder="e.g., Main Course, Desserts, Beverages">
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                                  placeholder="Brief description of this category"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Save Category
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
                    <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i> Use clear, descriptive names</li>
                    <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i> Categories help organize menu items</li>
                    <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i> Set to "Inactive" to hide from menu</li>
                    <li><i class="fas fa-lightbulb text-warning me-2"></i> You can edit categories anytime</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection