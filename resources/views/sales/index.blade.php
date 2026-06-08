
@extends('layouts.admin')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Sales Transactions</h2>
        <a href="{{ route('sales.create') }}" class="btn btn-primary">Create Transaction</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr><th>Invoice</th><th>Date</th><th>Customer</th><th>Total</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($sales as $s)
            <tr>
                <td>{{ $s->kode }}</td><td>{{ $s->sale_date }}</td><td>{{ $s->customer->nama ?? '' }}</td><td>{{ number_format($s->total_amount, 2) }}</td>
                <td>
                    <a href="{{ route('sales.show', $s->id) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('sales.edit', $s->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('sales.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete? Stock will be restored.')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
