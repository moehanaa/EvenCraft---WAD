<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketBenefit extends Model
{
    use HasFactory;

    protected $table = 'tiket_benefit';

    protected $fillable = [
        'id_tiket',
        'benefit',
    ];

    /**
     * Relasi Many-to-One balik ke model TicketCategory
     */
    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'id_tiket', 'id_tiket');
    }
}
