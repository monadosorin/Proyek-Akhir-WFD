<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Semua Booking (Global View)</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                @if ($bookings->isEmpty())
                    <p class="p-6 text-gray-500">Belum ada booking di sistem.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-600">
                                <tr>
                                    <th class="px-4 py-3">Mahasiswa</th>
                                    <th class="px-4 py-3">Dosen</th>
                                    <th class="px-4 py-3">Jadwal</th>
                                    <th class="px-4 py-3">Topik</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($bookings as $b)
                                    @php
                                        $badge = [
                                            'pending'  => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-blue-100 text-blue-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'done'     => 'bg-green-100 text-green-800',
                                        ][$b->status];
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="font-medium">{{ $b->mahasiswa->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $b->mahasiswa->nrp_nip }}</div>
                                        </td>
                                        <td class="px-4 py-3">{{ $b->slot->dosen->name }}</td>
                                        <td class="px-4 py-3">
                                            {{ $b->slot->tanggal->format('d M Y') }}
                                            <div class="text-xs text-gray-500">
                                                {{ \Illuminate\Support\Str::of($b->slot->jam_mulai)->limit(5,'') }}–{{ \Illuminate\Support\Str::of($b->slot->jam_selesai)->limit(5,'') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">{{ $b->topik }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-block px-2 py-1 rounded text-xs {{ $badge }}">{{ $b->status }}</span>
                                            @if ($b->status === 'rejected' && $b->alasan_tolak)
                                                <div class="text-xs text-red-700 mt-1">{{ $b->alasan_tolak }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($b->catatan)
                                                <span class="inline-block px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">ada</span>
                                            @else
                                                <span class="text-xs text-gray-400">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
