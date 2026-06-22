<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Slot Tersedia – {{ $dosen->name }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('dosen.browse') }}" class="text-sm text-indigo-600 hover:underline">&larr; Kembali ke daftar dosen</a>

            @if ($slots->isEmpty())
                <div class="mt-4 bg-white shadow rounded-lg p-6 text-gray-500">Tidak ada slot tersedia saat ini.</div>
            @else
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($slots as $slot)
                        <div class="bg-white shadow rounded-lg p-5 flex flex-col gap-2">
                            <div class="font-semibold">{{ $slot->tanggal->format('l, d M Y') }}</div>
                            <div class="text-sm text-gray-600">{{ \Illuminate\Support\Str::of($slot->jam_mulai)->limit(5,'') }}–{{ \Illuminate\Support\Str::of($slot->jam_selesai)->limit(5,'') }}</div>
                            @if ($slot->keterangan)
                                <div class="text-sm text-gray-500 italic">{{ $slot->keterangan }}</div>
                            @endif
                            <a href="{{ route('bookings.create', ['slot_id' => $slot->id]) }}" class="mt-2 inline-flex justify-center items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-500">Booking</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
