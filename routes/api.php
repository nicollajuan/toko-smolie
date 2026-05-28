<?php

use App\Http\Controllers\Api\ProdukController;
use Illuminate\Support\Facades\Route;

Route::get('/produk',               [ProdukController::class, 'index']);
Route::post('/produk',              [ProdukController::class, 'store']);
Route::put('/produk/{id}',          [ProdukController::class, 'update']);
Route::delete('/produk/{id}',       [ProdukController::class, 'destroy']);
Route::post('/produk/upload-image', [ProdukController::class, 'uploadImage']);