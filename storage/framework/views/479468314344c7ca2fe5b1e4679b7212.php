<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Daftar Laporan Warga')); ?>

            </h2>
            
            <a href="<?php echo e(route('laporan.create')); ?>" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-indigo-700 transition shadow-md">
                + Buat Laporan Baru
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
            <div id="loading-text" class="text-center text-gray-500 py-20">
                <svg class="animate-spin h-10 w-10 mx-auto text-indigo-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-lg font-medium">Sedang menghubungi server...</p>
            </div>
            
            
            <div id="laporan-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"></div>

            
            <div id="empty-state" class="hidden text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100">
                <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada laporan</h3>
                <p class="mt-2 text-gray-500">Jadilah yang pertama melaporkan masalah lingkungan di sekitarmu.</p>
                <div class="mt-6">
                    <a href="<?php echo e(route('laporan.create')); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg transition transform hover:scale-105">
                        Buat Laporan Sekarang
                    </a>
                </div>
            </div>

        </div>
    </div>

    
    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            const container = document.getElementById('laporan-container');
            const loadingText = document.getElementById('loading-text');
            const emptyState = document.getElementById('empty-state');
            
            // Ambil token dari penyimpanan browser
            const token = localStorage.getItem('api_token');

            // --- KONFIGURASI URL (Sesuaikan dengan Ngrok Temanmu) ---
            const apiUrl = "https://aweless-raisa-dutiable.ngrok-free.dev/api/laporan"; 
            const storageUrl = "https://aweless-raisa-dutiable.ngrok-free.dev/storage/"; 

            try {
                // Tembak API Backend
                const response = await fetch(apiUrl, {
                    method: "GET",
                    headers: {
                        "Authorization": "Bearer " + token,
                        "Accept": "application/json",
                        "ngrok-skip-browser-warning": "69420" // Mantra Anti Blokir Ngrok
                    }
                });

                if (!response.ok) throw new Error("Gagal mengambil data");

                const result = await response.json();
                
                // Ambil array datanya (kadang dibungkus 'data', kadang langsung array)
                const laporanList = result.data || result; 

                // Sembunyikan Loading
                loadingText.style.display = 'none';

                if (laporanList.length === 0) {
                    // Kalau data kosong, tampilkan pesan kosong
                    emptyState.classList.remove('hidden');
                } else {
                    // Kalau ada data, Loop dan buat kartu HTML
                    laporanList.forEach(laporan => {
                        
                        // Cek apakah ada foto? Kalau null pakai gambar default
                        const fotoUrl = laporan.foto 
                            ? storageUrl + laporan.foto 
                            : 'https://via.placeholder.com/400x300?text=No+Image';
                        
                        // Tentukan Warna Status
                        let statusBadge = '<span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-600 border border-gray-200">Dilaporkan</span>';
                        
                        if(laporan.status === 'Diproses') {
                            statusBadge = '<span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700 border border-yellow-200 flex items-center"><span class="w-2 h-2 bg-yellow-500 rounded-full mr-1"></span> Diproses</span>';
                        } else if(laporan.status === 'Selesai' || laporan.status === 'Selesai Ditangani') {
                            statusBadge = '<span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700 border border-green-200 flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span> Selesai</span>';
                        }

                        // Buat Tanggal yang enak dibaca
                        const tanggal = new Date(laporan.created_at).toLocaleDateString('id-ID', {
                            day: 'numeric', month: 'long', year: 'numeric'
                        });

                        // Template HTML Kartu
                        const cardHtml = `
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition duration-300 flex flex-col h-full group">
                                <div class="h-56 w-full bg-gray-200 relative overflow-hidden">
                                    <img src="${fotoUrl}" alt="Bukti Foto" class="w-full h-full object-cover transform transition duration-700 group-hover:scale-110">
                                    <div class="absolute top-3 right-3">
                                        ${statusBadge}
                                    </div>
                                </div>
                                <div class="p-6 flex flex-col flex-grow">
                                    <div class="flex items-center text-xs text-gray-400 mb-3">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        ${tanggal}
                                    </div>
                                    
                                    <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-1 hover:text-indigo-600 transition">
                                        <a href="/baca-laporan/${laporan.id}">${laporan.judul}</a>
                                    </h3>
                                    
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2 flex-grow">${laporan.deskripsi}</p>
                                    
                                    <div class="pt-4 border-t border-gray-50 flex items-center justify-between mt-auto">
                                        <div class="flex items-center text-xs text-gray-500 font-medium bg-gray-50 px-2 py-1 rounded-md">
                                            <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <span class="truncate max-w-[120px]">${laporan.lokasi}</span>
                                        </div>
                                        <a href="/baca-laporan/${laporan.id}" class="text-indigo-600 hover:text-indigo-800 text-sm font-bold flex items-center group-hover:underline">
                                            Lihat Detail 
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        // Masukkan kartu ke container
                        container.innerHTML += cardHtml;
                    });
                }

            } catch (error) {
                console.error("Error:", error);
                loadingText.innerHTML = `<div class="text-red-500 font-semibold bg-red-50 p-4 rounded-lg">
                    Gagal memuat data laporan.<br>
                    <span class="text-sm font-normal text-red-400">Pastikan Backend/Ngrok Nyala & Internet Stabil.</span>
                </div>`;
            }
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\ProjectWEB\LaporLingkungan-FE\resources\views/laporan/index.blade.php ENDPATH**/ ?>