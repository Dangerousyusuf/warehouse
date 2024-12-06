<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FactorySettings;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationOption;
use App\Models\Stocks;
use App\Models\StockMovement;
use App\Models\Transfer;
use App\Models\TransferItem;
use App\Models\Variation;
use App\Models\VariationOption;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;


class ProductController extends Controller
{

    public function index()
    {
        try {
            // Ürünlerle birlikte kategori ve varyant ilişkilerini çekiyoruz
            $products = Product::with(['category', 'children' => function ($query) {
                $query->whereNotNull('deleted_at');
            }])
            ->where('deleted_at', null)
            ->orderBy('created_at', 'desc') // En son kaydedilen ürünler ilk olacak şekilde sıralar
            ->get();

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
            $totalSimpleProducts = $products->where('children', '[]')->count();

            // Toplam ürün sayısını varyantlı ve varyantsız olarak hesapla
            $totalAllProducts = $totalProducts + $totalVariants;

            return view('product.product_list', compact('products', 'categories', 'totalProducts', 'activeProducts', 'inactiveProducts', 'totalVariants', 'totalSimpleProducts', 'totalAllProducts'));
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors('Ürün listesi alınamadı.');
        }
    }

    public function create()
    {
        // Tüm kategorileri çekiyoruz
        $categories = Category::all();
        $variations = Variation::with('options')->get()->map(function ($variation) {
            return [
                'id' => $variation->id,
                'name' => $variation->name,
                'options' => $variation->options->map(function ($option) {
                    return [
                        'id' => $option->id,
                        'value' => $option->value,
                    ];
                })->toArray(),

            ];
        });
        // FactorySettings verilerini al
        $factorySettings = FactorySettings::whereIn('variable', ['product_feature_unit', 'product_types'])->get();
        // Check if the settings exist and split the values into arrays
        $settings = $factorySettings->mapWithKeys(function ($setting) {
            return [$setting->variable => explode(',', $setting->value)];
        })->toArray();
        $units = $settings['product_feature_unit'] ?? [];
        $types = $settings['product_types'] ?? [];
        // Ürün ekleme formunu dndrnyoruz
        return view('product.product_add', compact('categories', 'variations', 'factorySettings', 'units', 'types'));
    }

