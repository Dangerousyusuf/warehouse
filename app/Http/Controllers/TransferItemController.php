<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Stocks;
use App\Models\Transfer;
use App\Models\TransferItem;
use App\Models\TransferList;
use App\Models\TransferListItem;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferItemController extends Controller
{

    /**
     * Tüm gelen transfer öğelerini listeleyin.
     */
    public function incoming()
    {
        $userId = Auth::id();
        $selectedWarehouseId = auth()->user()->selected_warehouse_id;
        $shelves = auth()->user()->warehouses()->where('warehouses.id', $selectedWarehouseId)->first()->shelves()->get();

        // Gelen transferleri filtrele
        $incomingItems = TransferListItem::where('status', 1) // Henüz tamamlanmamış transferler
            ->where('to_warehouse_id', $selectedWarehouseId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Gelen transfer verilerini düzenle
        $products = [];
        foreach ($incomingItems as $item) {
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
                'quantity' => $item->quantity,
                'warehouse' => $warehouse->name,
                'shelf' => $shelf->name,
                'stock_id' => $stock->id,
                'created_at' => $item->created_at,
                'transferList_id' => $item->id
            ];
        }

        return view('transfer.transfer_incoming', compact('products', 'shelves'));
    }





    /**
     * Tüm giden transfer öğelerini listeleyin.
     */
    public function outgoing()
    {
        // Giden transferleri 'from_warehouse' alanına göre filtrele
        $outgoingItems = TransferItem::whereHas('transfer', function ($query) {
            $query->where('from_warehouse', auth()->user()->warehouse_id);
        })->get();

        return view('transfer_items.outgoing', compact('outgoingItems'));
    }

    /**
     * Yeni bir transfer öğesi ekleyin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'to_warehouse' => 'required|string',
            'products' => 'required|json',
            'transferListId' => 'required|string'
        ]);
        $products = json_decode($request->products, true);
        $toWarehouseId = $request->input('to_warehouse');
        $transferListId = $request->input('transferListId');

        DB::beginTransaction();
        foreach ($products as $product) {
            $stock = Stocks::find($product['stock_id']);

            if ($stock) {
                $stock->stock_quantity -= $product['quantity'];
                $stock->save();

                $this->recordStockMovement(
                    $productId = $stock->product_id,
                    $quantity = $product['quantity'],
                    $type = 'transfer',
                    $stock->warehouse_id,
                    $toWarehouseId,
                    $stock->shelf_id ?? null,
                    $stockOut = null,
                    null,
                    $transfer_status = 0,
                    $created_user = auth()->id(), //HATA BURADA
                    $updated_user = null
                );

                try {
                    TransferListItem::where(['product_id' => $stock->product_id, 'stock_id' => $stock->id])->update(['status' => 1, 'to_warehouse_id' => $toWarehouseId, 'quantity' => $product['quantity'], 'from_shelves_id' => $stock->shelf_id]);
                } catch (\Exception $e) {
                    DB::rollback();
                    throw $e;
                }
            }

        }
        $transferList = TransferList::where('id', $transferListId)->first();


        if ($transferList) {
            $transferList->update(['status' => 1]);
        }

        DB::commit();

        return back()->with('success', 'Transfer işlemi başarıyla başlatıldı.');
    }


    public function bulkStoreIncoming(Request $request)
    {

        $request->validate([
            'shelves' => 'required|array', // Seçilen ürünlerin ID'leri
        ]);
        try {
            foreach ($request->shelves as $transferId => $shelves) {
                $item = TransferListItem::find($transferId);
                //dd($item);
                $stock = Stocks::where(['product_id' => $item->product_id, 'warehouse_id' => $item->to_warehouse_id])->first();

                if ($stock) {
                    $stock->increment('stock_quantity', $item->quantity);
                } else {

                    try {
                        Stocks::create([
                            'product_id' => $item->product_id,
                            'warehouse_id' => $item->to_warehouse_id,
                            'stock_quantity' => $item->quantity,
                            'shelf_id' => $shelves,
                            'created_user' => auth()->id()
                        ]);
                    } catch (\Exception $e) {
                        dd($e->getMessage());
                        return redirect()->back()->with('error', 'Stok kaydı oluşturulurken bir hata oluştu: ' . $e->getMessage());
                    }
                }


                $this->recordStockMovement(
                    $productId = $item->product_id,
                    $quantity = $item->quantity,
                    $type = 'transfer',
                    $fromWarehouseId = $item->from_warehouse_id,
                    $toWarehouseId = $item->to_warehouse_id,
                    $fromShelvesId = $item->from_shelves_id,
                    $toShelvesId = $shelves,
                    $stockOut = null,
                    $note = null,
                    $transfer_status = 1,
                    $created_user = auth()->id(),
                    $updated_user = null
                );

                TransferListItem::where('id', $transferId)->update(['status' => 2]);


            }

            return redirect()->back()->with('success', 'Seçilen transferler başarıyla onaylandı.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }

    }

    public function transferCancel(Request $request)
    {


        $request->validate([
            'transfer_id' => 'required|string', // Seçilen ürünlerin ID'leri
            'note' => 'sometimes|string'
        ]);

        

            $item = TransferListItem::find($request->transfer_id);

            $stock = Stocks::where(['product_id' => $item->product_id, 'warehouse_id' => $item->from_warehouse_id])->first();
            $stocks = $item->stock()->first();
            $warehouse = $stocks->warehouse;
            $shelf = $stocks->shelf;
         
            if ($stock) {
                $stock->increment('stock_quantity', $item->quantity);
            } else {

                try {
                    Stocks::create([
                        'product_id' => $item->product_id,
                        'warehouse_id' => $item->from_warehouse_id,
                        'stock_quantity' => $item->quantity,
                        'shelf_id' => $item->from_shelves_id,
                        'created_user' => auth()->id()
                    ]);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Stok kaydı oluşturulurken bir hata oluştu: ' . $e->getMessage());
                }
            }

            StockMovement::create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'type' => 'in',
                'from_warehouse_id' => $item->from_warehouse_id,
                'from_shelves_id' => $item->from_shelves_id,
                'note' => $warehouse->name . ' Tarafından İptal Edildi. Depo Notu:' . $request->note,
                'transfer_status' => 1,
                'created_user' => auth()->id(),
              
            ]);

           

            $item->delete();



            return redirect()->back()->with('success', 'Seçilen transferler başarıyla onaylandı.');
       

    }


    /**
     * Transfer öğesini düzenleme formunu gösterin.
     */
    public function edit($id)
    {
        $item = TransferItem::findOrFail($id);
        return view('transfer_items.edit', compact('item'));
    }

    /**
     * Transfer öğesini güncelleyin.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer',
            'status' => 'required|string',
        ]);

        $item = TransferItem::findOrFail($id);
        $item->update($request->all());

        return back()->with('success', 'Transfer öğesi başarıyla güncellendi.');
    }

    /**
     * Transfer öğesini silin.
     */
    public function destroy($id)
    {
        $item = TransferItem::findOrFail($id);
        $item->delete();

        return back()->with('success', 'Transfer öğesi başarıyla silindi.');
    }
    private function recordStockMovement($productId, $quantity, $type, $fromWarehouseId = null, $toWarehouseId = null, $fromShelvesId = null, $toShelvesId = null, $stockOut = null, $note = null, $transfer_status = 1, $created_user = null, $updated_user = null)
    {
        StockMovement::create([
            'product_id' => $productId,
            'quantity' => $quantity,
            'type' => $type,
            'stock_out' => $stockOut,
            'from_warehouse_id' => $fromWarehouseId,
            'to_warehouse_id' => $toWarehouseId,
            'from_shelves_id' => $fromShelvesId,
            'to_shelves_id' => $toShelvesId,
            'note' => $note,
            'transfer_status' => $transfer_status,
            'created_user' => $created_user,
            'update_user' => $updated_user,
        ]);

        
    }

}
