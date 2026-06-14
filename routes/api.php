<?php

use App\Http\Controllers\Api\ProdukController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\TransaksiController;

//  upload-image HARUS di atas /{id} agar tidak bentrok
Route::post('/produk/upload-image', [ProdukController::class, 'uploadImage']);

Route::get('/produk',               [ProdukController::class, 'index']);
Route::post('/produk',              [ProdukController::class, 'store']);
Route::put('/produk/{id}',          [ProdukController::class, 'update']);
Route::delete('/produk/{id}',       [ProdukController::class, 'destroy']);

Route::get('/kategori',             [KategoriController::class, 'index']);
Route::post('/kategori',            [KategoriController::class, 'store']);
Route::put('/kategori/{id}',        [KategoriController::class, 'update']);
Route::delete('/kategori/{id}',     [KategoriController::class, 'destroy']);

Route::post('/transaksi',                    [TransaksiController::class, 'store']);
Route::get('/transaksi',                     [TransaksiController::class, 'index']);
Route::get('/transaksi/all',                 [TransaksiController::class, 'all']);
Route::put('/transaksi/{id}/confirm',        [TransaksiController::class, 'confirm']);
Route::get('/transaksi/history/{email}',     [TransaksiController::class, 'history']);