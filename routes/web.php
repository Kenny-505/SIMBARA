<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\SuperAdmin\VerifikasiController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TransaksiController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Pendaftaran Routes (Public)
Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
    Route::get('/success', [PendaftaranController::class, 'success'])->name('success');
    Route::post('/status', [PendaftaranController::class, 'checkStatus'])->name('status');
});

// UNIFIED LOGIN SYSTEM - Single login for all roles
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $credentials = $request->only('username', 'password');

    // First try admin authentication
    if (Auth::guard('admin')->attempt($credentials)) {
        $admin = Auth::guard('admin')->user();
        
        // Check if admin is active
        if (!$admin->is_active) {
            Auth::guard('admin')->logout();
            return back()->withErrors([
                'username' => 'Akun admin tidak aktif. Silakan hubungi Super Admin.',
            ]);
        }

        $request->session()->regenerate();

        // Redirect based on role
        if ($admin->role->nama_role === 'superadmin') {
            return redirect()->intended('/superadmin/dashboard');
        } else {
            return redirect()->intended('/admin/dashboard');
        }
    }

    // Then try user authentication
    if (Auth::guard('user')->attempt($credentials)) {
        $user = Auth::guard('user')->user();
        
        // Check if user is active
        if (!$user->is_active) {
            Auth::guard('user')->logout();
            return back()->withErrors([
                'username' => 'Akun user tidak aktif. Silakan hubungi admin.',
            ]);
        }

        // Check if account is not expired
        if ($user->tanggal_berakhir && $user->tanggal_berakhir < now()) {
            Auth::guard('user')->logout();
            return back()->withErrors([
                'username' => 'Akun Anda telah kedaluwarsa. Silakan daftar ulang.',
            ]);
        }

        $request->session()->regenerate();

        // Redirect based on role
        if ($user->role->nama_role === 'user_fmipa') {
            return redirect()->intended('/user/civitas/dashboard');
        } else {
            return redirect()->intended('/user/non-civitas/dashboard');
        }
    }

    return back()->withErrors([
        'username' => 'Username atau password salah.',
    ]);
});

// UNIFIED LOGOUT SYSTEM
Route::post('/logout', function (Request $request) {
    // Clear all session data including cart before logout
    $request->session()->forget('cart');
    $request->session()->flush();
    
    // Logout from all guards
    Auth::guard('admin')->logout();
    Auth::guard('user')->logout();
    Auth::guard('web')->logout();

    // Clear all cache
    \Illuminate\Support\Facades\Cache::flush();
    
    // Invalidate and regenerate session
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    // Clear any remember tokens by setting a new session
    $request->session()->migrate(true);

    return redirect('/');
})->name('logout');

// Registration Routes
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');

// Default Dashboard (fallback)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Dashboard Routes
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Barang Management Routes
    Route::resource('barang', \App\Http\Controllers\Admin\BarangController::class);
    Route::get('barang/{id}/image/{image?}', [\App\Http\Controllers\Admin\BarangController::class, 'getImage'])->name('barang.image');
    
    // Peminjaman Approval Routes
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PeminjamanController::class, 'index'])->name('index');
        Route::get('/{peminjaman}', [\App\Http\Controllers\Admin\PeminjamanController::class, 'show'])->name('show');
        Route::post('/{peminjaman}/approve', [\App\Http\Controllers\Admin\PeminjamanController::class, 'approve'])->name('approve');
        Route::post('/{peminjaman}/reject', [\App\Http\Controllers\Admin\PeminjamanController::class, 'reject'])->name('reject');
        Route::post('/{peminjaman}/approve-all', [\App\Http\Controllers\Admin\PeminjamanController::class, 'approveAll'])->name('approve-all');
        Route::post('/{peminjaman}/reject-all', [\App\Http\Controllers\Admin\PeminjamanController::class, 'rejectAll'])->name('reject-all');
    });
    
    // Calendar Routes
    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CalendarController::class, 'index'])->name('index');
        Route::get('/events', [\App\Http\Controllers\Admin\CalendarController::class, 'getEvents'])->name('events');
        Route::get('/availability/{itemId}', [\App\Http\Controllers\Admin\CalendarController::class, 'getAvailability'])->name('availability');
        Route::get('/monthly-stats', [\App\Http\Controllers\Admin\CalendarController::class, 'getMonthlyStats'])->name('monthly-stats');
        Route::get('/stats', [\App\Http\Controllers\Admin\CalendarController::class, 'getStats'])->name('stats');
    });
});

