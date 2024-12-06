<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Stocks;
use Illuminate\Http\Request;
use App\Models\StockMovement;
use App\Models\Warehouse;

class StockMovementController extends Controller
{
    public function index()
    {
        $warehouseId = auth()->user()->selected_warehouse_id;

        if ($warehouseId) {

            $stockMovements = StockMovement::with(['product', 'fromWarehouse', 'toWarehouse', 'fromShelves', 'toShelves', 'user'])
                ->where(function($query) use ($warehouseId) {
                    $query->where('from_warehouse_id', $warehouseId)
                          ->orWhere('to_warehouse_id', $warehouseId);
                })
                ->orderBy('created_at', 'desc')
                ->get();
                
        } else {
            if (auth()->user()->role == 'admin') {
                $stockMovements = StockMovement::with(['product', 'fromWarehouse', 'toWarehouse', 'fromShelves', 'toShelves', 'user'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $stockMovements = [];
            }
        }

        foreach ($stockMovements as $movement) {
            $product = $movement->product;
            $productImages = !empty($product->image) ? explode(',', $product->image) : [];
            $firstImage = !empty($productImages) ? $productImages[0] : '';
            $movement->firstImage = $firstImage;
        }
    
        return view('stock.stock_movements', compact('stockMovements'));
    }

    public function show($id)
    {
        $warehouseId = auth()->user()->selected_warehouse_id;

        if ($warehouseId) {

            $stockMovements = StockMovement::with(['product', 'fromWarehouse', 'toWarehouse', 'fromShelves', 'toShelves', 'user'])
                ->orderBy('created_at', 'desc')
                ->where('from_warehouse_id', $warehouseId)
                ->where('product_id', $id)
                ->get();
                
        } else {
            if (auth()->user()->role == 'admin') {
                $stockMovements = StockMovement::with(['product', 'fromWarehouse', 'toWarehouse', 'fromShelves', 'toShelves', 'user'])
                    ->orderBy('created_at', 'desc')
                    ->where('product_id', $id)
                    ->get();
            } else {
                $stockMovements = [];
            }
        }

        foreach ($stockMovements as $movement) {
            $product = $movement->product;
            $productImages = explode(',', $product->image);
            $firstImage = !empty($productImages) ? $productImages[0] : '';
            $movement->firstImage = $firstImage;
        }
    
        return view('stock.stock_movements', compact('stockMovements'));
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer',
                'type' => 'required|in:in,out,transfer',
                'stock_out' => 'required|string',
                'from_warehouse_id' => 'nullable|exists:warehouses,id',
                'to_warehouse_id' => 'nullable|exists:warehouses,id',
                'note' => 'nullable|string'
            ]);

            $stockMovement = StockMovement::create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'type' => $request->type,
                'stock_out' => $request->stock_out,
                'from_warehouse_id' => $request->from_warehouse_id,
                'to_warehouse_id' => $request->to_warehouse_id,
                'note' => $request->note,
            ]);

            return $this->sendSuccess("Stok Hareketi Eklendi.", $stockMovement);
        } catch (\Throwable $th) {
            return $this->sendError("Stok Hareketi Eklenemedi", 500, $th->getMessage());
        }
    }

    


}
