@extends('layouts.admin')

@section('title', 'View Category')
@section('page-title', 'Category Details')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Category Details</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">View details functionality will be implemented soon.</p>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Categories
                </a>
            </div>
        </div>
    </div>
</div>
@endsection