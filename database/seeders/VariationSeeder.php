<?php

namespace Database\Seeders;

use App\Models\Variation;
use App\Models\VariationOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VariationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Varyasyonlar
        $variations = [
            'Color' => ['Red', 'Blue', 'Green', 'Yellow'],
            'Size' => ['Small', 'Medium', 'Large', 'X-Large'],
            'Material' => ['Cotton', 'Polyester', 'Wool'],
        ];

        // Her bir varyasyonu ve varyasyon seçeneklerini oluştur
        foreach ($variations as $variationName => $options) {
            // Varyasyonu oluştur
            $variation = Variation::create([
                'name' => $variationName,
            ]);

            // Varyasyon seçeneklerini oluştur
            foreach ($options as $optionValue) {
                VariationOption::create([
                    'variation_id' => $variation->id,
                    'value' => $optionValue,
                ]);
            }
        }
    }
}
