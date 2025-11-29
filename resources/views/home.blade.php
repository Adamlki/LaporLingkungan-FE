<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-900 leading-tight">
            {{ __('Semua Laporan Warga') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-green-50 via-white to-emerald-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ✅ Pesan sukses --}}
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-lg shadow-sm flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <span class="text-green-600 cursor-pointer" onclick="this.parentElement.style.display='none';">&times;</span>
                </div>
            @endif

            {{-- Tombol Buat Laporan --}}
            @auth
                <div class="flex justify-end mb-8">
                    <a href="{{ route('laporan.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-700 text-white font-semibold rounded-full shadow-md hover:shadow-lg hover:scale-105 transition transform duration-200 ease-in-out">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Laporan Baru
                    </a>
                </div>
            @endauth

            {{-- Daftar Laporan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($laporans as $laporan)
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transform hover:-translate-y-1 transition duration-300 group">
                        
                        {{-- Foto laporan --}}
                        {{-- Foto laporan --}}
                        @if($laporan->foto)
                            <div class="overflow-hidden h-48">
                                {{-- PERBAIKAN: Tambahkan 'img' dan atribut lazy loading --}}
                                <img src="{{ asset('storage/' . $laporan->foto) }}"
                                     alt="Foto Laporan {{ $laporan->judul }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                     loading="lazy"
                                     decoding="async">
                            </div>
                        @else
                            <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400 italic">
                                <svg class="w-12 h-12 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif

                        {{-- Konten laporan --}}
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-extrabold text-xl text-gray-800 line-clamp-1" title="{{ $laporan->judul }}">{{ $laporan->judul }}</h3>
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{Str::limit($laporan->lokasi, 30)}}
                            </p>

                            {{-- Status laporan (DIPERBAIKI SESUAI DATABASE) --}}
                            @php
                                $statusColors = [
                                    'Selesai Ditangani' => 'bg-green-100 text-green-800 border border-green-200',
                                    'Diproses' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                    'Dilaporkan' => 'bg-red-100 text-red-800 border border-red-200',
                                    'default' => 'bg-gray-100 text-gray-800 border border-gray-200',
                                ];
                                $statusColor = $statusColors[$laporan->status] ?? $statusColors['default'];
                            @endphp

                            <div class="flex items-center justify-between mt-4">
                                <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full {{ $statusColor }}">
                                    {{ $laporan->status }}
                                </span>
                                <span class="text-xs text-gray-400">{{ $laporan->created_at->diffForHumans() }}</span>
                            </div>

                            <hr class="my-4 border-gray-100">

                        {{-- BATAS ATAS GANTI --}}
                        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                            
                            {{-- Info User (Kiri) --}}
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs mr-2">
                                    {{ substr($laporan->user->name ?? 'X', 0, 1) }}
                                </div>
                                <div class="text-xs">
                                    <p class="font-medium text-gray-700">
                                        {{ $laporan->user->name ?? 'User Dihapus' }}
                                    </p>
                                    <p class="text-gray-400">
                                        {{ $laporan->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            {{-- Tombol Lihat Detail (Kanan) --}}
                            <a href="{{ route('laporan.baca', $laporan->id) }}"
                               class="group flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors duration-200">
                                Lihat Detail
                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>

                        </div>
                        {{-- BATAS BAWAH GANTI --}}
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-12">
                        <div class="bg-gray-100 rounded-full p-6 mb-4">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada laporan</h3>
                        <p class="text-gray-500">Jadilah yang pertama melaporkan kondisi lingkunganmu!</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-10">
                {{ $laporans->links() }}
            </div>
        </div>
    </div>
</x-app-layout>