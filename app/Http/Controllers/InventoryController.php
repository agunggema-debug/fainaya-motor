<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Tampilkan Dashboard Inventaris Gudang
     */
    public function index()
    {
        // Ambil semua item, kelompokkan agar yang stoknya kritis (low stock) muncul di atas
        $items = Item::orderByRaw('stock <= min_stock DESC')
                     ->orderBy('name', 'ASC')
                     ->get();

        return view('inventory.index', compact('items'));
    }

    /**
     * Proses Restock / Tambah Stok Barang Masuk
     */
    public function restock(Request $request, Item $item)
    {
        $request->validate([
            'added_stock' => 'required|integer|min:1',
        ]);

        // Tambahkan stok lama dengan stok baru masuk
        $item->increment('stock', $request->added_stock);

        return redirect()->route('inventory.index')
            ->with('success', "LOG_GUDANG: Berhasil menambah {$request->added_stock} pcs untuk unit [{$item->item_code}] {$item->name}.");
    }
}