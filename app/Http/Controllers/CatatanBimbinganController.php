<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CatatanBimbingan;
use Illuminate\Http\Request;

class CatatanBimbinganController extends Controller
{
    public function edit(Request $request, Booking $booking)
    {
        abort_if($booking->slot->dosen_id !== $request->user()->id, 403);
        abort_if($booking->status !== 'approved' && $booking->status !== 'done', 422, 'Catatan hanya untuk booking yang sudah disetujui.');

        $catatan = $booking->catatan;
        return view('catatan.edit', compact('booking', 'catatan'));
    }

    public function save(Request $request, Booking $booking)
    {
        abort_if($booking->slot->dosen_id !== $request->user()->id, 403);
        abort_if($booking->status !== 'approved' && $booking->status !== 'done', 422);

        $data = $request->validate([
            'progress' => 'required|string',
            'catatan_dosen' => 'required|string',
            'tindak_lanjut' => 'nullable|string',
        ]);

        CatatanBimbingan::updateOrCreate(
            ['booking_id' => $booking->id],
            $data
        );

        $booking->update(['status' => 'done']);

        return redirect()->route('bookings.incoming')->with('status', 'Catatan tersimpan.');
    }
}
