<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    use HasFactory;

    protected $table = 'tiket_kategori';
    protected $primaryKey = 'id_tiket';

    protected $fillable = [
        'event_id',
        'nama_kategori',
        'tipe_tier',
        'harga',
        'kuota',
        'terjual',
        'deskripsi',
        'periode_jual',
    ];

    protected $casts = [
        'harga'   => 'decimal:2',
        'kuota'   => 'integer',
        'terjual' => 'integer',
    ];

    /**
     * Relasi One-to-Many ke model TicketBenefit
     */
    public function benefits()
    {
        return $this->hasMany(TicketBenefit::class, 'id_tiket', 'id_tiket');
    }

    /**
     * Accessor kode tiket format TKT-XXX
     */
    public function getCodeAttribute(): string
    {
        return 'TKT-' . str_pad($this->id_tiket, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Accessor harga format Rupiah
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Accessor sisa kuota kursi
     */
    public function getSisaKuotaAttribute(): int
    {
        return max(0, $this->kuota - $this->terjual);
    }

    /**
     * Accessor persentase okupansi kuota
     */
    public function getPersenOkupansiAttribute(): int
    {
        return ($this->kuota > 0) ? min(100, (int) round(($this->terjual / $this->kuota) * 100)) : 0;
    }
}
