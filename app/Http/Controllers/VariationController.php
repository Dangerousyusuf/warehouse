<?php

namespace App\Http\Controllers;

use App\Models\ProductVariation;
use App\Models\Variation;
use App\Models\VariationOption;
use Illuminate\Http\Request;

class VariationController extends Controller
{
    public function index()
    {
        // Varyantları ve ilişkili değerlerini veritabanından çek
        $variations = Variation::with('options')->get();
        return view('variation.variation_list', compact('variations'));
    }

    public function create()
    {
        return view('variation.variation_add');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'variants.*.value' => 'required|string|max:255',
        ], [
            'name.required' => 'Varyant Adı Gerekli.',
            'variants.*.value.required' => 'En az bir varyant değeri eklenmeli.',
        ]);

        // Aynı isimde bir varyantın var olup olmadığını kontrol et
        $existingVariation = Variation::where('name', $validatedData['name'])->first();
        if ($existingVariation) {
            return redirect()->back()->withErrors(['name' => 'Bu varyant adı zaten mevcut.'])->withInput();
        }

        // Varyantı oluştur
        $variation = Variation::create(['name' => $validatedData['name']]);

        // Varyant değerlerini oluştur
        foreach ($validatedData['variants'] as $variant) {
            VariationOption::create([
                'variation_id' => $variation->id,
                'value' => $variant['value'],
            ]);
        }

        return redirect()->back()->with('success', 'Varyant başarıyla eklendi!');
    }

    public function edit($id)
    {
        // Varyantı ve ilişkili değerlerini getir
        $variation = Variation::with('options')->findOrFail($id);
        return view('variation.variation_edit', compact('variation'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'variants.*.value' => 'required|string|max:255',
        ], [
            'name.required' => 'Varyant Adı Gerekli.',
            'variants.*.value.required' => 'En az bir varyant değeri eklenmeli.',
        ]);

        // Varyantı güncelle
        $variation = Variation::findOrFail($id);
        $variation->name = $validatedData['name'];
        $variation->save();

        // Silinen varyantları kontrol et
        if ($request->has('deleted_variants')) {
            $deletedIds = explode(',', $request->input('deleted_variants')[0]); // Silinen ID'leri al
            VariationOption::whereIn('id', $deletedIds)->delete(); // Silinen varyantları veritabanından kaldır
        }

        // Eski varyant değerlerini sil
        VariationOption::where('variation_id', $variation->id)->delete();

        // Yeni varyant değerlerini ekle
        foreach ($validatedData['variants'] as $variant) {
            VariationOption::create([
                'variation_id' => $variation->id,
                'value' => $variant['value'],
            ]);
        }

        return redirect()->route('variations.edit', $variation->id)->with('success', 'Varyant başarıyla güncellendi!');
    }

    public function destroy($id)
    {
        $variation = Variation::findOrFail($id);
        $variation->options()->delete(); // İlişkili varyant değerlerini sil
        $variation->delete(); // Varyantı sil

        return redirect()->back()->with('success', 'Varyant başarıyla silindi!');
    }

    public function destroyProductVariantion($id)
    {
        $variation = ProductVariation::findOrFail($id);
        if ($variation->children()->exists()) {
            $variation->children()->each(function ($child) {
                $child->delete(); // Alt varyantları sil
            });
        }
    
        $variation->delete(); // Varyantı sil

        return redirect()->back()->with('success', 'Varyant başarıyla silindi!');
    }
}
