<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Booking Masuk</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-md p-3 text-sm">{{ session('status') }}</div>
            @endif

            @if ($bookings->isEmpty())
                <div class="bg-white shadow rounded-lg p-6 text-gray-500">Belum ada booking masuk.</div>
            @else
                <div class="grid grid-cols-1 gap-4">
                    @foreach ($bookings as $booking)
                        @php
                            $badge = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'approved' => 'bg-blue-100 text-blue-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'done' => 'bg-green-100 text-green-800',
                            ][$booking->status];
                        @endphp
                        <div class="bg-white shadow rounded-lg p-5">
                            <div class="flex flex-wrap justify-between items-start gap-2">
                                <div>
                                    <div class="font-semibold">{{ $booking->mahasiswa->name }} <span class="text-sm text-gray-500">({{ $booking->mahasiswa->nrp_nip }})</span></div>
                                    <div class="text-sm text-gray-600">
                                        {{ $booking->slot->tanggal->format('d M Y') }} ·
                                        {{ \Illuminate\Support\Str::of($booking->slot->jam_mulai)->limit(5,'') }}–{{ \Illuminate\Support\Str::of($booking->slot->jam_selesai)->limit(5,'') }}
                                    </div>
                                </div>
                                <span class="inline-block px-2 py-1 rounded text-xs {{ $badge }}">{{ $booking->status }}</span>
                            </div>

                            <div class="mt-3 text-sm">
                                <div><span class="font-medium">Topik:</span> {{ $booking->topik }}</div>
                                <div class="text-gray-600">{{ $booking->deskripsi }}</div>
                            </div>

                            @if ($booking->status === 'pending')
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <form method="POST" action="{{ route('bookings.approve', $booking) }}">
                                        @csrf
                                        <button class="px-3 py-1.5 text-sm bg-green-600 text-white rounded hover:bg-green-500">Setujui</button>
                                    </form>
                                    <form method="POST" action="{{ route('bookings.reject', $booking) }}" class="flex flex-wrap gap-2 flex-1" onsubmit="return this.alasan_tolak.value.trim().length > 0">
                                        @csrf
                                        <input name="alasan_tolak" placeholder="Alasan menolak..." required
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded text-sm flex-1 min-w-0">
                                        <button class="px-3 py-1.5 text-sm bg-red-600 text-white rounded hover:bg-red-500">Tolak</button>
                                    </form>
                                </div>
                            @elseif ($booking->status === 'approved')
                                <div class="mt-4">
                                    <a href="{{ route('catatan.edit', $booking) }}"
                                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold bg-green-600 text-white rounded-md hover:bg-green-500">
                                        ✓ Bimbingan Selesai
                                    </a>
                                    <p class="text-xs text-gray-500 mt-1">Klik setelah sesi selesai untuk mengisi catatan bimbingan.</p>
                                </div>
                            @elseif ($booking->status === 'done')
                                <div class="mt-4">
                                    <a href="{{ route('catatan.edit', $booking) }}"
                                       class="inline-flex items-center gap-2 px-3 py-1.5 text-sm border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                                        Lihat / Edit Catatan
                                    </a>
                                </div>
                            @elseif ($booking->status === 'rejected' && $booking->alasan_tolak)
                                <div class="mt-3 text-sm text-red-700">Alasan ditolak: {{ $booking->alasan_tolak }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
