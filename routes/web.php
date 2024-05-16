<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\HistoriController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\ProfileController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');

Route::get('home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout')->middleware('auth');

Route::get('register', [RegisterController::class, 'register'])->name('register');
Route::post('register/action', [RegisterController::class, 'actionregister'])->name('actionregister');

Route::controller(MobilController::class)->group(function(){
    Route::get('mobil', 'index')->middleware('auth');
    Route::post('mobil/addData', 'addData')->middleware('auth');
    Route::post('mobil/getData', 'getData')->middleware('auth');
    Route::post('mobil/getDataDetail', 'getDataDetail')->middleware('auth');
    Route::post('mobil/updateData', 'updateData')->middleware('auth');
    Route::post('mobil/getDataHistori', 'getDataHistori')->middleware('auth');
});

Route::controller(PenggunaController::class)->group(function(){
    Route::get('pengguna', 'index')->middleware('auth');
    Route::post('pengguna/getData', 'getData')->middleware('auth');
});

Route::controller(DashboardUserController::class)->group(function(){
    Route::get('dashboard_user', 'index')->middleware('auth');
    Route::post('dashboard_user/getDataMobil', 'getDataMobil')->middleware('auth');
    Route::post('dashboard_user/addTransaction', 'addTransaction')->middleware('auth');
});

Route::controller(DashboardAdminController::class)->group(function(){
    Route::get('dashboard_admin', 'index')->middleware('auth');
    Route::post('dashboard_admin/getData', 'getData')->middleware('auth');
    Route::post('dashboard_admin/addTransaction', 'addTransaction')->middleware('auth');
});

Route::controller(HistoriController::class)->group(function(){
    Route::get('histori', 'index')->middleware('auth');
    Route::post('histori/getData', 'getData')->middleware('auth');
});

Route::controller(ProfileController::class)->group(function(){
    Route::get('profile', 'index')->middleware('auth');
});