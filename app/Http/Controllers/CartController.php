<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $totalCost = 0;
        $user = auth()->guard('user')->user();
        $userType = $user->role->nama_role === 'user_non_fmipa' ? 'non_civitas' : 'civitas';
        
        foreach ($cart as $barangId => $item) {
            $barang = Barang::with('admin')->find($barangId);
            if ($barang) {
                $itemCost = $userType === 'non_civitas' ? ($barang->harga_sewa * $item['quantity']) : 0;
                $totalCost += $itemCost;
                
                $cartItems[] = [
                    'barang' => $barang,
                    'quantity' => $item['quantity'],
                    'cost' => $itemCost
                ];
            }
        }
        
        return view('user.cart', compact('cartItems', 'totalCost', 'userType'));
    }
    
    public function add(Request $request)
    {
        \Log::info('Cart add request received', [
            'request_data' => $request->all(),
            'user_id' => auth()->guard('user')->id(),
        ]);

        try {
            $request->validate([
                'barang_id' => 'required|exists:barang,id_barang',
                'quantity' => 'required|integer|min:1'
            ]);
            
            $barang = Barang::find($request->barang_id);
            
            if (!$barang) {
                \Log::error('Barang not found', ['barang_id' => $request->barang_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Barang tidak ditemukan'
                ]);
            }
            
            // Check stock availability
            if ($barang->stok_tersedia < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $barang->stok_tersedia
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Validation error in cart add', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error validasi: ' . $e->getMessage()
            ]);
        }
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->barang_id])) {
            $newQuantity = $cart[$request->barang_id]['quantity'] + $request->quantity;
            if ($newQuantity > $barang->stok_tersedia) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total quantity melebihi stok tersedia'
                ]);
            }
            $cart[$request->barang_id]['quantity'] = $newQuantity;
        } else {
            $cart[$request->barang_id] = [
                'quantity' => $request->quantity,
                'added_at' => now()
            ];
        }
        
        session()->put('cart', $cart);
        
        \Log::info('Item added to cart successfully', [
            'barang_id' => $request->barang_id,
            'quantity' => $request->quantity,
            'cart_count' => count($cart)
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Item berhasil ditambahkan ke keranjang',
            'cart_count' => count($cart)
        ]);
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id_barang',
            'quantity' => 'required|integer|min:1'
        ]);
        
        $barang = Barang::find($request->barang_id);
        
        if ($barang->stok_tersedia < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi'
            ]);
        }
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->barang_id])) {
            $cart[$request->barang_id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Quantity berhasil diupdate'
        ]);
    }
    
    public function remove(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id_barang'
        ]);
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->barang_id])) {
            unset($cart[$request->barang_id]);
            session()->put('cart', $cart);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus dari keranjang',
            'cart_count' => count($cart)
        ]);
    }
    
    public function clear()
    {
        session()->forget('cart');
        
        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan'
        ]);
    }
    
    public function getCount()
    {
        $cart = session()->get('cart', []);
        \Log::info('Cart count requested', ['count' => count($cart)]);
        return response()->json(['count' => count($cart)]);
    }
    
    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('user.gallery')->with('error', 'Keranjang kosong');
        }
        
        $cartItems = [];
        $totalCost = 0;
        $user = auth()->guard('user')->user();
        $userType = $user->role->nama_role === 'user_non_fmipa' ? 'non_civitas' : 'civitas';
        
        foreach ($cart as $barangId => $item) {
            $barang = Barang::with('admin')->find($barangId);
            if ($barang) {
                $itemCost = $userType === 'non_civitas' ? ($barang->harga_sewa * $item['quantity']) : 0;
                $totalCost += $itemCost;
                
                $cartItems[] = [
                    'barang' => $barang,
                    'quantity' => $item['quantity'],
                    'cost' => $itemCost
                ];
            }
        }
        
        return view('user.checkout', compact('cartItems', 'totalCost', 'userType'));
    }
} 