<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FactorySettings;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Stocks; // Stocks modelini ekleyin
use App\Models\Transfer;
use App\Models\TransferItem;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    //

    public function index()
    {
        $warehouseId = auth()->user()->selected_warehouse_id;

        if ($warehouseId) {
            $products = Product::with('category', 'variations.children')
                ->whereHas('stocks', function ($query) use ($warehouseId) {
                    $query->where('warehouse_id', $warehouseId);
                    $query->where('deleted_at', null);
                })
                ->orderBy('created_at', 'desc') // En son kaydedilen ürünler ilk olacak şekilde sıralar
                ->get();
        } else {
            if (auth()->user()->role == 'admin') {
                $products = Product::with('category', 'variations.children')->orderBy('created_at', 'desc') // En son kaydedilen ürünler ilk olacak şekilde sıralar
                    ->whereHas('stocks', function ($query) {
                        $query->where('deleted_at', null);
                    })
                    ->get();
            } else {
                $products = [];
            }
        }
      
        // Eğer kategori listesine de ihtiyacınız varsa bunu çekiyoruz
        $categories = $products->pluck('category')->unique('id');
        // Toplam ürün sayısını hesapla
        $totalProducts = $products->count();
        // Aktif ürün sayısını hesapla
        $activeProducts = $products->where('status', 'active')->count();
        // Pasif ürün sayısını hesapla
        $inactiveProducts = $products->where('status', 'inactive')->count();

        // Varyantlı ürünleri de dahil ederek toplam sayıları hesapla
        $totalVariants = $products->flatMap(function ($product) {
            return $product->variations;
        })->count();

        // Varyantsız ürün sayısını hesapla
        $totalSimpleProducts = $products->where('variations', '[]')->count();

        // Toplam ürün sayısını varyantlı ve varyantsız olarak hesapla
        $totalAllProducts = $totalProducts + $totalVariants;
        $warehouses = Warehouse::all();

        // Her ürün için stok bilgisi ve toplam stok miktarını alıyoruz
        foreach ($products as $product) {

            $product->stocks = $this->getProductStockInfo($product->id);
            $product->totalStock = $this->getTotalProductStock($product->id);

            // Eğer ürünün varyantları varsa, onların stok bilgilerini de çekiyoruz
            if ($product->variations->isNotEmpty()) {
                foreach ($product->variations as $variation) {
                    $variation->stocks = $this->getVariationStockInfo($variation->id);
                    $variation->totalStock = $this->getTotalVariationStock($variation->id);
                }
            }

            $product->warehouse_id = $warehouseId;
            $product->warehouse_name = Warehouse::find($warehouseId)->name;

            // İlk resimi alıyoruz (örneğin, resimleri virgülle ayırarak sakladıysanız)
            $productImages = explode(',', $product->image);
            $product->firstImage = $productImages[0] ?? null;
        }

        $stockDropCategory = FactorySettings::where('variable', 'stock_drop_category')->first()->value ?? '';

        return view('stock.stock_list', compact('products', 'warehouses', 'categories', 'totalProducts', 'activeProducts', 'inactiveProducts', 'totalVariants', 'totalSimpleProducts', 'totalAllProducts', 'stockDropCategory','warehouseId'));

    }

    public function showByShelf($id)
    {
        $warehouseId = auth()->user()->selected_warehouse_id;

        if ($warehouseId) {
            $products = Product::with('category', 'variations.children')
                ->whereHas('stocks', function ($query) use ($warehouseId, $id) {
                    $query->where('warehouse_id', $warehouseId)->where('shelf_id', $id);
                    $query->where('deleted_at', null);
                })
                ->orderBy('created_at', 'desc') // En son kaydedilen ürünler ilk olacak şekilde sıralar
                ->get();
        } else {
            if (auth()->user()->role == 'admin') {
                $products = Product::with('category', 'variations.children')
                ->where('shelf_id', $id)
                ->whereHas('stocks', function ($query) {
                    $query->where('deleted_at', null);
                })
                ->orderBy('created_at', 'desc') // En son kaydedilen ürünler ilk olacak şekilde sıralar
                    ->get();
            } else {
                $products = [];
            }
        }

        // Eğer kategori listesine de ihtiyacınız varsa bunu çekiyoruz
        $categories = $products->pluck('category')->unique('id');
        // Toplam ürün sayısını hesapla
        $totalProducts = $products->count();
        // Aktif ürün sayısını hesapla
        $activeProducts = $products->where('status', 'active')->count();
        // Pasif ürün sayısını hesapla
        $inactiveProducts = $products->where('status', 'inactive')->count();

        // Varyantlı ürünleri de dahil ederek toplam sayıları hesapla
        $totalVariants = $products->flatMap(function ($product) {
            return $product->variations;
        })->count();

        // Varyantsız ürün sayısını hesapla
        $totalSimpleProducts = $products->where('variations', '[]')->count();

        // Toplam ürün sayısını varyantlı ve varyantsız olarak hesapla
        $totalAllProducts = $totalProducts + $totalVariants;
        $warehouses = Warehouse::all();

        // Her ürün için stok bilgisi ve toplam stok miktarını alıyoruz
        foreach ($products as $product) {

            $product->stocks = $this->getProductStockInfo($product->id);
            $product->totalStock = $this->getTotalProductStock($product->id);

            // Eğer ürünün varyantları varsa, onların stok bilgilerini de çekiyoruz
            if ($product->variations->isNotEmpty()) {
                foreach ($product->variations as $variation) {
                    $variation->stocks = $this->getVariationStockInfo($variation->id);
                    $variation->totalStock = $this->getTotalVariationStock($variation->id);
                }
            }

            $product->warehouse_id = $warehouseId;
            $product->warehouse_name = Warehouse::find($warehouseId)->name;

            // İlk resimi alıyoruz (örneğin, resimleri virgülle ayırarak sakladıysanız)
            $productImages = explode(',', $product->image);
            $product->firstImage = $productImages[0] ?? null;
        }

        $stockDropCategory = FactorySettings::where('variable', 'stock_drop_category')->first()->value ?? '';

        return view('stock.stock_list', compact('products', 'warehouses', 'categories', 'totalProducts', 'activeProducts', 'inactiveProducts', 'totalVariants', 'totalSimpleProducts', 'totalAllProducts', 'stockDropCategory','warehouseId'));
    }

    public function destroy(Request $request)
    {
        $stock = Stocks::find($request->stock_id);
        if ($stock) {
            $stock->deleted_at = now();
            $stock->save();
            return redirect()->route('stock.stock_list')->with('success', 'Stok Silindi.');
        }
        else return redirect()->route('stock.stock_list')->with('error', 'Stok Bulunamadı.');
    }
       
    public function filter(Request $request)
    {
        // Kategorilerin alındığından emin olun
        $categorySlugs = $request->get('categories', []);

        if (!empty($categorySlugs)) {
            // Kategorilerin slug'larına göre ürünleri filtreliyoruz
            $products = Product::whereHas('category', function ($query) use ($categorySlugs) {
                $query->whereIn('slug', $categorySlugs);
                $query->whereHas('stocks', function ($query) {
                    $query->where('deleted_at', null);
                });
            })->with('category')->get();
            // Her ürün için stok bilgisi ve toplam stok miktarını alıyoruz
            foreach ($products as $product) {
                $product->stocks = $this->getProductStockInfo($product->id);
                $product->totalStock = $this->getTotalProductStock($product->id);

                // İlk resimi alıyoruz (örneğin, resimleri virgülle ayırarak sakladıysanız)
                $productImages = explode(',', $product->image);
                $product->firstImage = $productImages[0] ?? null;
            }
        } else {
            // Kategori seçimi yoksa tüm ürünleri getiriyoruz
            $products = Product::with('category', 'variations.children')->whereHas('stocks', function ($query) {
                $query->where('deleted_at', null);
            })->orderBy('created_at', 'desc') // En son kaydedilen ürünler ilk olacak şekilde sıralar
                ->get();
            // Her ürün için stok bilgisi ve toplam stok miktarını alıyoruz
            foreach ($products as $product) {
                $product->stocks = $this->getProductStockInfo($product->id);
                $product->totalStock = $this->getTotalProductStock($product->id);

                // İlk resimi alıyoruz (örneğin, resimleri virgülle ayırarak sakladıysanız)
                $productImages = explode(',', $product->image);
                $product->firstImage = $productImages[0] ?? null;
            }
        }

        // Eğer hata varsa, loglama yapın
        if ($products->isEmpty()) {
            \Log::error('Kategoriye göre ürünler bulunamadı.', ['categorySlugs' => $categorySlugs]);
        }

        return view('stock.partials.stock_list', compact('products'))->render();
    }

    public function getProductStockInfo($productId)
    {
        // Ürünün stok bilgilerini al
        $stockInfo = Stocks::with(['warehouse', 'shelf'])
            ->where('product_id', $productId)
            ->whereNull('product_variation_id')
            ->whereNull('deleted_at')
            ->orderBy('warehouse_id')
            ->get();

        // Stok bilgisi yoksa boş koleksiyon döndür
        return $stockInfo->isEmpty() ? collect() : $stockInfo;
    }

    public function getTotalProductStock($productId)
    {
        // Adminse hepsini göstermeli
        if (auth()->user()->role == 'admin') {
            return Stocks::where('product_id', $productId)->whereNull('deleted_at')->sum('stock_quantity');
        } else {
            $warehouseId = auth()->user()->selected_warehouse_id;
            return Stocks::where('product_id', $productId)->where('warehouse_id', $warehouseId)->whereNull('deleted_at')->sum('stock_quantity');
        }
    }

    public function getVariationStockInfo($variationId)
    {
        // Ürünün stok bilgilerini al
        $stockInfo = Stocks::with(['warehouse', 'shelf'])
            ->where('product_variation_id', $variationId)
            ->whereNull('product_id')
            ->whereNull('deleted_at')
            ->orderBy('warehouse_id')
            ->get();

        // Stok bilgisi yoksa boş koleksiyon döndür
        return $stockInfo->isEmpty() ? collect() : $stockInfo;
    }
    public function getTotalVariationStock($variationId)
    {
        // Gelen ürün id'sinden toplam ürün stoğunu göster
        return Stocks::where('product_variation_id', $variationId)->whereNull('deleted_at')->sum('stock_quantity');
    }

   

}
