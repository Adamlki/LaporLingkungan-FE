<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form id="login-form">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.getElementById('login-form').addEventListener('submit', async function(e) {
            e.preventDefault(); // Stop form biar tidak reload halaman

            // Ambil data input
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // URL API Backend (Port 8000)
            const apiUrl = "https://aweless-raisa-dutiable.ngrok-free.dev/api/login";

            try {
                // Tembak API
                const response = await fetch(apiUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password
                    })
                });

                const result = await response.json();

                if (response.ok) {

                    // Token ini kuncimu buat ambil data Laporan nanti
                    localStorage.setItem('api_token', result.access_token || result.token); 
                    
                    alert("Login Berhasil! Mengalihkan ke Dashboard...");
                    
                    // Pindah halaman ke Dashboard Frontend
                    window.location.href = "/dashboard"; 
                } else {
                    // Kalau password salah / email tidak ada
                    alert("Login Gagal: " + (result.message || "Periksa email dan password Anda."));
                    console.log(result);
                }

            } catch (error) {
                console.error("Error:", error);
                alert("Gagal menghubungi server Backend. Pastikan port 8000 nyala.");
            }
        });
    </script>
</x-guest-layout>