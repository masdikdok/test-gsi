<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('home');
});

Route::match(['get', 'post'], '/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')
    ->as('admin.')
    ->group(function() {
        Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('produksi', [\App\Http\Controllers\Admin\ProduksiController::class, 'index'])->name('produksi');
        Route::match(['post', 'get'], 'produksi/tambah', [\App\Http\Controllers\Admin\ProduksiController::class, 'tambah'])->name('produksi.tambah');
        Route::match(['post', 'get'], 'produksi/edit/{id}', [\App\Http\Controllers\Admin\ProduksiController::class, 'edit'])->name('produksi.edit');
        Route::delete('produksi/hapus/{id}', [\App\Http\Controllers\Admin\ProduksiController::class, 'hapus'])->name('produksi.hapus');

        Route::get('chart/produksi', [\App\Http\Controllers\Admin\DashboardController::class, 'chartProduksi'])->name('chart.produksi');
        Route::get('chart/item', [\App\Http\Controllers\Admin\DashboardController::class, 'chartBestItem'])->name('chart.best.item');
        Route::get('chart/lokasi', [\App\Http\Controllers\Admin\DashboardController::class, 'chartBestLokasi'])->name('chart.best.lokasi');
 });
