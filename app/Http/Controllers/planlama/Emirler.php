<?php

namespace App\Http\Controllers\planlama;

use App\Http\Controllers\Controller;
use App\Models\Emir;
use App\Models\StokHrkt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\search;

class Emirler extends Controller
{
  public function getEmirler()
  {
    // $emirler = DB::table('OFTV_01_EMIRLERIS')->get();

    $anaGrup = DB::table('OFTT_01_MAMULLER')->select('MMLGRPKOD')->distinct()->get();
    $stGrup = DB::table('OFTT_01_MAMULLER')->select('STGRPKOD')->distinct()->get();
    $istasyon = DB::table('OFTT_01_ISTASYONLAR')->select('ID', 'TANIM')->distinct()->get();

    return view('content.planlama.emirleris', compact('anaGrup', 'stGrup', 'istasyon'));
  }

  public function MamulAl($ISTKOD)
  {
    $mamul = DB::table('OFTT_01_MAMULLER')
      ->where('STGRPKOD', $ISTKOD)
      ->where('SILINDI', 0)
      ->select('KOD', 'TANIM', 'MMLGRPKOD', 'STGRPKOD')
      ->distinct()
      ->get();

    return response()->json($mamul);
  }

  public function index(Request $request)
  {
    $columns = [
      1 => 'ID',
      2 => 'ISTASYONID',
      3 => 'URUNID',
      4 => 'KOD',
      5 => 'TANIM',
      6 => 'MMLGRPKOD',
      7 => 'PLANLANANMIKTAR',
      8 => 'URETIMMIKTAR',
      9 => 'URETIMSIRA',
      10 => 'DURUM',
      11 => 'NOTLAR',
      12 => 'PROSESNOT',
      13 => 'KAYITTARIH',
      14 => 'ISTKOD',
      15 => 'ISTTANIM',
      16 => 'AKTIF',
    ];

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    $search = [];
    $istasyon = $request->input('grupSecimi');

    if (empty($request->input('search.value'))) {
      $emirler = DB::table('OFTV_01_EMIRLERIS')->where('ISTTANIM',  'LIKE', "%{$istasyon}%")->orderBy('URETIMSIRA', 'asc')->get();
    } else {
      $search = $request->input('search.value');

      $emirler = DB::table('OFTV_01_EMIRLERIS')
        ->where(function ($query) use ($istasyon) {
          $query->where('ISTTANIM',  'LIKE', "%{$istasyon}%"); // ISTKOD alanı için mutlak eşleşme
        })
        ->where(function ($query) use ($search) {
          $query->where('KOD', 'LIKE', "%{$search}%")
            ->orWhere('TANIM', 'LIKE', "%{$search}%")
            ->orWhere('ISTTANIM', 'LIKE', "%{$search}%")
            ->orWhere('MMLGRPKOD', 'LIKE', "%{$search}%")
            ->orWhere('DURUM', 'LIKE', "%{$search}%")
            ->orWhere('KAYITTARIH', 'LIKE', "%{$search}%");
        })
        ->orderBy('URETIMSIRA', 'asc')
        ->get();

      // $emirler = DB::table('OFTV_01_EMIRLERIS')
      //   ->where('KOD', 'LIKE', "%{$search}%")
      //   ->orWhere('TANIM', 'LIKE', "%{$search}%")
      //   ->orWhere('ISTTANIM', 'LIKE', "%{$search}%")
      //   ->orWhere('MMLGRPKOD', 'LIKE', "%{$search}%")
      //   ->orWhere('DURUM', 'LIKE', "%{$search}%")
      //   ->orWhere('KAYITTARIH', 'LIKE', "%{$search}%")
      //   ->orWhere('ISTKOD', 'LIKE', "%{$istasyon}%")
      //   ->orderBy('URETIMSIRA', 'asc')
      //   ->get();
    }

    $data = [];

    if (!empty($emirler)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($emirler as $klt) {
        $nestedData['fake_id'] = ++$ids;
        $nestedData['URETIMSIRA'] = $klt->URETIMSIRA;
        $nestedData['ID'] = $klt->ID;
        $nestedData['ISTASYONID'] = $klt->ISTASYONID;
        $nestedData['URUNID'] = $klt->URUNID;
        $nestedData['KOD'] = $klt->KOD;
        $nestedData['TANIM'] = $klt->TANIM;
        $nestedData['MMLGRPKOD'] = $klt->MMLGRPKOD;
        $nestedData['PLANLANANMIKTAR'] = number_format($klt->PLANLANANMIKTAR, 0, ',', '.');
        $nestedData['URETIMMIKTAR'] = number_format($klt->URETIMMIKTAR, 0, ',', '.');
        $nestedData['PROGRESS'] = number_format(($klt->URETIMMIKTAR / $klt->PLANLANANMIKTAR) * 100, 0, ',', '.');
        $nestedData['DURUM'] = $klt->DURUM;
        $nestedData['NOTLAR'] = $klt->NOTLAR;
        $nestedData['PROSESNOT'] = $klt->PROSESNOT;
        $nestedData['KAYITTARIH'] = $klt->KAYITTARIH;
        $nestedData['ISTKOD'] = $klt->ISTKOD;
        $nestedData['ISTTANIM'] = $klt->ISTTANIM;
        $nestedData['AKTIF'] = $klt->AKTIF;

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

  public function store(Request $request)
  {
    $kayitid = (int)$request->ID;
    $isAktif = $request->has('AKTIF') ? 1 : 0;

    $operator = User::where('name', Auth::user()->name)->select('id')->first();

    if ($operator) {
      $operatorID = $operator->id;
    } else {
      $operatorID = null;
    }

    $isttemp = DB::table('OFTT_01_ISTASYONLAR')
      ->where('ID', $request->ISTKOD)
      ->select('TANIM')
      ->first();

    $mamultemp = DB::table('OFTT_01_MAMULLER')
      ->where('STGRPKOD', $isttemp->TANIM)
      ->where('KOD', $request->TANIM)
      ->where('SILINDI', 0)
      ->select('ID', 'TANIM', 'MMLGRPKOD', 'STGRPKOD')
      ->first();

    if ($kayitid) {
      $emir = Emir::updateOrCreate(
        ['ID' => $kayitid],
        [
          'ISTASYONID' => $request->ISTKOD,
          'URUNID' => $mamultemp->ID,
          'PLANLANANMIKTAR' => $request->PLANLANANMIKTAR,
          'DURUM' => $request->DURUM,
          'DUZENLEYENID' => $operatorID,
          'DUZENTARIH' =>  now(),
          'NOTLAR' =>  $request->NOTLAR,
          'URETIMSIRA' =>  $request->URETIMSIRA,
          'AKTIF' => $request->has('AKTIF') ? 1 : 0,
        ]
      );
      $this->yenidenSirala();
      return response()->json('Updated');
    } else {
      $emir = Emir::updateOrCreate(
        ['ID' => $kayitid],
        [
          'ISTASYONID' => $request->ISTKOD,
          'URUNID' => $mamultemp->ID,
          'PLANLANANMIKTAR' => $request->PLANLANANMIKTAR,
          'DURUM' => $request->DURUM,
          'OLUSTURANID' => $operatorID,
          'KAYITTARIH' =>  now(),
          'NOTLAR' =>  $request->NOTLAR,
          'URETIMSIRA' =>  $request->URETIMSIRA,
          'AKTIF' => $request->has('AKTIF') ? 1 : 0,
        ]
      );
      $this->yenidenSirala();
      return response()->json($emir->wasRecentlyCreated ? 'Created' : 'Updated');
    }
  }

  public function show(string $id)
  {
    //
  }

  public function edit(string $id)
  {
    $emirler1 = DB::table('OFTV_01_EMIRLERIS')->where('ID', $id)->get();
    return response()->json($emirler1);
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

    $emir = Emir::updateOrCreate(
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
      $emirVeriler = DB::table('OFTV_01_EMIRLERIS')->get();
    } else {

      $emirVeriler = DB::table('OFTV_01_EMIRLERIS')
        ->where('KOD', 'LIKE', "%{$search}%")
        ->orWhere('TANIM', 'LIKE', "%{$search}%")
        ->orWhere('ISTTANIM', 'LIKE', "%{$search}%")
        ->orWhere('MMLGRPKOD', 'LIKE', "%{$search}%")
        ->orWhere('DURUM', 'LIKE', "%{$search}%")
        ->orWhere('KAYITTARIH', 'LIKE', "%{$search}%")
        ->orWhere('ISTKOD', 'LIKE', "%{$search}%")
        ->get();
    }

    return response()->json($emirVeriler);
  }

  public function yukariAt(Request $request)
  {
    $id = $request->input('id');

    // Geçerli kaydı bul
    $currentRecord = Emir::where('SILINDI', false)->find($id);

    if (!$currentRecord) {
      return response()->json(['success' => false, 'message' => 'Kayıt bulunamadı.']);
    }

    // Önceki kaydı bul
    $previousRecord = Emir::where('URETIMSIRA', '<', $currentRecord->URETIMSIRA)
    ->where('ISTASYONID',  $currentRecord->ISTASYONID)
      ->orderBy('URETIMSIRA', 'desc')
      ->first();

    if (!$previousRecord) {
      return response()->json(['success' => false, 'message' => 'Önceki kayıt bulunamadı.']);
    }

    // Sıra numaralarını değiştir
    $currentSira = $currentRecord->URETIMSIRA;
    $previousSira = $previousRecord->URETIMSIRA;

    $currentRecord->URETIMSIRA = $previousSira;
    $previousRecord->URETIMSIRA = $currentSira;

    // Veritabanını güncelle
    $currentRecord->save();
    $previousRecord->save();

    return response()->json(['success' => true]);
  }

  public function asagiAt(Request $request)
  {
    $id = $request->input('id');

    // Geçerli kaydı bul
    $currentRecord = Emir::where('SILINDI', false)->find($id);

    if (!$currentRecord) {
      return response()->json(['success' => false, 'message' => 'Kayıt bulunamadı.']);
    }

    // Önceki kaydı bul
    $nextRecord = Emir::where('URETIMSIRA', '>', $currentRecord->URETIMSIRA)
    ->where('ISTASYONID', $currentRecord->ISTASYONID)
    ->orderBy('URETIMSIRA', 'asc')
      ->first();

    if (!$nextRecord) {
      return response()->json(['success' => false, 'message' => 'Sonraki kayıt bulunamadı.']);
    }

    // Sıra numaralarını değiştirin
    $currentSira = $currentRecord->URETIMSIRA;
    $currentRecord->URETIMSIRA = $nextRecord->URETIMSIRA;
    $nextRecord->URETIMSIRA = $currentSira;

    // Değişiklikleri kaydedin
    $currentRecord->save();
    $nextRecord->save();

    return response()->json(['success' => true]);
  }

  public function yenidenSirala()
  {
    // Tablodaki tüm kayıtları 'sira' sütununa göre artan sırayla al
    $records = Emir::where('SILINDI', false)->orderBy('URETIMSIRA', 'asc')->get();

    // Yeni sıra numarasını başlat
    $newSira = 10;

    // Her bir kaydı döngü ile yeniden sıralama
    foreach ($records as $record) {
      // Mevcut kaydın sıra numarasını güncelle
      $record->URETIMSIRA = $newSira;
      $record->save();

      // Sıra numarasını bir sonraki adım için 10 artır
      $newSira += 10;
    }

    return response()->json(['success' => true]);
  }

  public function uretimKaydet(Request $request)
  {
    $kayitid = (int)$request->id;
    $operator = User::where('name', Auth::user()->name)->select('id')->first();
    $miktar = (int)$request->uretim_miktar;

    if ($operator) {
      $operatorID = $operator->id;
    } else {
      $operatorID = null;
    }

    // $mamultemp = Emir::where('ID', $kayitid)
    //   ->where('KOD', $request->TANIM)
    //   ->where('SILINDI', 0)
    //   ->select('ID', 'TANIM', 'MMLGRPKOD', 'STGRPKOD')
    //   ->first();

    $emir = Emir::find($kayitid);
    try {
      $emir->URETIMMIKTAR += $miktar;
      $emir->save();

      $hrkt = StokHrkt::create(
        [
          'TUR' => 'Üretimden Giriş',
          'STOKID' => $emir->URUNID,
          'ISTASYONID' => $emir->ISTASYONID,
          'ISEMRIID' => $kayitid,
          'MIKTAR' => $miktar,
          'OLUSTURANID' => $operatorID,
          'URETIMTARIH' =>  $request->tarih,
          'KAYITTARIH' =>  now(),
          'NOTLAR' =>  $request->notlar,
        ]
      );
      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
  }
}
