@csrf
<div>
    <x-input-label for="tanggal" value="Tanggal" />
    <x-text-input id="tanggal" name="tanggal" type="date" class="block mt-1 w-full" :value="old('tanggal', $slot->tanggal?->format('Y-m-d'))" required />
    <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
</div>

<div class="mt-4 grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="jam_mulai" value="Jam Mulai" />
        <x-text-input id="jam_mulai" name="jam_mulai" type="time" class="block mt-1 w-full" :value="old('jam_mulai', \Illuminate\Support\Str::of($slot->jam_mulai ?? '')->limit(5,''))" required />
        <x-input-error :messages="$errors->get('jam_mulai')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="jam_selesai" value="Jam Selesai" />
        <x-text-input id="jam_selesai" name="jam_selesai" type="time" class="block mt-1 w-full" :value="old('jam_selesai', \Illuminate\Support\Str::of($slot->jam_selesai ?? '')->limit(5,''))" required />
        <x-input-error :messages="$errors->get('jam_selesai')" class="mt-2" />
    </div>
</div>

<div class="mt-4">
    <x-input-label for="keterangan" value="Keterangan (opsional)" />
    <textarea id="keterangan" name="keterangan" rows="3"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('keterangan', $slot->keterangan) }}</textarea>
    <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
</div>

<div class="flex justify-end gap-2 mt-6">
    <a href="{{ route('slots.index') }}" class="px-4 py-2 text-sm bg-gray-200 rounded-md">Batal</a>
    <x-primary-button>{{ $submitLabel }}</x-primary-button>
</div>
