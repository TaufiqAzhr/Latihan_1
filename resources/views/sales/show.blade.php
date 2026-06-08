
@extends('layouts.admin')
@section('content')
    <h2>Transaction Detail: {{ $sale->kode }}</h2>
    <div class="mb-3">
        <strong>Date:</strong> {{ $sale->sale_date }}<br>
        <strong>Customer:</strong> {{ $sale->customer->nama ?? '' }}<br>
        <strong>Total Amount:</strong> {{ number_format($sale->total_amount, 2) }}
    </div>
    <table class="table table-bordered">
        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
        <tbody>
            @foreach($sale->saleItems as $item)
            <tr>
                <td>{{ $item->product->nama ?? '' }}</td>
                <td>{{ number_format($item->price, 2) }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('sales.index') }}" class="btn btn-secondary">Back</a>
@endsection
