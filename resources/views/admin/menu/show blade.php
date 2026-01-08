@extends('layouts.admin')

@section('title', 'View Menu Item')
@section('page-title', 'Menu Item Details')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Menu Item Details</h5>
    </div>
    <div class="card-body">
        <p class="text-muted">View details will go here. For now, you can go back.</p>
        <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Menu
        </a>
    </div>
</div>
@endsection