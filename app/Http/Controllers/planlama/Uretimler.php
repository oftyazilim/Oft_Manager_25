<?php

namespace App\Http\Controllers\planlama;

use App\Http\Controllers\Controller;
use App\Models\Emir;
use App\Models\StokHrkt;
use App\Models\User;
use App\Models\Mamul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Uretimler extends Controller
{

  public function getListe()
  {
    return view('content.planlama.uretim');
  }


  public function index(Request $request)
  {
    $columns = [
      1 => 'ID',
      2 => 'ISTKOD',
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
    $istasyon = $request->input('grupSecimi');


    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if (empty($request->input('search.value'))) {
      $kayitlar = DB::table('OFTV_01_STOKHRKT')
        ->where('ISTKOD',  'LIKE', "%{$istasyon}%")
        ->orderBy('ID', 'desc')
        ->get();
    } else {
      $search = $request->input('search.value');

      $kayitlar = DB::table('OFTV_01_STOKHRKT')
        ->where(function ($query) use ($istasyon) {
          $query->where('ISTKOD',  'LIKE', "%{$istasyon}%"); // ISTKOD alanı için mutlak eşleşme
        })
        ->where(function ($query) use ($search) {
          $query->where('KOD', 'LIKE', "%{$search}%")
            ->orWhere('TANIM', 'LIKE', "%{$search}%")
            ->orWhere('MMLGRPKOD', 'LIKE', "%{$search}%")
            ->orWhere('ID', 'LIKE', "%{$search}%")
            ->orWhere('NOTLAR', 'LIKE', "%{$search}%")
            ->orWhere('URETIMTARIH', 'LIKE', "%{$search}%");
        })
        ->orderBy('ID', 'desc')
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
    $uretimler = DB::table('OFTV_01_STOKHRKT')->where('ID', $id)->get();
    return response()->json($uretimler);
  }


  public function update(Request $request, string $id)
  {
    //
  }


  public function destroy(string $id)
  {
    $kayitid = (int)$id;
    $operator = User::where('name', Auth::user()->name)->select('id')->first();

    if ($operator) {
      $operatorID = $operator->id;
    } else {
      $operatorID = null;
    }

    $hrkt = StokHrkt::select('ISEMRIID', 'MIKTAR', 'STOKID')->where('ID', $kayitid)->first();

    $emir = Emir::find($hrkt->ISEMRIID);
    $emir->URETIMMIKTAR -= (int)$hrkt->MIKTAR;
    $emir->save();

    $mml = Mamul::find($hrkt->STOKID);
    $mml->MEVCUT -= (int)$hrkt->MIKTAR;
    $mml->GIREN -= (int)$hrkt->MIKTAR;
    $mml->save();

    $hrkte = StokHrkt::updateOrCreate(
      ['ID' => $kayitid],
      [
        'SILINDI' => 1,
        'SILENID' => $operatorID,
        'SILINMETARIH' => now(),
      ]
    );

    return response()->json($kayitid);
  }


  public function uretimKaydet(Request $request)
  {
    $kayitid = (int)$request->id;
    $operator = User::where('name', Auth::user()->name)->select('id')->first();
    $miktar = (int)$request->uretim_miktar -(int)$request->miktarTemp;

    if ($operator) {
      $operatorID = $operator->id;
    } else {
      $operatorID = null;
    }

    $hrkt = StokHrkt::select('ISEMRIID', 'MIKTAR', 'STOKID')->where('ID', $kayitid)->first();

    $emir = Emir::find($request->isemriid);
    try {

      $emir->URETIMMIKTAR += (int)$request->uretim_miktar -(int)$request->miktarTemp;
      $emir->save();

      $mml = Mamul::find($hrkt->STOKID);
      $mml->MEVCUT += (int)$request->uretim_miktar -(int)$request->miktarTemp;
      $mml->GIREN += (int)$request->uretim_miktar -(int)$request->miktarTemp;
      $mml->save();

      $hrkt = StokHrkt::find($kayitid);
      $hrkt->MIKTAR = (int)$request->uretim_miktar;
      $hrkt->DUZENLEYENID = $operatorID;
      $hrkt->URETIMTARIH = $request->tarih;
      $hrkt->DUZENTARIH = now();
      $hrkt->NOTLAR = $request->notlar;
      $hrkt->save();

      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
  }


  public function exportExcel(Request $request)
  {
    $istasyon = $request->input('grupSecimi');
    $search = $request->input('search');

    if (empty($search)) {
      $emirVeriler = DB::table('OFTV_01_STOKHRKT')->get();
    } else {

      $emirVeriler = DB::table('OFTV_01_STOKHRKT')
      ->where(function ($query) use ($istasyon) {
        $query->where('ISTKOD',  'LIKE', "%{$istasyon}%"); // ISTKOD alanı için mutlak eşleşme
      })
      ->where(function ($query) use ($search) {
        $query->where('KOD', 'LIKE', "%{$search}%")
          ->orWhere('TANIM', 'LIKE', "%{$search}%")
          ->orWhere('MMLGRPKOD', 'LIKE', "%{$search}%")
          ->orWhere('ID', 'LIKE', "%{$search}%")
          ->orWhere('NOTLAR', 'LIKE', "%{$search}%")
          ->orWhere('URETIMTARIH', 'LIKE', "%{$search}%");
      })
      ->orderBy('ID', 'desc')
      ->get();
    }

    return response()->json($emirVeriler);
  }
}
