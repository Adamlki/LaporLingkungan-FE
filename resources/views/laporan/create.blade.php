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
                    
                    {{-- KOTAK ERROR (Muncul jika ada error 422 dari API) --}}
                    <div id="error-box" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Ada kesalahan!</strong>
                        <ul id="error-list" class="mt-2 list-disc list-inside text-sm"></ul>
                    </div>

                    {{-- FORMULIR (ID untuk JS) --}}
                    <form id="create-form" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Judul --}}
                        <div>
                            <x-input-label for="judul" :value="__('Judul Laporan')" />
                            <x-text-input id="judul" class="block mt-1 w-full" type="text" name="judul" required autofocus placeholder="Contoh: Sampah menumpuk di pasar" />
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mt-4">
                            <x-input-label for="deskripsi" :value="__('Deskripsi')" />
                            <textarea id="deskripsi" name="deskripsi" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required placeholder="Jelaskan detail kejadian..."></textarea>
                        </div>

                        {{-- Lokasi --}}
                        <div class="mt-4">
                            <x-input-label for="lokasi" :value="__('Lokasi Kejadian')" />
                            <x-text-input id="lokasi" class="block mt-1 w-full" type="text" name="lokasi" required placeholder="Nama Jalan / RT RW / Desa" />
                        </div>

                        {{-- Foto --}}
                        <div class="mt-4">
                            <x-input-label for="foto" :value="__('Foto (Opsional)')" />
                            <input id="foto" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" type="file" name="foto" accept="image/*">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB.</p>
                        </div>

                        {{-- Tombol --}}
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('laporan.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 text-sm font-medium">Batal</a>
                            
                            <x-primary-button id="btn-submit" class="ml-4">
                                {{ __('Kirim Laporan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT JAVASCRIPT API --}}
    <script>
        document.getElementById('create-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Elemen UI
            const btnSubmit = document.getElementById('btn-submit');
            const errorBox = document.getElementById('error-box');
            const errorList = document.getElementById('error-list');

            // Reset Error
            errorBox.classList.add('hidden');
            errorList.innerHTML = '';
            
            // Loading State
            const originalText = btnSubmit.innerText;
            btnSubmit.innerText = "Mengirim...";
            btnSubmit.disabled = true;
            btnSubmit.classList.add('opacity-50');

            // Cek Token
            const token = localStorage.getItem('api_token');
            if (!token) {
                alert("Sesi habis. Silakan login kembali.");
                window.location.href = "/login";
                return;
            }

            // Persiapan Data
            const formData = new FormData(e.target);
            const baseUrl = "https://aweless-raisa-dutiable.ngrok-free.dev";
            const apiUrl = `${baseUrl}/api/laporan`;

            try {
                const response = await fetch(apiUrl, {
                    method: "POST",
                    headers: {
                        "Authorization": "Bearer " + token,
                        "Accept": "application/json",
                        "ngrok-skip-browser-warning": "69420"
                        // Jangan set Content-Type manual untuk FormData!
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    alert("✅ Laporan Berhasil Dikirim!");
                    window.location.href = "/laporan"; // Redirect ke halaman list
                } else {
                    // --- Handle Error Validasi (422) ---
                    errorBox.classList.remove('hidden');
                    
                    if (result.errors) {
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
                    
                    window.scrollTo(0,0);
                }
            } catch (error) {
                console.error("Error:", error);
                alert("⚠️ Gagal menghubungi server Ngrok. Periksa koneksi internet.");
            } finally {
                // Kembalikan Tombol
                btnSubmit.innerText = originalText;
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50');
            }
        });
    </script>
</x-app-layout>