<x-app-layout>

<div class="container mt-4">

    <h1>Add Product</h1>

    <form action="{{ route('products.store') }}" method="POST">

        @csrf

        <div class="mb-3">
            <label>Kode</label>
            <input type="text" name="kode" class="form-control">
        </div>

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control">
        </div>

        <div class="mb-3">
            <label>Satuan</label>
            <input type="text" name="satuan" class="form-control">
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">
            Save
        </button>

    </form>

</div>

</x-app-layout>