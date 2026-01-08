@extends('cashier.layout')

@section('title', 'Active Orders')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Active Orders (Preparing & Ready)</h4>
                <span class="badge bg-warning">Total: {{ $orders->count() }}</span>
            </div>
        </div>
        <div class="card-body">
            @if($orders->isEmpty())
                <div class="alert alert-info">No active orders.</div>
            @else
                <div class="row">
                    @foreach($orders as $order)
                    <div class="col-md-6 mb-3">
                        <div class="card border-{{ $order->status == 'ready' ? 'success' : 'warning' }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5>
                                            <i class="fas fa-{{ $order->status == 'ready' ? 'check-circle text-success' : 'utensils text-warning' }} me-2"></i>
                                            Order #{{ $order->id }}
                                        </h5>
                                        <p class="mb-1">
                                            <strong>Table:</strong> {{ $order->table_number ?? 'Takeaway' }}
                                        </p>
                                        <p class="mb-1">
                                            <strong>Items:</strong>
                                            @foreach($order->items as $item)
                                                {{ $item->quantity }}x {{ $item->menuItem->name }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        </p>
                                        <p class="mb-0">
                                            <strong>Time:</strong> {{ $order->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <form action="{{ route('cashier.orders.update-status', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('POST')
                                            @if($order->status == 'preparing')
                                            <button type="submit" name="status" value="ready" class="btn btn-success btn-sm">
                                                <i class="fas fa-check me-1"></i> Mark Ready
                                            </button>
                                            @else
                                            <button type="submit" name="status" value="served" class="btn btn-primary btn-sm">
                                                <i class="fas fa-check-double me-1"></i> Mark Served
                                            </button>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection