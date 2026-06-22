<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Booking</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-sm text-gray-700 mb-4 p-3 bg-gray-50 rounded border border-gray-200">
                    <div><span class="font-medium">Dosen:</span> {{ $booking->slot->dosen->name }}</div>
                    <div><span class="font-medium">Jadwal:</span> {{ $booking->slot->tanggal->format('d M Y') }}, {{ \Illuminate\Support\Str::of($booking->slot->jam_mulai)->limit(5,'') }}–{{ \Illuminate\Support\Str::of($booking->slot->jam_selesai)->limit(5,'') }}</div>
                    <div class="text-xs text-gray-500 mt-2">Jadwal tidak bisa diubah. Untuk pindah jadwal, batalkan booking ini lalu buat baru.</div>
                </div>
                <form method="POST" action="{{ route('bookings.update', $booking) }}">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="topik" value="Topik Bimbingan" />
                        <x-text-input id="topik" name="topik" type="text" class="block mt-1 w-full" :value="old('topik', $booking->topik)" required />
                        <x-input-error :messages="$errors->get('topik')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="deskripsi" value="Deskripsi" />
                        <textarea id="deskripsi" name="deskripsi" rows="4" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('deskripsi', $booking->deskripsi) }}</textarea>
                        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <a href="{{ route('bookings.mine') }}" class="px-4 py-2 text-sm bg-gray-200 rounded-md">Batal</a>
                        <x-primary-button>Simpan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