    public function getProduct($id)
    {
        try {
            $product = Product::with('category', 'warehouse', 'shelf')
            ->where('deleted_at', null)
            ->findOrFail($id);

            return $this->sendSuccess("Product detail", $product);
        } catch (\Throwable $th) {
            return $this->sendError("Failed to get Product detail", 500, $th->getMessage());
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'product_code' => 'required|string|unique:products,product_code',
                'barcode' => 'nullable|string|unique:products,barcode',
                'description' => 'nullable|string',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'standard_price' => 'nullable|numeric', // 'decimal:2' yerine 'numeric' kullanıyoruz
                'sale_price' => 'nullable|numeric', // 'decimal:2' yerine 'numeric' kullanıyoruz
                'last_restock_date' => 'nullable|date',
                'total_stock_limit' => 'nullable|integer',
                'unit' => 'required|string',
                'product_type' => 'required|string',
                'estimated_daily_usage' => 'nullable|integer',
                'estimated_delivery_time' => 'nullable|integer',
                'auto_order_quantity' => 'nullable|integer',
                'category_id' => 'required|exists:categories,id',
                'critical_stock_level' => 'nullable|integer',
                'weight' => 'nullable|numeric', // 'decimal:2' yerine 'numeric' kullanıyoruz
                'weight_unit' => 'nullable|string',
                'size_x' => 'nullable|numeric', // 'decimal:2' yerine 'numeric' kullanıyoruz
                'size_y' => 'nullable|numeric', // 'decimal:2' yerine 'numeric' kullanıyoruz
                'size_z' => 'nullable|numeric', // 'decimal:2' yerine 'numeric' kullanıyoruz
                'variant_names.*' => 'nullable|array', // Değiştirildi
                'variant_skus.*' => 'nullable|array',  // Değiştirildi
                'variant_names.*.*' => 'nullable|string', // Dizilerin içindeki elemanların string olduğunu belirtiyoruz
                'variant_skus.*.*' => 'nullable|string',
                'variant_images.*' => 'nullable|array',
                'variant_images.*.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'required|in:active,inactive',
                // 'slug' alanını kaldırıyoruz çünkü otomatik oluşturulacak
            ], [
                'name.required' => 'Ürün adı girmek zorunludur.',
                'product_code.required' => 'Ürün kodu girmek zorunludur.',
                'product_code.unique' => 'Bu ürün kodu zaten kullanımda.',
                'barcode.unique' => 'Bu barkod zaten kullanımda.',
                'unit.required' => 'Birim girmek zorunludur.',
                'product_type.required' => 'Ürün tipi girmek zorunludur.',
                'category_id.required' => 'Kategori seçmek zorunludur.',
                'status.required' => 'Durum seçmek zorunludur.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
                //return response()->json(['errors' => $validator->errors()], 422);
            }

            $validatedData = $validator->validated();


            $validatedData['slug'] = $this->createUniqueSlug($validatedData['name'], $validatedData['product_code']);
            DB::beginTransaction();
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                $imagePaths = [];
                foreach ($images as $image) {
                    // Orijinal resim üzerinde işlem yap
                    $img = Image::read($image); // Image::read yerine Image::make kullanıyoruz

                    // Resmi optimize et
                    $imageName = time() . '-' . $validatedData['slug'] . '-' . uniqid() . '.' . $image->getClientOriginalExtension(); // Benzersiz isim oluştur
                    $destinationPath = public_path('/storage/images/');
                    $img->resize(500, 500);
                    $img->save($destinationPath . $imageName);

                    // Resmin yolunu kaydet
                    $imagePaths[] = 'images/' . $imageName;
                }

                // Yüklenen resimlerin yollarını virgülle ayırarak sakla
                $validatedData['image'] = implode(',', $imagePaths);
            }
            $product = Product::create($validatedData);

