<!doctype html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>RuangRestu | @yield('title')</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description"
        content="Gradient Able is trending dashboard template made using Bootstrap 5 design framework. Gradient Able is available in Bootstrap, React, CodeIgniter, Angular,  and .net Technologies." />
    <meta name="keywords"
        content="Bootstrap admin template, Dashboard UI Kit, Dashboard Template, Backend Panel, react dashboard, angular dashboard" />
    <meta name="author" content="codedthemes" />

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('adm/assets/images/favicon.svg') }}" type="image/x-icon" />

    <!-- map-vector css -->
    <link rel="stylesheet" href="{{ asset('adm/assets/css/plugins/jsvectormap.min.css') }}" />
    <!-- [Google Font : Poppins] icon -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />

    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('adm/assets/fonts/tabler-icons.min.css') }}" />
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('adm/assets/fonts/feather.css') }}" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('adm/assets/fonts/fontawesome.css') }}" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('adm/assets/fonts/material.css') }}" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('adm/assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('adm/assets/css/style-preset.css') }}" />

    @stack('styles')
</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-header="header-1" data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true"
    data-pc-direction="ltr" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Sidebar Menu ] start -->
    @include('admin.partials.sidebar')
    <!-- [ Sidebar Menu ] end -->

    <!-- [ Header Topbar ] start -->
    @include('admin.partials.header')
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    @yield('content')
    <!-- [ Main Content ] end -->

    @include('admin.partials.footer')

    <!-- [Page Specific JS] start -->
    <script src="{{ asset('adm/assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('adm/assets/js/plugins/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('adm/assets/js/plugins/world.js') }}"></script>
    <script src="{{ asset('adm/assets/js/plugins/world-merc.js') }}"></script>
    <script src="{{ asset('adm/assets/js/pages/dashboard-sales.js') }}"></script>
    <!-- [Page Specific JS] end -->
    <!-- Required Js -->
    <script src="{{ asset('adm/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('adm/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('adm/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('adm/assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('adm/assets/js/script.js') }}"></script>
    <script src="{{ asset('adm/assets/js/theme.js') }}"></script>
    <script src="{{ asset('adm/assets/js/plugins/feather.min.js') }}"></script>


    <script>
        layout_change('light');
    </script>

    <script>
        layout_sidebar_change('light');
    </script>

    <script>
        change_box_container('false');
    </script>

    <script>
        layout_caption_change('true');
    </script>

    <script>
        layout_rtl_change('false');
    </script>

    <script>
        preset_change('preset-1');
    </script>

    <script>
        header_change('header-1');
    </script>

    @stack('scripts')

</body>
<!-- [Body] end -->

</html>
