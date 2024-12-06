<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stocks;
use App\Models\ProductVariation;
use App\Models\TransferList;
use App\Models\TransferListItem;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class TransferListController extends Controller
{
    public function add(Request $request)
    {
        $user = Auth::user();

        
        $productId = $request->input('product_id');
        $stockId = $request->input('stock_id');
        $quantity = $request->input('quantity');
        $toWarehouseId = $request->input('to_warehouse_id');
        $toShelveId = $request->input('to_shelve_id');

        $product = Product::findOrFail($productId);
        $stock = Stocks::findOrFail($stockId);
        
        $transferList = TransferList::where(['user_id' => $user->id, 'status' => 0])->first();
        if (!$transferList) {
            $transferList = TransferList::create(['user_id' => $user->id, 'status' => 0]);
        }

        $item = $transferList->items()->where('product_id', $productId)->first();

        if ($item) {
            $item->increment('quantity', 1);
        } else {
            $transferList->items()->create([
                'product_id' => $productId,
                'stock_id' => $stockId,
                'quantity' => $quantity ?? null,
                'from_warehouse_id' => $stock->warehouse_id,
                'from_shelves_id' => $stock->shelf_id,
                'to_warehouse_id' =>  $toWarehouseId ?? null,
                'to_shelves_id' => $toShelveId ?? null
            ]);
        }

        return response()->json(['message' => 'Ürün transfer listesine eklendi.']);
    }

    public function remove(Request $request)
    {
        $user = Auth::user();

        $transferList = TransferList::firstOrCreate(['user_id' => $user->id]);

        $productId = $request->input('product_id');
        $stockId = $request->input('stock_id');

        $product = Product::findOrFail($productId);
        $stock = Stocks::findOrFail($stockId);

        $item = $transferList->items()->where('product_id', $productId)->first();

        if ($item) {
            if ($item->quantity > 1) {
                $item->decrement('quantity', 1);
            } else {
                $item->delete();
            }
        }

        return response()->json(['message' => 'Ürün transfer listesinden kaldırıldı.']);
    }

    

    public function index()
    {
        $userId = Auth::id();
        $selectedWarehouseId = auth()->user()->selected_warehouse_id;
        $warehouses = Warehouse::where('id', '!=', $selectedWarehouseId)->whereNull('deleted_at')->get();
        
        $transferList = TransferList::where('user_id', $userId)->where('status', 0)->first();
        $transferListId = $transferList->id ?? null;
        $transferItems = $transferListId ? $transferList->items()->where('status', 0)->get() : null;
        $products = [];
        if (!empty($transferItems)) {
            foreach ($transferItems as $item) {
                $product = $item->product()->first();
                $stock = $item->stock()->first();
                $productImages = explode(',', $product->image);
                $product->firstImage = $productImages[0] ?? null;

                $warehouse = $stock->warehouse;
                $shelf = $stock->shelf;
                $products[] = [
                    'id' => $product->id,
                    'product_code' => $product->product_code,
                    'name' => $product->name,
                    'image' => $product->firstImage,
                    'type' => $product->product_type,
                    'quantity' => $stock->stock_quantity,
                    'warehouse' => $warehouse->name,
                    'shelf' => $shelf->name,
                    'stock_id' => $stock->id,
                    
                ];
            }
        }
        //dd($products);
        return view('transfer.transfer_list', compact('products','warehouses', 'transferListId'));
    }

    public function destroy(Request $request)
    {
        $transferList = TransferListItem::where('stock_id', $request->stock_id)->first();
        if ($transferList) {
            $transferList->delete();
            return redirect()->route('transfer.transfer_list')->with('success', 'Transfer Listesinden Ürün Kaldırıldı.');
        }
        else return redirect()->route('transfer.transfer_list')->with('error', 'Ürün Bulunamadı.');
    }


  // Transfer işlemini başlatma
  public function process()
  {
      $user = Auth::user();
      $transferList = TransferList::where('user_id', $user->id)->first();

      if (!$transferList || $transferList->items->isEmpty()) {
          return redirect()->back()->with('error', 'Transfer listesi boş.');
      }

      foreach ($transferList->items as $item) {
          if ($item->product_variation_id) {
              // Varyant için stok güncelleme
              $productVariation = ProductVariation::find($item->product_variation_id);
              if ($productVariation) {
                  // Varyant stok güncelleme işlemi
              }
          } elseif ($item->product_id) {
              // Ana ürün için stok güncelleme
              $product = Product::find($item->product_id);
              if ($product) {
                  // Ana ürün stok güncelleme işlemi
              }
          }
      }

      // Transfer listesini temizleyin
      $transferList->items()->delete();
      $transferList->delete();

      return redirect()->route('stock.index')->with('success', 'Transfer işlemi başarıyla tamamlandı.');
  }

}
