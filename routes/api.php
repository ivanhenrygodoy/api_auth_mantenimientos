<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MntProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function (){
    Route::post('/login', 'login');
});

Route::prefix('/products')->group(function () {
    Route::get('/', [MntProductController::class, 'search_product'])->middleware('auth', 'api');
    Route::post('/create-product', [MntProductController::class, 'post_product'])->middleware('auth', 'api');
    Route::put('/update-product/{id}', [MntProductController::class, 'put_product'])->middleware('auth', 'api');
    Route::put('/change-state-product/{id}', [MntProductController::class, 'change_product_state'])->middleware('auth', 'api');
});




