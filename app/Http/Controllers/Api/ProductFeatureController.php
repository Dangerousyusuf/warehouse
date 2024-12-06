<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductFeature;
use Illuminate\Http\Request;
use App\Models\FactorySettings; // FactorySetting modelini ekle

class ProductFeatureController extends Controller
{
    // Tek ürün özelliğini getir
    public function index()
    {
        $product_feature_unit = FactorySettings::where('variable', 'product_feature_unit')->first(); // {{ edit_1 }}
        $product_features = FactorySettings::where('variable', 'product_feature')->pluck('value'); // Mevcut kod

        return view('factory.factory_settings', [
            'productFeatureUnit' => $product_feature_unit ? $product_feature_unit->value : '', // {{ edit_2 }}
            'productFeatures' => $product_features
        ]); // Değeri aktar
    }

    // Ürün özelliğini güncelle
    public function update(Request $request)
    {
        $request->validate([
            'product_feature_unit' => 'required|string|max:255',
        ]);

        // FactorySettings tablosunda feature_unit variable'ı ile kaydı güncelle
        $feature = FactorySettings::updateOrCreate(
            ['variable' => 'product_feature_unit'], // Variable olarak feature_unit kullan
            ['value' => $request->input('product_feature_unit')] // Value kısmına gelen veriyi yaz
        );

        return response()->json(['message' => 'Ürün özellikleri başarıyla güncellendi.', 'product_feature_unit' => $feature]);
    }

    // Yeni ürün özelliği ekle
    public function store(Request $request)
    {
        $request->validate([
            'product_feature_unit' => 'required|string|max:255',
        ]);

        // FactorySettings tablosuna yeni bir kayıt ekle
        $feature = FactorySettings::create([
            'variable' => 'product_feature_unit', // Variable olarak feature_unit kullan
            'value' => $request->input('product_feature_unit') // Value kısmına gelen veriyi yaz
        ]);

        return response()->json(['message' => 'Ürün özellikleri başarıyla kaydedildi.', 'product_feature_unit' => $feature], 201);
    }
}
