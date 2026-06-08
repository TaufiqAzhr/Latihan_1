
@extends('layouts.admin')
@section('content')
    <h2>Edit Category</h2>
    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3"><label>Nama</label><input type="text" name="nama" value="{{ $category->nama }}" class="form-control" required></div>
        <div class="mb-3"><label>Deskripsi</label><textarea name="deskripsi" class="form-control">{{ $category->deskripsi }}</textarea></div>
        <button class="btn btn-success">Update</button>
    </form>
@endsection
