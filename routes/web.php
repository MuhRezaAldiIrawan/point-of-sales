<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterData\BankController;
use App\Http\Controllers\MasterData\BarangController;
use App\Http\Controllers\MasterData\JenisBarangController;
use App\Http\Controllers\MasterData\JenisStokController;
use App\Http\Controllers\MasterData\KaryawanController;
use App\Http\Controllers\MasterData\MerekController;
use App\Http\Controllers\MasterData\PelangganController;
use App\Http\Controllers\Transaksi\PenjualanController;
use App\Http\Controllers\Transaksi\PembayaranController;
use App\Http\Controllers\MasterData\SatuanController;
use App\Http\Controllers\MasterData\SettingController;
use App\Http\Controllers\MasterData\SupplierController;
use App\Http\Controllers\MasterData\DaftarHargaController;
use App\Http\Controllers\Inventori\BarangMasukController;
use App\Http\Controllers\Inventori\BarangKeluarController;
use App\Http\Controllers\Inventori\StokBarangController;
use App\Http\Controllers\Inventori\StokMinimController;
use App\Http\Controllers\LaporanInventori\DetailBarangMasukController;
use App\Http\Controllers\LaporanInventori\LaporanBarangMasukController;
use App\Http\Controllers\LaporanInventori\LaporanBarangKeluarController;
use App\Http\Controllers\LaporanInventori\DetailBarangKeluarController;
use App\Http\Controllers\LaporanPenjualan\LaporanPenjualanController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\LaporanPenjualan\PembayaranKreditController;

Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'index')->name('login');
    Route::post('/login', 'login')->name('auth.login');
    Route::post('/logout', 'logout')->name('auth.logout');
});


Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard', 'index')->name('dashboard.index');
});

// Start Transaksi Routes
Route::controller(PembayaranController::class)->group(function () {
    Route::get('/pembayaran', 'index')->name('pembayaran.index');
    Route::get('/pembayaran/{id}', 'show')->name('pembayaran.show');
});
// End Transaksi Routes

Route::controller(PenjualanController::class)->group(function () {
    Route::get('/penjualan', 'index')->name('penjualan.index');
    Route::get('/penjualan/create', 'create')->name('penjualan.create');
    Route::post('/penjualan', 'store')->name('penjualan.store');
    Route::get('/penjualan/{id}', 'show')->name('penjualan.show');
    Route::get('/penjualan/{id}/print', 'print')->name('penjualan.print');
    Route::get('/penjualan/{id}/edit', 'edit')->name('penjualan.edit');
    Route::put('/penjualan/{id}', 'update')->name('penjualan.update');
    Route::delete('/penjualan/{id}', 'destroy')->name('penjualan.destroy');
});

// Start Inventory Routes
Route::controller(BarangMasukController::class)->group(function () {
    Route::get('/barang-masuk', 'index')->name('barangmasuk.index');
    Route::get('/barang-masuk/create', 'create')->name('barangmasuk.create');
    Route::get('/barang-masuk/get-barangs', 'getBarangs')->name('barangmasuk.getBarangs');
    Route::post('/barang-masuk', 'store')->name('barangmasuk.store');
    Route::post('/barang-masuk/{id}/cancel', 'cancel')->name('barangmasuk.cancel');
    Route::get('/barang-masuk/{id}', 'show')->name('barangmasuk.show');
    Route::get('/barang-masuk/{id}/edit', 'edit')->name('barangmasuk.edit');
    Route::put('/barang-masuk/{id}', 'update')->name('barangmasuk.update');
    Route::delete('/barang-masuk/{id}', 'destroy')->name('barangmasuk.destroy');
});


Route::controller(BarangKeluarController::class)->group(function () {
    Route::get('/barangkeluar', 'index')->name('barangkeluar.index');
    Route::get('/barangkeluar/create', 'create')->name('barangkeluar.create');
    Route::get('/barangkeluar/get-barangs', 'getBarangs')->name('barangkeluar.getBarangs');
    Route::post('/barangkeluar', 'store')->name('barangkeluar.store');
    Route::post('/barangkeluar/{id}/cancel', 'cancel')->name('barangkeluar.cancel');
    Route::get('/barangkeluar/{id}', 'show')->name('barangkeluar.show');
    Route::get('/barangkeluar/{id}/edit', 'edit')->name('barangkeluar.edit');
    Route::put('/barangkeluar/{id}', 'update')->name('barangkeluar.update');
    Route::delete('/barangkeluar/{id}', 'destroy')->name('barangkeluar.destroy');
});


