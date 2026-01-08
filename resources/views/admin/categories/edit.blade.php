@extends('layouts.admin')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Category: {{ $category->name }}</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" 
                               value="{{ old('name', $category->name) }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" 
                                  rows="3">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select class="form-control @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Menu Items in this Category</label>
                        <div class="border rounded p-3">
                            @if($category->menuItems->count() > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach($category->menuItems as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $item->item_name }}</strong>
                                            <br>
                                            <small class="text-muted">₱{{ number_format($item->price, 2) }}</small>
                                        </div>
                                        <span class="badge bg-secondary">{{ $item->stock_quantity }} in stock</span>
                                    </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center py-3 text-muted">
                                    <i class="fas fa-utensils fa-2x mb-2"></i>
                                    <p>No menu items in this category</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Category Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="p-3 border rounded">
                            <h4 class="mb-1">{{ $category->menuItems->count() }}</h4>
                            <small class="text-muted">Menu Items</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 border rounded">
                            <h4 class="mb-1">{{ $totalItemsSold }}</h4>
                            <small class="text-muted">Items Sold</small>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <h6>Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.menu-items.create') }}?category_id={{ $category->id }}" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-plus me-2"></i> Add Menu Item
                        </a>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-2"></i> Delete Category
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong>{{ $category->name }}</strong>?</p>
                @if($category->menuItems->count() > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This category has {{ $category->menuItems->count() }} menu items. 
                        Deleting it will remove the category from these items.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Category</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .list-group-item {
        border: none;
        padding: 0.75rem 0;
    }
</style>
@endpush