<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Booking Saya</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-md p-3 text-sm">{{ session('status') }}</div>
            @endif

            @if ($bookings->isEmpty())
                <div class="bg-white shadow rounded-lg p-6 text-gray-500">
                    Belum ada booking. <a href="{{ route('dosen.browse') }}" class="text-indigo-600 hover:underline">Cari dosen</a> untuk memulai.
                </div>
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
                                    <div class="font-semibold">{{ $booking->slot->dosen->name }}</div>
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
                            @if ($booking->status === 'rejected' && $booking->alasan_tolak)
                                <div class="mt-3 text-sm bg-red-50 border border-red-200 rounded p-2">
                                    <span class="font-medium">Alasan ditolak:</span> {{ $booking->alasan_tolak }}
                                </div>
                            @endif
                            @if ($booking->catatan)
                                <div class="mt-3 text-sm bg-gray-50 border border-gray-200 rounded p-3 space-y-1">
                                    <div><span class="font-medium">Progress:</span> {{ $booking->catatan->progress }}</div>
                                    <div><span class="font-medium">Catatan Dosen:</span> {{ $booking->catatan->catatan_dosen }}</div>
                                    @if ($booking->catatan->tindak_lanjut)
                                        <div><span class="font-medium">Tindak Lanjut:</span> {{ $booking->catatan->tindak_lanjut }}</div>
                                    @endif
                                </div>
                            @endif
                            @if ($booking->status === 'pending')
                                <form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Batalkan booking ini?')" class="mt-3">
                                    @csrf @method('DELETE')
                                    <button class="text-sm text-red-600 hover:underline">Batalkan booking</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
