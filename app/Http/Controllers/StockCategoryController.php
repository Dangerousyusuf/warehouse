<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FactorySettings; // FactorySetting modelini içe aktar

class StockCategoryController extends Controller
{
    // Stok düşme kategorilerini getir
    public function index()
    {
        // FactorySettings tablosundan variable'ı 'stock_drop_category' olan verilerin value kısmını al
        $stock_drop_category_value = FactorySettings::where('variable', 'stock_drop_category')->pluck('value');

        return response()->json($stock_drop_category_value); // JSON formatında döndür
    }

    // Stok düşme kategorilerini güncelle
    public function update(Request $request)
    {
        $request->validate([
            'stock_drop_category' => 'required|string', // Kategorilerin bir string olarak gelmesini bekle
        ]);

        // FactorySettings tablosunda categories variable'ı ile kaydı güncelle
        $categories = FactorySettings::updateOrCreate(
            ['variable' => 'stock_drop_category'], // Variable olarak categories kullan
            ['value' => $request->input('stock_drop_category')] // Value kısmına gelen veriyi yaz
        );

        return response()->json(['message' => 'Stok düşme kategorileri başarıyla güncellendi.', 'stock_drop_category' => $categories]);
    }

    // Stok düşme kategorilerini ekle
    public function store(Request $request)
    {
        $request->validate([
            'stock_drop_category' => 'required|string', // Kategorilerin bir string olarak gelmesini bekle
        ]);

        // FactorySettings tablosuna yeni bir kayıt ekle
        $categories = FactorySettings::updateOrCreate([
            'variable' => 'stock_drop_category', // Variable olarak categories kullan
            'value' => $request->input('stock_drop_category') // Value kısmına gelen veriyi yaz
        ]);

        return response()->json(['message' => 'Stok düşme kategorileri başarıyla kaydedildi.', 'stock_drop_category' => $categories], 201);
    }

    // Stok limit uyarısını güncelle
    public function updateStockLimitWarning(Request $request)
    {
        $request->validate([
            'stock_limit_warning' => 'required|boolean', // Boolean olarak bekle
        ]);

        // FactorySettings tablosunda stock_limit_warning variable'ı ile kaydı güncelle
        $stockLimitWarning = FactorySettings::updateOrCreate(
            ['variable' => 'stock_limit_warning'], // Variable olarak stock_limit_warning kullan
            ['value' => $request->input('stock_limit_warning') ? '1' : '0'] // Value kısmına gelen veriyi yaz
        );

        return response()->json(['message' => 'Stok limit uyarısı başarıyla güncellendi.', 'stock_limit_warning' => $stockLimitWarning]);
    }
}
