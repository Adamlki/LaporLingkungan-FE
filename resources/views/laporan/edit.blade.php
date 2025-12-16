<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-900 leading-tight">
            {{ __('Edit Laporan Lingkungan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 sm:p-10 rounded-3xl shadow-2xl border border-gray-100 relative">

                {{-- Loading Indicator --}}
                <div id="loading-state" class="absolute inset-0 bg-white z-10 flex flex-col items-center justify-center rounded-3xl">
                    <svg class="animate-spin h-12 w-12 text-teal-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-500 font-medium">Mengambil data laporan...</p>
                </div>

                {{-- KOTAK ERROR (Muncul jika ada error 422 dari API) --}}
                <div id="error-box" class="hidden mb-6 p-4 bg-red-50 border border-red-300 text-red-800 rounded-xl shadow-md">
                    <p class="font-bold mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Gagal Memperbarui!
                    </p>
                    <ul id="error-list" class="list-disc ml-6 text-sm"></ul>
                </div>

                {{-- FORM EDIT LAPORAN --}}
                {{-- Kita hapus action PHP, ganti jadi id="edit-form" untuk JS --}}
                <form id="edit-form" class="space-y-6 hidden">
                    @csrf
                    
                    {{-- Judul Laporan --}}
                    <div>
                        <x-input-label for="judul" value="{{ __('Judul Laporan *') }}" />
                        <x-text-input type="text" id="judul" name="judul" required
                               class="mt-1 block w-full placeholder-gray-400"
                               placeholder="Contoh: Saluran air tersumbat di Jl. Mawar"
                        />
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <x-input-label for="deskripsi" value="{{ __('Deskripsi Detail Kejadian *') }}" />
                        <textarea id="deskripsi" name="deskripsi" rows="5" required
                                  class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400"
                                  placeholder="Jelaskan masalah, kapan terjadi, dan mengapa ini penting..."
                        ></textarea>
                    </div>

                    {{-- Lokasi Kejadian --}}
                    <div>
                        <x-input-label for="lokasi" value="{{ __('Lokasi Kejadian *') }}" />
                        <x-text-input type="text" id="lokasi" name="lokasi" required
                               class="mt-1 block w-full placeholder-gray-400"
                               placeholder="Contoh: Nama jalan, RT/RW, Kecamatan"
                        />
                    </div>

                    {{-- Status (Dropdown) --}}
                    {{-- Kita tampilkan saja, nanti JS yang isi value-nya --}}
                    <div id="status-wrapper" class="hidden mt-4">
                        <x-input-label for="status" :value="__('Status Laporan')" />
                        <select name="status" id="status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="Dilaporkan">Dilaporkan</option>
                            <option value="Diproses">Diproses</option>
                            <option value="Selesai Ditangani">Selesai Ditangani</option>
                        </select>
                    </div>

                    {{-- Ganti Foto (Opsional) --}}
                    <div>
                        <x-input-label for="foto" value="{{ __('Ganti Foto Bukti (Opsional)') }}" />
                        
                        {{-- Preview Foto Lama --}}
                        <div id="preview-container" class="mb-3 hidden">
                            <p class="text-sm font-medium text-gray-700 mb-2">Foto saat ini:</p>
                            <img id="foto-lama" src="" alt="Foto Lama" class="w-48 h-auto object-cover rounded-lg shadow-md border border-gray-200">
                        </div>

                        <input type="file" id="foto" name="foto"
                               class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 cursor-pointer transition duration-150"
                        >
                        <p class="mt-2 text-xs text-gray-500">
                             Maksimal 2MB | Format: JPG, PNG, JPEG. Biarkan kosong jika tidak ingin mengganti.
                        </p>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="pt-6 flex justify-between items-center border-t border-gray-200 mt-8">
                        
                        {{-- Tombol Hapus --}}
                        <button type="button" id="btn-delete-trigger" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Hapus Laporan
                        </button>
                        
                        {{-- Tombol Update --}}
                        <button type="submit" id="btn-submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-teal-600 to-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-teal-700 hover:to-emerald-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT API & LOGIKA (Pengganti Controller PHP) --}}
    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            // 1. Ambil ID Laporan dari URL Browser
            const pathArray = window.location.pathname.split('/');
            const laporanId = pathArray[2]; // Index ke-2 adalah ID (misal: /laporan/11/edit)

            const token = localStorage.getItem('api_token');
            const baseUrl = "https://aweless-raisa-dutiable.ngrok-free.dev";
            const apiUrl = `${baseUrl}/api/laporan/${laporanId}`;

            // Elemen HTML
            const loadingState = document.getElementById('loading-state');
            const editForm = document.getElementById('edit-form');
            const errorBox = document.getElementById('error-box');
            const errorList = document.getElementById('error-list');
            const btnSubmit = document.getElementById('btn-submit');

            // --- FUNGSI 1: LOAD DATA LAMA (GET) ---
            try {
                const response = await fetch(apiUrl, {
                    method: "GET",
                    headers: {
                        "Authorization": "Bearer " + token,
                        "Accept": "application/json",
                        "ngrok-skip-browser-warning": "69420"
                    }
                });

                if(!response.ok) throw new Error("Gagal load data");

                const result = await response.json();
                const data = result.data || result;

                // Isi Form dengan data API
                document.getElementById('judul').value = data.judul;
                document.getElementById('deskripsi').value = data.deskripsi;
                document.getElementById('lokasi').value = data.lokasi;
                
                // Set Status & Tampilkan dropdown jika perlu
                const statusSelect = document.getElementById('status');
                if(statusSelect) {
                     document.getElementById('status-wrapper').classList.remove('hidden');
                     statusSelect.value = data.status;
                }

                // Tampilkan Foto Lama
                if(data.foto) {
                    document.getElementById('preview-container').classList.remove('hidden');
                    document.getElementById('foto-lama').src = `${baseUrl}/storage/${data.foto}`;
                }

                // Buka Form
                loadingState.classList.add('hidden');
                editForm.classList.remove('hidden');

            } catch (error) {
                console.error(error);
                loadingState.innerHTML = `<p class="text-red-500 font-bold">Gagal memuat data. Pastikan ID benar & koneksi Ngrok aktif.</p>`;
            }

            // --- FUNGSI 2: UPDATE DATA (POST method PUT) ---
            editForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Reset Error Box
                errorBox.classList.add('hidden');
                errorList.innerHTML = '';
                btnSubmit.innerText = "Menyimpan...";
                btnSubmit.disabled = true;

                const formData = new FormData(e.target);
                formData.append('_method', 'PUT'); // Trick Laravel API

                try {
                    const resUpdate = await fetch(apiUrl, {
                        method: "POST", 
                        headers: {
                            "Authorization": "Bearer " + token,
                            "Accept": "application/json",
                            "ngrok-skip-browser-warning": "69420"
                        },
                        body: formData
                    });

                    const result = await resUpdate.json();

                    if(resUpdate.ok) {
                        alert("✅ Data Berhasil Diperbarui!");
                        window.location.href = `/baca-laporan/${laporanId}`; // Redirect ke detail
                    } else {
                        // === MENANGANI ERROR 422 (VALIDASI) ===
                        errorBox.classList.remove('hidden');
                        
                        if (result.errors) {
                            // Loop semua error validasi dari Laravel Backend
                            for (const [key, messages] of Object.entries(result.errors)) {
                                messages.forEach(msg => {
                                    const li = document.createElement('li');
                                    li.innerText = msg;
                                    errorList.appendChild(li);
                                });
                            }
                        } else {
                            const li = document.createElement('li');
                            li.innerText = result.message || "Terjadi kesalahan server.";
                            errorList.appendChild(li);
                        }
                        
                        // Scroll ke atas agar user lihat error
                        window.scrollTo(0,0);
                    }
                } catch(err) {
                    alert("Error koneksi.");
                } finally {
                    btnSubmit.innerHTML = `<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg> Simpan Perubahan`;
                    btnSubmit.disabled = false;
                }
            });

            // --- FUNGSI 3: DELETE DATA ---
            document.getElementById('btn-delete-trigger').addEventListener('click', async function() {
                if(!confirm("YAKIN HAPUS LAPORAN INI? Data tidak bisa dikembalikan.")) return;

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
                        alert("Laporan Terhapus.");
                        window.location.href = "/laporan";
                    } else {
                        alert("Gagal menghapus. Cek izin akun Anda.");
                    }
                } catch(e) {
                    alert("Error koneksi hapus.");
                }
            });
        });
    </script>
</x-app-layout>