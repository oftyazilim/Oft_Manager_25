<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MachineController extends Controller
{
  public function getPerformance()
  {
      // Veritabanından gerçekleşen iş emirlerini al
      $machines = DB::table('machine')->get(); // İş emri tamamlayan makinelerin verileri
      return response()->json($machines);
  }
}
