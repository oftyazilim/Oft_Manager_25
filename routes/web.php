<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\front_pages\Landing;
use App\Http\Controllers\laravel_example\UserManagement;
use App\Http\Controllers\planlama\Mamuller;
use App\Http\Controllers\planlama\Emirler;
use App\Http\Controllers\planlama\Uretimler;

Route::get('/', [Landing::class, 'index'])->name('front-pages-landing');


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {


  // Main Page Route
  Route::get('/home', [HomePage::class, 'index'])->name('pages-home');
  Route::get('/kullanicilar', [UserManagement::class, 'GetUser'])->name('kullanicilar');

  // locale
  Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
  Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

  // authentication
  Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
  Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
  Route::resource('/user-list', UserManagement::class);


  // Planlama
  Route::get('/planlama/mamuller', [Mamuller::class, 'getMamuller'])->name('planlama.mamuller');
  Route::get('/planlama/uretimler', [Uretimler::class, 'getListe'])->name('planlama.uretimler');
  Route::get('/planlama/isemirleri', [Emirler::class, 'getEmirler'])->name('planlama.isemirleri');

  Route::resource('/mamul-list', Mamuller::class);
  Route::resource('/emir-list', Emirler::class);
  Route::resource('/uretim-list', Uretimler::class);

  Route::get('/emir-list/mamulal/{ISTKOD}', [Emirler::class, 'MamulAl']);
  Route::get('/exportmamul/excel', [Mamuller::class, 'exportExcel']);
  Route::get('/exportemir/excel', [Emirler::class, 'exportExcel']);
  Route::post('/emir/yukariat', [Emirler::class, 'yukariAt']);
  Route::post('/emir/asagiat', [Emirler::class, 'asagiAt']);
  Route::post('/emir/uretimkaydet', [Emirler::class, 'uretimKaydet']);
  Route::post('/uretim/uretimkaydet', [Uretimler::class, 'uretimKaydet']);

  // Dashboard
  Route::get('/dashboard/verial', [HomePage::class, 'veriAl']);




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
