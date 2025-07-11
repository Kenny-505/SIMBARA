<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Admin;

class BarangController extends Controller
{
    /**
     * Display item detail
     */
    public function show($id)
    {
        $barang = Barang::with(['admin'])
            ->where('id_barang', $id)
            ->where('is_active', true)
            ->firstOrFail();

        // Get similar items (same first word in name)
        $firstWord = explode(' ', $barang->nama_barang)[0];
        $similarItems = Barang::with('admin')
            ->where('is_active', true)
            ->where('id_barang', '!=', $id)
            ->where('nama_barang', 'LIKE', "{$firstWord}%")
            ->where('stok_tersedia', '>', 0)
            ->limit(4)
            ->get();

        // Check if user is civitas or non-civitas for pricing display
        $userRole = auth()->guard('user')->user()->role->nama_role;
        $isCivitas = $userRole === 'user_fmipa';

        return view('user.item-detail', compact('barang', 'similarItems', 'isCivitas'));
    }

    /**
     * Get item image
     */
    public function getImage($id, $imageNumber = 1)
    {
        try {
            // Use direct PDO query to get LONGBLOB data
            $pdo = \DB::connection()->getPdo();
            $stmt = $pdo->prepare("SELECT foto_{$imageNumber} FROM barang WHERE id_barang = ? AND foto_{$imageNumber} IS NOT NULL");
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result["foto_{$imageNumber}"])) {
                $imageData = $result["foto_{$imageNumber}"];
                
                \Log::info('User Image found', [
                    'id' => $id,
                    'imageNumber' => $imageNumber,
                    'dataLength' => strlen($imageData)
                ]);
                
                return response($imageData)
                    ->header('Content-Type', 'image/jpeg')
                    ->header('Cache-Control', 'public, max-age=31536000');
            }
            
            \Log::warning('User Image not found', [
                'id' => $id,
                'imageNumber' => $imageNumber
            ]);
            
            // Return default placeholder image
            return response()->file(public_path('images/image.png'));
                
        } catch (\Exception $e) {
            \Log::error('User Image loading error', [
                'id' => $id,
                'imageNumber' => $imageNumber,
                'error' => $e->getMessage()
            ]);
            
            // Return default placeholder image on error
            return response()->file(public_path('images/image.png'));
        }
    }

    /**
     * Add item to cart/wishlist (for future implementation)
     */
    public function addToCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date'
        ]);

        $barang = Barang::findOrFail($id);

        // Check stock availability
        if ($request->quantity > $barang->stok_tersedia) {
            return back()->with('error', "Stok tidak mencukupi. Tersedia: {$barang->stok_tersedia}");
        }

        // TODO: Implement cart functionality
        // For now, redirect to peminjaman form with pre-filled data
        return redirect()->route('user.pengajuan.form', [
            'item_id' => $id,
            'quantity' => $request->quantity,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);
    }

    /**
     * Check real-time stock availability
     */
    public function checkStock($id)
    {
        $barang = Barang::findOrFail($id);
        
        return response()->json([
            'id_barang' => $barang->id_barang,
            'nama_barang' => $barang->nama_barang,
            'stok_total' => $barang->stok_total,
            'stok_tersedia' => $barang->stok_tersedia,
            'is_available' => $barang->stok_tersedia > 0,
            'harga_sewa' => $barang->harga_sewa
        ]);
    }
}