// Super Admin Routes
Route::prefix('superadmin')->name('superadmin.')->middleware('superadmin')->group(function () {
    // Dashboard Routes
    Route::get('dashboard', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/stats', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'getStats'])->name('dashboard.stats');
    
    // Verifikasi Pendaftaran Routes
    Route::prefix('verifikasi')->name('verifikasi.')->group(function () {
        Route::get('/', [VerifikasiController::class, 'index'])->name('index');
        Route::get('/{id}', [VerifikasiController::class, 'show'])->name('show');
        Route::get('/{id}/download-surat', [VerifikasiController::class, 'downloadSurat'])->name('download-surat');
        Route::post('/{id}/approve', [VerifikasiController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [VerifikasiController::class, 'reject'])->name('reject');
        Route::post('/bulk-approve', [VerifikasiController::class, 'bulkApprove'])->name('bulk-approve');
        Route::get('/stats', [VerifikasiController::class, 'getStats'])->name('stats');
    });
    
    // Inventaris Routes
    Route::prefix('inventaris')->name('inventaris.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\InventarisController::class, 'index'])->name('index');
        Route::get('/audit/stock', [\App\Http\Controllers\SuperAdmin\InventarisController::class, 'auditStock'])->name('audit');
        Route::post('/fix/stock', [\App\Http\Controllers\SuperAdmin\InventarisController::class, 'fixStock'])->name('fix');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\InventarisController::class, 'show'])->name('show');
        Route::get('/{id}/analysis', [\App\Http\Controllers\SuperAdmin\InventarisController::class, 'getStockAnalysis'])->name('analysis');
        Route::get('/export/csv', [\App\Http\Controllers\SuperAdmin\InventarisController::class, 'export'])->name('export');
        Route::get('/stats/ajax', [\App\Http\Controllers\SuperAdmin\InventarisController::class, 'getStats'])->name('stats');
        Route::get('/reports/utilization', [\App\Http\Controllers\SuperAdmin\InventarisController::class, 'getUtilizationReport'])->name('utilization');
    });
    
    // Peminjaman Management Routes
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\PeminjamanController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\PeminjamanController::class, 'show'])->name('show');
        Route::get('/stats/ajax', [\App\Http\Controllers\SuperAdmin\PeminjamanController::class, 'getStats'])->name('stats');
        Route::get('/export/csv', [\App\Http\Controllers\SuperAdmin\PeminjamanController::class, 'export'])->name('export');
    });
    
    // Transaksi Routes
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\TransaksiController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\TransaksiController::class, 'show'])->name('show');
        Route::post('/{id}/verify', [\App\Http\Controllers\SuperAdmin\TransaksiController::class, 'verify'])->name('verify');
        Route::post('/{id}/reject', [\App\Http\Controllers\SuperAdmin\TransaksiController::class, 'reject'])->name('reject');
        Route::get('/{id}/payment-proof', [\App\Http\Controllers\SuperAdmin\TransaksiController::class, 'showPaymentProof'])->name('payment-proof');
        Route::get('/export/csv', [\App\Http\Controllers\SuperAdmin\TransaksiController::class, 'export'])->name('export');
        Route::get('/stats/ajax', [\App\Http\Controllers\SuperAdmin\TransaksiController::class, 'getStats'])->name('stats');
    });
    
    // Payment Verification Routes - NEW
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/verify', function () {
            return view('superadmin.payment.verify');
        })->name('verify');
        Route::get('/queue/ajax', [\App\Http\Controllers\SuperAdmin\TransaksiController::class, 'getVerificationQueue'])->name('queue.ajax');
        Route::post('/verify-all', [\App\Http\Controllers\SuperAdmin\TransaksiController::class, 'verifyAll'])->name('verify-all');
    });
    
    // Pengembalian Routes
    Route::prefix('pengembalian')->name('pengembalian.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\PengembalianController::class, 'index'])->name('index');
        Route::get('/create/{peminjaman}', [\App\Http\Controllers\SuperAdmin\PengembalianController::class, 'create'])->name('create');
        Route::post('/store/{peminjaman}', [\App\Http\Controllers\SuperAdmin\PengembalianController::class, 'store'])->name('store');
        Route::get('/{id}/process', [\App\Http\Controllers\SuperAdmin\PengembalianController::class, 'processReturn'])->name('process');
        Route::post('/{id}/process-submit', [\App\Http\Controllers\SuperAdmin\PengembalianController::class, 'processReturnSubmit'])->name('process.submit');
        Route::put('/{id}/process-update', [\App\Http\Controllers\SuperAdmin\PengembalianController::class, 'processReturnUpdate'])->name('process-update');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\PengembalianController::class, 'show'])->name('show');
        Route::get('/export/csv', [\App\Http\Controllers\SuperAdmin\PengembalianController::class, 'export'])->name('export');
        Route::get('/stats/ajax', [\App\Http\Controllers\SuperAdmin\PengembalianController::class, 'getStats'])->name('stats');
        
        // Penalty Payment Verification Routes
        Route::get('/{id}/penalty-verification', [\App\Http\Controllers\SuperAdmin\PengembalianController::class, 'showPenaltyVerification'])->name('penalty-verification');
        Route::post('/{id}/verify-penalty-payment', [\App\Http\Controllers\SuperAdmin\PengembalianController::class, 'verifyPenaltyPayment'])->name('verify-penalty-payment');
    });
    
    // Kalender Routes
    Route::prefix('kalender')->name('kalender.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\KalenderController::class, 'index'])->name('index');
        Route::get('/events', [\App\Http\Controllers\SuperAdmin\KalenderController::class, 'getEvents'])->name('events');
        Route::post('/store', [\App\Http\Controllers\SuperAdmin\KalenderController::class, 'store'])->name('store');
        Route::get('/item/{itemId}/availability', [\App\Http\Controllers\SuperAdmin\KalenderController::class, 'getItemAvailability'])->name('item-availability');
        Route::get('/trends/monthly', [\App\Http\Controllers\SuperAdmin\KalenderController::class, 'getMonthlyTrends'])->name('monthly-trends');
        Route::post('/check-conflicts', [\App\Http\Controllers\SuperAdmin\KalenderController::class, 'checkConflicts'])->name('check-conflicts');
    });
});

