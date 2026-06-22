<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dosen Pembimbing</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if ($dosens->isEmpty())
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-900 shadow rounded-lg p-6">
                    <div class="font-medium">Anda belum ditugaskan ke dosen pembimbing.</div>
                    <div class="text-sm mt-1">Silakan hubungi kaprodi prodi {{ Auth::user()->prodi ?? 'Anda' }} untuk mendapatkan penugasan dosen pembimbing.</div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($dosens as $dosen)
                        <a href="{{ route('dosen.slots', $dosen) }}" class="bg-white shadow rounded-lg p-5 hover:ring-2 hover:ring-indigo-300 transition block">
                            <div class="font-semibold text-gray-900">{{ $dosen->name }}</div>
                            <div class="text-sm text-gray-500">{{ $dosen->prodi ?? '—' }}</div>
                            <div class="text-sm text-gray-600 mt-1">NIP: {{ $dosen->nrp_nip ?? '—' }}</div>
                            <div class="mt-3 text-sm">
                                <span class="inline-block px-2 py-1 rounded {{ $dosen->slot_tersedia_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $dosen->slot_tersedia_count }} slot tersedia
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
