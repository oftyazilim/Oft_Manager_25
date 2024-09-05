<?php

namespace App\Http\Controllers\planlama;

use App\Http\Controllers\Controller;
use App\Models\Mamul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Mamuller extends Controller
{

  public function getMamuller()
  {
    $mamuller = Mamul::where('SILINDI', false)->get();

    $anaGrup = DB::table('OFTT_01_MAMULLER')->select('MMLGRPKOD')->distinct()->get();
    $stGrup = DB::table('OFTT_01_MAMULLER')->select('STGRPKOD')->distinct()->get();
    $sinif = DB::table('OFTT_01_MAMULLER')->select('SINIF')->distinct()->get();

    return view('content.planlama.mamuller', compact('mamuller', 'anaGrup', 'stGrup', 'sinif'));
  }

  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $columns = [
      1 => 'KOD',
      2 => 'TANIM',
      3 => 'STGRPKOD',
      4 => 'MMLGRPKOD',
      5 => 'SINIF',
      6 => 'AKTIF',
      7 => 'MEVCUT',
      8 => 'ID',
    ];

    $search = [];

    // $toplamKg = Kalite2::where('silindi', false)->where('sevk_edildi', false)->get()->sum('kantarkg');

    // $totalData = Kalite2::where('silindi', false)->where('sevk_edildi', false)->get()->count();

    // $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if (empty($request->input('search.value'))) {
      $mamuller = Mamul::where('SILINDI', false)
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();
    } else {
      $search = $request->input('search.value');

      $mamuller = Mamul::where('SILINDI', false)
        ->where('KOD', 'LIKE', "%{$search}%")
        ->orWhere('TANIM', 'LIKE', "%{$search}%")
        ->orWhere('STGRPKOD', 'LIKE', "%{$search}%")
        ->orWhere('MMLGRPKOD', 'LIKE', "%{$search}%")
        ->orWhere('SINIF', 'LIKE', "%{$search}%")
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();
    }

    $data = [];

    if (!empty($mamuller)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($mamuller as $klt) {
        $nestedData['KOD'] = $klt->KOD;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['TANIM'] = $klt->TANIM;
        $nestedData['STGRPKOD'] = $klt->STGRPKOD;
        $nestedData['MMLGRPKOD'] = $klt->MMLGRPKOD;
        $nestedData['SINIF'] = $klt->SINIF;
        $nestedData['AKTIF'] = $klt->AKTIF;
        $nestedData['MEVCUT'] = $klt->MEVCUT;
        $nestedData['ID'] = $klt->ID;

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

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $kayitid = (int)$request->ID;
    $isAktif = $request->has('AKTIF') ? 1 : 0;
    $operatorID = Auth::user()->id;

    if ($kayitid) {
      //   $mamul = Mamul::findOrFail($kayitid);

      //   if ($mamul) {
      //     $mamul->update([
      //       'KOD' => $request->KOD,
      //       'TANIM' => $request->TANIM,
      //       'STGRPKOD' => $request->STGRPKOD,
      //       'MMLGRPKOD' => $request->MMLGRPKOD,
      //       'AKTIF' => $isAktif,
      //       'DUZENLEYENID' => $operatorID,
      //       'SONDRMTARIH' => now()
      //     ]);
      $mamul = Mamul::updateOrCreate(
        ['ID' => $kayitid], // Güncelleme için eşleştirilecek koşul
        [
          'KOD' => $request->KOD,
          'TANIM' => $request->TANIM,
          'STGRPKOD' => $request->STGRPKOD,
          'MMLGRPKOD' => $request->MMLGRPKOD,
          'SINIF' => $request->SINIF,
          'AKTIF' => $request->has('AKTIF') ? 1 : 0,
          'DUZENLEYENID' => $operatorID, // Örnek olarak operatör ID burada sabit
          'SONDRMTARIH' => now()
        ]
      );

      return response()->json('Updated');
      // } else {
      //   return response()->json('Record not found', 404);
      // }

    } else {
      $mamul = Mamul::updateOrCreate(
        ['ID' => $kayitid], // Güncelleme için eşleştirilecek koşul
        [
          'KOD' => $request->KOD,
          'TANIM' => $request->TANIM,
          'STGRPKOD' => $request->STGRPKOD,
          'MMLGRPKOD' => $request->MMLGRPKOD,
          'SINIF' => $request->SINIF,
          'AKTIF' => $request->has('AKTIF') ? 1 : 0,
          'DUZENLEYENID' => 1, // Örnek olarak operatör ID burada sabit
          'SONDRMTARIH' => now()
        ]
      );

      return response()->json($mamul->wasRecentlyCreated ? 'Created' : 'Updated');
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $yMamuller = Mamul::distinct()->where('ID', $id)->get();
    return response()->json($yMamuller);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $kayitid = (int)$id;
    $operatorID = Auth::user()->id;
    $mamul = Mamul::updateOrCreate(
      ['ID' => $kayitid],
      [
        'SILINDI' => 1,
        'SILENID' => $operatorID,
        'SILINMETARIH' => now(),
      ]
    );

    return response()->json($kayitid);
  }

  public function exportExcel(Request $request)
  {
    $search = $request->input('search');
    if (empty($search)) {
      $mamulVeriler = Mamul::where('SILINDI', false)->get();
    } else {
      $mamulVeriler = Mamul::where('SILINDI', false)
        ->where('KOD', 'LIKE', "%{$search}%")
        ->orWhere('TANIM', 'LIKE', "%{$search}%")
        ->orWhere('STGRPKOD', 'LIKE', "%{$search}%")
        ->orWhere('MMLGRPKOD', 'LIKE', "%{$search}%")
        ->orWhere('SINIF', 'LIKE', "%{$search}%")
        ->get();
    }

    // JSON formatında döndürün
    return response()->json($mamulVeriler);
  }
}
