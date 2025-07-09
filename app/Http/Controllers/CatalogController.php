<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Admin;

class CatalogController extends Controller
{
    /**
     * Display catalog of all active items
     */
    public function index(Request $request)
    {
        $query = Barang::with('admin')
            ->where('is_active', true)
            ->orderBy('nama_barang', 'asc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$search}%");
            });
        }

        // Filter by admin/lembaga
        if ($request->filled('lembaga')) {
            $query->where('id_admin', $request->lembaga);
        }

        // Filter by availability
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('stok_tersedia', '>', 0);
            } elseif ($request->availability === 'unavailable') {
                $query->where('stok_tersedia', 0);
            }
        }

        // Price range filter (for non-civitas users)
        if ($request->filled('min_price')) {
            $query->where('harga_sewa', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('harga_sewa', '<=', $request->max_price);
        }

        $barangs = $query->paginate(12);

        // Get all admins for filter dropdown
        $admins = Admin::where('is_active', true)
            ->orderBy('asal', 'asc')
            ->get();

        return view('user.gallery', compact('barangs', 'admins', 'request'));
    }

    /**
     * Search catalog items
     */
    public function search(Request $request)
    {
        $searchTerm = $request->get('q', '');
        
        $barangs = Barang::with('admin')
            ->where('is_active', true)
            ->where(function($query) use ($searchTerm) {
                $query->where('nama_barang', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('deskripsi', 'LIKE', "%{$searchTerm}%");
            })
            ->orderBy('nama_barang', 'asc')
            ->paginate(12);

        $admins = Admin::where('is_active', true)
            ->orderBy('asal', 'asc')
            ->get();

        return view('user.gallery', compact('barangs', 'admins'))
            ->with('searchTerm', $searchTerm);
    }

    /**
     * Get similar items based on first word of item name
     */
    public function getSimilarItems($itemName)
    {
        $firstWord = explode(' ', $itemName)[0];
        
        return Barang::with('admin')
            ->where('is_active', true)
            ->where('stok_tersedia', '>', 0)
            ->where('nama_barang', 'LIKE', "{$firstWord}%")
            ->orderBy('nama_barang', 'asc')
            ->limit(5)
            ->get();
    }

    /**
     * Check item availability for specific date range
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:barang,id_barang',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'quantity' => 'required|integer|min:1'
        ]);

        $barang = Barang::findOrFail($request->item_id);
        
        // Check if requested quantity is available
        if ($request->quantity > $barang->stok_tersedia) {
            return response()->json([
                'available' => false,
                'message' => "Stok tidak mencukupi. Tersedia: {$barang->stok_tersedia}"
            ]);
        }

        // TODO: Check for conflicting bookings in the date range
        // This will be implemented when we have the peminjaman system

        return response()->json([
            'available' => true,
            'message' => 'Barang tersedia untuk tanggal yang dipilih'
        ]);
    }
}
