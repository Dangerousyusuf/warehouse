<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\VariationOption;
use Spatie\Permission\Models\Role;
use App\Models\FactorySettings; // FactorySettings modelini içe aktar
use Illuminate\Http\Request;
use App\Models\ActivityLog; // Modeli içe aktar

class FactorySettingsController extends Controller
{
    /**
     * Kaynağın bir listesini görüntüle.
     */
    public function index()
    {
        // Tüm rolleri ve izinlerini çek
        $roles = Role::with('permissions')->get(); 


        // FactorySettings verilerini al
        $factorySettings = FactorySettings::all(); // {{ edit_1 }}

        $variationOptions = VariationOption::all();

        // Hem rolleri, ürün özelliklerini, stok kategorilerini hem de factory settings'i görünüm dosyasına gönder
        return view('factory.factory_settings', compact('roles','factorySettings','variationOptions')); 
    }

    public function update(Request $request)
    {
        // Formdan gelen verileri kontrol et
        $validatedData = $request->validate([
            'feature_unit' => 'nullable|string|max:255',
            'brands' => 'nullable|string|max:255',
            'product_types' => 'nullable|string|max:255',
        ]);

        $userId = auth()->id(); // Giriş yapmış kullanıcının ID'sini al

        try {
            // Ürün Özellikleri Birimi Güncelle
            $factorySetting = FactorySettings::where('variable', 'product_feature_unit')->first();
            if ($factorySetting) {
                $factorySetting->value = $validatedData['feature_unit'] ?? '';
                $factorySetting->save();
            }

            // Markalar Güncelle
            $factorySetting = FactorySettings::where('variable', 'brands')->first();
            if ($factorySetting) {
                $factorySetting->value = $validatedData['brands'] ?? '';
                $factorySetting->save();
            }

            // Ürün Türleri Güncelle
            $factorySetting = FactorySettings::where('variable', 'product_types')->first();
            if ($factorySetting) {
                $factorySetting->value = $validatedData['product_types'] ?? '';
                $factorySetting->save();
            }

            // Log kaydı oluştur
            ActivityLog::create([
                'action' => 'update',
                'model' => 'FactorySettings',
                'model_id' => $factorySetting->id ?? null,
                'user_id' => $userId,
                'description' => 'Ürün Ayarları güncellendi.',
            ]);

            return redirect()->route('factory.settings')->with('success', 'Ayarlar başarıyla güncellendi.');
        } catch (\Exception $e) {
            // Hata durumunda log kaydı
            ActivityLog::create([
                'action' => 'error',
                'model' => 'FactorySettings',
                'model_id' => null,
                'user_id' => $userId,
                'description' => 'Hata: ' . $e->getMessage(),
            ]);

            return redirect()->route('factory.settings')->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function updateStock(Request $request) // {{ edit_2 }}
    {
        // Formdan gelen verileri kontrol et
        $validatedData = $request->validate([
            'stock_drop_category' => 'nullable|string|max:255',
        ]);

        $userId = auth()->id(); // Giriş yapmış kullanıcının ID'sini al

        try {
            // Stok Düşme Kategorileri Güncelle
            $factorySetting = FactorySettings::where('variable', 'stock_drop_category')->first();
            if ($factorySetting) {
                $factorySetting->value = $validatedData['stock_drop_category'] ?? '';
                $factorySetting->save();
            }

            // Log kaydı oluştur
            ActivityLog::create([
                'action' => 'update',
                'model' => 'FactorySettings',
                'model_id' => $factorySetting->id ?? null,
                'user_id' => $userId,
                'description' => 'Stok Düşme Kategorileri güncellendi.',
            ]);

            return redirect()->route('factory.settings')->with('success', 'Stok ayarları başarıyla güncellendi.');
        } catch (\Exception $e) {
            // Hata durumunda log kaydı
            ActivityLog::create([
                'action' => 'error',
                'model' => 'FactorySettings',
                'model_id' => null,
                'user_id' => $userId,
                'description' => 'Hata: ' . $e->getMessage(),
            ]);

            return redirect()->route('factory.settings')->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }
}
