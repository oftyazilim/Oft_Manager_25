<?php

namespace App\Http\Controllers\dashboards;

use App\Http\Controllers\Controller;
use App\Models\StokHrkt;
use App\Models\Emir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Dashboards extends Controller
{
  public function montaj_01()
  {
    $pageConfigs = ['myLayout' => 'front'];
    return view('content.dashboards.montaj_01', ['pageConfigs' => $pageConfigs]);
  }

  public function zamanAl(){
    $veri = StokHrkt::select('KAYITTARIH')->orderBy('ID', 'desc')->first();
    return response()->json($veri);
  }

  public function miktarAl(Request $request)
  {
      $istasyon = $request->query('param1');
      $plan = DB::table('OFTV_01_EMIRLERIS')
              ->where('ISTKOD', $istasyon)
              ->select(DB::raw('SUM(PLANLANANMIKTAR) as toplam_planlanan, SUM(URETIMMIKTAR) as toplam_uretim'))
              ->first();

      // JSON olarak döndür
      return response()->json($plan);
  }

}
