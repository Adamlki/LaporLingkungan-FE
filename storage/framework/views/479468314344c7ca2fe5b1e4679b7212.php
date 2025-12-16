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
        <h2 class="font-bold text-3xl text-gray-900 leading-tight">
            <?php echo e(__('Semua Laporan Warga')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    
    <div class="py-12 bg-gradient-to-br from-green-50 via-white to-emerald-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            
            <div class="flex justify-end mb-8">
                <a href="<?php echo e(route('laporan.create')); ?>" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-700 text-white font-semibold rounded-full shadow-md hover:shadow-lg hover:scale-105 transition transform duration-200 ease-in-out">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Laporan Baru
                </a>
            </div>
            
            
            <div id="loading-text" class="flex flex-col items-center justify-center min-h-[50vh] text-gray-500">
                <svg class="animate-spin h-16 w-16 mb-4 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-lg font-semibold animate-pulse">Sedang mengambil data terbaru...</p>
            </div>
            
            
            <div id="laporan-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"></div>

            
            <div id="empty-state" class="hidden col-span-full flex flex-col items-center justify-center py-12">
                <div class="bg-gray-100 rounded-full p-6 mb-4">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Belum ada laporan</h3>
                <p class="text-gray-500">Jadilah yang pertama melaporkan kondisi lingkunganmu!</p>
            </div>

        </div>
    </div>

    
    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            const container = document.getElementById('laporan-container');
            const loadingText = document.getElementById('loading-text');
            const emptyState = document.getElementById('empty-state');
            
            const token = localStorage.getItem('api_token');
            const baseUrl = "https://aweless-raisa-dutiable.ngrok-free.dev"; 
            const apiUrl = `${baseUrl}/api/laporan`; 

            // Fungsi Helper untuk Waktu (Ago)
            function timeSince(date) {
                const seconds = Math.floor((new Date() - new Date(date)) / 1000);
                let interval = seconds / 31536000;
                if (interval > 1) return Math.floor(interval) + " tahun lalu";
                interval = seconds / 2592000;
                if (interval > 1) return Math.floor(interval) + " bulan lalu";
                interval = seconds / 86400;
                if (interval > 1) return Math.floor(interval) + " hari lalu";
                interval = seconds / 3600;
                if (interval > 1) return Math.floor(interval) + " jam lalu";
                interval = seconds / 60;
                if (interval > 1) return Math.floor(interval) + " menit lalu";
                return Math.floor(seconds) + " detik lalu";
            }

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
                const laporanList = result.data || result; 

                loadingText.style.display = 'none';

                if (laporanList.length === 0) {
                    emptyState.classList.remove('hidden');
                } else {
                    laporanList.forEach(laporan => {
                        
                        // Gambar
                        const fotoUrl = laporan.foto 
                            ? `${baseUrl}/storage/${laporan.foto}` 
                            : 'https://via.placeholder.com/400x300?text=No+Image';
                        
                        // Logic Warna Status (Disamakan dengan Home)
                        let statusBadge = '';
                        if(laporan.status === 'Selesai' || laporan.status === 'Selesai Ditangani') {
                            statusBadge = 'bg-green-100 text-green-800 border border-green-200';
                        } else if(laporan.status === 'Diproses') {
                            statusBadge = 'bg-yellow-100 text-yellow-800 border border-yellow-200';
                        } else {
                            statusBadge = 'bg-red-100 text-red-800 border border-red-200';
                        }

                        // Data User
                        const userName = laporan.user ? laporan.user.name : 'Anonim';
                        const userInitial = userName.charAt(0).toUpperCase();
                        const waktuLalu = timeSince(laporan.created_at);

                        // --- HTML TEMPLATE (PERSIS HOME.BLADE.PHP) ---
                        const cardHtml = `
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transform hover:-translate-y-1 transition duration-300 group flex flex-col h-full">
                                
                                <div class="h-48 overflow-hidden bg-gray-200 relative">
                                    <img src="${fotoUrl}" alt="${laporan.judul}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" loading="lazy">
                                </div>

                                <div class="p-6 flex flex-col flex-grow">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="font-extrabold text-xl text-gray-800 line-clamp-1" title="${laporan.judul}">
                                            <a href="/baca-laporan/${laporan.id}">${laporan.judul}</a>
                                        </h3>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mb-4 flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span class="truncate">${laporan.lokasi}</span>
                                    </p>

                                    <div class="flex items-center justify-between mt-4">
                                        <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full ${statusBadge}">
                                            ${laporan.status}
                                        </span>
                                        <span class="text-xs text-gray-400">${waktuLalu}</span>
                                    </div>

                                    <hr class="my-4 border-gray-100">

                                    <div class="mt-auto pt-2 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs mr-2 border border-indigo-200">
                                                ${userInitial}
                                            </div>
                                            <div class="text-xs">
                                                <p class="font-medium text-gray-700">${userName}</p>
                                                <p class="text-gray-400">${waktuLalu}</p>
                                            </div>
                                        </div>

                                        <a href="/baca-laporan/${laporan.id}" class="group flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors duration-200">
                                            Lihat Detail
                                            <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        container.innerHTML += cardHtml;
                    });
                }

            } catch (error) {
                console.error("Error:", error);
                loadingText.innerHTML = `<div class="text-center"><p class="text-red-500 font-bold">Gagal memuat data.</p><p class="text-sm text-gray-500">Cek koneksi Ngrok Backend.</p></div>`;
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