<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Shelf;
use App\Models\StockMovement;
use App\Models\Stocks;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouseId = auth()->user()->selected_warehouse_id;

        if ($warehouseId) {
            $warehouses = Warehouse::with([
                'shelves' => function ($query) {
                    $query->with([
                        'products' => function ($productQuery) {
                            $productQuery->whereNull('deleted_at'); // Sadece silinmemiş ürünleri çekiyoruz
                        }
                    ])->withSum('stocks as stock_quantity', 'stock_quantity'); // Rafların stok miktarını topluca çekiyoruz
                }
            ])
                ->where('id', $warehouseId)
                ->whereNull('deleted_at')
                ->orderByDesc('id')->get();
            if ($warehouses->isEmpty()) {
                $warehouses = [];
            }
        } else {
            if (auth()->user()->role == 'admin') {
                $warehouses = Warehouse::with([
                    'shelves' => function ($query) {
                        $query->with([
                            'products' => function ($productQuery) {
                                $productQuery->whereNull('deleted_at'); // Sadece silinmemiş ürünleri çekiyoruz
                            }
                        ])->withSum('products as stock_quantity', 'stock_quantity'); // Rafların stok miktarını topluca çekiyoruz
                    }
                ])->whereNull('deleted_at')
                    ->orderByDesc('id')->get();
            } else {
                $warehouses = [];
            }
        }

        $products = Product::with('category', 'variations.children')
            ->orderBy('created_at', 'desc')
            ->whereNull('deleted_at')
            ->get();

        $warehouseData = [];

        foreach ($warehouses as $warehouse) {
            $shelvesData = [];
            $totalOccupancy = 0;
            $shelfCount = $warehouse->shelves->count();

            foreach ($warehouse->shelves as $shelf) {
                $occupancyRate = $shelf->stock_limit > 0
                    ? round(($shelf->stock_quantity / $shelf->stock_limit) * 100)
                    : 0;
                $totalOccupancy += $occupancyRate;

                // Silinmemiş ürünleri listelemek için
                $productsData = $shelf->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'stock_quantity' => $product->stock_quantity
                    ];
                });

                $shelvesData[] = [
                    'id' => $shelf->id,
                    'name' => $shelf->name,
                    'occupancy_rate' => $occupancyRate,
                    'stock_quantity' => $shelf->stock_quantity,
                    'stock_limit' => $shelf->stock_limit,
                    'products' => $productsData
                ];
            }

            $averageOccupancy = $shelfCount > 0 ? round($totalOccupancy / $shelfCount) : 0;

            $warehouseData[$warehouse->id] = [
                'id' => $warehouse->id,
                'name' => $warehouse->name,
                'occupancy_rate' => $averageOccupancy,
                'created_at' => date('d M, H:i', strtotime($warehouse->created_at)),
                'shelves' => $shelvesData
            ];
        }

        return view('warehouse.warehouse_list', compact('warehouseData', 'products'));
    }



    public function getWarehouse($id)
    {
        try {
            $warehouse = Warehouse::find($id);
            return $this->sendSuccess("Warehouse list", $warehouse);
        } catch (\Throwable $th) {
            return $this->sendError("Failed to get Warehouse list", 500, $th->getMessage());
        }
    }

    public function getWarhouseShelves($warehouseId)
    {
        try {
            $shelves = Shelf::where('warehouse_id', $warehouseId)->get();
            return $this->sendSuccess("Shelf list", $shelves);
        } catch (\Throwable $th) {
            return $this->sendError("Failed to get Shelf list", 500, $th->getMessage());
        }
    }

    public function getShelfDetails($shelfId)
    {
        try {
            $shelf = Shelf::find($shelfId);
            if (!$shelf) {
                return $this->sendError("Shelf not found", 404, "Raf bulunamadı");
            }
            return $this->sendSuccess("Shelf details", $shelf);
        } catch (\Throwable $th) {
            return $this->sendError("Failed to get Shelf details", 500, $th->getMessage());
        }
    }

    public function edit($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $shelves = Shelf::where('warehouse_id', $id)->get();

        return view('warehouse.warehouse_edit', compact('warehouse', 'shelves'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'shelves' => 'sometimes|array',
            'shelves.*.name' => 'required|string|max:255',
            'shelves.*.stock_limit' => 'required|integer|min:0',
        ], [
            'name.required' => 'Depo adı gereklidir',
            'shelves.*.name.required' => 'Raf adı gereklidir',
            'shelves.*.stock_limit.required' => 'Stok limiti gereklidir',
            'shelves.*.stock_limit.integer' => 'Stok limiti sayı olmalıdır',
            'shelves.*.stock_limit.min' => 'Stok limiti 0 dan büyük olmalıdır',
        ]);

        // Doğrulama hatası kontrolü burada olmalı
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::transaction(function () use ($request) {
            $warehouse = Warehouse::create(['name' => $request->name]);
            // Aktivite kaydı oluştur
            ActivityLog::create([
                'action' => 'create',
                'model' => 'Warehouse',
                'model_id' => $warehouse->id,
                'user_id' => Auth::id(),
                'description' => 'Yeni depo oluşturuldu: ' . $warehouse->name,
            ]);

            if (isset($request->shelves) && is_array($request->shelves)) {
                foreach ($request->shelves as $shelfData) {
                    $warehouse->shelves()->create($shelfData);
                }
            }
        });

        return redirect()->route('warehouse.warehouse_list')->with('success', 'Depo başarıyla oluşturuldu.');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'shelves' => 'sometimes|array',
            'shelves.*.name' => 'required|string|max:255',
            'shelves.*.stock_limit' => 'required|integer|min:0',
        ], [
            'name.required' => 'Depo adı gereklidir',
            'shelves.*.name.required' => 'Raf adı gereklidir',
            'shelves.*.stock_limit.required' => 'Stok limiti gereklidir',
            'shelves.*.stock_limit.integer' => 'Stok limiti sayı olmalıdır',
            'shelves.*.stock_limit.min' => 'Stok limiti 0 dan büyük olmalıdır',
        ]);

        // Doğrulama hatası kontrolü burada olmalı
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $warehouse = Warehouse::findOrFail($id);
        $oldName = $warehouse->name; // Eski depo ismini kaydet

        try {
            if (isset($request->name)) {
                $warehouse->update(['name' => $request->name]);
            }

            if (isset($request->shelves)) {
                foreach ($request->shelves as $shelfData) {
                    if (isset($shelfData['id']) && $shelfData['id'] !== '') {
                        // Mevcut rafı güncelle
                        $shelf = Shelf::findOrFail($shelfData['id']);
                        $shelf->update([
                            'name' => $shelfData['name'],
                            'stock_limit' => $shelfData['stock_limit'],
                        ]);
                    } else {
                        // Yeni raf oluştur
                        Shelf::create([
                            'name' => $shelfData['name'],
                            'stock_limit' => $shelfData['stock_limit'],
                            'warehouse_id' => $warehouse->id
                        ]);
                    }
                }
            }
            // Aktivite kaydı oluştur
            ActivityLog::create([
                'action' => 'update',
                'model' => 'Warehouse',
                'model_id' => $warehouse->id,
                'user_id' => Auth::id(),
                'description' => 'Depo güncellendi: Eski İsim: ' . $oldName . ', Yeni İsim: ' . $warehouse->name,
            ]);

            return redirect()->back()->with('success', 'Depo başarıyla güncellendi');
        } catch (\Throwable $th) {
            return redirect()->route('warehouse.index')->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $warehouse = Warehouse::with('shelves.products')->findOrFail($id); // Raflar ve ürünleri önceden yükle

        DB::beginTransaction();
        try {
            // Rafları kontrol et
            foreach ($warehouse->shelves as $shelf) {
                if ($shelf->products()->whereNull('deleted_at')->count() > 0) { // Silinmemiş ürünleri kontrol et
                    DB::rollBack();
                    return redirect()->route('warehouse.warehouse_list')->with(
                        'error',
                        'Bu depoda ürünler var. Silmeden önce bu ürünleri başka bir depoya taşıyın veya silin.'
                    );
                }
                $shelf->deleted_at = now();
                $shelf->save(); // Rafı sil
            }

            // Aktivite kaydı oluştur
            ActivityLog::create([
                'action' => 'delete',
                'model' => 'Warehouse',
                'model_id' => $warehouse->id,
                'user_id' => Auth::id(),
                'description' => 'Depo silindi: ' . $warehouse->name,
            ]);

            $warehouse->deleted_at = now();
            $warehouse->save(); // Depoyu sil
            DB::commit();

            return redirect()->route('warehouse.warehouse_list')->with('success', 'Depo başarıyla silindi');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function selectWarehouse($id)
    {
        $user = Auth::user();

        if ($id === 'all') {
            $user->selected_warehouse_id = null;
        } elseif ($user->warehouses->contains($id)) {
            $user->selected_warehouse_id = $id;
        } else {
            return redirect()->back()->with('error', 'Geçersiz depo seçimi.');
        }

        $user->save();
        session(['selected_warehouse_id' => $user->selected_warehouse_id]);

        return redirect()->back()->with('success', 'Depo seçimi güncellendi.');
    }

    public function deleteShelf($id)
    {
        $shelf = Shelf::findOrFail($id);

        if (!$shelf) {
            return redirect()->back()->with('error', 'Raf bulunamadı.');
        }

        // Rafın içinde ürün olup olmadığını kontrol et
        if ($shelf->products()->count() > 0) {
            return redirect()->back()->with('error', 'Bu rafta ürünler var. Silmeden önce bu ürünleri başka bir rafa taşıyın veya silin.');
        }

        $shelf->delete();
        return redirect()->back()->with('success', 'Raf başarıyla silindi.');
    }

    public function addProducts(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'product_ids' => 'required|array',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1',
            'is_variants' => 'sometimes|array'
        ], [
            'product_ids.required' => 'Ürün seçimi gereklidir',
            'quantities.required' => 'Stok adetleri gereklidir',
            'quantities.*.integer' => 'Stok adetleri sayı olmalıdır',
            'quantities.*.min' => 'Stok adetleri 1 den büyük olmalıdır',
        ]);

        // Doğrulama hatası kontrolü burada olmalı
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $warehouseId = $request->input('warehouse_id');
        $shelfId = $request->input('shelf_id');
        $warehouse = Warehouse::findOrFail($warehouseId);
        foreach ($request->product_ids as $productId) {
            $quantity = $request->quantities[$productId];
            // Add main product stock to the specific shelf
            $existingStock = Stocks::where([
                ['product_id', '=', $productId],
                ['warehouse_id', '=', $warehouseId],
                ['shelf_id', '=', $shelfId],
                ['deleted_at', '=', null]
            ])->first();

            if ($existingStock) {
                $existingStock->increment('stock_quantity', $quantity);

            } else {
                try {
                    Stocks::create([
                        'product_id' => $productId,
                        'warehouse_id' => $warehouseId,
                        'shelf_id' => $shelfId, // Save to the specific shelf
                        'stock_quantity' => $quantity,
                        'created_user' => auth()->id(),
                        'updated_user' => auth()->id()
                    ]);
                    // Record stock movement
                    $this->recordStockMovement(
                        $productId,
                        null,
                        $quantity,
                        'in',
                        $warehouseId,
                        null,
                        $shelfId, // Include shelf ID here
                        null,
                        null,
                        1,
                        auth()->id(),
                        null
                    );

                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Stok eklenirken hata oluştu.');
                }
            }
        }

        return redirect()->back()->with('success', 'Ürünler başarıyla stoğa eklendi.');
    }


    private function recordStockMovement($productId, $productVariationId = null, $quantity, $type, $fromWarehouseId = null, $toWarehouseId = null, $fromShelvesId = null, $stockOut = null, $note = null, $transfer_status = 1, $created_user = null, $updated_user = null)
    {

        StockMovement::create([
            'product_id' => $productId,
            'product_variation_id' => $productVariationId,
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

    public function getUserWarehouses()
    {
        $user = Auth::user();
        $warehouses = $user->warehouses; // Kullanıcının yetkili olduğu depoları al
        return view('warehouses.index', compact('warehouses')); // Depoları view'a gönder
    }

    public function saveSelection(Request $request)
    {
        $user = Auth::user();
        $selectedWarehouseId = $request->input('selected_warehouse_id'); // Seçilen depo ID'sini al

        // Kullanıcının seçili depo ID'sini güncelle
        $user->selected_warehouse_id = $selectedWarehouseId; // Bu alanın veritabanında tanımlı olduğundan emin olun
        $user->save();

        return redirect()->back()->with('success', 'Seçim kaydedildi.'); // Başarılı mesajı ile geri yönlendir
    }

}