// User Dashboard Routes
Route::prefix('user')->name('user.')->group(function () {
    // User Civitas Dashboard Routes
    Route::get('civitas/dashboard', function () {
        return view('user.civitas.dashboard');
    })->middleware('user.civitas')->name('civitas.dashboard');
    
    // User Non-Civitas Dashboard Routes
    Route::get('non-civitas/dashboard', function () {
        return view('user.non-civitas.dashboard');
    })->middleware('user.non_civitas')->name('non_civitas.dashboard');
    
    // Catalog/Gallery Routes (accessible by all authenticated users)
    Route::middleware(['auth:user'])->group(function () {
        // Gallery/Catalog Routes
        Route::get('/gallery', [CatalogController::class, 'index'])->name('gallery');
        Route::get('/catalog/search', [CatalogController::class, 'search'])->name('catalog.search');
        Route::post('/catalog/check-availability', [CatalogController::class, 'checkAvailability'])->name('catalog.check-availability');
        
        // Item Detail Routes
        Route::get('/item/{id}', [BarangController::class, 'show'])->name('item.detail');
        Route::get('/item/{id}/image/{image?}', [BarangController::class, 'getImage'])->name('barang.image');
        Route::post('/item/{id}/add-to-cart', [BarangController::class, 'addToCart'])->name('item.add-to-cart');
        Route::get('/item/{id}/check-stock', [BarangController::class, 'checkStock'])->name('check-stock');
        

        

    });
});

