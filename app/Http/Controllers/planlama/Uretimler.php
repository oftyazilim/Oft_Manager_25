<?php

namespace App\Http\Controllers\planlama;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StokHrkt;
use Illuminate\Support\Facades\DB;

class Uretimler extends Controller
{

  public function getListe() {
    return view('content.planlama.uretim');
  }


  public function index(Request $request)
  {
    $columns = [
      1 => 'ID',
      2 => 'STOKID',
      3 => 'ISEMRIID',
      4 => 'STOKID',
      5 => 'KOD',
      6 => 'TANIM',
      7 => 'MMLGRPKOD',
      8 => 'MIKTAR',
      9 => 'URETIMTARIH',
      10 => 'NOTLAR',
    ];

    $search = [];

    $totalData = StokHrkt::count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if (empty($request->input('search.value'))) {
      $kayitlar = DB::table('OFTV_01_STOKHRKT')
        ->get();
    } else {
      $search = $request->input('search.value');

      $kayitlar = DB::table('OFTV_01_STOKHRKT')
      ->get();
    }

    $data = [];

    if (!empty($kayitlar)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($kayitlar as $kayit) {
        $nestedData['ID'] = $kayit->ID;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['ISTKOD'] = $kayit->ISTKOD;
        $nestedData['ISEMRIID'] = $kayit->ISEMRIID;
        $nestedData['STOKID'] = $kayit->STOKID;
        $nestedData['KOD'] = $kayit->KOD;
        $nestedData['TANIM'] = $kayit->TANIM;
        $nestedData['MMLGRPKOD'] = $kayit->MMLGRPKOD;
        $nestedData['MIKTAR'] = $kayit->MIKTAR;
        $nestedData['URETIMTARIH'] = $kayit->URETIMTARIH;
        $nestedData['NOTLAR'] = $kayit->NOTLAR;

        $data[] = $nestedData;
      }
    }

    if ($data) {
      return response()->json([
        'draw' => intval($request->input('draw')),
        'code' => 200,
        'data' => $data,
      ]);
    } else {
      return response()->json([
        'message' => 'Internal Server Error',
        'code' => 500,
        'data' => [],
      ]);
    }
  }


  public function create()
  {
    //
  }


  public function store(Request $request)
  {
    //
  }


  public function show(string $id)
  {
    //
  }


  public function edit(string $id)
  {
    //
  }


  public function update(Request $request, string $id)
  {
    //
  }


  public function destroy(string $id)
  {
    //
  }
}
