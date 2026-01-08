@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Management</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i>Add New User
        </a>
    </div>

    <!-- User Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Administrators</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->where('role', 'admin')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Cashiers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->where('role', 'cashier')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cash-register fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Customers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->where('role', 'customer')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Users</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="usersTable" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <strong>{{ $user->username }}</strong>
                            </td>
                            <td>{{ $user->full_name }}</td>
                            <td>
                                @switch($user->role)
                                    @case('admin')
                                        <span class="badge badge-danger">
                                            <i class="fas fa-user-shield me-1"></i>Admin
                                        </span>
                                        @break
                                    @case('cashier')
                                        <span class="badge badge-primary">
                                            <i class="fas fa-cash-register me-1"></i>Cashier
                                        </span>
                                        @break
                                    @case('customer')
                                        <span class="badge badge-success">
                                            <i class="fas fa-user me-1"></i>Customer
                                        </span>
                                        @break
                                @endswitch
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone_number ?? 'N/A' }}</td>
                            <td>
                                @if($user->status === 'active')
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-warning toggle-status"
                                            data-id="{{ $user->id }}"
                                            data-status="{{ $user->status }}"
                                            title="Toggle Status">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger delete-user"
                                            data-id="{{ $user->id }}"
                                            data-name="{{ $user->full_name }}"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete user <strong id="deleteUserName"></strong>?</p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Initialize DataTable
$(document).ready(function() {
    $('#usersTable').DataTable({
        pageLength: 25,
        order: [[0, 'desc']]
    });
});

// Delete user
$('.delete-user').click(function() {
    const userId = $(this).data('id');
    const userName = $(this).data('name');
    
    $('#deleteUserName').text(userName);
    $('#deleteForm').attr('action', `/admin/users/${userId}`);
    $('#deleteModal').modal('show');
});

// Toggle user status
$('.toggle-status').click(function() {
    const userId = $(this).data('id');
    const currentStatus = $(this).data('status');
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    
    if (confirm(`Change user status to ${newStatus}?`)) {
        $.ajax({
            url: `/admin/users/${userId}/toggle-status`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: newStatus
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            }
        });
    }
});
</script>
@endpush