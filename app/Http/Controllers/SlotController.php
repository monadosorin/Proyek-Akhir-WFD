<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    public function index(Request $request)
    {
        $slots = Slot::with('activeBooking.mahasiswa')
            ->where('dosen_id', $request->user()->id)
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        return view('slots.index', compact('slots'));
    }

    public function create()
    {
        return view('slots.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $data['dosen_id'] = $request->user()->id;
        Slot::create($data);

        return redirect()->route('slots.index')->with('status', 'Slot berhasil dibuat.');
    }

    public function edit(Request $request, Slot $slot)
    {
        abort_if($slot->dosen_id !== $request->user()->id, 403);

        if ($slot->activeBooking) {
            return redirect()->route('slots.index')->with('status', 'Slot sedang ada booking aktif, tidak bisa diedit.');
        }

        return view('slots.edit', compact('slot'));
    }

    public function update(Request $request, Slot $slot)
    {
        abort_if($slot->dosen_id !== $request->user()->id, 403);

        if ($slot->activeBooking) {
            return redirect()->route('slots.index')->with('status', 'Slot sedang ada booking aktif, tidak bisa diedit.');
        }

        $data = $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $slot->update($data);

        return redirect()->route('slots.index')->with('status', 'Slot diperbarui.');
    }

    public function destroy(Request $request, Slot $slot)
    {
        abort_if($slot->dosen_id !== $request->user()->id, 403);

        if ($slot->activeBooking) {
            return redirect()->route('slots.index')->with('status', 'Slot sudah dibooking, tidak bisa dihapus.');
        }

        $slot->delete();
        return redirect()->route('slots.index')->with('status', 'Slot dihapus.');
    }

    public function show(Slot $slot)
    {
        abort(404);
    }
}
