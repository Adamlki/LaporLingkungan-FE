<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard (Semua Laporan)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Daftar Laporan Masuk</h3>
                    
                    {{-- Loading Indicator --}}
                    <div id="loading-indicator" class="text-center py-10">
                        <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-gray-500 text-sm">Sedang mengambil data dari server...</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 hidden" id="main-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Laporan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="laporan-tbody" class="bg-white divide-y divide-gray-200">
                                {{-- DATA AKAN DIISI OLEH JAVASCRIPT --}}
                            </tbody>
                        </table>

                        {{-- State Kosong --}}
                        <div id="empty-state" class="hidden text-center py-8 text-gray-500">
                            Belum ada laporan masuk.
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT API --}}
    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            const token = localStorage.getItem('api_token');
            const baseUrl = "https://aweless-raisa-dutiable.ngrok-free.dev";
            const apiUrl = `${baseUrl}/api/laporan`;

            const loadingIndicator = document.getElementById('loading-indicator');
            const mainTable = document.getElementById('main-table');
            const tbody = document.getElementById('laporan-tbody');
            const emptyState = document.getElementById('empty-state');

            try {
                const response = await fetch(apiUrl, {
                    method: "GET",
                    headers: {
                        "Authorization": "Bearer " + token,
                        "Accept": "application/json",
                        "ngrok-skip-browser-warning": "69420"
                    }
                });

                if (!response.ok) throw new Error("Gagal mengambil data");

                const result = await response.json();
                const laporans = result.data || result; // Jaga-jaga struktur response

                loadingIndicator.classList.add('hidden');

                if (laporans.length === 0) {
                    emptyState.classList.remove('hidden');
                } else {
                    mainTable.classList.remove('hidden');
                    
                    laporans.forEach(laporan => {
                        // 1. Logika Warna Status (Manual JS pengganti Blade Ternary)
                        let statusClass = 'bg-gray-100 text-gray-800'; // Default
                        
                        if (laporan.status === 'Dilaporkan') {
                            statusClass = 'bg-red-100 text-red-800';
                        } else if (laporan.status === 'Diproses') {
                            statusClass = 'bg-yellow-100 text-yellow-800';
                        } else if (laporan.status === 'Selesai Ditangani' || laporan.status === 'Selesai') {
                            statusClass = 'bg-green-100 text-green-800';
                        }

                        // 2. Nama User
                        const userName = laporan.user ? laporan.user.name : 'User Dihapus';

                        // 3. Link Edit (Arahkan ke route lokal /laporan/{id}/edit)
                        const editUrl = `/laporan/${laporan.id}/edit`;

                        // 4. Render HTML Baris Tabel
                        const rowHtml = `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${laporan.judul}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${userName}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                        ${laporan.status}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="${editUrl}" class="text-indigo-600 hover:text-indigo-900 font-bold">
                                        Tindak Lanjuti
                                    </a>
                                </td>
                            </tr>
                        `;
                        
                        tbody.innerHTML += rowHtml;
                    });
                }

            } catch (error) {
                console.error(error);
                loadingIndicator.innerHTML = `<p class="text-red-500">Gagal memuat data. Cek koneksi Ngrok.</p>`;
            }
        });
    </script>
</x-app-layout>