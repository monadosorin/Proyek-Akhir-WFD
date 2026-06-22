<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Slot extends Model
{
    protected $fillable = [
        'dosen_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function activeBooking(): HasOne
    {
        return $this->hasOne(Booking::class)->whereIn('status', ['pending', 'approved', 'done']);
    }
}
