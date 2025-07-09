<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    
    protected $fillable = [
        'id_admin',
        'nama_barang',
        'stok_total',
        'stok_tersedia',
        'deskripsi',
        'harga_sewa',
        'foto_1',
        'foto_2',
        'foto_3',
        'denda_ringan',
        'denda_sedang',
        'denda_parah',
        'is_active'
    ];

    protected $casts = [
        'harga_sewa' => 'decimal:2',
        'denda_ringan' => 'decimal:2',
        'denda_sedang' => 'decimal:2',
        'denda_parah' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin');
    }

    public function peminjamanBarangs()
    {
        return $this->hasMany(PeminjamanBarang::class, 'id_barang');
    }

    public function pengembalianBarangs()
    {
        return $this->hasMany(PengembalianBarang::class, 'id_barang');
    }

    /**
     * Calculate penalty based on item condition and lateness
     */
    public function calculatePenalty($condition, $daysLate = 0)
    {
        $conditionPenalty = 0;
        
        // Penalty based on item condition
        switch ($condition) {
            case 'ringan':
                $conditionPenalty = $this->denda_ringan;
                break;
            case 'sedang':
                $conditionPenalty = $this->denda_sedang;
                break;
            case 'parah':
                $conditionPenalty = $this->denda_parah;
                break;
            default: // 'baik'
                $conditionPenalty = 0;
                break;
        }
        
        // Additional penalty for lateness (10% of item rental price per day)
        $latePenalty = 0;
        if ($daysLate > 0 && $this->harga_sewa > 0) {
            $latePenalty = ($this->harga_sewa * 0.1) * $daysLate;
        }
        
        return $conditionPenalty + $latePenalty;
    }

    /**
     * Get penalty breakdown for transparency
     */
    public function getPenaltyBreakdown($condition, $daysLate = 0)
    {
        $conditionPenalty = 0;
        
        switch ($condition) {
            case 'ringan':
                $conditionPenalty = $this->denda_ringan;
                break;
            case 'sedang':
                $conditionPenalty = $this->denda_sedang;
                break;
            case 'parah':
                $conditionPenalty = $this->denda_parah;
                break;
            default:
                $conditionPenalty = 0;
                break;
        }
        
        $latePenalty = 0;
        if ($daysLate > 0 && $this->harga_sewa > 0) {
            $latePenalty = ($this->harga_sewa * 0.1) * $daysLate;
        }
        
        return [
            'condition_penalty' => $conditionPenalty,
            'late_penalty' => $latePenalty,
            'total_penalty' => $conditionPenalty + $latePenalty,
            'condition' => $condition,
            'days_late' => $daysLate
        ];
    }

    /**
     * Get all penalty rates for this item
     */
    public function getAllPenaltyRates($daysLate = 0)
    {
        $latePenalty = 0;
        if ($daysLate > 0 && $this->harga_sewa > 0) {
            $latePenalty = ($this->harga_sewa * 0.1) * $daysLate;
        }

        return [
            'baik' => $latePenalty,
            'ringan' => $this->denda_ringan + $latePenalty,
            'sedang' => $this->denda_sedang + $latePenalty,
            'parah' => $this->denda_parah + $latePenalty
        ];
    }
}
