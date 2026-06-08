
@extends('layouts.admin')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Customers</h2>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">Add Customer</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr><th>Kode</th><th>Nama</th><th>Telepon</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($customers as $c)
            <tr>
                <td>{{ $c->kode }}</td><td>{{ $c->nama }}</td><td>{{ $c->telepon }}</td>
                <td>
                    <a href="{{ route('customers.edit', $c->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('customers.destroy', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
