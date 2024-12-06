<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shelf;
use Illuminate\Http\Request;

class ShelfController extends Controller
{
    public function index()
    {
        try {
            $shelfs = Shelf::all();
            return $this->sendSuccess("Shelf list", $shelfs);
        } catch (\Throwable $th) {
            return $this->sendError("Failed to get Shelf list", 500, $th->getMessage());
        }
    }
    public function getByWarehouseId($warehouseId)
    {
        try {
            $shelfs = Shelf::where('warehouse_id', $warehouseId)->get();
            return $this->sendSuccess("Shelf list", $shelfs);
        } catch (\Throwable $th) {
            return $this->sendError("Failed to get Shelf list", 500, $th->getMessage());
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock_limit' => 'required|integer',
            'warhouse_id' => 'required|exists:warhouses,id',
        ]);
        try {
            $shelves = Shelf::create([
                'name' => $request->name,
                'stock_limit' => $request->stock_limit,
                'warhouse_id' => $request->warhouse_id,
            ]);
            return $this->sendSuccess("Shelf created", $shelves, 201);
        } catch (\Throwable $th) {
            return $this->sendError("Failed to create Shelf", 500, $th->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock_limit' => 'required|integer',
            'warhouse_id' => 'required|exists:warhouses,id',
        ]);
        try {
            $shelves = Shelf::find($id);
            $shelves->update([
                'name' => $request->name,
                'stock_limit' => $request->stock_limit,
                'warhouse_id' => $request->warhouse_id,
            ]);
            return $this->sendSuccess("Shelf updated", $shelves);
        } catch (\Throwable $th) {
            return $this->sendError("Failed to update Shelf", 500, $th->getMessage());
        }
    }
    public function destroy($id)
    {   
        try {
            $shelves = Shelf::find($id);
            $shelves->delete();
            return $this->sendSuccess("Shelf deleted", $shelves);
        } catch (\Throwable $th) {
            return $this->sendError("Failed to delete Shelf", 500, $th->getMessage());
        }
    }
}
