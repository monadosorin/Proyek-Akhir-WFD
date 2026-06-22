<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanBimbingan extends Model
{
    protected $table = 'catatan_bimbingan';

    protected $fillable = [
        'booking_id',
        'progress',
        'catatan_dosen',
        'tindak_lanjut',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
