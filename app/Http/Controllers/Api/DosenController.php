<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $q = User::where('role', 'dosen')
            ->withCount(['slots as slot_tersedia_count' => fn ($q) => $q->where('status', 'tersedia')])
            ->orderBy('name');

        if ($request->user()->isMahasiswa() && $request->user()->dosen_pembimbing_id) {
            $q->where('id', $request->user()->dosen_pembimbing_id);
        } elseif ($request->user()->isMahasiswa()) {
            return [];
        }

        return $q->get(['id', 'name', 'nrp_nip', 'prodi']);
    }
}
