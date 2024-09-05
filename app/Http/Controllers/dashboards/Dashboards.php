<?php

namespace App\Http\Controllers\dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Dashboards extends Controller
{
  public function montaj_01()
  {
    $pageConfigs = ['myLayout' => 'front'];
    return view('content.dashboards.montaj_01', ['pageConfigs' => $pageConfigs]);
  }
}
