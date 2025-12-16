<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-900 leading-tight">
            {{ __('Detail Laporan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Loading State --}}
            <div id="loading-state" class="text-center py-20">
                <svg class="animate-spin h-12 w-12 text-indigo-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-500 font-medium">Sedang mengambil detail laporan...</p>
            </div>

            {{-- KONTEN UTAMA (Hidden dulu sampai data siap) --}}
            <div id="main-content" class="hidden bg-white p-8 rounded-3xl shadow-2xl border border-gray-100">

                {{-- Bagian 1: Header Laporan dan Aksi --}}
                <div class="flex flex-col md:flex-row justify-between items-start border-b pb-4 mb-8 border-gray-200">
                    <div>
                        {{-- Judul --}}
                        <h1 id="detail-judul" class="text-4xl font-extrabold text-gray-900 mb-2"></h1>
                        
                        {{-- Status Badge --}}
                        <span id="detail-status" class="inline-block text-sm font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-md mt-2"></span>
                    </div>

                    {{-- Tombol Aksi (Edit/Hapus) --}}
                    <div id="action-buttons" class="flex space-x-3 mt-4 md:mt-0 hidden">
                        {{-- Edit --}}
                        <a id="btn-edit" href="#" class="inline-flex items-center px-5 py-2.5 bg-gray-800 border border-transparent rounded-xl font-bold text-sm text-white shadow-lg hover:bg-gray-700 transition duration-150 transform hover:scale-[1.01]">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            EDIT LAPORAN
                        </a>
                        
                        {{-- Hapus --}}
                        <button id="btn-delete" type="button" class="inline-flex items-center px-4 py-2.5 bg-red-600 border border-transparent rounded-xl font-semibold text-sm text-white shadow-md hover:bg-red-700 transition duration-150">
                            HAPUS
                        </button>
                    </div>
                </div>

                {{-- Bagian 2: Grid Konten --}}
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">
                    
                    {{-- Kolom Kiri: Foto Bukti (3/5 Lebar) --}}
                    <div class="lg:col-span-3">
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Foto Bukti Kejadian</h3>
                        <div class="relative bg-gray-100 p-3 rounded-2xl shadow-xl border border-gray-200">
                            
                            {{-- Container Foto Ada --}}
                            <img id="detail-foto" src="" alt="Bukti Foto" class="w-full h-auto max-h-[70vh] object-contain rounded-xl hidden">
                            
                            {{-- Container Foto Kosong --}}
                            <div id="no-foto" class="w-full h-96 bg-gray-200 flex flex-col items-center justify-center text-gray-500 text-xl font-semibold rounded-xl hidden">
                                TIDAK ADA FOTO BUKTI
                            </div>

                            <p class="mt-3 text-sm text-gray-500 text-center">Tampilan foto asli dari lokasi kejadian.</p>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Detail Deskripsi & Metadata (2/5 Lebar) --}}
                    <div class="lg:col-span-2 space-y-8">

                        {{-- Card Deskripsi --}}
                        <div class="bg-indigo-50 p-6 rounded-2xl border border-indigo-200 shadow-md">
                            <h3 class="text-xl font-bold text-indigo-800 mb-3 border-b border-indigo-300 pb-2">Deskripsi Kejadian</h3>
                            <p id="detail-deskripsi" class="text-gray-700 leading-relaxed"></p>
                        </div>
                        
                        {{-- Card Metadata --}}
                        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Informasi Tambahan</h3>
                            
                            <div class="space-y-4">
                                {{-- Lokasi --}}
                                <div class="flex items-center text-gray-700 border-b pb-3">
                                    <svg class="w-6 h-6 mr-3 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                    <div>
                                        <span class="font-bold text-sm block">Lokasi:</span>
                                        <span id="detail-lokasi" class="text-base"></span>
                                    </div>
                                </div>

                                {{-- Pelapor --}}
                                <div class="flex items-center text-gray-700 border-b pb-3">
                                    <svg class="w-6 h-6 mr-3 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                    <div>
                                        <span class="font-bold text-sm block">Pelapor:</span>
                                        <span id="detail-pelapor" class="text-base"></span>
                                    </div>
                                </div>
                                
                                {{-- Waktu --}}
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-6 h-6 mr-3 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h-2V9h4v8z"/></svg>
                                    <div>
                                        <span class="font-bold text-sm block">Waktu Laporan:</span>
                                        <span id="detail-waktu" class="text-base"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol Kembali --}}
                <div class="pt-6 border-t border-gray-200 flex justify-start mt-8">
                    <a href="{{ route('laporan.index') }}" class="inline-flex items-center px-6 py-2.5 border border-gray-400 rounded-xl shadow-md text-base font-medium text-gray-800 hover:bg-gray-100 transition duration-150 transform hover:scale-[1.01]">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Daftar Laporan
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- SCRIPT JAVASCRIPT (PERBAIKAN LOGIKA ID) --}}
    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            // --- PERBAIKAN: Ambil ID dari URL Browser secara Manual ---
            // URL browser biasanya: localhost:8000/baca-laporan/123
            // .split('/') akan menghasilkan array: ["", "baca-laporan", "123"]
            // Jadi ID ada di index ke-2
            const pathArray = window.location.pathname.split('/');
            const laporanId = pathArray[2]; // Pastikan ini mengambil angka ID

            console.log("ID Laporan Terdeteksi:", laporanId);

            const token = localStorage.getItem('api_token');
            const baseUrl = "https://aweless-raisa-dutiable.ngrok-free.dev";
            const apiUrl = `${baseUrl}/api/laporan/${laporanId}`;

            const loadingState = document.getElementById('loading-state');
            const mainContent = document.getElementById('main-content');

            try {
                const response = await fetch(apiUrl, {
                    method: "GET",
                    headers: {
                        "Authorization": "Bearer " + token,
                        "Accept": "application/json",
                        "ngrok-skip-browser-warning": "69420"
                    }
                });

                if(!response.ok) throw new Error("Gagal mengambil data");

                const result = await response.json();
                const laporan = result.data || result;

                // --- 1. ISI DATA KE TAMPILAN ---
                
                // Judul & Deskripsi
                document.getElementById('detail-judul').innerText = laporan.judul;
                document.getElementById('detail-deskripsi').innerText = laporan.deskripsi;
                
                // Status dengan Warna Khusus
                const statusBadge = document.getElementById('detail-status');
                statusBadge.innerText = `STATUS: ${laporan.status}`;
                
                // Logika Warna Status (Sama seperti desain lama)
                let statusClass = "bg-gray-600 text-white"; // Default
                if(laporan.status === 'Selesai' || laporan.status === 'Selesai Ditangani') {
                    statusClass = "bg-green-600 text-white";
                } else if (laporan.status === 'Diproses') {
                    statusClass = "bg-yellow-500 text-gray-900";
                } else if (laporan.status === 'Dilaporkan') {
                    statusClass = "bg-red-600 text-white";
                }
                statusBadge.className += " " + statusClass;

                // Metadata
                document.getElementById('detail-lokasi').innerText = laporan.lokasi;
                document.getElementById('detail-pelapor').innerText = laporan.user ? laporan.user.name : 'Anonim';
                
                // Format Tanggal
                const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                document.getElementById('detail-waktu').innerText = new Date(laporan.created_at).toLocaleDateString('id-ID', dateOptions);

                // Foto Logic
                const imgElem = document.getElementById('detail-foto');
                const noImgElem = document.getElementById('no-foto');
                
                if(laporan.foto) {
                    imgElem.src = `${baseUrl}/storage/${laporan.foto}`;
                    imgElem.classList.remove('hidden');
                } else {
                    noImgElem.classList.remove('hidden');
                }

                // --- 2. TOMBOL AKSI (EDIT/HAPUS) ---
                document.getElementById('action-buttons').classList.remove('hidden');
                
                // Set Link Edit (Arahkan ke route edit local)
                document.getElementById('btn-edit').href = `/laporan/${laporanId}/edit`;

                // Set Fungsi Delete
                document.getElementById('btn-delete').addEventListener('click', async function() {
                    if(!confirm("ANDA YAKIN INGIN MENGHAPUS LAPORAN INI? Aksi ini tidak dapat dibatalkan.")) return;

                    try {
                        const resDel = await fetch(apiUrl, {
                            method: "DELETE",
                            headers: {
                                "Authorization": "Bearer " + token,
                                "Accept": "application/json",
                                "ngrok-skip-browser-warning": "69420"
                            }
                        });

                        if(resDel.ok) {
                            alert("Laporan berhasil dihapus!");
                            window.location.href = "/laporan";
                        } else {
                            alert("Gagal menghapus laporan. Pastikan Anda login sebagai pemilik laporan.");
                        }
                    } catch(e) {
                        alert("Terjadi kesalahan koneksi saat menghapus.");
                    }
                });

                // Tampilkan Konten, Sembunyikan Loading
                loadingState.classList.add('hidden');
                mainContent.classList.remove('hidden');

            } catch (error) {
                console.error(error);
                loadingState.innerHTML = `<p class="text-red-500 font-bold text-xl">Gagal memuat detail laporan.</p><p class="text-gray-500">Kemungkinan ID Laporan salah atau koneksi Ngrok putus.</p>`;
            }
        });
    </script>
</x-app-layout>