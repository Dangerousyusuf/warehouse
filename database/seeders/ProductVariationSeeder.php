<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductVariationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Örnek ürünler ekleyelim
        $product = Product::create([
            'name' => 'T-Shirt',
            'product_code' => 'TSHIRT001234',
            'category_id' => 59, // Bu kategori id, database'deki var olan bir kategori olmalı
            'status' => 'active',
            'unit' => '0',
            'slug' => 'basic-t-shirt4',
        ]);

        // Ürün varyasyonları ve seçeneklerini ekleyelim
        $variations = [
            [
                'sku' => 'TSHIRT001234-RED-S',
                'price' => 24.99,
                'stock_quantity' => 50,
                'options' => [
                    'color' => 'Kırmızı',
                    'size' => 'S',
                ],
            ],
            [
                'sku' => 'TSHIRT001234-RED-M',
                'price' => 24.99,
                'stock_quantity' => 50,
                'options' => [
                    'color' => 'Kırmızı',
                    'size' => 'M',
                ],
            ],
            [
                'sku' => 'TSHIRT001234-BLUE-L',
                'price' => 24.99,
                'stock_quantity' => 30,
                'options' => [
                    'color' => 'Mavi',
                    'size' => 'L',
                ],
            ],
        ];

        foreach ($variations as $variation) {
            $productVariation = ProductVariation::create([
                'product_id' => $product->id,
                'sku' => $variation['sku'],
                'price' => $variation['price'],
                'stock_quantity' => $variation['stock_quantity'],
            ]);

            // Varyasyon seçeneklerini ekle
            foreach ($variation['options'] as $key => $value) {
                ProductVariationOption::createIfNotExists($productVariation->id, $key, $value);
            }
        }
    }
}
    

