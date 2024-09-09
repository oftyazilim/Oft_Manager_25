@isset($pageConfigs)
    {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

@php
    $configData = Helper::appClasses();

    /* Display elements */
    $customizerHidden = $customizerHidden ?? '';

@endphp
{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

@extends('layouts/commonMaster')

<!-- Page Scripts -->
@section('page-style')
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/montaj_01.js'])
@endsection



@section('layoutContent')
    <div class="card m-1">
        <div class="row border rounded p-2">
            <div class="col-6 sola-div">
                <img class="logo justify-content-center" src="{{ asset('assets/img/logoson.png') }}" alt="">
                <h3 class="mt-7 ms-3 ">OFT Yazılım</h3>
            </div>
            <h6 class="col-6 saga-div" id="guncelleme" style="font-size: 24px; padding-right: 10px; color:limegreen;">
                Son Veri Güncelleme: 05.09.2024 23:00
            </h6>

        </div>
    </div>

    <div class="custom-row">

        <div class="custom-col card m-1 ">
            <div class="card-header pt-0 m-0">BORU</div>
            <div class="card-body p-0 m-1">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Haftalık Plan</h6>
                    <h4 class="miktar" id="BG-1Plan" style="color: limegreen">0</h4>
                </div>
                <div class="border rounded p-2 mt-2">
                    <h5 class="text-center baslik">Üretilen</h5>
                    <h3 class="baslikUretim fade-text text-center" style="font-size: 20px; margin-top: -20px;"></h3>
                    <h4 class="miktarUretim1 fade-text miktar" id="BG-1Uretilen"
                        style="font-size: 100px; margin-top: 20px; color: rgb(139, 136, 236)">0</h4>
                </div>
                <div class="border rounded p-2 mt-2">
                    <h6 class="text-center baslik">Kalan</h6>
                    <h4 class="miktar" id="BG-1Kalan" style="color: rgb(210, 138, 138)">0</h4>
                </div>
                <div class="border rounded p-2 mt-2">
                    <h6 class="text-center baslik">Durum (%)</h6>
                    <h4 id="BG-1AnlikYuzde" class="miktar-yuzde"></h4>
                    </span>
                    <div class="progress" style="height: 20px;">
                        <div id="BG-1Progress" class="progress-bar" role="progressbar"
                            style="width: 0%; background-color: yellow;" aria-valuenow="0" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-col card m-1">
            <div class="card-header pt-0 m-0">POMPA</div>
            <div class="card-body p-0 m-1">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Haftalık Plan</h6>
                    <h4 class="miktar" id="PG-1Plan" style="color: limegreen">0</h4>
                </div>
                <div class="border rounded p-2 mt-2">
                    <h5 class="text-center baslik">Üretilen</h5>
                    <h3 class="baslikUretim fade-text text-center" style="font-size: 20px; margin-top: -20px;"></h3>
                    <h4 class="miktarUretim2 fade-text miktar" id="PG-1Uretilen"
                        style="font-size: 100px; margin-top: 20px; color: rgb(139, 136, 236)">0</h4>
                </div>
                <div class="border rounded p-2 mt-2">
                    <h6 class="text-center baslik">Kalan</h6>
                    <h4 class="miktar" id="PG-1Kalan" style="color: rgb(210, 138, 138)">0</h4>
                </div>
                <div class="border rounded p-2 mt-2">
                    <h6 class="text-center baslik">Durum (%)</h6>
                    <h4 id="PG-1AnlikYuzde" class="miktar-yuzde"></h4>
                    <div class="progress" style="height: 20px;">
                        <div id="PG-1Progress" class="progress-bar" role="progressbar"
                            style="width: 0%; background-color: yellow;" aria-valuenow="0" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-col card m-1">
            <div class="card-header pt-0 m-0">ŞASE</div>
            <div class="card-body p-0 m-1">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Haftalık Plan</h6>
                    <h4 class="miktar" id="ŞG-1Plan" style="color: limegreen">0</h4>
                </div>
                <div class="border rounded p-2 mt-2">
                    <h5 class="text-center baslik">Üretilen</h5>
                    <h3 class="baslikUretim fade-text text-center" style="font-size: 20px; margin-top: -20px;"></h3>
                    <h4 class="miktarUretim3 fade-text miktar" id="ŞG-1Uretilen"
                        style="font-size: 100px; margin-top: 20px; color: rgb(139, 136, 236)">0</h4>
                </div>
                <div class="border rounded p-2 mt-2">
                    <h6 class="text-center baslik">Kalan</h6>
                    <h4 class="miktar" id="ŞG-1Kalan" style="color: rgb(210, 138, 138)">0</h4>
                </div>
                <div class="border rounded p-2 mt-2">
                    <h6 class="text-center baslik">Durum (%)</h6>
                    <h4 id="ŞG-1AnlikYuzde" class="miktar-yuzde"></h4>
                    <div class="progress" style="height: 20px;">
                        <div id="ŞG-1Progress" class="progress-bar" role="progressbar"
                            style="width: 0%; background-color: yellow;" aria-valuenow="0" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-col card m-1">
            <div class="card-header pt-0 m-0">SİFON</div>
            <div class="card-body p-0 m-1">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Haftalık Plan</h6>
                    <h4 class="miktar" id="SG-1Plan" style="color: limegreen">0</h4>
                </div>
                <div class="border rounded p-2 mt-2">
                    <h5 class="text-center baslik">Üretilen</h5>
                    <h3 class="baslikUretim fade-text text-center" style="font-size: 20px; margin-top: -20px;"></h3>
                    <h4 class="miktarUretim4 fade-text miktar" id="SG-1Uretilen"
                        style="font-size: 100px; margin-top: 20px; color: rgb(139, 136, 236)">0</h4>
                </div>
                <div class="border rounded p-2 mt-2">
                    <h6 class="text-center baslik">Kalan</h6>
                    <h4 class="miktar" id="SG-1Kalan" style="color: rgb(210, 138, 138)">0</h4>
                </div>
                <div class="border rounded p-2 mt-2">
                    <h6 class="text-center baslik">Durum (%)</h6>
                    <h4 id="SG-1AnlikYuzde" class="miktar-yuzde"></h4>
                    <div class="progress" style="height: 20px;">
                        <div id="SG-1Progress" class="progress-bar" role="progressbar"
                            style="width: 0%; background-color: yellow;" aria-valuenow="0" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-col card m-1">
            <div class="card-header pt-0 m-0">VENTURİ</div>
            <div class="card-body p-0 m-1">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Haftalık Plan</h6>
                    <h4 class="miktar" id="VG-1Plan" style="color: limegreen">0</h4>
                </div>
                <div class="border rounded p-2 mt-2">
                    <h5 class="text-center baslik">Üretilen</h5>
                    <h3 class="baslikUretim fade-text text-center" style="font-size: 20px; margin-top: -20px;"></h3>
                    <h4 class="miktarUretim5 fade-text miktar" id="VG-1Uretilen"
                        style="font-size: 100px; margin-top: 20px; color: rgb(139, 136, 236)">0</h4>
                </div>
                <div class="border rounded p-2 mt-2">
                    <h6 class="text-center baslik">Kalan</h6>
                    <h4 class="miktar" id="VG-1Kalan" style="color: rgb(210, 138, 138)">0</h4>
                </div>
                <div class="border rounded p-2 mt-2">
                    <h6 class="text-center baslik">Durum (%)</h6>
                    <h4 id="VG-1AnlikYuzde" class="miktar-yuzde"></h4>
                    <div class="progress" style="height: 20px;">
                        <div id="VG-1Progress" class="progress-bar" role="progressbar"
                            style="width: 0%; background-color: yellow;" aria-valuenow="0" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-col card m-1">
            <div class="card-header pt-0 m-0">GALVANİZ</div>
            <div class="card-body p-0 m-1">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Haftalık Plan</h6>
                    <h4 class="miktar" id="GG-1Plan" style="color: limegreen">0</h4>
                </div>
                <div class="border rounded p-2 mt-2">
                    <h5 class="text-center baslik">Üretilen</h5>
                    <h3 class="baslikUretim fade-text text-center" style="font-size: 20px; margin-top: -20px;"></h3>
                    <h4 class="miktarUretim6 fade-text miktar" id="GG-1Uretilen"
                        style="font-size: 100px; margin-top: 20px; color: rgb(139, 136, 236)">0</h4>
                </div>
                <div class="border rounded p-2 mt-2">
                    <h6 class="text-center baslik">Kalan</h6>
                    <h4 class="miktar" id="GG-1Kalan" style="color: rgb(210, 138, 138)">0</h4>
                </div>
                <div class="border rounded p-2 mt-1">
                    <h6 class="text-center baslik">Durum (%)</h6>
                    <h4 id="GG-1AnlikYuzde" class="miktar-yuzde"></h4>
                    <div class="progress" style="height: 20px;">
                        <div id="GG-1Progress" class="progress-bar" role="progressbar"
                            style="width: 0%; background-color: yellow;" aria-valuenow="0" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>




    </div>

    <div class="custom-row">
        <div class="custom-col card m-1" style="height: 150px; ">
            <div class="card-body p-1 m-1">
                <div class="border rounded p-0 pb-2 pt-2">
                    <h6 class="text-center baslik">Genel Durum</h6>
                    <h4 id="text-container" class="fade-text1 text-center mt-4 pt-3"
                        style="color: limegreen;
                    font-size: 70px; font-weight: bold;"><span
                            class="yuzdeIsareti">%0</span>
                    </h4>
                </div>
            </div>
        </div>
        <div class="custom-col-yazi card m-1">
            <div class="card-body  p-1 m-1">
                <div class="border rounded p-2">
                    <div class="marquee">
                        <h1 id="altMesaj" class="marquee-text"></h1>
                    </div>
                </div>
            </div>

        </div>
    </div>




    <div id="text-container" class="fade-text">
        <!-- Dinamik olarak yazılar burada değişecek -->
    </div>
@endsection
