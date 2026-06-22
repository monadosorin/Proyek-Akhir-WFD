<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Catatan Bimbingan</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-sm text-gray-700 mb-4 p-3 bg-gray-50 rounded border border-gray-200 space-y-1">
                    <div><span class="font-medium">Mahasiswa:</span> {{ $booking->mahasiswa->name }} ({{ $booking->mahasiswa->nrp_nip }})</div>
                    <div><span class="font-medium">Jadwal:</span> {{ $booking->slot->tanggal->format('d M Y') }}, {{ \Illuminate\Support\Str::of($booking->slot->jam_mulai)->limit(5,'') }}–{{ \Illuminate\Support\Str::of($booking->slot->jam_selesai)->limit(5,'') }}</div>
                    <div><span class="font-medium">Topik:</span> {{ $booking->topik }}</div>
                </div>

                <form method="POST" action="{{ route('catatan.save', $booking) }}">
                    @csrf
                    <div>
                        <x-input-label for="progress" value="Progress" />
                        <textarea id="progress" name="progress" rows="3" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('progress', $catatan?->progress) }}</textarea>
                        <x-input-error :messages="$errors->get('progress')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="catatan_dosen" value="Catatan Dosen" />
                        <textarea id="catatan_dosen" name="catatan_dosen" rows="4" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('catatan_dosen', $catatan?->catatan_dosen) }}</textarea>
                        <x-input-error :messages="$errors->get('catatan_dosen')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="tindak_lanjut" value="Tindak Lanjut (opsional)" />
                        <textarea id="tindak_lanjut" name="tindak_lanjut" rows="2"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('tindak_lanjut', $catatan?->tindak_lanjut) }}</textarea>
                        <x-input-error :messages="$errors->get('tindak_lanjut')" class="mt-2" />
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <a href="{{ route('bookings.incoming') }}" class="px-4 py-2 text-sm bg-gray-200 rounded-md">Kembali</a>
                        <x-primary-button>Simpan Catatan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
