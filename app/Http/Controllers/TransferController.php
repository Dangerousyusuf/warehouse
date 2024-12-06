<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    //
    /**
     * Tüm transferleri listeleyin.
     */
    public function index()
    {
        $transfers = Transfer::with('items')->get();
        return view('transfers.index', compact('transfers'));
    }

    /**
     * Yeni bir transfer oluşturma formunu gösterin.
     */
    public function create()
    {
        return view('transfers.create');
    }

    /**
     * Yeni bir transfer kaydedin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'from_warehouse' => 'required|integer',
            'to_warehouse' => 'required|integer',
            'transfer_date' => 'required|date',
            'created_by' => 'required|integer',
        ]);

        $transfer = Transfer::create($request->all());
        return redirect()->route('transfers.index')->with('success', 'Transfer başarıyla oluşturuldu.');
    }

    /**
     * Belirli bir transferi gösterin.
     */
    public function show($id)
    {
        $transfer = Transfer::with('items')->findOrFail($id);
        return view('transfers.show', compact('transfer'));
    }

    /**
     * Transfer düzenleme formunu gösterin.
     */
    public function edit($id)
    {
        $transfer = Transfer::findOrFail($id);
        return view('transfers.edit', compact('transfer'));
    }

    /**
     * Transferi güncelleyin.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'from_warehouse' => 'required|integer',
            'to_warehouse' => 'required|integer',
            'transfer_date' => 'required|date',
        ]);

        $transfer = Transfer::findOrFail($id);
        $transfer->update($request->all());

        return redirect()->route('transfers.index')->with('success', 'Transfer başarıyla güncellendi.');
    }

    // TransferController içinde transfer onayı
    public function approve($id)
    {
        $transfer = Transfer::findOrFail($id);

        // Stok güncelleme işlemi burada yapılır
        foreach ($transfer->items as $item) {
            $product = $item->product;
            $product->decrement('stock', $item->quantity);
        }

        $transfer->status = 'Onaylandı';
        $transfer->save();

        return redirect()->route('transfers.index')->with('success', 'Transfer onaylandı ve stok güncellendi.');
    }

    /**
     * Transferi silin.
     */
    public function destroy($id)
    {
        $transfer = Transfer::findOrFail($id);
        $transfer->delete();

        return redirect()->route('transfers.index')->with('success', 'Transfer başarıyla silindi.');
    }
}