            // Depo stok bilgilerini ekle
            if ($request->has('stock')) {
                foreach ($request->input('stock') as $warehouseId => $stockQuantity) {
                    if (!is_null($stockQuantity)) {
                        Stocks::create([
                            'product_id' => $product->id,
                            'warehouse_id' => $warehouseId,
                            'stock_quantity' => $stockQuantity,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        // Stok hareketini kaydet

                        $this->recordStockMovement(
                            $productId = $product->id,
                            $quantity = $stockQuantity,
                            $type = 'in',
                            $fromWarehouseId = $warehouseId,
                            $toWarehouseId = null,
                            $fromShelvesId = null,
                            $stockOut = null,
                            $note = null,
                            $transfer_status = 1,
                            $created_user = auth()->id(), //HATA BURADA
                            $updated_user = null
                        );
                    }
                }
            }

            // Varyantlar için kayıt işlemi
            if ($request->has('variant_names')) {
                foreach ($request->input('variant_names') as $variantId => $variantOptions) {
                    foreach ($variantOptions as $variantOptionId => $variantName) {
                        // Her varyant seçeneği için SKU ve resim bilgilerini alıyoruz
                        $sku = $request->input("variant_skus.{$variantId}.{$variantOptionId}");
                        $variantImage = $request->file("variant_images.{$variantId}.{$variantOptionId}");
                       
                        // Varyant kaydı oluşturuyoruz
                        $productVariation= Product::create([
                            'name' => $variantName,
                            'product_code' => $sku,
                            'category_id' => $product->category_id,
                            'parent_id' => $product->id, // Ana ürünle ilişkilendirme
                            'status' => 'active',
                            'slug' => $this->createUniqueSlug($variantName, $sku),
                        ]);
                        // VARYASYONLU ÜRÜNLERE RESİM EKLEME YAPILACAK
                        // Eğer varyant için resim yüklendiyse kaydediyoruz
                        if ($variantImage) {
                            // Orijinal resim üzerinde işlem yap
                            $img = Image::read($variantImage);  // Image::read kullanıyoruz

                            // Benzersiz isim oluştur
                            $imageName = time() . str_replace(' ', '', strtolower($variantName)) . uniqid() . '.' . $variantImage->getClientOriginalExtension();
                            $destinationPath = public_path('/storage/images/');

                            // Resmi optimize edip boyutlandır
                            $img->resize(500, 500);
                            $img->save($destinationPath . $imageName);

                            // Resmin yolunu kaydet
                            $imagePath = 'images/' . $imageName;

                            // Resmi veritabanına kaydet
                            $productVariation->update(['image' => $imagePath]);
                        }

                        // Alt varyantları kaydet
                        if ($request->has("sub_variant_names.{$variantOptionId}")) {
                            foreach ($request->input("sub_variant_names.{$variantOptionId}") as $subIndex => $subVariantName) {
                                $subSku = $request->input("sub_variant_skus.{$variantOptionId}.{$subIndex}");
                                $subVariantOptionId = $request->input("sub_variant_option_ids.{$variantOptionId}.{$subIndex}"); // Alt varyantın variant_option_id'si
                                $subVariantImage = $request->file("sub_variant_images.{$variantOptionId}.{$subIndex}");
                                // Alt varyant kaydı oluştur
                                $subVariant= Product::create([
                                    'name' => $subVariantName,
                                    'product_code' => $subSku,
                                    'category_id' => $product->category_id,
                                    'parent_id' => $product->id, // Ana ürünle ilişkilendirme
                                    'variation_option_id' => $subVariantOptionId,
                                    'parent_variation_id' => $productVariation->id,
                                    'status' => 'active',
                                    'slug' => $this->createUniqueSlug($subVariantName, $subSku),
                                ]);

                                // Alt varyant resmi kaydet
                                if ($subVariantImage) {
                                    // Orijinal resim üzerinde işlem yap
                                    $img = Image::read($subVariantImage);  // Image::read kullanıyoruz

                                    // Benzersiz isim oluştur
                                    $imageName = time() . str_replace(' ', '', strtolower($subVariantName)) . uniqid() . '.' . $subVariantImage->getClientOriginalExtension();
                                    $destinationPath = public_path('/storage/images/');

                                    // Resmi optimize edip boyutlandır
                                    $img->resize(500, 500);
                                    $img->save($destinationPath . $imageName);

                                    // Resmin yolunu kaydet
                                    $imagePath = 'images/' . $imageName;

                                    // Resmi veritabanına kaydet
                                    $subVariant->update(['image' => $imagePath]);
                                }
                            }

                        }
                    }
                }
            }

            DB::commit();
            

            return redirect()->route('product.product_add')->with('success', 'Ürün başarıyla kaydedildi.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors('Ürün kaydedilemedi.')->withInput();
        }

    }

    public function edit($id)
    {
        $product = Product::with('children.children')->findOrFail($id);
        $categories = Category::all();
        $variations = Variation::with('options')->get()->map(function ($variation) {
            return [
                'id' => $variation->id,
                'name' => $variation->name,
                'options' => $variation->options->map(function ($option) {
                    return [
                        'id' => $option->id,
                        'value' => $option->value,
                    ];
                })->toArray(),

            ];
        });
        // FactorySettings verilerini al
        $factorySettings = FactorySettings::whereIn('variable', ['product_feature_unit', 'product_types'])->get();
        // Check if the settings exist and split the values into arrays
        $settings = $factorySettings->mapWithKeys(function ($setting) {
            return [$setting->variable => explode(',', $setting->value)];
        })->toArray();
        $units = $settings['product_feature_unit'] ?? [];
        $types = $settings['product_types'] ?? [];

        return view('product.product_edit', compact('product', 'categories', 'units', 'types', 'variations'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'product_code' => 'required|string|unique:products,product_code,' . $id,
                'barcode' => 'nullable|string|unique:products,barcode,' . $id,
                'description' => 'nullable|string', // 'text' yerine 'string' kullanıyoruz
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'standard_price' => 'nullable|numeric', // 'decimal:2' yerine 'numeric' kullanıyoruz
                'sale_price' => 'nullable|numeric', // 'decimal:2' yerine 'numeric' kullanıyoruz
                'last_restock_date' => 'nullable|date',
                'total_stock_limit' => 'nullable|integer',
                'unit' => 'required|string',
                'product_type' => 'required|string',
                'estimated_daily_usage' => 'nullable|integer',
                'estimated_delivery_time' => 'nullable|integer',
                'auto_order_quantity' => 'nullable|integer',
                'category_id' => 'required|exists:categories,id',
                'critical_stock_level' => 'nullable|integer',
                'weight' => 'nullable|numeric', // 'decimal:2' yerine 'numeric' kullanıyoruz
                'weight_unit' => 'nullable|string',
                'size_x' => 'nullable|numeric', // 'decimal:2' yerine 'numeric' kullanıyoruz
                'size_y' => 'nullable|numeric', // 'decimal:2' yerine 'numeric' kullanıyoruz
                'status' => 'required|in:active,inactive',
                // 'slug' alanını kaldırıyoruz çünkü otomatik oluşturulacak
            ], [
                'name.required' => 'Ürün adı girmek zorunludur.',
                'product_code.required' => 'Ürün kodu girmek zorunludur.',
                'product_code.unique' => 'Bu ürün kodu zaten kullanımda.',
                'barcode.unique' => 'Bu barkod zaten kullanımda.',
                'unit.required' => 'Birim girmek zorunludur.',
                'product_type.required' => 'Ürün tipi girmek zorunludur.',
                'category_id.required' => 'Kategori seçmek zorunludur.',
                'status.required' => 'Durum seçmek zorunludur.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            DB::beginTransaction();
            $product = Product::findOrFail($id);

            $validatedData = $validator->validated();
            $validatedData['slug'] = $this->createUniqueSlug($validatedData['name'], $validatedData['product_code']);

            $imagePaths = [];
            // Var olan resimleri kontrol et
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                foreach ($images as $image) {
                    // Orijinal resim üzerinde işlem yap
                    $img = Image::read($image); // Image::read yerine Image::make kullanıyoruz

                    // Resmi optimize et
                    $imageName = time() . '-' . $validatedData['slug'] . '-' . uniqid() . '.' . $image->getClientOriginalExtension(); // Benzersiz isim oluştur
                    $destinationPath = public_path('/storage/images/');
                    $img->resize(500, 500);
                    $img->save($destinationPath . $imageName);

                    // Resmin yolunu kaydet
                    $imagePaths[] = 'images/' . $imageName;
                }

                // Yüklenen resimlerin yollarını virgülle ayırarak sakla
                //$validatedData['image'] = implode(',', $imagePaths);
            }

            if ($imagePaths) {
                $validatedData['image'] = implode(',', array_merge($imagePaths));
            }

            $product->update($validatedData);
            DB::commit();
            return redirect()->route('product.product_edit', $id)->with('success', 'Ürün başarıyla güncellendi.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors('Ürün güncellenemedi.')->withInput();
        }
    }


    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->children()->where('deleted_at', null)->exists()) {
            return redirect()->route('product.product_list')->with('error', 'Bu ürün varyantları olduğu için silinemez.');
        }
        if ($product->stocks()->where('deleted_at', null)->exists()) {
            return redirect()->route('product.product_list')->with('error', 'Bu ürün stokları olduğu için silinemez. Önce stokları silebilirsiniz.');
        }
        if (TransferItem::where('product_id', $id)->whereNull('deleted_at')->exists()) {
            $transferItem = TransferItem::where('product_id', $id)->whereNull('deleted_at')->first();
        
            if ($transferItem) {
                $transferStatus = Transfer::where('id', $transferItem->transfer_id)->value('status');
        
                if ($transferStatus === 'Beklemede') {
                    return redirect()->route('product.product_list')->with(
                        'error',
                        'Bu ürün transfer listesinde olduğu için silinemez. Önce transfer listesini silebilirsiniz.'
                    );
                }
            }
        }
               
        $product->deleted_at = now();
        $product->save();
        return redirect()->route('product.product_list')->with('success', 'Ürün başarıyla silindi.');
    }

