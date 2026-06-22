<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CatatanBimbinganController;
use App\Http\Controllers\DosenBrowseController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SlotController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    return match ($user->role) {
        'kaprodi'   => redirect()->route('kaprodi.mahasiswa'),
        'dosen'     => redirect()->route('slots.index'),
        'mahasiswa' => redirect()->route('dosen.browse'),
    };
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:kaprodi'])->prefix('kaprodi')->name('kaprodi.')->group(function () {
    Route::get('/mahasiswa', [KaprodiController::class, 'mahasiswaIndex'])->name('mahasiswa');
    Route::post('/mahasiswa/{mahasiswa}/assign', [KaprodiController::class, 'assign'])->name('assign');
    Route::post('/mahasiswa/{mahasiswa}/unassign', [KaprodiController::class, 'unassign'])->name('unassign');
    Route::get('/dosen', [KaprodiController::class, 'dosenIndex'])->name('dosen');
    Route::post('/dosen/{dosen}/quota', [KaprodiController::class, 'updateQuota'])->name('quota');
    Route::get('/bookings', [KaprodiController::class, 'bookingsIndex'])->name('bookings');
});

Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::resource('slots', SlotController::class)->except(['show']);
    Route::get('/dosen/bookings', [BookingController::class, 'incomingIndex'])->name('bookings.incoming');
    Route::post('/dosen/bookings/{booking}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    Route::post('/dosen/bookings/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    Route::get('/dosen/bookings/{booking}/catatan', [CatatanBimbinganController::class, 'edit'])->name('catatan.edit');
    Route::post('/dosen/bookings/{booking}/catatan', [CatatanBimbinganController::class, 'save'])->name('catatan.save');
});

Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/dosen-list', [DosenBrowseController::class, 'index'])->name('dosen.browse');
    Route::get('/dosen-list/{dosen}/slots', [DosenBrowseController::class, 'slots'])->name('dosen.slots');
    Route::get('/bookings', [BookingController::class, 'mineIndex'])->name('bookings.mine');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::patch('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

require __DIR__.'/auth.php';
