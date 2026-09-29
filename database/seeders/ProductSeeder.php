<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed sample products for the catalog.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Beras Setra Ramos 5 kg', 'category' => 'Sembako', 'description' => 'Beras kualitas pilihan kemasan 5 kg.', 'price' => 74800, 'stock' => 24, 'is_active' => true],
            ['name' => 'Minyak Goreng 1 L', 'category' => 'Sembako', 'description' => 'Minyak goreng untuk kebutuhan memasak sehari-hari.', 'price' => 18500, 'stock' => 36, 'is_active' => true],
            ['name' => 'Gula Pasir 1 kg', 'category' => 'Sembako', 'description' => 'Gula pasir putih kemasan 1 kg.', 'price' => 17200, 'stock' => 30, 'is_active' => true],
            ['name' => 'Mi Instan Goreng', 'category' => 'Makanan', 'description' => 'Mi instan rasa mi goreng.', 'price' => 3500, 'stock' => 80, 'is_active' => true],
            ['name' => 'Susu UHT Cokelat 1 L', 'category' => 'Minuman', 'description' => 'Susu UHT rasa cokelat kemasan 1 liter.', 'price' => 19800, 'stock' => 18, 'is_active' => true],
            ['name' => 'Air Mineral 600 ml', 'category' => 'Minuman', 'description' => 'Air mineral dalam kemasan botol 600 ml.', 'price' => 4000, 'stock' => 60, 'is_active' => true],
            ['name' => 'Kopi Bubuk 200 g', 'category' => 'Minuman', 'description' => 'Kopi bubuk untuk seduhan sehari-hari, kemasan 200 g.', 'price' => 24500, 'stock' => 15, 'is_active' => true],
            ['name' => 'Sabun Mandi', 'category' => 'Perawatan Diri', 'description' => 'Sabun mandi batang dengan aroma segar.', 'price' => 5500, 'stock' => 40, 'is_active' => true],
            ['name' => 'Deterjen Bubuk 800 g', 'category' => 'Kebutuhan Rumah', 'description' => 'Deterjen bubuk untuk mencuci pakaian.', 'price' => 21800, 'stock' => 20, 'is_active' => true],
            ['name' => 'Telur Ayam 1 kg', 'category' => 'Makanan', 'description' => 'Telur ayam segar, dijual per kilogram.', 'price' => 29500, 'stock' => 12, 'is_active' => true],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['name' => $product['name']], $product);
        }
    }
}