    public function addStock(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'stock_quantity' => 'required|integer|min:1',
            ], [
                'stock_quantity.required' => 'Stok miktarı girmek zorunludur.',
                'stock_quantity.integer' => 'Stok miktarı tam sayı olmalıdır.',
                'stock_quantity.min' => 'Stok miktarı en az 1 olmalıdır.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $product = Product::findOrFail($id);
            $product->stock_quantity += $request->stock_quantity; // Mevcut stok miktarını artır
            $product->save(); // Değişiklikleri kaydet

            return $this->sendSuccess("Stok başarıyla eklendi", $product);
        } catch (\Throwable $th) {
            return $this->sendError("Stok eklenemedi", 500, $th->getMessage());
        }
    }




    public function updateStock(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'stock_amount' => 'required|integer|min:1',
            'stock_action' => 'required|in:add,remove,transfer',
            'stockId' => 'required|integer|exists:stocks,id',
            'to_warehouse' => 'nullable|integer|exists:warehouses,id',
            'note' => 'nullable|string|max:255',
            'stock_out' => 'nullable|string',
        ], [
            'product_id.required' => 'Ürün ID gereklidir.',
            'stock_amount.required' => 'Stok miktarı girmek zorunludur.',
            'stock_amount.integer' => 'Stok miktarı tam sayı olmalıdır.',
            'stock_amount.min' => 'Stok miktarı en az 1 olmalıdır.',
            'stock_action.required' => 'Stok ekleme/çıkarma/transfer işlemi seçmek zorunludur.',
            'stock_action.in' => 'Geçersiz stok işlemi.',
            'stockId.required' => 'Stok ID gereklidir.',
            'to_warehouse.integer' => 'Depo ID tam sayı olmalıdır.',
            'to_warehouse.exists' => 'Belirtilen depo bulunamadı.',
            'note.max' => 'Not alanı en fazla 255 karakter olmalıdır.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $product = Product::find($request->input('product_id'));  // Ürünü bul
        $stockAmount = $request->input('stock_amount');  // Kullanıcının girdiği stok miktarı
        $stockAction = $request->input('stock_action');   // Stok Ekle/Çıkar işlemi
        $stock = Stocks::find($request->input('stockId'));

        $fromWarehouseId = $stock->warehouse_id;
        $toWarehouseId = $request->input('to_warehouse');
        $fromShelvesId = $stock->shelf_id;
        $note = $request->input('note');
        $stockOut = $request->input('stock_out');

        if ($product) {
            if ($stockAction == 'add') {
                // Stok ekleme
                $stock->stock_quantity += $stockAmount;
                $this->recordStockMovement(
                    $productId = $stock->product_id,
                    $quantity = $stockAmount,
                    $type = 'in',
                    $fromWarehouseId,
                    $toWarehouseId = null,
                    $fromShelvesId = $fromShelvesId ?? null,
                    $stockOut = null,
                    $note,
                    $transfer_status = 1,
                    $created_user = auth()->id(), //HATA BURADA
                    $updated_user = null
                );

            } else if ($stockAction == 'remove') {
                // Stok çıkarma
                $stock->stock_quantity -= $stockAmount;
                $this->recordStockMovement(
                    $productId = $stock->product_id,
                    $quantity = $stockAmount,
                    $type = 'out',
                    $fromWarehouseId,
                    $toWarehouseId = null,
                    $fromShelvesId = $fromShelvesId ?? null,
                    $stockOut,
                    $note,
                    $transfer_status = 1,
                    $created_user = auth()->id(), //HATA BURADA
                    $updated_user = null
                );
                if ($product->stock_quantity < 0) {
                    $product->stock_quantity = 0;  // Stok negatif olamaz
                }
            } else if ($stockAction == 'transfer') {

                //$stock->stock_quantity -= $stockAmount;
            
                // Transfer ana kaydını oluştur
                $transfer = Transfer::create([
                    'from_warehouse' => $fromWarehouseId,
                    'to_warehouse' => $toWarehouseId,
                    'from_shelves' => $fromShelvesId,
                    'to_shelves' => null,
                    'transfer_date' => now(),
                    'status' => 'Beklemede', // İlk durumda transfer 'Beklemede' olacak
                    'created_by' => auth()->id(),
                ]);

                // Transfer edilen her bir ürünü kaydet
                TransferItem::create([
                    'transfer_id' => $transfer->id,
                    'product_id' => $stock->product_id,
                    'variant_id' => $stock->variation_id ?? null,
                    'quantity' => $stockAmount,
                ]);

                $this->recordStockMovement(
                    $productId = $stock->product_id,
                    $quantity = $stockAmount,
                    $type = 'transfer',
                    $fromWarehouseId,
                    $toWarehouseId,
                    $fromShelvesId = $fromShelvesId ?? null,
                    $stockOut = null,
                    $note,
                    $transfer_status = 0,
                    $created_user = auth()->id(), //HATA BURADA
                    $updated_user = null
                );
                if ($product->stock_quantity < 0) {
                    $product->stock_quantity = 0;  // Stok negatif olamaz
                }
            }
            $stock->save();  // Ürünü kaydet
            return redirect()->back()->with('success', 'Stok güncellendi!');
        } else {
            return redirect()->back()->with('error', 'Stok Güncellenemedi!');
        }
    }

    public function filterByCategory($category_id)
    {
        // Seçilen kategoriye ait ürünleri alıyoruz
        $products = Product::where('category_id', $category_id)->with('category', 'variations.children')
        ->where('deleted_at', null)
        ->get();

        if($products->isEmpty()) {
            $products = [];
        }

        // Tüm kategorileri de listeye ekleyerek filtrelenmiş ürünlerle birlikte geri döndürüyoruz
        $categories = Category::all();

        return view('product.product_list', compact('products', 'categories', 'category_id'));
    }

    public function filter(Request $request)
    {
        // Kategorilerin alındığından emin olun
        $categorySlugs = $request->get('categories', []);

        if (!empty($categorySlugs)) {
            // Kategorilerin slug'larına göre ürünleri filtreliyoruz
            $products = Product::whereHas('category', function ($query) use ($categorySlugs) {
                $query->whereIn('slug', $categorySlugs);
                $query->where('deleted_at', null);
            })->with('category')->get();
        } else {
            // Kategori seçimi yoksa tüm ürünleri getiriyoruz
            $products = Product::with('category')->where('deleted_at', null)->get();
        }

        // Eğer hata varsa, loglama yapın
        if ($products->isEmpty()) {
            \Log::error('Kategoriye göre ürünler bulunamadı.', ['categorySlugs' => $categorySlugs]);
            $products = [];
        }

        return view('product.partials.product_list', compact('products'))->render();
    }



    public function getProductByName($name)
    {
        try {
            $product = Product::where('name', $name)->get();
            return $this->sendSuccess("Product by name", $product);
        } catch (\Throwable $th) {
            return $this->sendError("Failed to get product by name", 500, $th->getMessage());
        }
    }

    public function getProductById($id)
    {
        try {
            $product = Product::findOrFail($id);
            return $this->sendSuccess("Product by id", $product);
        } catch (\Throwable $th) {
            return $this->sendError("Failed to get product by id", 500, $th->getMessage());
        }
    }



    private function createUniqueSlug($name, $id = 0)
    {
        $slug = \Str::slug($name);
        $count = Product::where('slug', 'LIKE', "{$slug}%")
            ->where('id', '!=', $id)
            ->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }
        return $slug;
    }

    private function recordStockMovement($productId, $quantity, $type, $fromWarehouseId = null, $toWarehouseId = null, $fromShelvesId = null, $stockOut = null, $note = null, $transfer_status = 1, $created_user = null, $updated_user = null)
    {

        StockMovement::create([
            'product_id' => $productId,
            'quantity' => $quantity,
            'type' => $type,
            'stock_out' => $stockOut,
            'from_warehouse_id' => $fromWarehouseId,
            'to_warehouse_id' => $toWarehouseId,
            'from_shelves_id' => $fromShelvesId,
            'note' => $note,
            'transfer_status' => $transfer_status,
            'created_user' => $created_user, //auth()->id()
            'update_user' => $updated_user,
        ]);
    }

}




