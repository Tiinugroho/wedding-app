<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('admin.dashboard') }}" class="b-brand text-primary">
                <h3 class="text-white font-bold m-0 tracking-wide">RUANG<span class="text-warning">RESTU</span></h3>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                
                <li class="pc-item pc-caption">
                    <label>Main Menu</label>
                </li>
                
                <li class="pc-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="pc-link">
                        <span class="pc-micon"><i class="ph ph-gauge"></i></span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label>Master Data</label>
                    <i class="ph ph-database"></i>
                </li>

                <li class="pc-item {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.packages.index') }}" class="pc-link"> 
                        <span class="pc-micon"><i class="ph ph-tag"></i></span>
                        <span class="pc-mtext">Paket Harga</span>
                    </a>
                </li>

                <li class="pc-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ph ph-squares-four"></i></span>
                        <span class="pc-mtext">Kategori Tema</span>
                    </a>
                </li>

                <li class="pc-item {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.templates.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ph ph-palette"></i></span>
                        <span class="pc-mtext">Tema / Template</span>
                    </a>
                </li>

                <li class="pc-item {{ request()->routeIs('admin.musics.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.musics.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ph ph-music-notes"></i></span>
                        <span class="pc-mtext">Daftar Musik</span>
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label>Transaksi & Klien</label>
                    <i class="ph ph-shopping-cart"></i>
                </li>

                <li class="pc-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <a href="#" class="pc-link">
                        <span class="pc-micon"><i class="ph ph-receipt"></i></span>
                        <span class="pc-mtext">Pesanan (Orders)</span>
                    </a>
                </li>

                <li class="pc-item {{ request()->routeIs('admin.invitations.*') ? 'active' : '' }}">
                    <a href="#" class="pc-link">
                        <span class="pc-micon"><i class="ph ph-envelope-open"></i></span>
                        <span class="pc-mtext">Data Undangan</span>
                    </a>
                </li>

                <li class="pc-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="#" class="pc-link">
                        <span class="pc-micon"><i class="ph ph-users"></i></span>
                        <span class="pc-mtext">Data Pengguna</span>
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label>Sistem</label>
                    <i class="ph ph-gear"></i>
                </li>
                
                <li class="pc-item">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="pc-link text-danger">
                            <span class="pc-micon"><i class="ph ph-sign-out text-danger"></i></span>
                            <span class="pc-mtext">Keluar</span>
                        </a>
                    </form>
                </li>

            </ul>
        </div>
    </div>
</nav>