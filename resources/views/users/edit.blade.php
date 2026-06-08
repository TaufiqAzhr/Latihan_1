
@extends('layouts.admin')
@section('content')
    <h2>Edit User</h2>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3"><label>Name</label><input type="text" name="name" value="{{ $user->name }}" class="form-control" required></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" value="{{ $user->email }}" class="form-control" required></div>
        <div class="mb-3"><label>Password (leave blank to keep)</label><input type="password" name="password" class="form-control"></div>
        <button class="btn btn-success">Update</button>
    </form>
@endsection
