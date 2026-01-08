@extends('layouts.admin')

@section('title', 'Categories Management')
@section('page-title', 'Categories')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Food Categories</h5>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Add New Category
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="row">
            @foreach($categories as $category)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">{{ $category->name }}</h6>
                        <small class="text-muted">{{ $category->description }}</small>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <span class="badge bg-info">{{ $category->menuItems->count() }} items</span>
                            <span class="badge {{ $category->status == 'active' ? 'bg-success' : 'bg-danger' }} ms-2">
                                {{ ucfirst($category->status) }}
                            </span>
                        </p>
                        
                        @if($category->menuItems->count() > 0)
                        <div class="menu-items-list">
                            @foreach($category->menuItems as $item)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-1">
                                <div>
                                    <small><strong>{{ $item->item_name }}</strong></small>
                                    <br>
                                    <small class="text-muted">₱{{ number_format($item->price, 2) }}</small>
                                </div>
                                <small class="badge bg-secondary">{{ $item->stock_quantity }} in stock</small>
                            </div>
                            @endforeach
                            
                            @if($category->menuItems->count() == 10)
                            <div class="text-center mt-2">
                                <small class="text-muted">... and more</small>
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="text-center py-3 text-muted">
                            <i class="fas fa-utensils fa-2x mb-2"></i>
                            <p>No menu items</p>
                        </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection