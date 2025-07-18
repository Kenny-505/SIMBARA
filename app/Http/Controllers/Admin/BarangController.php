<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $query = Barang::where('id_admin', $admin->id_admin);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        
        // Filter by availability
        if ($request->filled('status')) {
            if ($request->status === 'available') {
                $query->where('stok_tersedia', '>', 0);
            } elseif ($request->status === 'unavailable') {
                $query->where('stok_tersedia', 0);
            }
        }
        
        $barangs = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get statistics
        $totalBarang = Barang::where('id_admin', $admin->id_admin)->count();
        $tersediaCount = Barang::where('id_admin', $admin->id_admin)->where('stok_tersedia', '>', 0)->count();
        $tidakTersediaCount = Barang::where('id_admin', $admin->id_admin)->where('stok_tersedia', 0)->count();
        $stokMenipis = Barang::where('id_admin', $admin->id_admin)
            ->whereRaw('stok_tersedia <= (stok_total * 0.2)')
            ->where('stok_tersedia', '>', 0)
            ->count();
        
        return view('admin.barang.index', compact(
            'barangs', 
            'totalBarang', 
            'tersediaCount', 
            'tidakTersediaCount', 
            'stokMenipis'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'stok_total' => 'required|integer|min:0',
            'stok_tersedia' => 'required|integer|min:0',
            'harga_sewa' => 'required|numeric|min:0',
            'gambar_1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_3' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $data = $request->all();
        $data['id_admin'] = $admin->id_admin;
        
        // Handle image uploads
        foreach (['gambar_1', 'gambar_2', 'gambar_3'] as $index => $imageField) {
            if ($request->hasFile($imageField)) {
                $fotoField = 'foto_' . ($index + 1);
                $data[$fotoField] = file_get_contents($request->file($imageField)->getRealPath());
                unset($data[$imageField]); // Remove the original file upload from data array
            }
        }
        
        Barang::create($data);
        
        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        $admin = Auth::guard('admin')->user();
        
        // Ensure admin can only view their own items
        if ($barang->id_admin !== $admin->id_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('admin.barang.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        $admin = Auth::guard('admin')->user();
        
        // Ensure admin can only edit their own items
        if ($barang->id_admin !== $admin->id_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('admin.barang.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $admin = Auth::guard('admin')->user();
        
        // Ensure admin can only update their own items
        if ($barang->id_admin !== $admin->id_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'stok_total' => 'required|integer|min:0',
            'stok_tersedia' => 'required|integer|min:0|lte:stok_total',
            'harga_sewa' => 'required|numeric|min:0',
            'denda_ringan' => 'required|numeric|min:0',
            'denda_sedang' => 'required|numeric|min:0',
            'denda_parah' => 'required|numeric|min:0',
            'gambar_1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_3' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $data = $request->all();
        
        // Handle image uploads
        foreach (['gambar_1', 'gambar_2', 'gambar_3'] as $index => $imageField) {
            if ($request->hasFile($imageField)) {
                $fotoField = 'foto_' . ($index + 1);
                $data[$fotoField] = file_get_contents($request->file($imageField)->getRealPath());
                unset($data[$imageField]); // Remove the original file upload from data array
            }
        }
        
        $barang->update($data);
        
        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        $admin = Auth::guard('admin')->user();
        
        // Ensure admin can only delete their own items
        if ($barang->id_admin !== $admin->id_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if item has active peminjaman
        if ($barang->peminjamanBarangs()->whereHas('peminjaman', function($query) {
            $query->where('status_peminjaman', 'approved');
        })->exists()) {
            return back()->with('error', 'Barang tidak dapat dihapus karena sedang dipinjam.');
        }
        
        // Images are stored in database as BLOB, no files to delete
        
        $barang->delete();
        
        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
    
    /**
     * Get image for display
     */
    public function getImage($id, $image = 1)
    {
        try {
            // Use direct PDO query to get LONGBLOB data
            $pdo = \DB::connection()->getPdo();
            $stmt = $pdo->prepare("SELECT foto_{$image} FROM barang WHERE id_barang = ? AND foto_{$image} IS NOT NULL");
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result["foto_{$image}"])) {
                $imageData = $result["foto_{$image}"];
                
                \Log::info('Admin Image found', [
                    'id' => $id,
                    'image' => $image,
                    'dataLength' => strlen($imageData)
                ]);
                
                return response($imageData)
                    ->header('Content-Type', 'image/jpeg');
            }
            
            \Log::warning('Admin Image not found', [
                'id' => $id,
                'image' => $image
            ]);
            
            abort(404);
                
        } catch (\Exception $e) {
            \Log::error('Admin Image loading error', [
                'id' => $id,
                'image' => $image,
                'error' => $e->getMessage()
            ]);
            
            abort(404);
        }
    }
} 