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

    <div class="w-full flex min-h-screen">

        @include('customer.partials.sidebar')


        <main class="flex-1 w-full p-6 md:p-10 lg:p-12 pb-32 lg:pb-12 h-screen overflow-y-auto overflow-x-hidden">

            @yield('content')

        </main>
        
    </div>
    {{-- @include('customer.partials.footer') --}}

    @include('customer.partials.mobile_nav')


    @stack('scripts')

</body>

</html>
