<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Barang;
use App\Models\PeminjamanBarang;
use App\Models\PengembalianBarang;
use App\Models\Peminjaman;
use App\Models\Pengembalian;

class AuditStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:audit {--fix : Automatically fix inconsistencies} {--item-id= : Audit specific item only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit stock consistency and optionally fix issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Starting stock audit...');
        
        // Get items to audit
        if ($this->option('item-id')) {
            $barangs = Barang::where('id_barang', $this->option('item-id'))
                ->where('is_active', true)
                ->get();
            
            if ($barangs->isEmpty()) {
                $this->error('Item not found or inactive');
                return 1;
            }
        } else {
            $barangs = Barang::where('is_active', true)->get();
        }

        $this->info("Auditing {$barangs->count()} items...");
        
        $issues = [];
        $fixed = [];
        
        $bar = $this->output->createProgressBar($barangs->count());
        $bar->start();

        foreach ($barangs as $barang) {
            $audit = $this->auditSingleItem($barang);
            
            if (!$audit['is_consistent']) {
                $issues[] = $audit;
                
                if ($this->option('fix')) {
                    $fixResult = $this->fixItemStock($barang, $audit);
                    if ($fixResult['success']) {
                        $fixed[] = $fixResult;
                    }
                }
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();

        // Display results
        $totalItems = $barangs->count();
        $issuesFound = count($issues);
        $consistentItems = $totalItems - $issuesFound;

        $this->newLine();
        $this->info("📊 Audit Results:");
        $this->line("Total items: {$totalItems}");
        $this->line("Consistent: " . ($consistentItems > 0 ? "<fg=green>{$consistentItems}</>" : '0'));
        $this->line("Issues found: " . ($issuesFound > 0 ? "<fg=red>{$issuesFound}</>" : '0'));

        if ($issuesFound > 0) {
            $this->newLine();
            $this->warn("⚠️  Issues found:");
            
            foreach ($issues as $issue) {
                $this->line("• {$issue['nama_barang']}:");
                foreach ($issue['issues'] as $problem) {
                    $this->line("  - {$problem}");
                }
            }

            if ($this->option('fix')) {
                $fixedCount = count($fixed);
                $this->newLine();
                $this->info("🔧 Fixed {$fixedCount} items");
                
                foreach ($fixed as $fix) {
                    $this->line("• {$fix['nama_barang']}: Available {$fix['old_available']} → {$fix['new_available']}");
                }
            } else {
                $this->newLine();
                $this->comment("💡 Run with --fix to automatically fix issues");
            }
        } else {
            $this->newLine();
            $this->info("✅ All stock is consistent!");
        }

        return 0;
    }

    /**
     * Audit single item
     */
    private function auditSingleItem(Barang $barang)
    {
        $result = [
            'id_barang' => $barang->id_barang,
            'nama_barang' => $barang->nama_barang,
            'current_stock_total' => $barang->stok_total,
            'current_stock_available' => $barang->stok_tersedia,
            'is_consistent' => true,
            'issues' => []
        ];

        // Calculate expected stock
        $calculated = $this->calculateExpectedStock($barang);
        
        // Check for inconsistencies
        if ($barang->stok_tersedia != $calculated['expected_available']) {
            $result['is_consistent'] = false;
            $result['issues'][] = "Available stock mismatch. Current: {$barang->stok_tersedia}, Expected: {$calculated['expected_available']}";
        }
        
        if ($barang->stok_tersedia > $barang->stok_total) {
            $result['is_consistent'] = false;
            $result['issues'][] = "Available stock exceeds total stock";
        }
        
        if ($barang->stok_tersedia < 0) {
            $result['is_consistent'] = false;
            $result['issues'][] = "Negative available stock";
        }

        $result['expected_available'] = $calculated['expected_available'];
        $result['total_borrowed'] = $calculated['total_borrowed'];
        $result['total_returned'] = $calculated['total_returned'];
        $result['total_damaged'] = $calculated['total_damaged'];

        return $result;
    }

    /**
     * Calculate expected stock based on transaction history
     */
    private function calculateExpectedStock(Barang $barang)
    {
        $initialStock = $barang->stok_total;
        
        // Get all approved loans
        $totalBorrowed = PeminjamanBarang::where('id_barang', $barang->id_barang)
            ->where('status_persetujuan', 'approved')
            ->whereHas('peminjaman', function($q) {
                $q->whereIn('status_peminjaman', ['ongoing', 'returned']);
            })
            ->sum('jumlah_pinjam');
        
        // Get all completed returns
        $pengembalianBarangs = PengembalianBarang::where('id_barang', $barang->id_barang)
            ->whereHas('pengembalian', function($q) {
                $q->whereIn('status_pengembalian', ['completed', 'fully_completed']);
            })
            ->get();
        
        $totalReturned = 0;
        $totalDamaged = 0;
        
        foreach ($pengembalianBarangs as $item) {
            if ($item->kondisi_barang === 'parah') {
                $totalDamaged += $item->jumlah_kembali;
            } else {
                $totalReturned += $item->jumlah_kembali;
            }
        }
        
        $expectedAvailable = $initialStock - $totalBorrowed + $totalReturned;
        
        return [
            'expected_available' => max(0, $expectedAvailable),
            'total_borrowed' => $totalBorrowed,
            'total_returned' => $totalReturned,
            'total_damaged' => $totalDamaged
        ];
    }

    /**
     * Fix item stock
     */
    private function fixItemStock(Barang $barang, array $audit)
    {
        $oldAvailable = $barang->stok_tersedia;
        $newAvailable = max(0, $audit['expected_available']);

        $barang->update([
            'stok_tersedia' => $newAvailable
        ]);

        return [
            'success' => true,
            'nama_barang' => $barang->nama_barang,
            'old_available' => $oldAvailable,
            'new_available' => $newAvailable
        ];
    }
} 