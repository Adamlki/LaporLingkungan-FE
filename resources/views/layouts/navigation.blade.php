<nav x-data="{ open: false }" class="bg-white shadow-lg border-b border-gray-100 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                {{-- Logo Aplikasi --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-8 w-auto fill-current text-indigo-600 font-extrabold" />
                    </a>
                    <span class="ml-2 text-xl font-extrabold text-gray-800 tracking-wide font-sans">Lapor Lingkungan</span>
                </div>

                {{-- Navigasi Utama (Desktop) --}}
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex items-center">
                    
                    {{-- Link Laporan (Home) --}}
                    <x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.index')">
                        {{ __('Laporan Warga') }}
                    </x-nav-link>

                    {{-- MENU KHUSUS USER LOGIN (Diatur via JS) --}}
                    <div id="desktop-auth-menu" class="hidden flex space-x-2 items-center h-full">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        
                        {{-- Link Admin (Nanti JS yang cek role, sementara disembunyikan) --}}
                        {{-- <x-nav-link href="#" id="admin-link" class="hidden">Admin Panel</x-nav-link> --}}
                    </div>
                </div>
            </div>

            {{-- Bagian Kanan --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                
                {{-- OPSI 1: TAMPILAN BELUM LOGIN (GUEST) --}}
                <div id="guest-menu" class="flex space-x-2">
                    <a href="{{ route('login') }}" class="font-semibold text-gray-700 hover:text-indigo-600 transition duration-150 px-3 py-2 text-base">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-md transition duration-150 px-4 py-2 text-base shadow-md">
                        Daftar
                    </a>
                </div>

                {{-- OPSI 2: TAMPILAN SUDAH LOGIN (AUTH) --}}
                <div id="auth-menu" class="hidden">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-base font-semibold text-gray-700 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-full px-4 py-2 transition duration-150 ease-in-out shadow-sm">
                                {{-- NAMA USER DARI JS --}}
                                <div class="mr-2" id="nav-user-name">Pengguna</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <div class="border-t border-gray-100"></div>

                            {{-- LOGOUT MANUAL VIA JS --}}
                            <button id="logout-btn" class="w-full text-left block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                <span class="text-red-600 font-semibold">{{ __('Log Out') }}</span>
                            </button>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            {{-- Tombol Hamburger (Mobile) --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Responsive Navigation Links (Mobile) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        
        {{-- MENU MOBILE UMUM --}}
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.index')">
                {{ __('Laporan') }}
            </x-responsive-nav-link>
            
            {{-- MENU MOBILE AUTH (Akan ditampilkan JS) --}}
            <div id="mobile-auth-links" class="hidden">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            </div>
        </div>

        {{-- MENU MOBILE PROFILE / LOGIN --}}
        <div class="pt-4 pb-1 border-t border-gray-200">
            
            {{-- Kalau Belum Login --}}
            <div id="mobile-guest-menu">
                <x-responsive-nav-link :href="route('login')">
                    {{ __('Masuk') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">
                    {{ __('Daftar') }}
                </x-responsive-nav-link>
            </div>

            {{-- Kalau Sudah Login --}}
            <div id="mobile-auth-menu" class="hidden">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800" id="mobile-user-name">User</div>
                    <div class="font-medium text-sm text-gray-500">Pengguna Aplikasi</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    <button id="mobile-logout-btn" class="w-full text-left block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                        {{ __('Log Out') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT PENGENDALI NAVIGASI --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const token = localStorage.getItem('api_token');

            // Elemen Desktop
            const guestMenu = document.getElementById('guest-menu');
            const authMenu = document.getElementById('auth-menu');
            const desktopAuthLinks = document.getElementById('desktop-auth-menu');
            const navUserName = document.getElementById('nav-user-name');
            const logoutBtn = document.getElementById('logout-btn');

            // Elemen Mobile
            const mobileGuestMenu = document.getElementById('mobile-guest-menu');
            const mobileAuthMenu = document.getElementById('mobile-auth-menu');
            const mobileAuthLinks = document.getElementById('mobile-auth-links');
            const mobileUserName = document.getElementById('mobile-user-name');
            const mobileLogoutBtn = document.getElementById('mobile-logout-btn');

            if (token) {
                // --- KONDISI SUDAH LOGIN ---
                // Sembunyikan menu tamu
                if(guestMenu) guestMenu.classList.add('hidden');
                if(mobileGuestMenu) mobileGuestMenu.classList.add('hidden');

                // Tampilkan menu user
                if(authMenu) authMenu.classList.remove('hidden');
                if(desktopAuthLinks) desktopAuthLinks.classList.remove('hidden');
                
                if(mobileAuthMenu) mobileAuthMenu.classList.remove('hidden');
                if(mobileAuthLinks) mobileAuthLinks.classList.remove('hidden');

                // Ambil Nama User (Opsional, biar cantik)
                // Kita ambil dari localStorage kalau ada (biasanya disimpan pas login)
                // Atau fetch API lagi (tapi biar cepet, kita biarkan default dulu atau ambil dari cache)
                fetch("https://aweless-raisa-dutiable.ngrok-free.dev/api/user", {
                    headers: { "Authorization": "Bearer " + token,
                    "Accept": "application/json",
                    "ngrok-skip-browser-warning": "69420" }
                })
                .then(res => res.json())
                .then(data => {
                    if(navUserName) navUserName.innerText = data.name;
                    if(mobileUserName) mobileUserName.innerText = data.name;
                })
                .catch(err => console.log("Gagal load user nav"));

            } else {
                // --- KONDISI BELUM LOGIN ---
                // Pastikan menu user tersembunyi
                if(authMenu) authMenu.classList.add('hidden');
                if(desktopAuthLinks) desktopAuthLinks.classList.add('hidden');
            }

            // --- FUNGSI LOGOUT ---
            function handleLogout() {
                if(confirm("Yakin ingin keluar?")) {
                    // 1. Hapus Token
                    localStorage.removeItem('api_token');
                    // 2. Redirect ke Login
                    window.location.href = "/login";
                }
            }

            if(logoutBtn) logoutBtn.addEventListener('click', handleLogout);
            if(mobileLogoutBtn) mobileLogoutBtn.addEventListener('click', handleLogout);
        });
    </script>
</nav>