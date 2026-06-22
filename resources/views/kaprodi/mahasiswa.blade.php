<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Penugasan Dosen Pembimbing</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-md p-3 text-sm">{{ session('status') }}</div>
            @endif

            <form method="GET" action="{{ route('kaprodi.mahasiswa') }}" class="flex gap-2">
                <input name="search" value="{{ request('search') }}" placeholder="Cari nama atau NRP..."
                    class="flex-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <button class="px-4 py-2 text-sm bg-gray-800 text-white rounded-md hover:bg-gray-700">Cari</button>
                @if (request('search'))
                    <a href="{{ route('kaprodi.mahasiswa') }}" class="px-4 py-2 text-sm bg-gray-200 rounded-md hover:bg-gray-300">Reset</a>
                @endif
            </form>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                @if ($mahasiswas->isEmpty())
                    <p class="p-6 text-gray-500">Tidak ada mahasiswa ditemukan.</p>
                @else
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-600">
                                <tr>
                                    <th class="px-4 py-3">Nama</th>
                                    <th class="px-4 py-3">NRP</th>
                                    <th class="px-4 py-3">Prodi</th>
                                    <th class="px-4 py-3">Pembimbing</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($mahasiswas as $m)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $m->name }}</td>
                                        <td class="px-4 py-3">{{ $m->nrp_nip ?? '—' }}</td>
                                        <td class="px-4 py-3">{{ $m->prodi ?? '—' }}</td>
                                        <td class="px-4 py-3">{{ $m->dosenPembimbing?->name ?? '—' }}</td>
                                        <td class="px-4 py-3">
                                            <form method="POST" action="{{ route('kaprodi.assign', $m) }}" class="flex flex-wrap gap-2 items-center">
                                                @csrf
                                                <select name="dosen_pembimbing_id" required
                                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md text-sm">
                                                    <option value="">— pilih dosen —</option>
                                                    @foreach ($dosens as $d)
                                                        @php $full = $d->quota !== null && $d->supervisees_count >= $d->quota; @endphp
                                                        <option value="{{ $d->id }}"
                                                            {{ $m->dosen_pembimbing_id === $d->id ? 'selected' : '' }}
                                                            {{ $full && $m->dosen_pembimbing_id !== $d->id ? 'disabled' : '' }}>
                                                            {{ $d->name }}
                                                            ({{ $d->supervisees_count }}{{ $d->quota !== null ? '/'.$d->quota : '' }})
                                                            {{ $full && $m->dosen_pembimbing_id !== $d->id ? '— penuh' : '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button class="px-3 py-1.5 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-500">Tugaskan</button>
                                            </form>
                                            @if ($m->dosen_pembimbing_id)
                                                <form method="POST" action="{{ route('kaprodi.unassign', $m) }}" class="mt-2" onsubmit="return confirm('Lepas penugasan ini?')">
                                                    @csrf
                                                    <button class="text-xs text-red-600 hover:underline">Lepas penugasan</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <ul class="md:hidden divide-y divide-gray-200">
                        @foreach ($mahasiswas as $m)
                            <li class="p-4 space-y-2">
                                <div class="font-medium">{{ $m->name }} <span class="text-xs text-gray-500">({{ $m->nrp_nip }})</span></div>
                                <div class="text-sm text-gray-600">{{ $m->prodi ?? '—' }}</div>
                                <div class="text-sm">Pembimbing: <span class="font-medium">{{ $m->dosenPembimbing?->name ?? 'Belum ditugaskan' }}</span></div>
                                <form method="POST" action="{{ route('kaprodi.assign', $m) }}" class="flex flex-col gap-2">
                                    @csrf
                                    <select name="dosen_pembimbing_id" required class="border-gray-300 rounded-md text-sm">
                                        <option value="">— pilih dosen —</option>
                                        @foreach ($dosens as $d)
                                            @php $full = $d->quota !== null && $d->supervisees_count >= $d->quota; @endphp
                                            <option value="{{ $d->id }}"
                                                {{ $m->dosen_pembimbing_id === $d->id ? 'selected' : '' }}
                                                {{ $full && $m->dosen_pembimbing_id !== $d->id ? 'disabled' : '' }}>
                                                {{ $d->name }} ({{ $d->supervisees_count }}{{ $d->quota !== null ? '/'.$d->quota : '' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="px-3 py-1.5 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-500">Tugaskan</button>
                                </form>
                                @if ($m->dosen_pembimbing_id)
                                    <form method="POST" action="{{ route('kaprodi.unassign', $m) }}" onsubmit="return confirm('Lepas penugasan ini?')">
                                        @csrf
                                        <button class="text-xs text-red-600 hover:underline">Lepas penugasan</button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
