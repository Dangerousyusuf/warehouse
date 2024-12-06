<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ActivityLog;

class CategoryController extends Controller
{
    /**
     * Kategori listesi.
     */
    public function index()
    {
        $categories = Category::orderByDesc('id')->get();
        return view('category.category_list', compact('categories'));
    }

    /**
     * Yeni bir kategori ekler.
     */
    public function store(Request $request)
    {
        // Form verilerini doğrulama
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ],[
            'name.required' => 'Kategori adı gereklidir',
        ]);

        // Slug oluşturma
        $slug = Str::slug($validatedData['name']);
        $originalSlug = $slug; // Orijinal slug'ı sakla
        $count = 1;

        // Aynı slug mevcutsa, yeni bir slug oluştur
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Kategoriyi veritabanına kaydetme
        $category = Category::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'slug' => $slug,
        ]);

        // Aktivite kaydı oluştur
        ActivityLog::create([
            'action' => 'create',
            'model' => 'Category',
            'model_id' => $category->id,
            'user_id' => auth()->id(),
            'description' => 'Yeni kategori oluşturuldu: ' . $category->name,
        ]);

        // Başarılı olduğunda yönlendirme
        return redirect()->route('categories.index')->with('success', 'Kategori başarıyla oluşturuldu.');
    }

    /**
     * Kategori düzenleme sayfasını gösterir.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('category.category_edit', compact('category'));
    }

    /**
     * Kategoriyi günceller.
     */
    public function update(Request $request, $id)
    {
        // Form verilerini doğrulama
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ],[
            'name.required' => 'Kategori adı gereklidir',
        ]);

        // Mevcut kategoriyi güncelleme
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'slug' => Str::slug($validatedData['name']),
        ]);

        // Aktivite kaydı oluştur
        ActivityLog::create([
            'action' => 'update',
            'model' => 'Category',
            'model_id' => $category->id,
            'user_id' => auth()->id(),
            'description' => 'Kategori güncellendi: ' . $category->name,
        ]);

        // Başarılı olduğunda yönlendirme
        return redirect()->route('categories.index')->with('success', 'Kategori başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        // Kategoriyi buluyoruz
        $category = Category::findOrFail($id);

        // Kategoriye bağlı ürün var mı diye kontrol ediyoruz
        $productsCount = $category->products()->count(); // products ilişkisi ile kategoriye bağlı ürün sayısını alıyoruz

        if ($productsCount > 0) {
            // Eğer kategoride ürün varsa silme işlemi yapılmıyor ve hata mesajı döndürülüyor
            return redirect()->route('categories.index')->with('error', 'Bu kategoride ürünler mevcut, bu yüzden silinemez.');
        }

        // Eğer ürün yoksa kategori silinir
        $category->delete();

        // Silme işlemi başarılı olursa yönlendirme ve başarılı mesaj
        return redirect()->route('categories.index')->with('success', 'Kategori başarıyla silindi.');
    }
}
