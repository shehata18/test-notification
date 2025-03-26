@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create New Order</div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('orders.store') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="product_name">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name"
                                    value="{{ old('product_name') }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="quantity">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity"
                                    value="{{ old('quantity', 1) }}" min="1" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="total_price">Total Price</label>
                                <input type="number" class="form-control" id="total_price" name="total_price"
                                    value="{{ old('total_price') }}" step="0.01" min="0" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="shipping_address">Shipping Address</label>
                                <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="notes">Notes (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Place Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
