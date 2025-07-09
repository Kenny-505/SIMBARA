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
        
        // Generate unique kode_barang
        do {
            $kode = strtoupper(Str::random(8));
        } while (Barang::where('kode_barang', $kode)->exists());
        
        $data = $request->all();
        $data['id_admin'] = $admin->id_admin;
        $data['kode_barang'] = $kode;
        
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
            'stok_tersedia' => 'required|integer|min:0',
            'harga_sewa' => 'required|numeric|min:0',
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
        
        // Delete images
        foreach (['gambar_1', 'gambar_2', 'gambar_3'] as $imageField) {
            if ($barang->$imageField && file_exists(public_path('images/barang/' . $barang->$imageField))) {
                unlink(public_path('images/barang/' . $barang->$imageField));
            }
        }
        
        $barang->delete();
        
        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
    
    /**
     * Get image for display
     */
    public function getImage($id, $image = 1)
    {
        $barang = Barang::findOrFail($id);
        $fotoField = 'foto_' . $image;
        
        if (!$barang->$fotoField) {
            abort(404);
        }
        
        return response($barang->$fotoField)
            ->header('Content-Type', 'image/jpeg');
    }
} 