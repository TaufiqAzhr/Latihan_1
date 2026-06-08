<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('customer')->latest()->get();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::where('stok', '>', 0)->get();
        // Generate automatic kode
        $lastSale = Sale::orderBy('id', 'desc')->first();
        $nextId = $lastSale ? $lastSale->id + 1 : 1;
        $kode = 'INV-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        
        return view('sales.create', compact('customers', 'products', 'kode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:sales',
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.qty' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $saleItems = [];

            // 1. Calculate total and check stock
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if ($item['qty'] > $product->stok) {
                    throw new \Exception("Qty for product {$product->nama} exceeds available stock ({$product->stok}).");
                }

                $subtotal = $product->harga * $item['qty'];
                $totalAmount += $subtotal;

                $saleItems[] = [
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $product->harga,
                    'subtotal' => $subtotal,
                ];
            }

            // 2. Create Sale Header
            $sale = Sale::create([
                'kode' => $request->kode,
                'customer_id' => $request->customer_id,
                'sale_date' => $request->sale_date,
                'total_amount' => $totalAmount,
            ]);

            // 3. Create Sale Items and Reduce Stock
            foreach ($saleItems as $item) {
                $sale->saleItems()->create($item);
                
                $product = Product::find($item['product_id']);
                $product->stok -= $item['qty'];
                $product->save();
            }

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Sale transaction created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Sale $sale)
    {
        $sale->load('customer', 'saleItems.product');
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $sale->load('saleItems');
        $customers = Customer::all();
        $products = Product::all();
        
        return view('sales.edit', compact('sale', 'customers', 'products'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'kode' => 'required|unique:sales,kode,' . $sale->id,
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.qty' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // 1. Revert old stock
            foreach ($sale->saleItems as $oldItem) {
                $product = Product::find($oldItem->product_id);
                if ($product) {
                    $product->stok += $oldItem->qty;
                    $product->save();
                }
            }
            
            // Delete old items
            $sale->saleItems()->delete();

            $totalAmount = 0;
            $saleItems = [];

            // 2. Calculate new total and check stock (with restored stock)
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if ($item['qty'] > $product->stok) {
                    throw new \Exception("Qty for product {$product->nama} exceeds available stock ({$product->stok}).");
                }

                $subtotal = $product->harga * $item['qty'];
                $totalAmount += $subtotal;

                $saleItems[] = [
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $product->harga,
                    'subtotal' => $subtotal,
                ];
            }

            // 3. Update Sale Header
            $sale->update([
                'kode' => $request->kode,
                'customer_id' => $request->customer_id,
                'sale_date' => $request->sale_date,
                'total_amount' => $totalAmount,
            ]);

            // 4. Create New Sale Items and Reduce Stock
            foreach ($saleItems as $item) {
                $sale->saleItems()->create($item);
                
                $product = Product::find($item['product_id']);
                $product->stok -= $item['qty'];
                $product->save();
            }

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Sale transaction updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Sale $sale)
    {
        try {
            DB::beginTransaction();

            // Return stock
            foreach ($sale->saleItems as $item) {
                $product = $item->product;
                if ($product) {
                    $product->stok += $item->qty;
                    $product->save();
                }
            }

            $sale->delete(); 

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Sale transaction deleted successfully, and stock has been restored.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete transaction.');
        }
    }
}
