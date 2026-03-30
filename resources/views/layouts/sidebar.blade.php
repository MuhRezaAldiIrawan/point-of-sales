<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="{{ route('dashboard.index') }}">
                    @if ($globalSetting && $globalSetting->logo)
                        <img class="brand-logo" alt="Logo {{ $globalSetting->nama_perusahaan ?? 'POS System' }}"
                            src="{{ asset('storage/' . $globalSetting->logo) }}">
                    @else
                        <img class="brand-logo" alt="Default Logo" src="{{ asset('app-assets/images/logo/logo.png') }}">
                    @endif
                    <h3 class="brand-text">{{ $globalSetting->nama_perusahaan ?? 'POS System' }}</h3>
                </a>
            </li>
            <li class="nav-item d-md-none">
                <a class="nav-link close-navbar"><i class="ft-x"></i></a>
            </li>
        </ul>
    </div>
    <div class="navigation-background"></div>
    <div class="main-menu-content">
        @php
            $role = strtolower(auth()->user()->jabatan->nama ?? '');
            $isAdmin = $role === 'administrator';
        @endphp
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <!-- ===== DASHBOARD ===== -->
            <li class="navigation-header">
                <span data-i18n="">Dashboard</span>
                <i class="ft-minus" data-toggle="tooltip" data-placement="right" title="Dashboard"></i>
            </li>
            <li class="nav-item {{ setActive(['dashboard.*']) }}">
                <a href="{{ route('dashboard.index') }}"><i class="la la-home"></i><span class="menu-title"
                        data-i18n="">Dashboard</span></a>
            </li>

            <!-- ===== TRANSAKSI ===== -->
            @if ($isAdmin || in_array($role, ['kasir', 'owner', 'purchasing']))
                <li class="navigation-header">
                    <span data-i18n="">Transaksi</span>
                    <i class="ft-minus" data-toggle="tooltip" data-placement="right" title="Transaksi"></i>
                </li>
                @if ($isAdmin || in_array($role, ['kasir', 'purchasing']))
                    <li class="nav-item {{ setActive('penjualan.*') }}">
                        <a href="{{ route('penjualan.index') }}"><i class="ft-shopping-cart"></i><span
                                class="menu-title" data-i18n="">Penjualan</span></a>
                    </li>
                @endif
                @if ($isAdmin || in_array($role, ['kasir', 'owner', 'purchasing']))
                    <li class="nav-item {{ setActive('pembayaran.*') }}">
                        <a href="{{ route('pembayaran.index') }}"><i class="ft-credit-card"></i><span
                                class="menu-title" data-i18n="">Pembayaran</span></a>
                    </li>
                @endif
            @endif

            <!-- ===== INVENTORY ===== -->
            @if ($isAdmin || in_array($role, ['kasir', 'purchasing']))
                <li class="navigation-header">
                    <span data-i18n="">Inventory</span>
                    <i class="ft-minus" data-toggle="tooltip" data-placement="right" title="Inventory"></i>
                </li>
                @if ($isAdmin || $role === 'purchasing')
                    <li class="nav-item {{ setActive('barangmasuk.*') }}">
                        <a href="{{ route('barangmasuk.index') }}"><i class="ft-inbox"></i><span class="menu-title"
                                data-i18n="">Barang Masuk</span></a>
                    </li>
                    <li class="nav-item {{ setActive('barangkeluar.*') }}">
                        <a href="{{ route('barangkeluar.index') }}"><i class="ft-check-square"></i><span
                                class="menu-title" data-i18n="">Barang Keluar</span></a>
                    </li>
                    <li class="nav-item {{ setActive('daftarharga.*') }}">
                        <a href="{{ route('daftarharga.index') }}"><i class="ft-tag"></i><span class="menu-title"
                                data-i18n="">Daftar Harga</span></a>
                    </li>
                @endif
                <li class="nav-item {{ setActive('stokbarang.*') }}">
                    <a href="{{ route('stokbarang.index') }}"><i class="ft-box"></i><span class="menu-title"
                            data-i18n="">Stok Barang</span></a>
                </li>
                <li class="nav-item {{ setActive('stokminim.*') }}">
                    <a href="{{ route('stokminim.index') }}"><i class="ft-alert-triangle"></i><span class="menu-title"
                            data-i18n="">Stok Minim</span></a>
                </li>
            @endif

            <!-- ===== MASTER DATA ===== -->
            @php
                $canSeeBarang = $isAdmin || $role === 'purchasing';
                $canSeePelanggan = $isAdmin || in_array($role, ['kasir', 'purchasing']);
                $canSeeBank = $isAdmin || in_array($role, ['hr', 'purchasing']);
                $canSeeKaryawan = $isAdmin || in_array($role, ['hr', 'owner']);
                $canSeeSettings = $isAdmin || in_array($role, ['hr', 'owner', 'purchasing']);
                $canSeeMasterData = $canSeeBarang || $canSeePelanggan || $canSeeBank || $canSeeKaryawan || $canSeeSettings;
            @endphp
            @if ($canSeeMasterData)
                <li class="navigation-header">
                    <span data-i18n="">Master Data</span>
                    <i class="ft-minus" data-toggle="tooltip" data-placement="right" title="Master Data"></i>
                </li>
                <li class="nav-item">
                    <a href="#"><i class="ft-droplet"></i><span class="menu-title" data-i18n="">Master Data</span></a>
                    <ul class="menu-content">
                        <li class="navigation-divider"></li>
                        @if ($canSeeBarang)
                            <li class="{{ setActive('barang.*') }}"><a class="menu-item" href="{{ route('barang.index') }}">Barang</a></li>
                            <li class="{{ setActive('jenisbarang.*') }}"><a class="menu-item" href="{{ route('jenisbarang.index') }}">Jenis Barang</a></li>
                            <li class="{{ setActive('merek.*') }}"><a class="menu-item" href="{{ route('merek.index') }}">Merek</a></li>
                            <li class="{{ setActive('supplier.*') }}"><a class="menu-item" href="{{ route('supplier.index') }}">Supplier</a></li>
                            <li class="navigation-divider"></li>
                            <li class="{{ setActive('satuan.*') }}"><a class="menu-item" href="{{ route('satuan.index') }}">Satuan</a></li>
                            <li class="menu-item {{ setActive('jenisstok.*') }}"><a class="menu-item" href="{{ route('jenisstok.index') }}">Jenis Stok</a></li>
                        @endif
                        @if ($canSeePelanggan)
                            <li class="menu-item {{ setActive('pelanggan.*') }}"><a class="menu-item" href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
                        @endif
                        @if ($canSeeBarang || $canSeePelanggan)
                            <li class="navigation-divider"></li>
                        @endif
                        @if ($canSeeBank)
                            <li class="menu-item {{ setActive('bank.*') }}"><a class="menu-item" href="{{ route('bank.index') }}">Bank</a></li>
                        @endif
                        @if ($canSeeKaryawan)
                            <li class="menu-item {{ setActive('karyawan.*') }}"><a class="menu-item" href="{{ route('karyawan.index') }}">Karyawan</a></li>
                        @endif
                        @if ($canSeeSettings)
                            <li class="menu-item {{ setActive('setting.*') }}"><a class="menu-item" href="{{ route('setting.index') }}">Settings</a></li>
                        @endif
                    </ul>
                </li>
            @endif

            <!-- ===== LAPORAN PENJUALAN ===== -->
            @if ($isAdmin || in_array($role, ['owner']))
                <li class="navigation-header">
                    <span data-i18n="">Laporan Penjualan</span>
                    <i class="ft-minus" data-toggle="tooltip" data-placement="right" title="Laporan Penjualan"></i>
                </li>
                <li class="nav-item">
                    <a href="#"><i class="ft-shopping-cart"></i><span class="menu-title" data-i18n="">Penjualan</span></a>
                    <ul class="menu-content">
                        <li class="navigation-divider"></li>
                        <li class="{{ setActive('laporan.penjualan.*') }}"><a class="menu-item" href="{{ route('laporan.penjualan.index') }}">Penjualan</a></li>
                    </ul>
                </li>
                <li class="nav-item {{ setActive('pembayarankredit.*') }}">
                    <a href="{{ route('pembayarankredit.index') }}"><i class="ft-credit-card"></i><span class="menu-title" data-i18n="">Pembayaran Kredit</span></a>
                </li>
            @endif

            <!-- ===== LAPORAN INVENTORI ===== -->
            @if ($isAdmin || in_array($role, ['owner', 'purchasing']))
                <li class="navigation-header">
                    <span data-i18n="">Laporan Inventori</span>
                    <i class="ft-minus" data-toggle="tooltip" data-placement="right" title="Laporan Inventori"></i>
                </li>
                <li class="nav-item">
                    <a href="#"><i class="ft-inbox"></i><span class="menu-title" data-i18n="">Barang Masuk</span></a>
                    <ul class="menu-content">
                        <li class="navigation-divider"></li>
                        <li class="{{ setActive('laporanbarangmasuk.*') }}"><a class="menu-item" href="{{ route('laporanbarangmasuk.index') }}">Barang Masuk</a></li>
                        <li class="{{ setActive('laporandetailbarangmasuk.*') }}"><a class="menu-item" href="{{ route('laporandetailbarangmasuk.index') }}">Detail Barang Masuk</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#"><i class="ft-check-square"></i><span class="menu-title" data-i18n="">Barang Keluar</span></a>
                    <ul class="menu-content">
                        <li class="navigation-divider"></li>
                        <li class="{{ setActive('laporanbarangkeluar.*') }}"><a class="menu-item" href="{{ route('laporanbarangkeluar.index') }}">Barang Keluar</a></li>
                        <li class="{{ setActive('laporandetailbarangkeluar.*') }}"><a class="menu-item" href="{{ route('laporandetailbarangkeluar.index') }}">Detail Barang Keluar</a></li>
                    </ul>
                </li>
            @endif

        </ul>
    </div>
</div>