// User Routes (accessible by all user types after login)
Route::middleware(['auth:user'])->prefix('user')->name('user.')->group(function () {
    // Unified Dashboard Route
    Route::get('/dashboard', function () {
        $user = Auth::guard('user')->user();
        if ($user->role->nama_role === 'user_fmipa') {
            return view('user.civitas.dashboard');
        } else {
            return view('user.non-civitas.dashboard');
        }
    })->name('dashboard');

    // Cart Routes
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::put('/update', [CartController::class, 'update'])->name('update');
        Route::delete('/remove', [CartController::class, 'remove'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
        Route::get('/count', [CartController::class, 'getCount'])->name('count');
    });
    
    // Catalog Routes
    Route::prefix('catalog')->name('catalog.')->group(function () {
        Route::get('/', [CatalogController::class, 'index'])->name('index');
        Route::get('/{id}', [CatalogController::class, 'show'])->name('show');
        Route::get('/search', [CatalogController::class, 'search'])->name('search');
    });
    
    // Pengajuan Routes (Complete)
    Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
        Route::get('/', [PeminjamanController::class, 'showPengajuan'])->name('index');
        Route::get('/form', [PeminjamanController::class, 'showPengajuanForm'])->name('form');
        Route::post('/store', [PeminjamanController::class, 'store'])->name('store');
        Route::get('/{id}', [PeminjamanController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PeminjamanController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [PeminjamanController::class, 'update'])->name('update');
        Route::put('/{id}', [PeminjamanController::class, 'update'])->name('update.put');
        Route::post('/{id}/submit', [PeminjamanController::class, 'submitPengajuan'])->name('submit');
        Route::delete('/{id}/cancel', [PeminjamanController::class, 'cancel'])->name('cancel');
        Route::post('/{id}/confirm', [PeminjamanController::class, 'confirmPeminjaman'])->name('confirm');
        Route::post('/{id}/confirm-partial', [PeminjamanController::class, 'confirmPartialPeminjaman'])->name('confirm-partial');
    });
    
    // Peminjaman Routes (Complete)
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/', [PeminjamanController::class, 'index'])->name('index');
        Route::get('/{id}/detail', [PeminjamanController::class, 'showPeminjamanDetail'])->name('detail');
        Route::get('/{id}/payment', [PeminjamanController::class, 'showPaymentForm'])->name('payment');
        Route::post('/{id}/upload-payment', [PeminjamanController::class, 'uploadPayment'])->name('upload-payment');
        Route::delete('/item/{itemId}', [PeminjamanController::class, 'deleteRejectedItem'])->name('delete-item');
        Route::get('/{id}/return', [PengembalianController::class, 'create'])->name('return');
    });
    
    // Payment Routes - NEW
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/status', [PaymentController::class, 'status'])->name('status');
        Route::get('/{id}/summary', [PaymentController::class, 'summary'])->name('summary');
        Route::post('/{id}/upload', [PaymentController::class, 'upload'])->name('upload');
        Route::get('/history', [PaymentController::class, 'history'])->name('history');
        Route::get('/stats/ajax', [PaymentController::class, 'getStats'])->name('stats');
    });
    
    // Transaksi Routes - NEW
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('index');
        Route::get('/{id}', [TransaksiController::class, 'show'])->name('show');
        Route::post('/create', [TransaksiController::class, 'create'])->name('create');
        Route::get('/stats/ajax', [TransaksiController::class, 'getStats'])->name('stats');
    });
    

    
    // Pengembalian Routes
    Route::prefix('pengembalian')->name('pengembalian.')->group(function () {
        Route::get('/', [PengembalianController::class, 'index'])->name('index');
        Route::get('/create/{peminjaman}', [PengembalianController::class, 'create'])->name('create');
        Route::post('/store/{peminjaman}', [PengembalianController::class, 'store'])->name('store');
        Route::post('/submit/{peminjaman}', [PengembalianController::class, 'submitReturnRequest'])->name('submit');
        Route::get('/{id}', [PengembalianController::class, 'show'])->name('show');
        Route::delete('/{id}/cancel', [PengembalianController::class, 'cancel'])->name('cancel');
        Route::get('/stats/ajax', [PengembalianController::class, 'getStats'])->name('stats');
        
        // Penalty Payment Routes
        Route::get('/{id}/penalty-payment', [PengembalianController::class, 'showPenaltyPayment'])->name('penalty-payment');
        Route::post('/{id}/upload-penalty-payment', [PengembalianController::class, 'uploadPenaltyPayment'])->name('upload-penalty-payment');
    });
});

// Temporary debug route to test payment form access
Route::get('/debug-payment/{id}', function($id) {
    $user = Auth::guard('user')->user();
    
    if (!$user) {
        return 'User not authenticated';
    }
    
    $peminjaman = \App\Models\Peminjaman::where('id_peminjaman', $id)
        ->where('id_user', $user->id_user)
        ->first();
    
    if (!$peminjaman) {
        return 'Peminjaman not found for this user';
    }
    
    return [
        'user_role' => $user->role->nama_role,
        'peminjaman_id' => $peminjaman->id_peminjaman,
        'kode' => $peminjaman->kode_peminjaman,
        'status_pengajuan' => $peminjaman->status_pengajuan,
        'status_pembayaran' => $peminjaman->status_pembayaran,
        'payment_url' => route('user.peminjaman.payment', $id),
        'can_access_payment' => $peminjaman->status_pengajuan === 'confirmed' && 
                               in_array($peminjaman->status_pembayaran, ['pending', 'waiting_verification'])
    ];
})->name('debug.payment');

