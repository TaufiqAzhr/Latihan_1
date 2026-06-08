
@extends('layouts.admin')
@section('content')
    <h2>Edit Product</h2>
    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3"><label>Kode</label><input type="text" name="kode" value="{{ $product->kode }}" class="form-control" required></div>
        <div class="mb-3"><label>Nama</label><input type="text" name="nama" value="{{ $product->nama }}" class="form-control" required></div>
        <div class="mb-3"><label>Kategori</label>
            <select name="category_id" class="form-control" required>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ $product->category_id == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3"><label>Satuan</label><input type="text" name="satuan" value="{{ $product->satuan }}" class="form-control" required></div>
        <div class="mb-3"><label>Harga</label><input type="number" step="0.01" name="harga" value="{{ $product->harga }}" class="form-control" required></div>
        <div class="mb-3"><label>Stok</label><input type="number" name="stok" value="{{ $product->stok }}" class="form-control" required></div>
        <button class="btn btn-success">Update</button>
    </form>
@endsection
