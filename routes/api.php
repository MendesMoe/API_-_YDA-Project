<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FirmController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OdetailController;
use App\Http\Controllers\TypeController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//////// LES ROUTES RESSOURCES /////

Route::resource('/users', UserController::class);

Route::resource('/firms', FirmController::class);

Route::resource('/products', ProductController::class);

Route::resource('/orders', OrderController::class);

Route::resource('/services', ServiceController::class);

Route::resource('/odetails', OdetailController::class);

Route::resource('/types', TypeController::class);

//////// LES ROUTES AuthController /////

Route::post('inscription', [AuthController::class, 'newUser']);

Route::get('verify-token/{token}', [AuthController::class, 'verifyToken'])->name('verify-token');

Route::post('login', [AuthController::class, 'sendMagicLink'])->name('auth.login');

Route::post('connexion', [AuthController::class, 'login']);

Route::post('logout', [AuthController::class, 'logout']);

Route::put('majMdp/{id}', [AuthController::class, 'majPassword']);

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
