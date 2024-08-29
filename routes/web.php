<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\pages\IsEmirleri;
use App\Http\Controllers\front_pages\Landing;
use App\Http\Controllers\laravel_example\UserManagement;
use App\Http\Controllers\satis\SatisController;
use App\Http\Controllers\stoklar\Kalite2Controller;
use App\Http\Controllers\stoklar\Kalite2sController;

Route::get('/', [Landing::class, 'index'])->name('front-pages-landing');


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {


  // Main Page Route
  Route::get('/home', [HomePage::class, 'index'])->name('pages-home');
  Route::get('/kullanicilar', [UserManagement::class, 'GetUser'])->name('kullanicilar');
  Route::get('/isemirleri', [IsEmirleri::class, 'index'])->name('pages-isemirleri');
  Route::get('/table/test', [IsEmirleri::class, 'TestAl'])->name('test-al');

  // locale
  Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
  Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

  // authentication
  Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
  Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
  Route::resource('/user-list', UserManagement::class);
  Route::resource('/emir-list', IsEmirleri::class);
  Route::resource('/satis-list', SatisController::class);

  Route::get('/api/emirler', [IsEmirleri::class, 'getEmirler'])->name('api.emirler');



  // 2. Kaliteler - Akyazı
  Route::get('/stoklar/kalite2liste', [Kalite2Controller::class, 'getKalite2liste'])->name('stoklar.kalite2liste');
  Route::resource('/stok-list', Kalite2Controller::class);
  Route::get('/stok/verial', [Kalite2Controller::class, 'veriAl']);
  Route::get('/export/excel', [Kalite2Controller::class, 'exportExcel']);
  
  
  // 2. Kaliteler - Şekerpınar
  Route::get('/stoklar/kalite2sliste', [Kalite2sController::class, 'getKalite2liste'])->name('stoklar.kalite2sliste');
  Route::resource('/stok-lists', Kalite2sController::class);
  Route::get('/stok/verials', [Kalite2sController::class, 'veriAl']);
  Route::get('/export/excels', [Kalite2sController::class, 'exportExcel']);
  
  // Dashboard
  Route::get('/dashboard/verial', [HomePage::class, 'veriAl']);




  Route::get('/satis/musteriSiparisleri', [SatisController::class, 'getUser'])->name('satis-musteriSiparisleri');

  // Route::middleware([
  //   'auth:sanctum',
  //   config('jetstream.auth_session'),
  //   'verified',
  // ])->group(function () {
  //   Route::get('/dashboard', function () {
  //     return view('content.dashboard.dashboards-analytics');
  //   })->name('dashboard');
  // });
});
