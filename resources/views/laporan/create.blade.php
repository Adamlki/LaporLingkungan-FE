<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Laporan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('web.laporan.store') }}" enctype="multipart/form-data">
                        @csrf
@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Ada kesalahan!</strong>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                        <div>
                            <x-input-label for="judul" :value="__('Judul Laporan')" />
                            <x-text-input id="judul" class="block mt-1 w-full" type="text" name="judul" :value="old('judul')" required autofocus />
                            <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="deskripsi" :value="__('Deskripsi')" />
                            <textarea id="deskripsi" name="deskripsi" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('deskripsi') }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="lokasi" :value="__('Lokasi Kejadian')" />
                            <x-text-input id="lokasi" class="block mt-1 w-full" type="text" name="lokasi" :value="old('lokasi')" required />
                            <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="foto" :value="__('Foto (Opsional)')" />
                            <input id="foto" class="block mt-1 w-full" type="file" name="foto">
                            <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Kirim Laporan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>