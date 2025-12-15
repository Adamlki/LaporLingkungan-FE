<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">Edit Laporan</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div id="loading" class="text-center">Mengambil data lama...</div>

                {{-- FORM EDIT (Awalnya hidden) --}}
                <form id="edit-form" class="hidden" enctype="multipart/form-data">
                    @csrf
                    {{-- Input Judul --}}
                    <div class="mb-4">
                        <x-input-label for="judul" :value="__('Judul Laporan')" />
                        <x-text-input id="judul" class="block mt-1 w-full" type="text" name="judul" required />
                    </div>

                    {{-- Input Deskripsi --}}
                    <div class="mb-4">
                        <x-input-label for="deskripsi" :value="__('Deskripsi')" />
                        <textarea id="deskripsi" name="deskripsi" rows="4" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                    </div>

                    {{-- Input Lokasi --}}
                    <div class="mb-4">
                        <x-input-label for="lokasi" :value="__('Lokasi')" />
                        <x-text-input id="lokasi" class="block mt-1 w-full" type="text" name="lokasi" required />
                    </div>

                    {{-- Input Ganti Foto --}}
                    <div class="mb-6">
                        <x-input-label for="foto" :value="__('Ganti Foto (Opsional)')" />
                        <input id="foto" name="foto" type="file" accept="image/*" class="block mt-1 w-full text-sm text-gray-500" />
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('laporan.index') }}" class="text-gray-600">Batal</a>
                        <x-primary-button id="btn-update">Update Laporan</x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- SCRIPT UPDATE --}}
    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            const laporanId = "{{ $id }}"; 
            const token = localStorage.getItem('api_token');
            const apiUrl = "https://aweless-raisa-dutiable.ngrok-free.dev/api/laporan/" + laporanId;

            // 1. LOAD DATA LAMA
            try {
                const response = await fetch(apiUrl, {
                    headers: { 
                        "Authorization": "Bearer " + token,
                        "Accept": "application/json",
                        "ngrok-skip-browser-warning": "69420"
                    }
                });
                const data = await response.json();
                const laporan = data.data || data;

                // Isi Form
                document.getElementById('loading').classList.add('hidden');
                document.getElementById('edit-form').classList.remove('hidden');
                
                document.getElementById('judul').value = laporan.judul;
                document.getElementById('deskripsi').value = laporan.deskripsi;
                document.getElementById('lokasi').value = laporan.lokasi;

            } catch (error) {
                document.getElementById('loading').innerText = "Gagal mengambil data edit.";
            }

            // 2. PROSES UPDATE
            document.getElementById('edit-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const btn = document.getElementById('btn-update');
                btn.innerText = "Menyimpan...";
                btn.disabled = true;

                const formData = new FormData(e.target);
                // TRICK LARAVEL: Kirim POST tapi rasa PUT buat handle file upload
                formData.append('_method', 'PUT'); 

                try {
                    const resUpdate = await fetch(apiUrl, {
                        method: "POST", // Pakai POST karena ada FormData + _method PUT
                        headers: {
                            "Authorization": "Bearer " + token,
                            "Accept": "application/json",
                            "ngrok-skip-browser-warning": "69420"
                        },
                        body: formData
                    });

                    if (resUpdate.ok) {
                        alert("Update Berhasil!");
                        window.location.href = "/baca-laporan/" + laporanId;
                    } else {
                        const err = await resUpdate.json();
                        alert("Gagal update: " + (err.message || "Cek input"));
                        btn.innerText = "Update Laporan";
                        btn.disabled = false;
                    }
                } catch (e) {
                    alert("Error koneksi update.");
                    btn.innerText = "Update Laporan";
                    btn.disabled = false;
                }
            });
        });
    </script>
</x-app-layout>