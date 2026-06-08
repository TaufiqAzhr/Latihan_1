<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        \App\Models\Category::create(['nama' => 'Elektronik', 'deskripsi' => 'Barang elektronik']);
        \App\Models\Customer::create(['kode' => 'CUST001', 'nama' => 'Budi Santoso', 'telepon' => '08123456789']);
        \App\Models\Product::create(['kode' => 'PRD001', 'nama' => 'Laptop', 'category_id' => 1, 'satuan' => 'Pcs', 'harga' => 5000000, 'stok' => 10]);
    }
}
