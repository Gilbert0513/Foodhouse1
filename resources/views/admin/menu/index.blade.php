@extends('layouts.admin')

@section('title', 'Menu Management')
@section('page-title', 'Menu Items')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Menu Items List</h5>
        <a href="{{ route('admin.menu.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Add New Item
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sample data for now -->
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No menu items found. <a href="{{ route('admin.menu.create') }}">Add your first item</a>
                        </td>
                    </tr>
                    <!--
                    <tr>
                        <td>1</td>
                        <td>Chicken Adobo</td>
                        <td>Main Course</td>
                        <td>₱150.00</td>
                        <td>50</td>
                        <td><span class="badge bg-success">Available</span></td>
                        <td>
                            <button class="btn btn-sm btn-info">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection