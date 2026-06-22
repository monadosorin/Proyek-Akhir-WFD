<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Dosen &amp; Kuota</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-md p-3 text-sm">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow rounded-lg overflow-hidden">
                @if ($dosens->isEmpty())
                    <p class="p-6 text-gray-500">Belum ada dosen terdaftar.</p>
                @else
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-600">
                                <tr>
                                    <th class="px-4 py-3">Nama</th>
                                    <th class="px-4 py-3">NIP</th>
                                    <th class="px-4 py-3">Prodi</th>
                                    <th class="px-4 py-3">Bimbingan Saat Ini</th>
                                    <th class="px-4 py-3">Kuota</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($dosens as $d)
                                    <tr>
                                        <td class="px-4 py-3 font-medium">{{ $d->name }}</td>
                                        <td class="px-4 py-3">{{ $d->nrp_nip ?? '—' }}</td>
                                        <td class="px-4 py-3">{{ $d->prodi ?? '—' }}</td>
                                        <td class="px-4 py-3">{{ $d->supervisees_count }}</td>
                                        <td class="px-4 py-3">
                                            <form method="POST" action="{{ route('kaprodi.quota', $d) }}" class="flex gap-2 items-center">
                                                @csrf
                                                <input type="number" name="quota" min="0" max="1000"
                                                    value="{{ $d->quota }}"
                                                    placeholder="tak terbatas"
                                                    class="w-32 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md text-sm">
                                                <button class="px-3 py-1.5 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-500">Simpan</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <ul class="md:hidden divide-y divide-gray-200">
                        @foreach ($dosens as $d)
                            <li class="p-4 space-y-2">
                                <div class="font-medium">{{ $d->name }} <span class="text-xs text-gray-500">({{ $d->nrp_nip }})</span></div>
                                <div class="text-sm text-gray-600">{{ $d->prodi ?? '—' }}</div>
                                <div class="text-sm">Bimbingan: <span class="font-medium">{{ $d->supervisees_count }}</span></div>
                                <form method="POST" action="{{ route('kaprodi.quota', $d) }}" class="flex gap-2 items-center">
                                    @csrf
                                    <input type="number" name="quota" min="0" max="1000" value="{{ $d->quota }}" placeholder="tak terbatas"
                                        class="flex-1 border-gray-300 rounded-md text-sm">
                                    <button class="px-3 py-1.5 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-500">Simpan</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
