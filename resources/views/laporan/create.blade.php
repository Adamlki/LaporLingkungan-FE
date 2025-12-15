<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Buat Laporan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- FORMULIR (Tanpa Action PHP, Pakai ID untuk JS) --}}
                <form id="laporan-form" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- Judul --}}
                    <div class="mb-4">
                        <x-input-label for="judul" :value="__('Judul Laporan')" />
                        <x-text-input id="judul" class="block mt-1 w-full" type="text" name="judul" required placeholder="Contoh: Sampah di Sungai" />
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-4">
                        <x-input-label for="deskripsi" :value="__('Deskripsi')" />
                        <textarea id="deskripsi" name="deskripsi" rows="4" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Jelaskan detail kejadian..."></textarea>
                    </div>

                    {{-- Lokasi --}}
                    <div class="mb-4">
                        <x-input-label for="lokasi" :value="__('Lokasi Kejadian')" />
                        <x-text-input id="lokasi" class="block mt-1 w-full" type="text" name="lokasi" required placeholder="Jl. Mawar No. 12" />
                    </div>

                    {{-- Foto --}}
                    <div class="mb-6">
                        <x-input-label for="foto" :value="__('Bukti Foto')" />
                        <input id="foto" name="foto" type="file" accept="image/*" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                    </div>

                    {{-- Tombol --}}
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('laporan.index') }}" class="text-gray-600 hover:text-gray-900">Batal</a>
                        <x-primary-button id="btn-submit">
                            {{ __('Kirim Laporan') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- SCRIPT JS UNTUK KIRIM KE NGROK --}}
    <script>
        document.getElementById('laporan-form').addEventListener('submit', async function(e) {
            e.preventDefault(); // Stop reload halaman

            const token = localStorage.getItem('api_token');
            if (!token) {
                alert("Sesi habis. Login dulu.");
                window.location.href = "/login";
                return;
            }

            // Siapkan Data Form (Termasuk Gambar)
            const formData = new FormData(e.target);

            // Ubah tombol jadi loading
            const btnSubmit = document.getElementById('btn-submit');
            btnSubmit.innerText = "Mengirim...";
            btnSubmit.disabled = true;

            // URL NGROK TEMANMU
            const apiUrl = "https://aweless-raisa-dutiable.ngrok-free.dev/api/laporan";

            try {
                const response = await fetch(apiUrl, {
                    method: "POST",
                    headers: {
                        "Authorization": "Bearer " + token,
                        "Accept": "application/json",
                        "ngrok-skip-browser-warning": "69420" // Anti Blokir Ngrok
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    alert("Laporan Berhasil Dikirim!");
                    window.location.href = "/laporan"; // Pindah ke list laporan
                } else {
                    alert("Gagal: " + (result.message || "Cek data inputan."));
                    btnSubmit.innerText = "Kirim Laporan";
                    btnSubmit.disabled = false;
                }
            } catch (error) {
                console.error(error);
                alert("Gagal koneksi ke server Ngrok.");
                btnSubmit.innerText = "Kirim Laporan";
                btnSubmit.disabled = false;
            }
        });
    </script>
</x-app-layout>