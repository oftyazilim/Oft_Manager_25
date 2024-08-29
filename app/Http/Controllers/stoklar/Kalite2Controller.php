<?php

namespace App\Http\Controllers\stoklar;

use App\Http\Controllers\Controller;
use App\Models\Kalite2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class Kalite2Controller extends Controller
{
  public function exportExcel(Request $request)
  {
    $search = $request->input('search');
    if (empty($search)) {
      $kalite2Veriler = Kalite2::where('silindi', false)->where('sevk_edildi', false)->get();
    } else {
      $kalite2Veriler = Kalite2::where('silindi', false)->where('sevk_edildi', false)->
      where('mamul', 'LIKE', "%{$search}%")
        ->orWhere('nevi', 'LIKE', "%{$search}%")
        ->orWhere('pkno', 'LIKE', "%{$search}%")
        ->orWhere('operator', 'LIKE', "%{$search}%")
        ->get();
    }

    // JSON formatında döndürün
    return response()->json($kalite2Veriler);
  }

  public function kompleAl()
  {
    $kalite2 = Kalite2::where('silindi', false)->where('sevk_edildi', false)->get();

    return response()->json([
      $kalite2,
    ]);
  }

  public function getKalite2liste()
  {
    $kalite2 = Kalite2::where('silindi', false)->where('sevk_edildi', false)->get();

    $mamullers = DB::connection('sqlAkyazi')
      ->table('mamuller')
      ->select('mamul')
      ->where('tip', 'Boru')
      ->orWhere('tip', 'Profil')
      ->distinct()
      ->get();

    $nevi = DB::connection('sqlAkyazi')
      ->table('mamuller')
      ->select('nevi')
      ->where('tip', 'Boru')
      ->orWhere('tip', 'Profil')
      ->distinct()
      ->get();

    $hatlar = DB::connection('sqlAkyazi')
      ->table('caldurum')
      ->select('hat')
      ->distinct()
      ->get();

    return view('content.stoklar.Kalite2liste', compact('mamullers', 'kalite2', 'hatlar', 'nevi'));
  }

  public function veriAl()
  {
    $kalite2 = Kalite2::where('silindi', false)->where('sevk_edildi', false)->get();

    $paketCount = $kalite2->count();
    $toplamKg = number_format($kalite2->sum('kantarkg'), 0, ',', '.');

    $toplamKgHr = number_format($kalite2->where('silindi', false)->where('sevk_edildi', false)->where('nevi', '==', 'HR')->sum('kantarkg'), 0, ',', '.');
    $toplamKgDiger = number_format($kalite2->where('silindi', false)->where('sevk_edildi', false)->where('nevi', '!=', 'HR')->sum('kantarkg'), 0, ',', '.');

    return response()->json([
      $paketCount,
      $toplamKg,
      $toplamKgHr,
      $toplamKgDiger,
    ]);
  }

  public function index(Request $request)
  {
    $columns = [
      1 => 'mamul',
      2 => 'boy',
      3 => 'adet2',
      4 => 'kantarkg',
      5 => 'adet',
      6 => 'kg',
      7 => 'nevi',
      8 => 'pkno',
      9 => 'hat',
      10 => 'tarih',
      11 => 'saat',
      12 => 'operator',
      13 => 'mamulkodu',
      14 => 'basildi',
      15 => 'id',
    ];

    $search = [];

    $toplamKg = Kalite2::where('silindi', false)->where('sevk_edildi', false)->get()->sum('kantarkg');

    $totalData = Kalite2::where('silindi', false)->where('sevk_edildi', false)->get()->count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if (empty($request->input('search.value'))) {
      $kalite2 = Kalite2::where('silindi', false)->where('sevk_edildi', false)
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();
    } else {
      $search = $request->input('search.value');

      $kalite2 = Kalite2::where('silindi', false)->where('sevk_edildi', false)
        ->where('mamul', 'LIKE', "%{$search}%")
        ->orWhere('nevi', 'LIKE', "%{$search}%")
        ->orWhere('pkno', 'LIKE', "%{$search}%")
        ->orWhere('operator', 'LIKE', "%{$search}%")->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();

      $toplamKg = Kalite2::where('silindi', false)->where('sevk_edildi', false)
        ->where('mamul', 'LIKE', "%{$search}%")
        ->orWhere('boy', 'LIKE', "%{$search}%")
        ->orWhere('nevi', 'LIKE', "%{$search}%")->sum('kantarkg');

      $totalFiltered = Kalite2::where('silindi', false)->where('sevk_edildi', false)
        ->where('mamul', 'LIKE', "%{$search}%")
        ->orWhere('boy', 'LIKE', "%{$search}%")
        ->orWhere('nevi', 'LIKE', "%{$search}%")->count();
    }

    $data = [];

    if (!empty($kalite2)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($kalite2 as $klt) {
        $nestedData['mamul'] = $klt->mamul;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['adet2'] = number_format($klt->adet2, 0, ',', '.');
        $nestedData['boy'] = $klt->boy;
        $nestedData['kantarkg'] = number_format($klt->kantarkg, 0, ',', '.');
        $nestedData['adet'] = number_format($klt->adet, 0, ',', '.');
        $nestedData['kg'] = number_format($klt->kg, 2, ',', '.');
        $nestedData['nevi'] = $klt->nevi;
        $nestedData['pkno'] = $klt->pkno;
        $nestedData['hat'] = $klt->hat;
        $nestedData['tarih'] = $klt->tarih;
        $nestedData['saat'] = $klt->saat;
        $nestedData['operator'] = $klt->operator;
        $nestedData['mamulkodu'] = $klt->mamulkodu;
        $nestedData['basildi'] = $klt->basildi;
        $nestedData['id'] = $klt->id;

        $data[] = $nestedData;
      }
    }

    if ($data) {
      return response()->json([
        'draw' => intval($request->input('draw')),
        'toplamKg' => number_format($toplamKg, 0, ',', '.'),
        'recordsTotal' => intval($totalData),
        'recordsFiltered' => intval($totalFiltered),
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

  public function destroy($id)
  {
    // Mevcut kaydı bul
    $kayit = Kalite2::find($id);

    // Kayıt bulunduysa güncelle
    if ($kayit) {
        $kayit->silen = Auth::user()->name;
        $kayit->silindi = 1;
        $kayit->silindi_at = now();
        $kayit->save(); // Güncellenmiş veriyi kaydet

        return response()->json('Silindi');
    }

    // Kayıt bulunamadıysa hata mesajı döndür
    return response()->json('Kayıt bulunamadı', 404);
  }

  public function edit($id): JsonResponse
  {
    $kalite2 = Kalite2::distinct()->where('id', $id)
      ->get();
    return response()->json($kalite2);
  }

  public function store(Request $request)
  {
    $kayitID = $request->id;

    if ($kayitID) {
      $isBasildi = $request->has('basildi') ? 1 : 0;
      $mamuller = DB::connection('sqlAkyazi')
        ->table('mamuller')
        ->select('mamulkodu', 'minkg', 'kalinlik')
        ->where('mamul', $request->mamul)
        ->where('nevi', $request->nevi)
        ->first();
      $teorikKg = $request->adet2 * $mamuller->minkg * ($request->boy / 1000);

      $kayit = Kalite2::updateOrCreate(
        ['id' => $kayitID],
        [
          'mamul' => $request->mamul,
          'boy' => $request->boy,
          'nevi' => $request->nevi,
          'adet2' => $request->adet2,
          'kg' => $teorikKg,
          'hat' => $request->hat,
          'mamulkodu' => $mamuller->mamulkodu,
          'kantarkg' => $request->kantarkg,
          'kalinlik' => $mamuller->kalinlik,
          'basildi' => $isBasildi,
          'updated_at' => now(),
        ]
      );
      return response()->json('Updated');
    } else {
      $isBasildi = $request->has('basildi') ? 1 : 0;
      $currentDate = Carbon::now()->format('Y-m-d');
      $currentTime = Carbon::now()->format('H:i:s');
      $operatorName = Auth::user()->name;
      $paketno = $this->paketNoAl($request->hat);
      $mamuller = DB::connection('sqlAkyazi')
        ->table('mamuller')
        ->select('mamulkodu', 'minkg', 'kalinlik')
        ->where('mamul', $request->mamul)
        ->where('nevi', $request->nevi)
        ->first();
      $teorikKg = $request->adet2 * $mamuller->minkg * ($request->boy / 1000);

      $kayit = Kalite2::updateOrCreate(
        ['id' => $kayitID],
        [
          'mamul' => $request->mamul,
          'boy' => $request->boy,
          'pkno' => $paketno,
          'tarih' => $currentDate,
          'saat' => $currentTime,
          'hat' => $request->hat,
          'operator' => $operatorName,
          'nevi' => $request->nevi,
          'mamulkodu' => $mamuller->mamulkodu,
          'adet' => $request->adet2,
          'adet2' => $request->adet2,
          'kg' => $teorikKg,
          'kantarkg' => $request->kantarkg,
          'kalinlik' => $mamuller->kalinlik,
          'basildi' => $isBasildi,
          'created_at' => now(),
          'updated_at' => now(),
        ]
      );
      return response()->json('Created');
    }
  }

  public function paketNoAl(string $hat)
  {
    $pkno = 1;
    $tarih = now();


    if (substr($hat, 1, 1) === "A") {
      switch (substr($hat, 3, 1)) {
        case "1":
          $pkno = 1001;
          break;
        case "2":
          $pkno = 2001;
          break;
        case "3":
          $pkno = 3001;
          break;
        case "4":
          $pkno = 4001;
          break;
      }
    } else {
      switch (substr($hat, 3, 1)) {
        case "1":
          $pkno = 5001;
          break;
        case "2":
          $pkno = 6001;
          break;
        case "3":
          $pkno = 7001;
          break;
        case "4":
          $pkno = 8001;
          break;
      }
    }


    // Veriyi güncelle ve sonra çek
    DB::connection('sqlAkyazi')->table('paketno')
      ->where('hat', $hat)
      ->increment('paketno', 1);

    $paketNoData = DB::connection('sqlAkyazi')->table('paketno')
      ->where('hat', $hat)
      ->select('tarih', 'paketno')
      ->first();

    if ($paketNoData) {
      $tarih = Carbon::parse($paketNoData->tarih);
      $pkno = $paketNoData->paketno;
    }

    if (strtotime($tarih) < strtotime(Carbon::create(Carbon::now()->year, Carbon::now()->month, Carbon::now()->day))) {
      $tarih = Carbon::now();
      if (substr($hat, 1, 1) === "A") {
        switch (substr($hat, 3, 1)) {
          case "1":
            $pkno = 1001;
            break;
          case "2":
            $pkno = 2001;
            break;
          case "3":
            $pkno = 3001;
            break;
          case "4":
            $pkno = 4001;
            break;
        }
      } else {
        switch (substr($hat, 3, 1)) {
          case "1":
            $pkno = 5001;
            break;
          case "2":
            $pkno = 6001;
            break;
          case "3":
            $pkno = 7001;
            break;
          case "4":
            $pkno = 8001;
            break;
        }
      }

      DB::connection('sqlAkyazi')->table('paketno')
        ->where('hat', $hat)
        ->update([
          'tarih' => $tarih,
          'paketno' => $pkno
        ]);
    }

    $paketno = sprintf('%02d %02d %02d %04d', $tarih->year % 100, $tarih->month, $tarih->day, $pkno);

    return $paketno;
  }
}
