<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="{{ route('dashboard.index') }}">
                    @if($globalSetting && $globalSetting->logo)
                        <img class="brand-logo" alt="Logo {{ $globalSetting->nama_perusahaan ?? 'POS System' }}"
                             src="{{ asset('storage/' . $globalSetting->logo) }}">
                    @else
                        <img class="brand-logo" alt="Default Logo"
                             src="{{ asset('app-assets/images/logo/logo.png') }}">
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
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <!-- ===== DASHBOARD ===== -->
            <li class="navigation-header">
                <span data-i18n="">Dashboard</span>
                <i class="ft-minus" data-toggle="tooltip" data-placement="right" title="Dashboard"></i>
            </li>
            <li class="nav-item {{ setActive(['dashboard.*']) }}">
                <a href="{{ route('dashboard.index') }}"><i class="la la-home"></i><span class="menu-title" data-i18n="">Dashboard</span></a>
            </li>

            <!-- ===== TRANSAKSI ===== -->
            <li class="navigation-header">
                <span data-i18n="">Transaksi</span>
                <i class="ft-minus" data-toggle="tooltip" data-placement="right" title="Transaksi"></i>
            </li>
            <li class="nav-item {{ setActive('penjualan.*') }}">
                <a href="{{ route('penjualan.index') }}"><i class="ft-shopping-cart"></i><span class="menu-title" data-i18n="">Penjualan</span></a>
            </li>
            <li class="nav-item {{ setActive('pembayaran.*') }}">
                <a href="{{ route('pembayaran.index')}}"><i class="ft-credit-card"></i><span class="menu-title" data-i18n="">Pembayaran</span></a>
            </li>
            {{-- <li class="nav-item {{ setActive('daftarbayar.*') }}">
                <a href="../../../../../index.html"><i class="ft-layers"></i><span class="menu-title" data-i18n="">Daftar Bayar</span></a>
            </li> --}}

            <!-- ===== INVENTORY ===== -->
            <li class="navigation-header">
                <span data-i18n="">Inventory</span>
                <i class="ft-minus" data-toggle="tooltip" data-placement="right" title="Inventory"></i>
            </li>
            <li class="nav-item {{ setActive('barangmasuk.*') }}" >
                <a href="{{ route('barangmasuk.index') }}"><i class="ft-inbox"></i><span class="menu-title" data-i18n="">Barang Masuk</span></a>
            </li>
            <li class="nav-item {{ setActive('barangkeluar.*') }}">
                <a href="{{ route('barangkeluar.index') }}"><i class="ft-check-square"></i><span class="menu-title" data-i18n="">Barang Keluar</span></a>
            </li>
            <li class="nav-item {{ setActive('daftarharga.*') }}">
                <a href="{{ route('daftarharga.index') }}"><i class="ft-tag"></i><span class="menu-title" data-i18n="">Daftar Harga</span></a>
            </li>
            <li class="nav-item {{ setActive('stokbarang.*') }}">
                <a href="{{ route('stokbarang.index') }}"><i class="ft-box"></i><span class="menu-title" data-i18n="">Stok Barang</span></a>
            </li>
            <li class="nav-item {{ setActive('stokminim.*') }}">
                <a href="{{ route('stokminim.index') }}"><i class="ft-alert-triangle"></i><span class="menu-title" data-i18n="">Stok Minim</span></a>
            </li>

            <!-- ===== MASTER DATA ===== -->
            <li class="navigation-header">
                <span data-i18n="">Master Data</span>
                <i class="ft-minus" data-toggle="tooltip" data-placement="right" title="Master Data"></i>
            </li>
            <li class="nav-item">
                <a href="#"><i class="ft-droplet"></i><span class="menu-title" data-i18n="">Master Data</span></a>
                <ul class="menu-content">
                    <li class="navigation-divider"></li>
                    <li class="{{ setActive('barang.*') }}"><a class="menu-item" href="{{ route('barang.index') }}" data-i18n="nav.starter_kit.fixed_navbar">Barang</a></li>
                    <li class="{{ setActive('jenisbarang.*') }}"><a class="menu-item" href="{{ route('jenisbarang.index') }}" data-i18n="nav.starter_kit.fixed_navigation">Jenis Barang</a></li>
                    {{-- <li class="{{ setActive('daftarharga.*') }}"><a class="menu-item" href="{{ route('daftarharga.index') }}" data-i18n="nav.starter_kit.fixed_navbar_navigation">Daftar Harga</a></li> --}}
                    <li class="{{ setActive('merek.*') }}"><a class="menu-item" href="{{ route('merek.index') }}" data-i18n="nav.starter_kit.fixed_navbar_navigation">Merek</a></li>
                    <li class="{{ setActive('supplier.*') }}"><a class="menu-item" href="{{ route('supplier.index') }}" data-i18n="nav.starter_kit.fixed_navbar_footer">Supplier</a></li>
                    <li class="navigation-divider"></li>
                    <li class="{{ setActive('satuan.*') }}"><a class="menu-item" href="{{ route('satuan.index') }}" data-i18n="nav.starter_kit.fixed_layout">Satuan</a></li>
                    <li class="menu-item {{ setActive('jenisstok.*') }}"><a class="menu-item" href="{{ route('jenisstok.index') }}" data-i18n="nav.starter_kit.boxed_layout">Jenis Stok</a></li>
                    <li class="menu-item {{ setActive('pelanggan.*') }}"><a class="menu-item" href="{{ route('pelanggan.index') }}" data-i18n="nav.starter_kit.static_layout">Pelanggan</a></li>
                    <li class="navigation-divider"></li>
                    <li class="menu-item {{ setActive('bank.*') }}"><a class="menu-item" href="{{ route('bank.index') }}" data-i18n="nav.starter_kit.dark_layout">Bank</a></li>
                    <li class="menu-item {{ setActive('karyawan.*') }}"><a class="menu-item" href="{{ route('karyawan.index') }}" data-i18n="nav.starter_kit.light_layout">Karyawan</a></li>
                    <li class="menu-item {{ setActive('setting.*') }}"><a class="menu-item" href="{{ route('setting.index') }}" data-i18n="nav.starter_kit.light_layout">Settings</a></li>
                </ul>
            </li>

            <!-- ===== PENJUALAN ===== -->
            <li class="navigation-header">
                <span data-i18n="">Laporan Penjualan</span>
                <i class="ft-minus" data-toggle="tooltip" data-placement="right" title="Laporan Penjualan"></i>
            </li>
            <li class="nav-item">
                <a href="#"><i class="ft-shopping-cart"></i><span class="menu-title" data-i18n="">Penjualan</span></a>
                <ul class="menu-content">
                    <li class="navigation-divider"></li>
                    <li class="{{ setActive('laporan.penjualan.*') }}"><a class="menu-item" href="{{ route('laporan.penjualan.index') }}" data-i18n="nav.starter_kit.fixed_navbar">Penjualan</a></li>
                </ul>
            </li>
            <li class="nav-item {{ setActive('pembayarankredit.*') }}">
                <a href="{{ route('pembayarankredit.index') }}"><i class="ft-credit-card"></i><span class="menu-title" data-i18n="">Pembayaran Kredit</span></a>
            </li>

            <!-- ===== INVENTORI DETAIL ===== -->
            <li class="navigation-header">
                <span data-i18n="">Laporan Inventori</span>
                <i class="ft-minus" data-toggle="tooltip" data-placement="right" title="Laporan Inventori"></i>
            </li>
            <li class="nav-item">
                <a href="#"><i class="ft-inbox"></i><span class="menu-title" data-i18n="">Barang Masuk</span></a>
                <ul class="menu-content">
                    <li class="navigation-divider"></li>
                    <li class="{{ setActive('laporanbarangmasuk.*') }}"><a class="menu-item" href="{{ route('laporanbarangmasuk.index') }}" data-i18n="nav.starter_kit.fixed_navbar">Barang Masuk</a></li>
                    <li class="{{ setActive('laporandetailbarangmasuk.*') }}"><a class="menu-item" href="{{ route('laporandetailbarangmasuk.index')}}" data-i18n="nav.starter_kit.fixed_navigation">Detail Barang Masuk</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#"><i class="ft-check-square"></i><span class="menu-title" data-i18n="">Barang Keluar</span></a>
                <ul class="menu-content">
                    <li class="navigation-divider"></li>
                    <li class="{{ setActive('laporanbarangkeluar.*') }}"><a class="menu-item" href="{{ route('laporanbarangkeluar.index') }}" data-i18n="nav.starter_kit.fixed_navbar">Barang Keluar</a></li>
                    <li class="{{ setActive('laporandetailbarangkeluar.*') }}"><a class="menu-item" href="{{ route('laporandetailbarangkeluar.index') }}" data-i18n="nav.starter_kit.fixed_navigation">Detail Barang Keluar</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
