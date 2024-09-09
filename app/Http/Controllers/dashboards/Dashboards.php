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

  public function zamanAl()
  {
    $veri = StokHrkt::select('KAYITTARIH')->where('SILINDI', false)->orderBy('ID', 'desc')->first();
    return response()->json($veri);
  }

  public function miktarAl(Request $request)
  {
    $istasyon = $request->query('param1');
    $planHafta = DB::table('OFTV_01_EMIRLERISHFT')
      ->where('ISTKOD', $istasyon)
      ->select(DB::raw('SUM(PLANLANANMIKTAR) as toplam_planlanan, SUM(URETIMMIKTAR) as toplam_uretim'))
      ->first();
    $urtGun = DB::table('OFTV_01_STOKHRKTGUN')
      ->where('ISTKOD', $istasyon)
      ->select(DB::raw('SUM(MIKTAR) as toplam_uretim'))
      ->first();

    $urtHafta = DB::table('OFTV_01_STOKHRKTHFT')
      ->where('ISTKOD', $istasyon)
      ->select(DB::raw('SUM(MIKTAR) as toplam_uretim'))
      ->first();

    if ($planHafta) {
      return response()->json([
        'planHafta' => $planHafta,
        'urtGun' => $urtGun,
        'urtHafta' => $urtHafta,
      ]);
    } else {
      return response()->json([
        'message' => 'Internal Server Error',
        'code' => 500,
        'planHafta' => [],
        'urtHafta' => [],
        'urtGun' => [],
      ]);
    }
  }

  public function mesajAl()
  {
    $mesaj = DB::table('OFTT_01_AYARLAR')->select('DEGER')->where('TANIM', 'MESAJ1')->first();

    $sureler = [];
    $sure =  DB::table('haftalikSureler')->select('planDakika', 'calismaDakika')->first();
    $sureler['calisma'] = $sure->calismaDakika;
    $sureler['plan'] = $sure->planDakika;
    return response()->json(['mesaj' => $mesaj, 'sureler' => $sureler]);
  }
}