// Temporary debug route to check image data in database
Route::get('/debug-images', function() {
    try {
        // Method 1: Using Eloquent
        $barangEloquent = \App\Models\Barang::select('id_barang', 'nama_barang', 'foto_1')->first();
        
        // Method 2: Using Query Builder
        $barangQueryBuilder = \DB::table('barang')->select('id_barang', 'nama_barang', 'foto_1')->first();
        
        // Method 3: Using Raw PDO
        $pdo = \DB::connection()->getPdo();
        $stmt = $pdo->prepare("SELECT id_barang, nama_barang, foto_1, LENGTH(foto_1) as foto_1_size FROM barang WHERE foto_1 IS NOT NULL LIMIT 1");
        $stmt->execute();
        $barangRaw = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Method 4: Check if BLOB is being truncated
        $blobCheck = \DB::select("SELECT id_barang, nama_barang, LENGTH(foto_1) as foto_1_size, SUBSTRING(foto_1, 1, 50) as foto_1_sample FROM barang WHERE foto_1 IS NOT NULL LIMIT 1");
        
        return response()->json([
            'status' => 'success',
            'method_1_eloquent' => [
                'id' => $barangEloquent->id_barang ?? 'NULL',
                'nama' => $barangEloquent->nama_barang ?? 'NULL',
                'foto_1_exists' => !empty($barangEloquent->foto_1),
                'foto_1_length' => $barangEloquent->foto_1 ? strlen($barangEloquent->foto_1) : 0,
                'foto_1_type' => gettype($barangEloquent->foto_1 ?? null),
            ],
            'method_2_query_builder' => [
                'id' => $barangQueryBuilder->id_barang ?? 'NULL',
                'nama' => $barangQueryBuilder->nama_barang ?? 'NULL',
                'foto_1_exists' => !empty($barangQueryBuilder->foto_1),
                'foto_1_length' => $barangQueryBuilder->foto_1 ? strlen($barangQueryBuilder->foto_1) : 0,
                'foto_1_type' => gettype($barangQueryBuilder->foto_1 ?? null),
            ],
            'method_3_raw_pdo' => $barangRaw,
            'method_4_blob_check' => $blobCheck,
            'php_info' => [
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'pdo_mysql_version' => \DB::connection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION),
            ]
        ], 200, [], JSON_PRETTY_PRINT);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500, [], JSON_PRETTY_PRINT);
    }
})->name('debug.images');

// Clear cache route
Route::get('/clear-cache', function() {
    try {
        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        \Artisan::call('route:clear');
        
        return response()->json([
            'status' => 'success',
            'message' => 'All caches cleared successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
})->name('clear.cache');

// Test database configuration for BLOB handling
Route::get('/debug-db-config', function() {
    try {
        $mysqlVars = \DB::select("SHOW VARIABLES WHERE Variable_name IN ('max_allowed_packet', 'group_concat_max_len', 'innodb_log_file_size', 'wait_timeout', 'interactive_timeout')");
        
        $mysqlStatus = \DB::select("SHOW STATUS WHERE Variable_name IN ('Bytes_sent', 'Bytes_received', 'Max_used_connections')");
        
        // Test simple BLOB query
        $testQuery = \DB::select("SELECT id_barang, nama_barang, CHAR_LENGTH(foto_1) as foto_1_char_length, OCTET_LENGTH(foto_1) as foto_1_byte_length FROM barang WHERE foto_1 IS NOT NULL LIMIT 3");
        
        return response()->json([
            'status' => 'success',
            'mysql_variables' => $mysqlVars,
            'mysql_status' => $mysqlStatus,
            'test_query' => $testQuery,
            'database_config' => [
                'driver' => config('database.default'),
                'host' => config('database.connections.mysql.host'),
                'database' => config('database.connections.mysql.database'),
                'charset' => config('database.connections.mysql.charset'),
                'collation' => config('database.connections.mysql.collation'),
            ]
        ], 200, [], JSON_PRETTY_PRINT);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500, [], JSON_PRETTY_PRINT);
    }
})->name('debug.db.config');

// Test specific image loading
Route::get('/test-image/{id}', function($id) {
    try {
        // Direct PDO approach
        $pdo = \DB::connection()->getPdo();
        $stmt = $pdo->prepare("SELECT foto_1, LENGTH(foto_1) as size FROM barang WHERE id_barang = ? AND foto_1 IS NOT NULL");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && $result['foto_1']) {
            $imageData = $result['foto_1'];
            
            return response($imageData)
                ->header('Content-Type', 'image/jpeg')
                ->header('Content-Length', strlen($imageData))
                ->header('Cache-Control', 'public, max-age=31536000');
        }
        
        return response()->json([
            'status' => 'error',
            'message' => 'No image found for ID: ' . $id,
            'query_result' => $result
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'id' => $id
        ]);
    }
})->name('test.image');

require __DIR__.'/auth.php';
