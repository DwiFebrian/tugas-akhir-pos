<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

// Route::get('/', function () {
//     return view('dashboard');
// });
Route::get('/', [Controller::class, 'readDashboard'])->middleware('auth');
Route::get('/index.php', [Controller::class, 'readDashboard'])->middleware('auth');

// LOGIN ==============================================================================
Route::get('/login', [Controller::class, 'login'])->name('login')->middleware('guest');
Route::post('/loginAct', [Controller::class, 'loginAct']);


// Route::group(['middleware' => ['auth', 'kasir']], function () {
//     // Definisi rute yang hanya bisa diakses oleh kasir
//     Route::get('/dashboard', [Controller::class, 'readDashboard']);
//     Route::get('/kasir', [Controller::class, 'readKasir']);
//     Route::get('/penjualan', [Controller::class, 'readPenjualan']);
//     Route::get('/keungan', [Controller::class, 'readKeuangan']);
// });

// Route::group(['middleware' => ['auth', 'gudang']], function () {
//     // Definisi rute yang hanya bisa diakses oleh gudang
//     Route::get('/dashboard', [Controller::class, 'readDashboard']);
//     Route::get('/kategori', [Controller::class, 'readKategori']);
//     Route::get('/produk', [Controller::class, 'readProduk']);
// });

// Route::group(['middleware' => ['auth', 'admin']], function () {
//     // Definisi rute yang hanya bisa diakses oleh admin
//     Route::get('/dashboard', [Controller::class, 'readDashboard']);
//     Route::get('/kasir', [Controller::class, 'readKasir']);
//     Route::get('/kategori', [Controller::class, 'readKategori']);
//     Route::get('/penjualan', [Controller::class, 'readPenjualan']);
//     Route::get('/produk', [Controller::class, 'readProduk']);
//     Route::get('/keungan', [Controller::class, 'readKeuangan']);
//     // Route::get('/kasir', [Controller::class, 'readKasir']);
//     // Route::get('/kasir', [Controller::class, 'readKasir']);
// });

Route::post('/logout', [Controller::class, 'logout']);


// DASHBOARD ==========================================================================
Route::get('/dashboard', [Controller::class, 'readDashboard'])->middleware('auth');

// KASIR ==============================================================================
Route::get('/kasir', [Controller::class, 'readKasir'])->middleware('auth'); //tampil data
Route::post('/kasir/actKasir', [Controller::class, 'actKasir'])->middleware('auth'); //view create
Route::get('/kasir/resetKasir', [Controller::class, 'resetKasir'])->middleware('auth'); //insert data
Route::get('/kasir/deleteKasir/{id}', [Controller::class, 'deleteKasir']); //view update
Route::post('/kasir/updateKasir', [Controller::class, 'updateKasir'])->middleware('auth'); //update data
Route::post('/kasir/simpan', [Controller::class, 'simpanPenjualan'])->middleware('auth'); //action delete
//................................................................................................
Route::post('/kasir/recalculate', [Controller::class, 'recalculate']);
Route::get('/getProducts', [Controller::class, 'getProducts']);


// PENJUALAN ==========================================================================
Route::get('/penjualan', [Controller::class, 'readPenjualan'])->middleware('auth'); //tampil data

// DETAIL PENJUALAN ===================================================================
Route::get('/detail-penjualan', [Controller::class, 'readDPenjualan'])->middleware('auth'); //tampil data

// KATEGORI ===========================================================================
Route::get('/kategori', [Controller::class, 'readKategori'])->middleware('auth'); //tampil data
Route::get('/kategori/createKategori', [Controller::class, 'createKategori'])->middleware('auth'); //view create
Route::post('/kategori', [Controller::class, 'storeKategori'])->middleware('auth'); //insert data
Route::get('/kategori/{id}/update', [Controller::class, 'editKategori'])->middleware('auth'); //view update
Route::put('/kategori/{id}', [Controller::class, 'updateKategori'])->middleware('auth'); //update data
Route::delete('/kategori/{id}', [Controller::class, 'deleteKategori'])->middleware('auth'); //action delete

// PRODUK =============================================================================
Route::get('/produk', [Controller::class, 'readProduk'])->middleware('auth'); //tampil data
Route::get('/produk/createProduk', [Controller::class, 'createProduk'])->middleware('auth'); //view create
Route::post('/produk', [Controller::class, 'storeProduk'])->middleware('auth'); //insert data
Route::get('/produk/{id}/update', [Controller::class, 'editProduk'])->middleware('auth'); //view update
Route::put('/produk/{id}', [Controller::class, 'updateProduk'])->middleware('auth'); //update data
Route::delete('/produk/{id}', [Controller::class, 'deleteProduk'])->middleware('auth'); //action delete
// Route::get('/produk/{id}/barcode', [Controller::class, 'generateBarcode'])->name('produk.generateBarcode');


// Keuangan =============================================================================
Route::get('/keuangan', [Controller::class, 'readKeuangan'])->middleware('auth'); //tampil data
Route::get('/keuangan/createKeuangan', [Controller::class, 'createKeuangan'])->middleware('auth'); //view create
Route::post('/keuangan', [Controller::class, 'storeKeuangan'])->middleware('auth'); //insert data
Route::get('/keuangan/{id}/update', [Controller::class, 'editKeuangan'])->middleware('auth'); //view update
Route::put('/keuangan/{id}', [Controller::class, 'updateKeuangan'])->middleware('auth'); //update data
Route::get('/filter', [Controller::class, 'filter']); //FILTER DATA



// USER =============================================================================
Route::get('/user', [Controller::class, 'readUser'])->middleware('auth'); //tampil data
Route::get('/user/createUser', [Controller::class, 'createUser'])->middleware('auth');; //view create
Route::post('/user', [Controller::class, 'storeUser'])->middleware('auth');; //insert data
Route::get('/user/{id}/update', [Controller::class, 'editUser'])->middleware('auth');; //view update
Route::put('/user/{id}', [Controller::class, 'updateUser'])->middleware('auth');; //update data
Route::delete('/user/{id}', [Controller::class, 'deleteUser'])->middleware('auth');; //action delete