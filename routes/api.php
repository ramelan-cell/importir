<?php

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CategoryBarangController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get_user', [ApiController::class, 'get_user']);
    Route::get('categorybarang', [CategoryBarangController::class, 'index']);
    Route::get('categorybarang/{id}', [CategoryBarangController::class, 'show']);
    Route::post('categorybarang/create', [CategoryBarangController::class, 'store']);
    Route::put('categorybarang/update/{barang}',  [CategoryBarangController::class, 'update']);
    Route::delete('categorybarang/delete/{barang}',  [CategoryBarangController::class, 'destroy']);
    Route::get('barang', [BarangController::class, 'index']);
    Route::get('barang/{id}', [BarangController::class, 'show']);
    Route::post('barang/create', [BarangController::class, 'store']);
    Route::put('barang/update/{barang}',  [BarangController::class, 'update']);
    Route::delete('barang/delete/{barang}',  [BarangController::class, 'destroy']);
    Route::get('barangmasuk', [BarangMasukController::class, 'index']);
    Route::get('barangmasuk/{id}', [BarangMasukController::class, 'show']);
    Route::post('barangmasuk/create', [BarangMasukController::class, 'store']);
    Route::put('barangmasuk/update/{barangmasuk}',  [BarangMasukController::class, 'update']);
    Route::delete('barangmasuk/delete/{barangmasuk}',  [BarangMasukController::class, 'destroy']);
    Route::get('barangkeluar', [BarangKeluarController::class, 'index']);
    Route::get('barangkeluar/{id}', [BarangKeluarController::class, 'show']);
    Route::post('barangkeluar/create', [BarangKeluarController::class, 'store']);
    Route::put('barangkeluar/update/{barangkeluar}',  [BarangKeluarController::class, 'update']);
    Route::delete('barangkeluar/delete/{barangkeluar}',  [BarangKeluarController::class, 'destroy']);
    Route::get('laporanstok', [BarangController::class, 'laporanStok']);
    Route::post('laporanbarangmasuk', [BarangMasukController::class, 'laporanBarangMasuk']);
    Route::post('laporanbarangkeluar', [BarangKeluarController::class, 'laporanBarangKeluar']);
});
