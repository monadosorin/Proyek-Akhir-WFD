<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Slot;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function mine(Request $request)
    {
        return Booking::with(['slot.dosen:id,name', 'catatan'])
            ->where('mahasiswa_id', $request->user()->id)
            ->latest()
            ->get();
    }

    public function store(Request $request)
    {
        abort_if(! $request->user()->isMahasiswa(), 403, 'Hanya mahasiswa yang dapat booking.');

        $data = $request->validate([
            'slot_id' => 'required|exists:slots,id',
            'topik' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $slot = Slot::findOrFail($data['slot_id']);
        abort_if($slot->status !== 'tersedia', 422, 'Slot sudah tidak tersedia.');
        abort_if($slot->dosen_id !== $request->user()->dosen_pembimbing_id, 403, 'Slot ini bukan dari dosen pembimbing Anda.');

        $booking = Booking::create([
            'slot_id' => $slot->id,
            'mahasiswa_id' => $request->user()->id,
            'topik' => $data['topik'],
            'deskripsi' => $data['deskripsi'],
            'status' => 'pending',
        ]);
        $slot->update(['status' => 'menunggu']);

        return response()->json($booking, 201);
    }

    public function approve(Request $request, Booking $booking)
    {
        abort_if(! $request->user()->isDosen(), 403);
        abort_if($booking->slot->dosen_id !== $request->user()->id, 403);
        abort_if($booking->status !== 'pending', 422);

        $booking->update(['status' => 'approved']);
        $booking->slot->update(['status' => 'terbooking']);
        return response()->json($booking);
    }
}
