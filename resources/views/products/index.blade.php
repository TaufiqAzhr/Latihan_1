<x-app-layout>

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h1>Data Products</h1>

        <a href="{{ route('products.create') }}" class="btn btn-primary">
            Add Product
        </a>
    </div>

    <table class="table table-bordered">

        <tr>
            <th>ID</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Action</th>
        </tr>

        @foreach($products as $product)

        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->kode }}</td>
            <td>{{ $product->nama }}</td>
            <td>{{ $product->satuan }}</td>
            <td>{{ $product->harga }}</td>

            <td>

                <a href="{{ route('products.edit', $product->id) }}"
                    class="btn btn-warning btn-sm">
                    Edit
                </a>

                <form action="{{ route('products.destroy', $product->id) }}"
                    method="POST"
                    class="d-inline">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="btn btn-danger btn-sm">
                        Delete
                    </button>

                </form>

            </td>

        </tr>

        @endforeach

    </table>

</div>

</x-app-layout>