
@extends('layouts.admin')
@section('content')
    <h2>Create Product</h2>
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="mb-3"><label>Kode</label><input type="text" name="kode" class="form-control" required></div>
        <div class="mb-3"><label>Nama</label><input type="text" name="nama" class="form-control" required></div>
        <div class="mb-3"><label>Kategori</label>
            <select name="category_id" class="form-control" required>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}">{{ $c->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3"><label>Satuan</label><input type="text" name="satuan" class="form-control" required></div>
        <div class="mb-3"><label>Harga</label><input type="number" step="0.01" name="harga" class="form-control" required></div>
        <div class="mb-3"><label>Stok</label><input type="number" name="stok" class="form-control" required></div>
        <button class="btn btn-success">Save</button>
    </form>
@endsection
