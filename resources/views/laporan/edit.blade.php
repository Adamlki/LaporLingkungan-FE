<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-900 leading-tight">
            {{ __('Edit Laporan Lingkungan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 sm:p-10 rounded-3xl shadow-2xl border border-gray-100">

                {{-- == PENTING: Tampilkan Pesan Error Validasi == --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-300 text-red-800 rounded-xl shadow-md">
                        <p class="font-bold mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Gagal Memperbarui! Mohon Periksa Input Anda:
                        </p>
                        <ul class="list-disc ml-6 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- FORM EDIT LAPORAN --}}
                <form action="{{ route('laporan.update', $laporan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT') {{-- Method spoofing untuk UPDATE --}}

                    {{-- Judul Laporan --}}
                    <div>
                        <x-input-label for="judul" value="{{ __('Judul Laporan *') }}" />
                        <x-text-input type="text" id="judul" name="judul" value="{{ old('judul', $laporan->judul) }}" required
                               class="mt-1 block w-full @error('judul') border-red-500 @enderror"
                               placeholder="Contoh: Saluran air tersumbat di Jl. Mawar"
                        />
                        @error('judul')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <x-input-label for="deskripsi" value="{{ __('Deskripsi Detail Kejadian *') }}" />
                        <textarea id="deskripsi" name="deskripsi" rows="5" required
                                  class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('deskripsi') @enderror"
                                  placeholder="Jelaskan masalah, kapan terjadi, dan mengapa ini penting..."
                        >{{ old('deskripsi', $laporan->deskripsi) }}</textarea>
                        @error('deskripsi')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Lokasi Kejadian --}}
                    <div>
                        <x-input-label for="lokasi" value="{{ __('Lokasi Kejadian *') }}" />
                        <x-text-input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $laporan->lokasi) }}" required
                               class="mt-1 block w-full @error('lokasi') border-red-500 @enderror"
                               placeholder="Contoh: Nama jalan, RT/RW, Kecamatan"
                        />
                        @error('lokasi')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Status (Dropdown) --}}
@if(Auth::user()->isAdmin())
    <div class="mt-4">
        <x-input-label for="status" :value="__('Ubah Status Laporan')" />
        <select name="status" id="status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
            <option value="Dilaporkan" @selected(old('status', $laporan->status) == 'Dilaporkan')>Dilaporkan</option>
            <option value="Diproses" @selected(old('status', $laporan->status) == 'Diproses')>Diproses</option>
            <option value="Selesai Ditangani" @selected(old('status', $laporan->status) == 'Selesai Ditangani')>Selesai Ditangani</option>
        </select>
    </div>
@endif
                    {{-- Ganti Foto (Opsional) --}}
                    <div>
                        <x-input-label for="foto" value="{{ __('Ganti Foto Bukti (Opsional)') }}" />
                        <input type="file" id="foto" name="foto"
                               class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 cursor-pointer transition duration-150" {{-- Warna tombol file diubah ke Teal --}}
                        >
                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                             Maksimal 2MB | Format: JPG, PNG, JPEG. Biarkan kosong jika tidak ingin mengganti foto.
                        </p>
                        @error('foto')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror

                        {{-- Tampilkan Foto Saat Ini --}}
                        @if($laporan->foto)
                        <div class="mt-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Foto saat ini:</p>
                            <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto saat ini" class="w-48 h-auto object-cover rounded-lg shadow-md border border-gray-200">
                        </div>
                        @endif
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="pt-6 flex justify-between items-center border-t border-gray-200 mt-8"> {{-- Border lebih jelas --}}
                        
                        {{-- Tombol Hapus (Danger Button) - Pindah ke kiri --}}
                        <div> {{-- Div pembungkus untuk Hapus --}}
                            <x-danger-button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-laporan-deletion')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Hapus Laporan
                            </x-danger-button>
                        </div>
                        
                        {{-- Tombol Update (Primary Button - Warna Teal/Emerald) --}}
                        <x-primary-button class="text-base font-bold bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 focus:ring-teal-300">
                             <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Update Laporan
                        </x-primary-button>
                    </div>

                </form>

                {{-- Modal Konfirmasi Hapus (Sangat Direkomendasikan!) --}}
                <x-modal name="confirm-laporan-deletion" focusable>
                    <form method="post" action="{{ route('laporan.destroy', $laporan->id) }}" class="p-6">
                        @csrf
                        @method('delete')

                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Konfirmasi Hapus Laporan') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Apakah Anda benar-benar yakin ingin menghapus laporan ini? Data yang dihapus tidak dapat dikembalikan.') }}
                        </p>

                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click="$dispatch('close')">
                                {{ __('Batal') }}
                            </x-secondary-button>

                            <x-danger-button class="ms-3">
                                {{ __('Ya, Hapus Laporan') }}
                            </x-danger-button>
                        </div>
                    </form>
                </x-modal>

            </div>
        </div>
    </div>
</x-app-layout>