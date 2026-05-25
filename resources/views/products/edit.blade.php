<x-app-layout>

<div class="container mt-4">

    <h1>Edit Product</h1>

    <form action="{{ route('products.update', $product->id) }}"
        method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Kode</label>
            <input type="text"
                name="kode"
                value="{{ $product->kode }}"
                class="form-control">
        </div>

        <div class="mb-3">
            <label>Nama</label>
            <input type="text"
                name="nama"
                value="{{ $product->nama }}"
                class="form-control">
        </div>

        <div class="mb-3">
            <label>Satuan</label>
            <input type="text"
                name="satuan"
                value="{{ $product->satuan }}"
                class="form-control">
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number"
                name="harga"
                value="{{ $product->harga }}"
                class="form-control">
        </div>

        <button type="submit" class="btn btn-success">
            Update
        </button>

    </form>

</div>

</x-app-layout>