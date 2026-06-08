
@extends('layouts.admin')
@section('content')
    <h2>Edit Transaction</h2>
    <div class="alert alert-warning">Editing transaction will restore old stock, and re-apply new stock based on your edits.</div>
    <form action="{{ route('sales.update', $sale->id) }}" method="POST" id="saleForm">
        @csrf @method('PUT')
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
                        <option value="{{ $c->id }}" {{ $sale->customer_id == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
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

@push('scripts')
<script>
    const products = @json($products);
    const existingItems = @json($sale->saleItems);
    let rowIndex = 0;

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal-input').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('grandTotal').value = total.toFixed(2);
    }

    function createRow(item = null) {
        const tbody = document.querySelector('#itemsTable tbody');
        let options = '<option value="">-- Select Product --</option>';
        
        products.forEach(p => {
            // Note: Since this is edit, the stock includes previously bought item if we don't adjust it here.
            // In backend we just revert and recalculate. For frontend simplicity we just let them pick.
            const selected = item && item.product_id == p.id ? 'selected' : '';
            options += `<option value="${p.id}" data-price="${p.harga}" data-stok="${p.stok}" ${selected}>${p.nama} (${p.kode})</option>`;
        });

        const tr = document.createElement('tr');
        const price = item ? item.price : '';
        const qty = item ? item.qty : '';
        const subtotal = item ? item.subtotal : '';

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

    document.getElementById('addRow').addEventListener('click', function() {
        createRow();
    });

    document.querySelector('#itemsTable').addEventListener('change', function(e) {
        if(e.target.classList.contains('product-select')) {
            const tr = e.target.closest('tr');
            const selectedOption = e.target.options[e.target.selectedIndex];
            if(selectedOption.value) {
                tr.querySelector('.price-input').value = selectedOption.dataset.price;
            } else {
                tr.querySelector('.price-input').value = '';
            }
            tr.querySelector('.qty-input').dispatchEvent(new Event('input', {bubbles:true}));
        }
    });

    document.querySelector('#itemsTable').addEventListener('input', function(e) {
        if(e.target.classList.contains('qty-input')) {
            const tr = e.target.closest('tr');
            const price = parseFloat(tr.querySelector('.price-input').value) || 0;
            const qty = parseInt(e.target.value) || 0;
            
            tr.querySelector('.subtotal-input').value = (price * qty).toFixed(2);
            calculateTotal();
        }
    });

    document.querySelector('#itemsTable').addEventListener('click', function(e) {
        if(e.target.classList.contains('removeRow')) {
            e.target.closest('tr').remove();
            calculateTotal();
        }
    });
</script>
@endpush
