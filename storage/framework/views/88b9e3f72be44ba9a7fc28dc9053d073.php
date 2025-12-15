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
        <h2 class="font-bold text-3xl text-gray-900 leading-tight">Detail Laporan</h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-3xl shadow-2xl border border-gray-100 relative">
                
                
                <div id="loading" class="text-center py-10">Memuat data...</div>

                
                <div id="content" class="hidden">
                    <div class="flex justify-between items-start border-b pb-4 mb-8 border-gray-200">
                        <div>
                            <h1 id="detail-judul" class="text-4xl font-extrabold text-gray-900 mb-2"></h1>
                            <span id="detail-status" class="inline-block text-sm font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-md mt-2"></span>
                        </div>

                        
                        <div class="flex space-x-3 mt-1" id="action-buttons">
                            <a id="btn-edit" href="#" class="inline-flex items-center px-5 py-2.5 bg-gray-800 border border-transparent rounded-xl font-bold text-sm text-white shadow-lg hover:bg-gray-700">EDIT</a>
                            <button id="btn-delete" class="inline-flex items-center px-4 py-2.5 bg-red-600 border border-transparent rounded-xl font-semibold text-sm text-white shadow-md hover:bg-red-700">HAPUS</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">
                        
                        <div class="lg:col-span-3">
                            <img id="detail-foto" src="" alt="Bukti" class="w-full h-auto max-h-[70vh] object-contain rounded-xl shadow-md">
                        </div>
                        
                        
                        <div class="lg:col-span-2 space-y-8">
                            <div class="bg-indigo-50 p-6 rounded-2xl border border-indigo-200 shadow-md">
                                <h3 class="text-xl font-bold text-indigo-800 mb-3 border-b border-indigo-300 pb-2">Deskripsi</h3>
                                <p id="detail-deskripsi" class="text-gray-700 leading-relaxed"></p>
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200">
                                <p class="font-bold">Lokasi: <span id="detail-lokasi" class="font-normal"></span></p>
                                <p class="font-bold mt-2">Tanggal: <span id="detail-tanggal" class="font-normal"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            // Ambil ID dari Controller (yang dikirim via view)
            const laporanId = "<?php echo e($id); ?>"; 
            const token = localStorage.getItem('api_token');
            
            // URL Ngrok
            const apiUrl = "https://aweless-raisa-dutiable.ngrok-free.dev/api/laporan/" + laporanId;
            const storageUrl = "https://aweless-raisa-dutiable.ngrok-free.dev/storage/";

            try {
                const response = await fetch(apiUrl, {
                    headers: { 
                        "Authorization": "Bearer " + token,
                        "Accept": "application/json",
                        "ngrok-skip-browser-warning": "69420"
                    }
                });

                if(!response.ok) throw new Error("Gagal load data");
                const data = await response.json();
                const laporan = data.data || data; // Jaga-jaga format response

                // Isi Data ke HTML
                document.getElementById('loading').classList.add('hidden');
                document.getElementById('content').classList.remove('hidden');

                document.getElementById('detail-judul').innerText = laporan.judul;
                document.getElementById('detail-deskripsi').innerText = laporan.deskripsi;
                document.getElementById('detail-lokasi').innerText = laporan.lokasi;
                document.getElementById('detail-tanggal').innerText = new Date(laporan.created_at).toLocaleDateString();
                document.getElementById('detail-status').innerText = laporan.status;
                document.getElementById('detail-status').className += (laporan.status === 'Selesai' ? ' bg-green-600 text-white' : ' bg-yellow-500 text-white');

                // Foto
                const fotoElem = document.getElementById('detail-foto');
                if(laporan.foto) {
                    fotoElem.src = storageUrl + laporan.foto;
                } else {
                    fotoElem.style.display = 'none';
                }

                // Setup Tombol Edit
                document.getElementById('btn-edit').href = "/laporan/" + laporanId + "/edit";

                // Setup Tombol Hapus
                document.getElementById('btn-delete').addEventListener('click', async function() {
                    if(!confirm("Yakin hapus laporan ini?")) return;

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
                            alert("Terhapus!");
                            window.location.href = "/laporan";
                        } else {
                            alert("Gagal menghapus.");
                        }
                    } catch(e) {
                        alert("Error koneksi hapus.");
                    }
                });

            } catch (error) {
                console.error(error);
                document.getElementById('loading').innerText = "Gagal memuat detail laporan.";
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
<?php endif; ?><?php /**PATH C:\laragon\www\ProjectWEB\LaporLingkungan-FE\resources\views/laporan/show.blade.php ENDPATH**/ ?>