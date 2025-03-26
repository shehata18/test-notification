@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Order #{{ $order->id }}</span>
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm">Back to Orders</a>
                    </div>

                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Order Date:</div>
                            <div class="col-md-8">{{ $order->created_at->format('F d, Y h:i A') }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Status:</div>
                            <div class="col-md-8">
                                <span
                                    class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Product:</div>
                            <div class="col-md-8">{{ $order->product_name }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Quantity:</div>
                            <div class="col-md-8">{{ $order->quantity }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Total Price:</div>
                            <div class="col-md-8">${{ number_format($order->total_price, 2) }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Shipping Address:</div>
                            <div class="col-md-8">{{ $order->shipping_address }}</div>
                        </div>

                        @if ($order->notes)
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Notes:</div>
                                <div class="col-md-8">{{ $order->notes }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
