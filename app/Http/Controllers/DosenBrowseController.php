<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use App\Models\User;
use Illuminate\Http\Request;

class DosenBrowseController extends Controller
{
    public function index(Request $request)
    {
        $pembimbingId = $request->user()->dosen_pembimbing_id;

        $dosens = collect();
        if ($pembimbingId) {
            $dosens = User::where('id', $pembimbingId)
                ->where('role', 'dosen')
                ->withCount(['slots as slot_tersedia_count' => fn ($q) => $q->where('status', 'tersedia')])
                ->get();
        }

        return view('dosen.index', compact('dosens'));
    }

    public function slots(Request $request, User $dosen)
    {
        abort_if($dosen->role !== 'dosen', 404);
        abort_if($request->user()->dosen_pembimbing_id !== $dosen->id, 403, 'Dosen ini bukan pembimbing Anda.');

        $slots = Slot::where('dosen_id', $dosen->id)
            ->where('status', 'tersedia')
            ->where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        return view('dosen.slots', compact('dosen', 'slots'));
    }
}
