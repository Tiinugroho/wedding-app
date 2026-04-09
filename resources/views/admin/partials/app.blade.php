<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RuangRestu | @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass-sidebar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-right: 1px solid #f1f5f9;
        }

        .nav-item.active {
            background: linear-gradient(to right, #FF5A5A, #FF8B5A);
            color: white;
            box-shadow: 0 10px 20px -5px rgba(255, 90, 90, 0.3);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #FFD45A;
            border-radius: 10px;
        }

        .dashboard-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        }
    </style>
    @stack('styles')
</head>

<body class="bg-slate-50 min-h-screen text-slate-800">
    @if (session('success') || session('error') || session('status'))
        <div id="toast-notification"
            class="fixed top-24 right-4 md:right-8 z-[100] transform transition-all duration-500 translate-x-0 opacity-100 flex items-center p-4 mb-4 text-slate-500 bg-white rounded-[1.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.08)] border border-slate-100 min-w-[300px]"
            role="alert">
            @if (session('success') || session('status') == 'verification-link-sent')
                <div
                    class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-green-500 bg-green-100 rounded-xl">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
            @elseif(session('error'))
                <div
                    class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-red-500 bg-red-100 rounded-xl">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z" />
                    </svg>
                    <span class="sr-only">Error icon</span>
                </div>
            @endif
            <div class="ms-4 text-sm font-bold text-slate-700 pr-6">
                {{ session('success') ?? session('error') }}
                {{ session('status') == 'verification-link-sent' ? 'Link verifikasi telah dikirim ke email Anda.' : '' }}
            </div>
            <button type="button" onclick="closeToast()"
                class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8"
                aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
        <script>
            // Script untuk menghilangkan toast otomatis setelah 5 detik
            function closeToast() {
                const toast = document.getElementById('toast-notification');
                if (toast) {
                    toast.classList.replace('translate-x-0', 'translate-x-full');
                    toast.classList.replace('opacity-100', 'opacity-0');
                    setTimeout(() => toast.remove(), 500); // Hapus dari DOM setelah animasi selesai
                }
            }
            setTimeout(closeToast, 5000);
        </script>
    @endif

    <div id="loading-overlay"
        class="fixed inset-0 z-[9999] hidden bg-slate-900/80 backdrop-blur-sm flex-col items-center justify-center">
        <svg class="animate-spin h-12 w-12 text-rOrange mb-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
            </circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
        <p class="text-white font-bold text-lg animate-pulse">Menyimpan Perubahan...</p>
    </div>

    <div class="w-full flex min-h-screen">

        @include('admin.partials.sidebar')


        <main class="flex-1 w-full p-6 md:p-10 lg:p-12 pb-32 lg:pb-12 h-screen overflow-y-auto overflow-x-hidden">

            @yield('content')

        </main>

    </div>
    {{-- @include('admin.partials.footer') --}}

    {{-- @include('admin.partials.mobile_nav') --}}


    <script>
        // Script untuk membuka/menutup Sidebar Offcanvas di Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');

            // Toggle class translate untuk sidebar
            sidebar.classList.toggle('-translate-x-full');
            // Toggle class hidden untuk backdrop
            backdrop.classList.toggle('hidden');
        }
    </script>
    @stack('scripts')

</body>

</html>
