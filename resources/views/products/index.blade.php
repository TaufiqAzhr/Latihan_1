
@extends('layouts.admin')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Products</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Satuan</th><th>Harga</th><th>Stok</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($products as $p)
            <tr>
                <td>{{ $p->kode }}</td><td>{{ $p->nama }}</td><td>{{ $p->category->nama ?? '' }}</td>
                <td>{{ $p->satuan }}</td><td>{{ $p->harga }}</td><td>{{ $p->stok }}</td>
                <td>
                    <a href="{{ route('products.edit', $p->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('products.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
