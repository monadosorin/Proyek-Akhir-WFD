<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Slot;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function mineIndex(Request $request)
    {
        $bookings = Booking::with(['slot.dosen', 'catatan'])
            ->where('mahasiswa_id', $request->user()->id)
            ->latest()
            ->get();

        return view('bookings.mine', compact('bookings'));
    }

    public function create(Request $request)
    {
        $slot = Slot::with('dosen')->findOrFail($request->query('slot_id'));
        abort_if($slot->status !== 'tersedia', 422, 'Slot sudah tidak tersedia.');
        abort_if($slot->dosen_id !== $request->user()->dosen_pembimbing_id, 403, 'Slot ini bukan dari dosen pembimbing Anda.');
        return view('bookings.create', compact('slot'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'slot_id' => 'required|exists:slots,id',
            'topik' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $slot = Slot::findOrFail($data['slot_id']);
        abort_if($slot->status !== 'tersedia', 422, 'Slot sudah tidak tersedia.');
        abort_if($slot->dosen_id !== $request->user()->dosen_pembimbing_id, 403, 'Slot ini bukan dari dosen pembimbing Anda.');

        Booking::create([
            'slot_id' => $slot->id,
            'mahasiswa_id' => $request->user()->id,
            'topik' => $data['topik'],
            'deskripsi' => $data['deskripsi'],
            'status' => 'pending',
        ]);

        $slot->update(['status' => 'menunggu']);

        return redirect()->route('bookings.mine')->with('status', 'Booking dikirim, menunggu persetujuan.');
    }

    public function cancel(Request $request, Booking $booking)
    {
        abort_if($booking->mahasiswa_id !== $request->user()->id, 403);
        abort_if($booking->status !== 'pending', 422, 'Hanya booking pending yang dapat dibatalkan.');

        $booking->slot->update(['status' => 'tersedia']);
        $booking->delete();

        return redirect()->route('bookings.mine')->with('status', 'Booking dibatalkan.');
    }

    public function incomingIndex(Request $request)
    {
        $bookings = Booking::with(['slot', 'mahasiswa', 'catatan'])
            ->whereHas('slot', fn ($q) => $q->where('dosen_id', $request->user()->id))
            ->latest()
            ->get();

        return view('bookings.incoming', compact('bookings'));
    }

    public function approve(Request $request, Booking $booking)
    {
        abort_if($booking->slot->dosen_id !== $request->user()->id, 403);
        abort_if($booking->status !== 'pending', 422);

        $booking->update(['status' => 'approved']);
        $booking->slot->update(['status' => 'terbooking']);
        return back()->with('status', 'Booking disetujui.');
    }

    public function reject(Request $request, Booking $booking)
    {
        abort_if($booking->slot->dosen_id !== $request->user()->id, 403);
        abort_if($booking->status !== 'pending', 422);

        $data = $request->validate([
            'alasan_tolak' => 'required|string|max:1000',
        ]);

        $booking->update([
            'status' => 'rejected',
            'alasan_tolak' => $data['alasan_tolak'],
        ]);
        $booking->slot->update(['status' => 'tersedia']);

        return back()->with('status', 'Booking ditolak.');
    }
}
