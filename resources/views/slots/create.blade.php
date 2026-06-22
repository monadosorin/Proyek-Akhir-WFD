<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Slot Bimbingan</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('slots.store') }}">
                    @include('slots._form', ['slot' => new \App\Models\Slot, 'submitLabel' => 'Simpan'])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
