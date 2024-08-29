<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Uretim;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomePage extends Controller
{
  public function index()
  {
    return view('content.pages.pages-home');
  }

  public function veriAl()
  {
    $tonaj = number_format(Uretim::
      where('TARIH', '>=', Carbon::now()->startOfMonth())
      ->get()->sum('KG') / 1000, 0, ",", ".");

      $siparisKgAy = number_format(DB::connection('sqlSekerpinar')->table('OFTV_MUS_SIP_AY')->get()->sum('topkg'), 0, ',', '.');
      $hammaddeKgAy = DB::connection('sqlAkyazi')->table('OFTV_HMM_GRS_AY')->get()->sum('KG');
      $hammaddeKgAy += DB::connection('sqlSekerpinar')->table('OFTV_HMM_GRS_AY')->get()->sum('KG');

      $gecenHaftaTonaj = Uretim::where('TARIH', '>=', Carbon::now()->subWeek()->startOfWeek())
              ->where('TARIH', '<=', Carbon::now()->subWeek()->startOfWeek()->addDays(Carbon::now()->dayOfWeek)->endOfDay())
              ->get()->sum('KG') / 1000;
      $buHaftaTonaj = Uretim::where('TARIH', '>=', Carbon::now()->startOfWeek())
              ->get()->sum('KG') / 1000;

    return response()->json([
      $tonaj,
      $siparisKgAy,
      number_format($hammaddeKgAy, 0, ',', '.'),
      $gecenHaftaTonaj,
      $buHaftaTonaj
    ]);
  }
}
