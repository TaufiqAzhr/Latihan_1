
@extends('layouts.admin')
@section('content')
    <h2>Create Transaction</h2>
    <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Invoice Code</label>
                <input type="text" name="kode" value="{{ $kode }}" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label>Date</label>
                <input type="date" name="sale_date" value="{{ date('Y-m-d') }}" class="form-control" required>
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

@push('scripts')
<script>
    const products = @json($products);
    let rowIndex = 0;

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal-input').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('grandTotal').value = total.toFixed(2);
    }

    document.getElementById('addRow').addEventListener('click', function() {
        const tbody = document.querySelector('#itemsTable tbody');
        let options = '<option value="">-- Select Product --</option>';
        products.forEach(p => {
            options += `<option value="${p.id}" data-price="${p.harga}" data-stok="${p.stok}">${p.nama} (${p.kode})</option>`;
        });

        const tr = document.createElement('tr');
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

    document.querySelector('#itemsTable').addEventListener('change', function(e) {
        if(e.target.classList.contains('product-select')) {
            const tr = e.target.closest('tr');
            const selectedOption = e.target.options[e.target.selectedIndex];
            if(selectedOption.value) {
                tr.querySelector('.price-input').value = selectedOption.dataset.price;
                tr.querySelector('.stok-input').value = selectedOption.dataset.stok;
                tr.querySelector('.qty-input').max = selectedOption.dataset.stok;
            } else {
                tr.querySelector('.price-input').value = '';
                tr.querySelector('.stok-input').value = '';
            }
            // trigger recalculation if qty is already there
            tr.querySelector('.qty-input').dispatchEvent(new Event('input', {bubbles:true}));
        }
    });

    document.querySelector('#itemsTable').addEventListener('input', function(e) {
        if(e.target.classList.contains('qty-input')) {
            const tr = e.target.closest('tr');
            const price = parseFloat(tr.querySelector('.price-input').value) || 0;
            const qty = parseInt(e.target.value) || 0;
            const stok = parseInt(tr.querySelector('.stok-input').value) || 0;
            
            if(qty > stok) {
                alert('Qty cannot exceed available stock!');
                e.target.value = stok;
            }
            
            tr.querySelector('.subtotal-input').value = (price * (parseInt(e.target.value) || 0)).toFixed(2);
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
