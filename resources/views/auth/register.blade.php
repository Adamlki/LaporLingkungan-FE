<x-guest-layout>
    <form id="register-form">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.getElementById('register-form').addEventListener('submit', async function(e) {
            e.preventDefault(); // 1. Mencegah halaman reload (submit standar HTML)

            // 2. Ambil data dari inputan form
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;

            // 3. Siapkan URL API (Sesuai Postman kamu)
            const apiUrl = "https://aweless-raisa-dutiable.ngrok-free.dev/api/register";

            try {
                // 4. Tembak API menggunakan Fetch
                const response = await fetch(apiUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        password: password,
                        password_confirmation: password_confirmation
                    })
                });

                const result = await response.json();

                if (response.ok) {
                    // 5. Jika Sukses (Status 200/201)
                    alert("Registrasi Berhasil! Silakan Login.");
                    window.location.href = "/login"; // Arahkan user ke halaman login view
                } else {
                    // 6. Jika Gagal (Misal validasi error)
                    console.log(result);
                    alert("Gagal: " + (result.message || "Terjadi kesalahan"));
                }

            } catch (error) {
                console.error("Error:", error);
                alert("Terjadi kesalahan koneksi ke server.");
            }
        });
    </script>

</x-guest-layout>