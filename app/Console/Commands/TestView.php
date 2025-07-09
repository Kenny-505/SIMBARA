<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use Carbon\Carbon;

class TestView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:view {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test superadmin peminjaman show view';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('id') ?? 13; // Default to 13 like in the error
        
        try {
            // Test if view file exists
            $viewPath = resource_path('views/superadmin/peminjaman/show.blade.php');
            if (!file_exists($viewPath)) {
                $this->error("View file does not exist: $viewPath");
                return 1;
            }
            $this->info("✓ View file exists: $viewPath");
            
            // Test if view can be resolved
            if (!view()->exists('superadmin.peminjaman.show')) {
                $this->error("View cannot be resolved: superadmin.peminjaman.show");
                return 1;
            }
            $this->info("✓ View can be resolved");
            
            // Get peminjaman data
            $peminjaman = Peminjaman::with([
                'user.role',
                'peminjamanBarangs.barang.admin',
                'peminjamanBarangs.approvedBy',
                'transaksi',
                'pengembalian.pengembalianBarangs.barang'
            ])->find($id);
            
            if (!$peminjaman) {
                $this->error("Peminjaman with ID $id not found");
                return 1;
            }
            $this->info("✓ Peminjaman data found: {$peminjaman->kode_peminjaman}");
            
            // Create sample data like in controller
            $timeline = collect([
                [
                    'date' => $peminjaman->created_at,
                    'title' => 'Pengajuan Dibuat',
                    'description' => "Pengajuan peminjaman dibuat oleh {$peminjaman->user->nama_penanggung_jawab}",
                    'type' => 'created',
                    'icon' => 'plus-circle'
                ]
            ]);
            
            $summary = [
                'total_items' => $peminjaman->peminjamanBarangs->count(),
                'approved_items' => $peminjaman->peminjamanBarangs->where('status_persetujuan', 'approved')->count(),
                'pending_items' => $peminjaman->peminjamanBarangs->where('status_persetujuan', 'pending')->count(),
                'rejected_items' => $peminjaman->peminjamanBarangs->where('status_persetujuan', 'rejected')->count(),
                'duration_days' => Carbon::parse($peminjaman->tanggal_mulai)->diffInDays(Carbon::parse($peminjaman->tanggal_selesai)) + 1,
                'is_overdue' => $peminjaman->status_peminjaman === 'ongoing' && Carbon::parse($peminjaman->tanggal_selesai)->isPast(),
                'days_overdue' => 0
            ];
            
            // Try to render the view
            $view = view('superadmin.peminjaman.show', compact('peminjaman', 'timeline', 'summary'));
            $content = $view->render();
            
            $this->info("✓ View rendered successfully");
            $this->info("Content length: " . strlen($content) . " characters");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            $this->error("File: " . $e->getFile() . ":" . $e->getLine());
            return 1;
        }
    }
}