Route::controller(StokBarangController::class)->group(function () {
    Route::get('/stok-barang', 'index')->name('stokbarang.index');
});

Route::controller(StokMinimController::class)->group(function () {
    Route::get('/stok-minim', 'index')->name('stokminim.index');
});

// End Inventory Routes


//  Start Master Data Routes
Route::controller(BarangController::class)->group(function () {
    Route::get('/barang', 'index')->name('barang.index');
    Route::get('/barang/create', 'create')->name('barang.create');
    Route::post('/barang', 'store')->name('barang.store');
    Route::get('/barang/edit/{id}', 'edit')->name('barang.edit');
    Route::put('/barang/{id}', 'update')->name('barang.update');
    Route::delete('/barang/{id}', 'destroy')->name('barang.destroy');
    Route::get('/barang/get-suppliers', 'getSuppliers')->name('barang.getSuppliers');
    Route::get('/barang/get-jenis-barang', 'getJenisBarang')->name('barang.getJenisBarang');
    Route::get('/barang/get-merek', 'getMerek')->name('barang.getMerek');
    Route::get('/barang/get-satuan', 'getSatuan')->name('barang.getSatuan');
});

Route::controller(DaftarHargaController::class)->group(function () {
    Route::get('/daftar-harga', 'index')->name('daftarharga.index');
    Route::get('/daftar-harga/create', 'create')->name('daftarharga.create');
    Route::get('/daftar-harga/get-barangs', 'getBarangs')->name('daftarharga.getBarangs');
    Route::post('/daftar-harga', 'store')->name('daftarharga.store');
    Route::get('/daftar-harga/{id}/edit', 'edit')->name('daftarharga.edit');
    Route::put('/daftar-harga/{id}', 'update')->name('daftarharga.update');
    Route::delete('/daftar-harga/{id}', 'destroy')->name('daftarharga.destroy');
});


Route::controller(MerekController::class)->group(function () {
    Route::get('/merek', 'index')->name('merek.index');
    Route::post('/merek', 'store')->name('merek.store');
    Route::get('/merek/edit/{id}', 'edit')->name('merek.edit');
    Route::put('/merek/{id}', 'update')->name('merek.update');
    Route::delete('/merek/{id}', 'destroy')->name('merek.destroy');
});


Route::controller(JenisBarangController::class)->group(function () {
    Route::get('/jenis-barang', 'index')->name('jenisbarang.index');
    Route::post('/jenis-barang', 'store')->name('jenisbarang.store');
    Route::get('/jenis-barang/edit/{id}', 'edit')->name('jenis-barang.edit');
    Route::put('/jenis-barang/{id}', 'update')->name('jenis-barang.update');
    Route::delete('/jenis-barang/{id}', 'destroy')->name('jenis-barang.destroy');
});

Route::controller(SatuanController::class)->group(function () {
    Route::get('/satuan', 'index')->name('satuan.index');
    Route::post('/satuan', 'store')->name('satuan.store');
    Route::get('/satuan/edit/{id}', 'edit')->name('satuan.edit');
    Route::put('/satuan/{id}', 'update')->name('satuan.update');
    Route::delete('/satuan/{id}', 'destroy')->name('satuan.destroy');
});

Route::controller(SupplierController::class)->group(function () {
    Route::get('/supplier', 'index')->name('supplier.index');
    Route::post('/supplier', 'store')->name('supplier.store');
    Route::get('/supplier/edit/{id}', 'edit')->name('supplier.edit');
    Route::put('/supplier/{id}', 'update')->name('supplier.update');
    Route::delete('/supplier/{id}', 'destroy')->name('supplier.destroy');
});

Route::controller(JenisStokController::class)->group(function () {
    Route::get('/jenis-stok', 'index')->name('jenisstok.index');
    Route::post('/jenis-stok', 'store')->name('jenisstok.store');
    Route::get('/jenis-stok/edit/{id}', 'edit')->name('jenisstok.edit');
    Route::put('/jenis-stok/{id}', 'update')->name('jenisstok.update');
    Route::delete('/jenis-stok/{id}', 'destroy')->name('jenisstok.destroy');
});

