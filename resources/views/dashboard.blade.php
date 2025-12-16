<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-900 leading-tight">
            {{ __('Dashboard Aktivitas') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 via-white to-indigo-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Pesan Selamat Datang (Disederhanakan untuk publik) --}}
            <div class="mb-10 p-6 bg-white border border-gray-200 rounded-2xl shadow-lg flex items-center space-x-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">
                        Selamat Datang di Portal Laporan Warga!
                    </p>
                    <p class="text-sm text-gray-500 mt-1">
                        Pantau kondisi lingkungan Madiun secara real-time. Mari jaga kebersihan bersama!
                    </p>
                </div>
            </div>
            
            {{-- Statistik Laporan --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                
                {{-- Kartu 1: Total Laporan --}}
                <div class="relative bg-white p-6 rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-[1.02] transition duration-300 group">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-100 rounded-full opacity-30 blur-xl"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-lg font-semibold text-gray-500 uppercase tracking-wider">Total Laporan</p>
                            <div class="p-2 bg-indigo-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <span id="stat-total" class="block text-5xl font-extrabold text-green-600 mb-2">-</span>
                        <a href="{{ route('laporan.create') }}" class="text-sm font-medium text-green-600 hover:text-green-900 flex items-center">
                            Buat Laporan Baru <span class="ml-1">&rarr;</span>
                        </a>
                    </div>
                </div>

                {{-- Kartu 2: Sedang Diproses --}}
                <div class="relative bg-white p-6 rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-[1.02] transition duration-300 group">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-yellow-100 rounded-full opacity-30 blur-xl"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-lg font-semibold text-gray-500 uppercase tracking-wider">Sedang Diproses</p>
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <span id="stat-proses" class="block text-5xl font-extrabold text-yellow-600 mb-2">-</span>
                        <p class="text-sm font-medium text-gray-500">Menunggu Tindak Lanjut</p>
                    </div>
                </div>

                {{-- Kartu 3: Laporan Selesai --}}
                <div class="relative bg-white p-6 rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-[1.02] transition duration-300 group">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-green-100 rounded-full opacity-30 blur-xl"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-lg font-semibold text-gray-500 uppercase tracking-wider">Sudah Selesai</p>
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <span id="stat-selesai" class="block text-5xl font-extrabold text-green-600 mb-2">-</span>
                        <p class="text-sm font-medium text-gray-500">Masalah Sudah Teratasi</p>
                    </div>
                </div>
            </div>

            {{-- CTA Laporkan --}}
            <div class="p-10 bg-gradient-to-r from-teal-600 to-emerald-700 rounded-3xl shadow-2xl flex flex-col md:flex-row justify-between items-center text-white space-y-6 md:space-y-0 md:space-x-8">
                <div class="text-center md:text-left max-w-2xl">
                    <h3 class="text-3xl font-extrabold mb-3">Lihat Masalah Lingkungan? Laporkan!</h3>
                    <p class="text-lg opacity-90">Setiap laporan Anda sangat berarti untuk Madiun yang lebih baik. Jangan ragu, buat laporan baru sekarang!</p>
                </div>
                
                 <a href="{{ route('laporan.create') }}" 
                    class="flex-shrink-0 inline-flex items-center px-10 py-4 bg-white border border-transparent rounded-full font-bold text-lg text-emerald-700 tracking-wide shadow-xl hover:bg-gray-100 hover:text-emerald-800 transition duration-150 transform hover:scale-[1.03] focus:ring-4 focus:ring-emerald-300">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    LAPOR SEKARANG
                </a>
            </div>
            
        </div>
    </div>

    {{-- SCRIPT PENGAMBIL DATA API --}}
    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            // URL NGROK (Pastikan Link Ini Benar)
            const baseUrl = "https://aweless-raisa-dutiable.ngrok-free.dev"; 
            const apiUrlLaporan = `${baseUrl}/api/laporan`; 

            // Header (Tanpa Token, Public Access)
            const headers = {
                "Accept": "application/json",
                "ngrok-skip-browser-warning": "69420"
            };

            // Jika user login, tambahkan token (opsional, biar backend tau siapa yg request)
            const token = localStorage.getItem('api_token');
            if (token) {
                headers["Authorization"] = "Bearer " + token;
            }

            try {
                // STEP 1: AMBIL DATA LAPORAN & HITUNG
                const responseLaporan = await fetch(apiUrlLaporan, { method: "GET", headers: headers });
                
                if (responseLaporan.ok) {
                    const resultLaporan = await responseLaporan.json();
                    
                    // Handle format data
                    const listLaporan = resultLaporan.data || resultLaporan;

                    if (Array.isArray(listLaporan)) {
                        // A. Update TOTAL
                        document.getElementById('stat-total').innerText = listLaporan.length;

                        // B. Update DIPROSES
                        const diprosesCount = listLaporan.filter(item => item.status === 'Diproses').length;
                        document.getElementById('stat-proses').innerText = diprosesCount;

                        // C. Update SELESAI
                        const selesaiCount = listLaporan.filter(item => 
                            item.status === 'Selesai' || item.status === 'Selesai Ditangani'
                        ).length;
                        document.getElementById('stat-selesai').innerText = selesaiCount;
                    }
                } else {
                    console.error("Gagal mengambil laporan:", responseLaporan.status);
                    document.getElementById('stat-total').innerText = "ERR";
                }

            } catch (error) {
                console.error("Error Koneksi:", error);
                document.getElementById('stat-total').innerText = "ERR";
            }
        });
    </script>
</x-app-layout>