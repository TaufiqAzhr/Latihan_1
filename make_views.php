<?php
$viewsDir = __DIR__ . '/resources/views';

$directories = [
    'layouts', 'auth', 'users', 'customers', 'categories', 'products', 'sales'
];

foreach ($directories as $dir) {
    if (!is_dir("$viewsDir/$dir")) {
        mkdir("$viewsDir/$dir", 0777, true);
    }
}

$files = [
    'layouts/admin.blade.php' => '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack(\'styles\')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="{{ route(\'dashboard\') }}">Sales System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="{{ route(\'dashboard\') }}">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route(\'users.index\') }}">Users</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route(\'customers.index\') }}">Customers</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route(\'categories.index\') }}">Categories</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route(\'products.index\') }}">Products</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route(\'sales.index\') }}">Sales</a></li>
          </ul>
          <ul class="navbar-nav">
             <li class="nav-item">
                <form action="{{ route(\'logout\') }}" method="POST">
                    @csrf
                    <button class="btn btn-danger" type="submit">Logout</button>
                </form>
             </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container mt-4">
        @if(session(\'success\'))
            <div class="alert alert-success">{{ session(\'success\') }}</div>
        @endif
        @if(session(\'error\'))
            <div class="alert alert-danger">{{ session(\'error\') }}</div>
        @endif
        @yield(\'content\')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack(\'scripts\')
</body>
</html>
',
    'auth/login.blade.php' => '
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">Login</div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">{{ $errors->first() }}</div>
                        @endif
                        <form method="POST" action="{{ route(\'login\') }}">
                            @csrf
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
',
    'dashboard.blade.php' => '
@extends(\'layouts.admin\')
@section(\'content\')
    <h1>Welcome to Dashboard</h1>
@endsection
',
    // USERS
    'users/index.blade.php' => '
@extends(\'layouts.admin\')
@section(\'content\')
    <div class="d-flex justify-content-between mb-3">
        <h2>Users</h2>
        <a href="{{ route(\'users.create\') }}" class="btn btn-primary">Add User</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr><th>Name</th><th>Email</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="{{ route(\'users.edit\', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route(\'users.destroy\', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm(\'Delete?\')">
                        @csrf @method(\'DELETE\')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
',
    'users/create.blade.php' => '
@extends(\'layouts.admin\')
@section(\'content\')
    <h2>Create User</h2>
    <form action="{{ route(\'users.store\') }}" method="POST">
        @csrf
        <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" required></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
        <button class="btn btn-success">Save</button>
    </form>
@endsection
',
    'users/edit.blade.php' => '
@extends(\'layouts.admin\')
@section(\'content\')
    <h2>Edit User</h2>
    <form action="{{ route(\'users.update\', $user->id) }}" method="POST">
        @csrf @method(\'PUT\')
        <div class="mb-3"><label>Name</label><input type="text" name="name" value="{{ $user->name }}" class="form-control" required></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" value="{{ $user->email }}" class="form-control" required></div>
        <div class="mb-3"><label>Password (leave blank to keep)</label><input type="password" name="password" class="form-control"></div>
        <button class="btn btn-success">Update</button>
    </form>
@endsection
',
    // CUSTOMERS
    'customers/index.blade.php' => '
@extends(\'layouts.admin\')
@section(\'content\')
    <div class="d-flex justify-content-between mb-3">
        <h2>Customers</h2>
        <a href="{{ route(\'customers.create\') }}" class="btn btn-primary">Add Customer</a>
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
                    <a href="{{ route(\'customers.edit\', $c->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route(\'customers.destroy\', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm(\'Delete?\')">
                        @csrf @method(\'DELETE\')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
',
    'customers/create.blade.php' => '
@extends(\'layouts.admin\')
@section(\'content\')
    <h2>Create Customer</h2>
    <form action="{{ route(\'customers.store\') }}" method="POST">
        @csrf
        <div class="mb-3"><label>Kode</label><input type="text" name="kode" class="form-control" required></div>
        <div class="mb-3"><label>Nama</label><input type="text" name="nama" class="form-control" required></div>
        <div class="mb-3"><label>Alamat</label><textarea name="alamat" class="form-control"></textarea></div>
        <div class="mb-3"><label>Telepon</label><input type="text" name="telepon" class="form-control"></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control"></div>
        <button class="btn btn-success">Save</button>
    </form>
@endsection
',
    'customers/edit.blade.php' => '
@extends(\'layouts.admin\')
@section(\'content\')
    <h2>Edit Customer</h2>
    <form action="{{ route(\'customers.update\', $customer->id) }}" method="POST">
        @csrf @method(\'PUT\')
        <div class="mb-3"><label>Kode</label><input type="text" name="kode" value="{{ $customer->kode }}" class="form-control" required></div>
        <div class="mb-3"><label>Nama</label><input type="text" name="nama" value="{{ $customer->nama }}" class="form-control" required></div>
        <div class="mb-3"><label>Alamat</label><textarea name="alamat" class="form-control">{{ $customer->alamat }}</textarea></div>
        <div class="mb-3"><label>Telepon</label><input type="text" name="telepon" value="{{ $customer->telepon }}" class="form-control"></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" value="{{ $customer->email }}" class="form-control"></div>
        <button class="btn btn-success">Update</button>
    </form>
@endsection
',
    // CATEGORIES
    'categories/index.blade.php' => '
@extends(\'layouts.admin\')
@section(\'content\')
    <div class="d-flex justify-content-between mb-3">
        <h2>Categories</h2>
        <a href="{{ route(\'categories.create\') }}" class="btn btn-primary">Add Category</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr><th>Nama</th><th>Deskripsi</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($categories as $c)
            <tr>
                <td>{{ $c->nama }}</td><td>{{ $c->deskripsi }}</td>
                <td>
                    <a href="{{ route(\'categories.edit\', $c->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route(\'categories.destroy\', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm(\'Delete?\')">
                        @csrf @method(\'DELETE\')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
',
    'categories/create.blade.php' => '
@extends(\'layouts.admin\')
@section(\'content\')
    <h2>Create Category</h2>
    <form action="{{ route(\'categories.store\') }}" method="POST">
        @csrf
        <div class="mb-3"><label>Nama</label><input type="text" name="nama" class="form-control" required></div>
        <div class="mb-3"><label>Deskripsi</label><textarea name="deskripsi" class="form-control"></textarea></div>
        <button class="btn btn-success">Save</button>
    </form>
@endsection
',
    'categories/edit.blade.php' => '
@extends(\'layouts.admin\')
@section(\'content\')
    <h2>Edit Category</h2>
    <form action="{{ route(\'categories.update\', $category->id) }}" method="POST">
        @csrf @method(\'PUT\')
        <div class="mb-3"><label>Nama</label><input type="text" name="nama" value="{{ $category->nama }}" class="form-control" required></div>
        <div class="mb-3"><label>Deskripsi</label><textarea name="deskripsi" class="form-control">{{ $category->deskripsi }}</textarea></div>
        <button class="btn btn-success">Update</button>
    </form>
@endsection
',
    // PRODUCTS
    'products/index.blade.php' => '
@extends(\'layouts.admin\')
@section(\'content\')
    <div class="d-flex justify-content-between mb-3">
        <h2>Products</h2>
        <a href="{{ route(\'products.create\') }}" class="btn btn-primary">Add Product</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Satuan</th><th>Harga</th><th>Stok</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($products as $p)
            <tr>
                <td>{{ $p->kode }}</td><td>{{ $p->nama }}</td><td>{{ $p->category->nama ?? \'\' }}</td>
                <td>{{ $p->satuan }}</td><td>{{ $p->harga }}</td><td>{{ $p->stok }}</td>
                <td>
                    <a href="{{ route(\'products.edit\', $p->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route(\'products.destroy\', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm(\'Delete?\')">
                        @csrf @method(\'DELETE\')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
',
    'products/create.blade.php' => '
@extends(\'layouts.admin\')
@section(\'content\')
    <h2>Create Product</h2>
    <form action="{{ route(\'products.store\') }}" method="POST">
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
',
    'products/edit.blade.php' => '
@extends(\'layouts.admin\')
@section(\'content\')
    <h2>Edit Product</h2>
    <form action="{{ route(\'products.update\', $product->id) }}" method="POST">
        @csrf @method(\'PUT\')
        <div class="mb-3"><label>Kode</label><input type="text" name="kode" value="{{ $product->kode }}" class="form-control" required></div>
        <div class="mb-3"><label>Nama</label><input type="text" name="nama" value="{{ $product->nama }}" class="form-control" required></div>
        <div class="mb-3"><label>Kategori</label>
            <select name="category_id" class="form-control" required>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ $product->category_id == $c->id ? \'selected\' : \'\' }}>{{ $c->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3"><label>Satuan</label><input type="text" name="satuan" value="{{ $product->satuan }}" class="form-control" required></div>
        <div class="mb-3"><label>Harga</label><input type="number" step="0.01" name="harga" value="{{ $product->harga }}" class="form-control" required></div>
        <div class="mb-3"><label>Stok</label><input type="number" name="stok" value="{{ $product->stok }}" class="form-control" required></div>
        <button class="btn btn-success">Update</button>
    </form>
@endsection
',
    // SALES
    'sales/index.blade.php' => '
@extends(\'layouts.admin\')
@section(\'content\')
    <div class="d-flex justify-content-between mb-3">
        <h2>Sales Transactions</h2>
        <a href="{{ route(\'sales.create\') }}" class="btn btn-primary">Create Transaction</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr><th>Invoice</th><th>Date</th><th>Customer</th><th>Total</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($sales as $s)
            <tr>
                <td>{{ $s->kode }}</td><td>{{ $s->sale_date }}</td><td>{{ $s->customer->nama ?? \'\' }}</td><td>{{ number_format($s->total_amount, 2) }}</td>
                <td>
                    <a href="{{ route(\'sales.show\', $s->id) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route(\'sales.edit\', $s->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route(\'sales.destroy\', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm(\'Delete? Stock will be restored.\')">
                        @csrf @method(\'DELETE\')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
',
    'sales/show.blade.php' => '
@extends(\'layouts.admin\')
@section(\'content\')
    <h2>Transaction Detail: {{ $sale->kode }}</h2>
    <div class="mb-3">
        <strong>Date:</strong> {{ $sale->sale_date }}<br>
        <strong>Customer:</strong> {{ $sale->customer->nama ?? \'\' }}<br>
        <strong>Total Amount:</strong> {{ number_format($sale->total_amount, 2) }}
    </div>
    <table class="table table-bordered">
        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
        <tbody>
            @foreach($sale->saleItems as $item)
            <tr>
                <td>{{ $item->product->nama ?? \'\' }}</td>
                <td>{{ number_format($item->price, 2) }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route(\'sales.index\') }}" class="btn btn-secondary">Back</a>
@endsection
',
    'sales/create.blade.php' => '
@extends(\'layouts.admin\')
@section(\'content\')
    <h2>Create Transaction</h2>
    <form action="{{ route(\'sales.store\') }}" method="POST" id="saleForm">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Invoice Code</label>
                <input type="text" name="kode" value="{{ $kode }}" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label>Date</label>
                <input type="date" name="sale_date" value="{{ date(\'Y-m-d\') }}" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>Customer</label>
                <select name="customer_id" class="form-control" required>
                    <option value="">-- Select Customer --</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <h4>Items</h4>
        <table class="table table-bordered" id="itemsTable">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Available Stock</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th><button type="button" class="btn btn-sm btn-success" id="addRow">+</button></th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be added here via JS -->
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Total</th>
                    <th><input type="text" id="grandTotal" class="form-control" readonly></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        
        <button class="btn btn-primary" type="submit">Save Transaction</button>
    </form>
@endsection

@push(\'scripts\')
<script>
    const products = @json($products);
    let rowIndex = 0;

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll(\'.subtotal-input\').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById(\'grandTotal\').value = total.toFixed(2);
    }

    document.getElementById(\'addRow\').addEventListener(\'click\', function() {
        const tbody = document.querySelector(\'#itemsTable tbody\');
        let options = \'<option value="">-- Select Product --</option>\';
        products.forEach(p => {
            options += `<option value="${p.id}" data-price="${p.harga}" data-stok="${p.stok}">${p.nama} (${p.kode})</option>`;
        });

        const tr = document.createElement(\'tr\');
        tr.innerHTML = `
            <td>
                <select name="products[${rowIndex}][product_id]" class="form-control product-select" required>
                    ${options}
                </select>
            </td>
            <td><input type="text" class="form-control price-input" readonly></td>
            <td><input type="text" class="form-control stok-input" readonly></td>
            <td><input type="number" name="products[${rowIndex}][qty]" class="form-control qty-input" min="1" required></td>
            <td><input type="text" class="form-control subtotal-input" readonly></td>
            <td><button type="button" class="btn btn-sm btn-danger removeRow">-</button></td>
        `;
        tbody.appendChild(tr);
        rowIndex++;
    });

    document.querySelector(\'#itemsTable\').addEventListener(\'change\', function(e) {
        if(e.target.classList.contains(\'product-select\')) {
            const tr = e.target.closest(\'tr\');
            const selectedOption = e.target.options[e.target.selectedIndex];
            if(selectedOption.value) {
                tr.querySelector(\'.price-input\').value = selectedOption.dataset.price;
                tr.querySelector(\'.stok-input\').value = selectedOption.dataset.stok;
                tr.querySelector(\'.qty-input\').max = selectedOption.dataset.stok;
            } else {
                tr.querySelector(\'.price-input\').value = \'\';
                tr.querySelector(\'.stok-input\').value = \'\';
            }
            // trigger recalculation if qty is already there
            tr.querySelector(\'.qty-input\').dispatchEvent(new Event(\'input\', {bubbles:true}));
        }
    });

    document.querySelector(\'#itemsTable\').addEventListener(\'input\', function(e) {
        if(e.target.classList.contains(\'qty-input\')) {
            const tr = e.target.closest(\'tr\');
            const price = parseFloat(tr.querySelector(\'.price-input\').value) || 0;
            const qty = parseInt(e.target.value) || 0;
            const stok = parseInt(tr.querySelector(\'.stok-input\').value) || 0;
            
            if(qty > stok) {
                alert(\'Qty cannot exceed available stock!\');
                e.target.value = stok;
            }
            
            tr.querySelector(\'.subtotal-input\').value = (price * (parseInt(e.target.value) || 0)).toFixed(2);
            calculateTotal();
        }
    });

    document.querySelector(\'#itemsTable\').addEventListener(\'click\', function(e) {
        if(e.target.classList.contains(\'removeRow\')) {
            e.target.closest(\'tr\').remove();
            calculateTotal();
        }
    });
</script>
@endpush
',
    'sales/edit.blade.php' => '
@extends(\'layouts.admin\')
@section(\'content\')
    <h2>Edit Transaction</h2>
    <div class="alert alert-warning">Editing transaction will restore old stock, and re-apply new stock based on your edits.</div>
    <form action="{{ route(\'sales.update\', $sale->id) }}" method="POST" id="saleForm">
        @csrf @method(\'PUT\')
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Invoice Code</label>
                <input type="text" name="kode" value="{{ $sale->kode }}" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label>Date</label>
                <input type="date" name="sale_date" value="{{ $sale->sale_date }}" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>Customer</label>
                <select name="customer_id" class="form-control" required>
                    <option value="">-- Select Customer --</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ $sale->customer_id == $c->id ? \'selected\' : \'\' }}>{{ $c->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <h4>Items</h4>
        <table class="table table-bordered" id="itemsTable">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th><button type="button" class="btn btn-sm btn-success" id="addRow">+</button></th>
                </tr>
            </thead>
            <tbody>
                <!-- Init rows based on existing items -->
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th><input type="text" id="grandTotal" class="form-control" value="{{ $sale->total_amount }}" readonly></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        
        <button class="btn btn-primary" type="submit">Update Transaction</button>
    </form>
@endsection

@push(\'scripts\')
<script>
    const products = @json($products);
    const existingItems = @json($sale->saleItems);
    let rowIndex = 0;

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll(\'.subtotal-input\').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById(\'grandTotal\').value = total.toFixed(2);
    }

    function createRow(item = null) {
        const tbody = document.querySelector(\'#itemsTable tbody\');
        let options = \'<option value="">-- Select Product --</option>\';
        
        products.forEach(p => {
            // Note: Since this is edit, the stock includes previously bought item if we don\'t adjust it here.
            // In backend we just revert and recalculate. For frontend simplicity we just let them pick.
            const selected = item && item.product_id == p.id ? \'selected\' : \'\';
            options += `<option value="${p.id}" data-price="${p.harga}" data-stok="${p.stok}" ${selected}>${p.nama} (${p.kode})</option>`;
        });

        const tr = document.createElement(\'tr\');
        const price = item ? item.price : \'\';
        const qty = item ? item.qty : \'\';
        const subtotal = item ? item.subtotal : \'\';

        tr.innerHTML = `
            <td>
                <select name="products[${rowIndex}][product_id]" class="form-control product-select" required>
                    ${options}
                </select>
            </td>
            <td><input type="text" class="form-control price-input" value="${price}" readonly></td>
            <td><input type="number" name="products[${rowIndex}][qty]" class="form-control qty-input" value="${qty}" min="1" required></td>
            <td><input type="text" class="form-control subtotal-input" value="${subtotal}" readonly></td>
            <td><button type="button" class="btn btn-sm btn-danger removeRow">-</button></td>
        `;
        tbody.appendChild(tr);
        rowIndex++;
    }

    existingItems.forEach(item => {
        createRow(item);
    });

    document.getElementById(\'addRow\').addEventListener(\'click\', function() {
        createRow();
    });

    document.querySelector(\'#itemsTable\').addEventListener(\'change\', function(e) {
        if(e.target.classList.contains(\'product-select\')) {
            const tr = e.target.closest(\'tr\');
            const selectedOption = e.target.options[e.target.selectedIndex];
            if(selectedOption.value) {
                tr.querySelector(\'.price-input\').value = selectedOption.dataset.price;
            } else {
                tr.querySelector(\'.price-input\').value = \'\';
            }
            tr.querySelector(\'.qty-input\').dispatchEvent(new Event(\'input\', {bubbles:true}));
        }
    });

    document.querySelector(\'#itemsTable\').addEventListener(\'input\', function(e) {
        if(e.target.classList.contains(\'qty-input\')) {
            const tr = e.target.closest(\'tr\');
            const price = parseFloat(tr.querySelector(\'.price-input\').value) || 0;
            const qty = parseInt(e.target.value) || 0;
            
            tr.querySelector(\'.subtotal-input\').value = (price * qty).toFixed(2);
            calculateTotal();
        }
    });

    document.querySelector(\'#itemsTable\').addEventListener(\'click\', function(e) {
        if(e.target.classList.contains(\'removeRow\')) {
            e.target.closest(\'tr\').remove();
            calculateTotal();
        }
    });
</script>
@endpush
',
];

foreach ($files as $file => $content) {
    file_put_contents("$viewsDir/$file", $content);
}

echo "Views generated successfully.\n";