Route::controller(PelangganController::class)->group(function () {
    Route::get('/pelanggan', 'index')->name('pelanggan.index');
    Route::post('/pelanggan', 'store')->name('pelanggan.store');
    Route::get('/pelanggan/edit/{id}', 'edit')->name('pelanggan.edit');
    Route::put('/pelanggan/{id}', 'update')->name('pelanggan.update');
    Route::delete('/pelanggan/{id}', 'destroy')->name('pelanggan.destroy');
});

Route::controller(BankController::class)->group(function () {
    Route::get('/bank', 'index')->name('bank.index');
    Route::post('/bank', 'store')->name('bank.store');
    Route::get('/bank/edit/{id}', 'edit')->name('bank.edit');
    Route::put('/bank/{id}', 'update')->name('bank.update');
    Route::delete('/bank/{id}', 'destroy')->name('bank.destroy');
});

Route::controller(KaryawanController::class)->group(function () {
    Route::get('/karyawan', 'index')->name('karyawan.index');
    Route::get('/karyawan/create', 'create')->name('karyawan.create');
    Route::post('/karyawan', 'store')->name('karyawan.store');
    Route::get('/karyawan/detail/{id}', 'show')->name('karyawan.show');
    Route::get('/karyawan/edit/{id}', 'edit')->name('karyawan.edit');
    Route::put('/karyawan/{id}', 'update')->name('karyawan.update');
    Route::post('/karyawan/reset-password/{id}', 'resetPassword')->name('karyawan.resetPassword');
    Route::delete('/karyawan/{id}', 'destroy')->name('karyawan.destroy');
});

Route::controller(SettingController::class)->group(function () {
    Route::get('/setting', 'index')->name('setting.index');
    Route::put('/setting/{id}', 'update')->name('setting.update');
});

//  End Master Data Routes

// Start Report Routes
Route::controller(LaporanBarangMasukController::class)->group(function () {
    Route::get('/laporan-barang-masuk', 'index')->name('laporanbarangmasuk.index');
    Route::get('/laporan-barang-masuk/data', 'data')->name('laporanbarangmasuk.data');
    Route::get('/laporan-barang-masuk/detail/{id}', 'detail')->name('laporanbarangmasuk.detail');
});

Route::controller(DetailBarangMasukController::class)->group(function () {
    Route::get('/laporan-detail-barang-masuk', 'index')->name('laporandetailbarangmasuk.index');
    Route::get('/laporan-detail-barang-masuk/data', 'data')->name('laporandetailbarangmasuk.data');
    Route::get('/laporan-detail-barang-masuk/detail/{id}', 'detail')->name('laporandetailbarangmasuk.detail');
});

Route::controller(LaporanBarangKeluarController::class)->group(function () {
    Route::get('/laporan-barang-keluar', 'index')->name('laporanbarangkeluar.index');
    Route::get('/laporan-barang-keluar/data', 'data')->name('laporanbarangkeluar.data');
    Route::get('/laporan-barang-keluar/detail/{id}', 'detail')->name('laporanbarangkeluar.detail');
});

Route::controller(DetailBarangKeluarController::class)->group(function () {
    Route::get('/laporan-detail-barang-keluar', 'index')->name('laporandetailbarangkeluar.index');
    Route::get('/laporan-detail-barang-keluar/data', 'data')->name('laporandetailbarangkeluar.data');
    Route::get('/laporan-detail-barang-keluar/detail/{id}', 'detail')->name('laporandetailbarangkeluar.detail');
});

Route::controller(LaporanPenjualanController::class)->group(function () {
    Route::get('/laporan-penjualan', 'index')->name('laporan.penjualan.index');
    Route::get('/laporan/penjualan/{id}/detail', 'detail')->name('laporan.penjualan.detail');
});

Route::controller(PembayaranKreditController::class)->group(function () {
    Route::get('/laporan-pembayaran-kredit', 'index')->name('pembayarankredit.index');
    Route::get('/laporan-pembayaran-kredit/{id}', 'show')->name('pembayarankredit.show');
});
// End Report Routes

// Profile Routes

Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'index')->name('profile.index');
    Route::put('/profile', 'update')->name('profile.update');
});

// End Profile Routes

// API Routes for AJAX
Route::prefix('api')->group(function () {
    Route::get('/pelanggan/search', [PenjualanController::class, 'getPelanggan'])->name('api.pelanggan.search');
    Route::get('/barang/search', [PenjualanController::class, 'getProduk'])->name('api.barang.search');
});
