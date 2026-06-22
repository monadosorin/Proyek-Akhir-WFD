<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Slot Bimbingan Saya</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-md p-3 text-sm">{{ session('status') }}</div>
            @endif

            <div class="flex justify-end">
                <a href="{{ route('slots.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-semibold rounded-md hover:bg-gray-700">+ Tambah Slot</a>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                @if ($slots->isEmpty())
                    <p class="p-6 text-gray-500">Belum ada slot. Klik <span class="font-medium">Tambah Slot</span> untuk membuat.</p>
                @else
                    <div class="hidden sm:block">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-600">
                                <tr>
                                    <th class="px-4 py-3">Tanggal</th>
                                    <th class="px-4 py-3">Jam</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Booking</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($slots as $slot)
                                    <tr>
                                        <td class="px-4 py-3">{{ $slot->tanggal->format('d M Y') }}</td>
                                        <td class="px-4 py-3">{{ \Illuminate\Support\Str::of($slot->jam_mulai)->limit(5,'') }}–{{ \Illuminate\Support\Str::of($slot->jam_selesai)->limit(5,'') }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-block px-2 py-1 rounded text-xs {{ ['tersedia' => 'bg-green-100 text-green-800', 'menunggu' => 'bg-yellow-100 text-yellow-800', 'terbooking' => 'bg-blue-100 text-blue-800'][$slot->status] }}">
                                                {{ $slot->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">{{ optional($slot->activeBooking?->mahasiswa)->name ?? '—' }}</td>
                                        <td class="px-4 py-3 flex gap-2">
                                            <a href="{{ route('slots.edit', $slot) }}" class="text-indigo-600 hover:underline">Edit</a>
                                            <form action="{{ route('slots.destroy', $slot) }}" method="POST" onsubmit="return confirm('Hapus slot ini?')">
                                                @csrf @method('DELETE')
                                                <button class="text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <ul class="sm:hidden divide-y divide-gray-200">
                        @foreach ($slots as $slot)
                            <li class="p-4 space-y-2">
                                <div class="flex justify-between">
                                    <span class="font-medium">{{ $slot->tanggal->format('d M Y') }}</span>
                                    <span class="text-xs px-2 py-1 rounded {{ ['tersedia' => 'bg-green-100 text-green-800', 'menunggu' => 'bg-yellow-100 text-yellow-800', 'terbooking' => 'bg-blue-100 text-blue-800'][$slot->status] }}">{{ $slot->status }}</span>
                                </div>
                                <div class="text-sm text-gray-600">{{ \Illuminate\Support\Str::of($slot->jam_mulai)->limit(5,'') }}–{{ \Illuminate\Support\Str::of($slot->jam_selesai)->limit(5,'') }}</div>
                                @if ($slot->activeBooking)
                                    <div class="text-sm text-gray-600">Oleh: {{ $slot->activeBooking->mahasiswa->name }}</div>
                                @endif
                                <div class="flex gap-3 text-sm">
                                    <a href="{{ route('slots.edit', $slot) }}" class="text-indigo-600">Edit</a>
                                    <form action="{{ route('slots.destroy', $slot) }}" method="POST" onsubmit="return confirm('Hapus slot ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600">Hapus</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
