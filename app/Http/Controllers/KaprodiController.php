<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class KaprodiController extends Controller
{
    public function mahasiswaIndex(Request $request)
    {
        $q = User::where('role', 'mahasiswa')->with('dosenPembimbing:id,name');

        if ($request->filled('search')) {
            $s = $request->string('search');
            $q->where(fn ($x) => $x->where('name', 'like', "%$s%")->orWhere('nrp_nip', 'like', "%$s%"));
        }

        $mahasiswas = $q->orderBy('name')->get();
        $dosens = User::where('role', 'dosen')
            ->withCount('supervisees')
            ->orderBy('name')
            ->get(['id', 'name', 'quota']);

        return view('kaprodi.mahasiswa', compact('mahasiswas', 'dosens'));
    }

    public function assign(Request $request, User $mahasiswa)
    {
        abort_if($mahasiswa->role !== 'mahasiswa', 422, 'User bukan mahasiswa.');

        $data = $request->validate([
            'dosen_pembimbing_id' => 'required|exists:users,id',
        ]);

        $dosen = User::findOrFail($data['dosen_pembimbing_id']);
        abort_if($dosen->role !== 'dosen', 422, 'Target bukan dosen.');

        if ($dosen->quota !== null) {
            $currentCount = $dosen->supervisees()->where('id', '!=', $mahasiswa->id)->count();
            if ($currentCount >= $dosen->quota) {
                return back()->with('status', "Kuota dosen {$dosen->name} sudah penuh ({$currentCount}/{$dosen->quota}).");
            }
        }

        $mahasiswa->update(['dosen_pembimbing_id' => $dosen->id]);

        return back()->with('status', "{$mahasiswa->name} ditugaskan ke {$dosen->name}.");
    }

    public function unassign(Request $request, User $mahasiswa)
    {
        abort_if($mahasiswa->role !== 'mahasiswa', 422);
        $mahasiswa->update(['dosen_pembimbing_id' => null]);
        return back()->with('status', "Penugasan {$mahasiswa->name} dibatalkan.");
    }

    public function dosenIndex()
    {
        $dosens = User::where('role', 'dosen')
            ->withCount('supervisees')
            ->orderBy('name')
            ->get();

        return view('kaprodi.dosen', compact('dosens'));
    }

    public function updateQuota(Request $request, User $dosen)
    {
        abort_if($dosen->role !== 'dosen', 422);

        $data = $request->validate([
            'quota' => 'nullable|integer|min:0|max:1000',
        ]);

        $currentCount = $dosen->supervisees()->count();
        if ($data['quota'] !== null && $data['quota'] < $currentCount) {
            return back()->with('status', "Kuota tidak bisa lebih kecil dari jumlah bimbingan saat ini ({$currentCount}).");
        }

        $dosen->update(['quota' => $data['quota']]);
        return back()->with('status', "Kuota {$dosen->name} diperbarui.");
    }

    public function bookingsIndex()
    {
        $bookings = Booking::with(['slot.dosen:id,name', 'mahasiswa:id,name,nrp_nip', 'catatan'])
            ->latest()
            ->get();

        return view('kaprodi.bookings', compact('bookings'));
    }
}
