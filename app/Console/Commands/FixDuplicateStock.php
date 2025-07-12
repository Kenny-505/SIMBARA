<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Barang;
use App\Models\PeminjamanBarang;
use App\Models\PengembalianBarang;
use App\Models\Pengembalian;
use Illuminate\Support\Facades\DB;

class FixDuplicateStock extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'stock:fix-duplicate {--dry-run : Show what would be fixed without making changes} {--barang-id= : Fix specific barang ID only}';

    /**
     * The console command description.
     */
    protected $description = 'Fix stock duplications caused by duplicate return processing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $specificBarangId = $this->option('barang-id');
        
        $this->info('Starting stock duplication fix...');
        $this->info('Mode: ' . ($isDryRun ? 'DRY RUN (no changes will be made)' : 'LIVE (changes will be applied)'));
        $this->newLine();

        // Get barangs to process
        $query = Barang::with('admin');
        if ($specificBarangId) {
            $query->where('id_barang', $specificBarangId);
        }
        $barangs = $query->get();

        if ($barangs->count() === 0) {
            $this->error('No barang found to process.');
            return 1;
        }

        $this->info("Processing {$barangs->count()} barang(s)...");
        $this->newLine();

        $fixedCount = 0;
        $issuesFound = [];

        DB::beginTransaction();
        
        try {
            foreach ($barangs as $barang) {
                $result = $this->calculateCorrectStock($barang);
                
                $currentAvailable = $barang->stok_tersedia;
                $currentTotal = $barang->stok_total;
                $expectedAvailable = $result['expected_available'];
                $expectedTotal = $result['expected_total'];
                
                // Check if there's a discrepancy
                $availableDiscrepancy = $currentAvailable - $expectedAvailable;
                $totalDiscrepancy = $currentTotal - $expectedTotal;
                
                if ($availableDiscrepancy != 0 || $totalDiscrepancy != 0) {
                    $issuesFound[] = [
                        'barang' => $barang,
                        'current_available' => $currentAvailable,
                        'expected_available' => $expectedAvailable,
                        'available_discrepancy' => $availableDiscrepancy,
                        'current_total' => $currentTotal,
                        'expected_total' => $expectedTotal,
                        'total_discrepancy' => $totalDiscrepancy,
                        'calculation_details' => $result
                    ];
                    
                    if (!$isDryRun) {
                        // Apply the fix
                        $barang->update([
                            'stok_tersedia' => max(0, $expectedAvailable),
                            'stok_total' => max(0, $expectedTotal)
                        ]);
                        $fixedCount++;
                    }
                    
                    $this->displayIssue($barang, $result, $currentAvailable, $currentTotal, $expectedAvailable, $expectedTotal);
                }
            }
            
                         if ($isDryRun) {
                 DB::rollBack();
                 $this->newLine();
                 $this->info("DRY RUN COMPLETED");
                 $this->info("Found " . count($issuesFound) . " barang(s) with stock discrepancies.");
                 $this->info("Run without --dry-run to apply fixes.");
             } else {
                 DB::commit();
                 $this->newLine();
                 $this->info("FIXES APPLIED");
                 $this->info("Fixed " . $fixedCount . " barang(s) with stock discrepancies.");
                
                // Clear duplicate prevention cache for all fixed items
                foreach ($issuesFound as $issue) {
                    $barang = $issue['barang'];
                    // Clear cache that might prevent future updates
                    $pengembalians = Pengembalian::whereHas('pengembalianBarangs', function($q) use ($barang) {
                        $q->where('id_barang', $barang->id_barang);
                    })->get();
                    
                    foreach ($pengembalians as $pengembalian) {
                        $cacheKey = "stock_returned_pengembalian_{$pengembalian->id_pengembalian}";
                        \Cache::forget($cacheKey);
                    }
                }
                
                $this->info("Cleared duplicate prevention cache for fixed items.");
            }
            
            return 0;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error during stock fix: " . $e->getMessage());
            return 1;
        }
    }

    private function calculateCorrectStock(Barang $barang)
    {
        $initialTotal = $barang->stok_total;
        
        // Get all approved peminjaman for this item
        $peminjamanBarangs = PeminjamanBarang::where('id_barang', $barang->id_barang)
            ->where('status_persetujuan', 'approved')
            ->whereHas('peminjaman', function($q) {
                $q->whereIn('status_peminjaman', ['ongoing', 'returned']);
            })
            ->with('peminjaman')
            ->get();
        
        $totalBorrowed = $peminjamanBarangs->sum('jumlah_pinjam');
        
        // Get all completed returns for this item
        $pengembalianBarangs = PengembalianBarang::where('id_barang', $barang->id_barang)
            ->whereHas('pengembalian', function($q) {
                $q->whereIn('status_pengembalian', ['completed', 'fully_completed']);
            })
            ->with('pengembalian')
            ->get();
        
        $totalReturnedGood = 0;
        $totalDamaged = 0;
        
        foreach ($pengembalianBarangs as $item) {
            if ($item->kondisi_barang === 'parah') {
                $totalDamaged += $item->jumlah_kembali;
            } else {
                $totalReturnedGood += $item->jumlah_kembali;
            }
        }
        
        // Calculate expected stock
        // Available stock = initial total - borrowed + returned (good condition)
        $expectedAvailable = $initialTotal - $totalBorrowed + $totalReturnedGood;
        
        // Total stock should be reduced by severely damaged items
        $expectedTotal = $initialTotal - $totalDamaged;
        
        // Ensure available doesn't exceed total
        $expectedAvailable = min($expectedAvailable, $expectedTotal);
        
        return [
            'expected_available' => max(0, $expectedAvailable),
            'expected_total' => max(0, $expectedTotal),
            'initial_total' => $initialTotal,
            'total_borrowed' => $totalBorrowed,
            'total_returned_good' => $totalReturnedGood,
            'total_damaged' => $totalDamaged,
            'calculation' => sprintf("Available: %d - %d + %d = %d, Total: %d - %d = %d", 
                $initialTotal, $totalBorrowed, $totalReturnedGood, $expectedAvailable,
                $initialTotal, $totalDamaged, $expectedTotal)
        ];
    }
    
    private function displayIssue($barang, $result, $currentAvailable, $currentTotal, $expectedAvailable, $expectedTotal)
    {
        $this->warn("=== " . $barang->nama_barang . " (ID: " . $barang->id_barang . ") ===");
        $adminName = ($barang->admin && $barang->admin->nama_lengkap) ? $barang->admin->nama_lengkap : 'Unknown';
        $this->line("Admin: " . $adminName);
        $this->newLine();
        
        // Current vs Expected
        $this->line("<comment>Stock Analysis:</comment>");
        $this->line("   Current Available: <fg=red>" . $currentAvailable . "</fg=red>");
        $this->line("   Expected Available: <fg=green>" . $expectedAvailable . "</fg=green>");
        $this->line("   Discrepancy: <fg=yellow>" . ($currentAvailable - $expectedAvailable) . "</fg=yellow>");
        $this->newLine();
        
        $this->line("   Current Total: <fg=red>" . $currentTotal . "</fg=red>");
        $this->line("   Expected Total: <fg=green>" . $expectedTotal . "</fg=green>");
        $this->line("   Discrepancy: <fg=yellow>" . ($currentTotal - $expectedTotal) . "</fg=yellow>");
        $this->newLine();
        
        // Calculation details
        $this->line("<comment>Calculation Details:</comment>");
        $this->line("   Initial Total: " . $result['initial_total']);
        $this->line("   Total Borrowed: " . $result['total_borrowed']);
        $this->line("   Total Returned (Good): " . $result['total_returned_good']);
        $this->line("   Total Damaged: " . $result['total_damaged']);
        $this->line("   Formula: " . $result['calculation']);
        $this->newLine();
    }
} 