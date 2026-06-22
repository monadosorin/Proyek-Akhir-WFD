<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nrp_nip',
        'prodi',
        'dosen_pembimbing_id',
        'quota',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isKaprodi(): bool
    {
        return $this->role === 'kaprodi';
    }

    public function isDosen(): bool
    {
        return $this->role === 'dosen';
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    public function slots(): HasMany
    {
        return $this->hasMany(Slot::class, 'dosen_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'mahasiswa_id');
    }

    public function dosenPembimbing(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing_id');
    }

    public function supervisees(): HasMany
    {
        return $this->hasMany(User::class, 'dosen_pembimbing_id');
    }
}
