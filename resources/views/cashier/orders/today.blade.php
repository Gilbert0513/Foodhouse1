@extends('cashier.layout')

@section('title', "Today's Orders")

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Today's Orders ({{ date('F d, Y') }})</h4>
                <span class="badge bg-primary">Total: {{ $orders->count() }}</span>
            </div>
        </div>
        <div class="card-body">
            @if($orders->isEmpty())
                <div class="alert alert-info">No orders today yet.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->created_at->format('h:i A') }}</td>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->customer->name ?? 'Walk-in' }}</td>
                                <td>{{ $order->items->count() }} items</td>
                                <td>₱{{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status_color }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $order->payment_status_color }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('cashier.orders.show', $order) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($order->payment_status == 'pending')
                                    <a href="{{ route('cashier.orders.payment', $order) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-credit-card"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection