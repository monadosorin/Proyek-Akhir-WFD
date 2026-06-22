<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    public function index(Request $request)
    {
        $q = Slot::query()
            ->where('status', 'tersedia')
            ->where('tanggal', '>=', now()->toDateString())
            ->with('dosen:id,name,prodi')
            ->orderBy('tanggal')
            ->orderBy('jam_mulai');

        if ($request->filled('dosen_id')) {
            $q->where('dosen_id', $request->integer('dosen_id'));
        }

        return $q->get();
    }
}
