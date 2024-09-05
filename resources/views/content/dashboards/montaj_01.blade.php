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
    @vite(['resources/assets/css/montaj_01.css'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/montaj_01.js'])
@endsection



@section('layoutContent')


    <div class="card m-1">
        <div class="border rounded p-2">
            <h6 class="centered-div " id="guncelleme" style="font-size: 24px; justify-content: right;
            padding-right: 10px; color:limegreen;">
                Son Güncelleme: 05.09.2024 23:00
            </h6>
        </div>
    </div>

    <div class="custom-row">

        <div class="custom-col card m-1">
            <div class="card-header">BORU</div>
            <div class="card-body  p-2 m-3">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Haftalık Plan</h6>
                    <h4 class="miktar" id="boruPlan" style="color: limegreen">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Üretilen</h6>
                    <h4 class="miktar" id="boruUretilen" style="color: rgb(139, 136, 236)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Kalan</h6>
                    <h4 class="miktar" id="boruKalan" style="color: rgb(210, 138, 138)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Anlık Performans</h6>
                    <h4  id="boruAnlikYuzde" class="miktar-yuzde"><span class="yuzdeIsareti">%0</span></h4>
                </div>
            </div>
        </div>

        <div class="custom-col card m-1">
            <div class="card-header">POMPA</div>
            <div class="card-body  p-2 m-3">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Haftalık Plan</h6>
                    <h4 class="miktar" style="color: limegreen">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Üretilen</h6>
                    <h4 class="miktar" style="color: rgb(139, 136, 236)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Kalan</h6>
                    <h4 class="miktar" style="color: rgb(210, 138, 138)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Anlık Performans</h6>
                    <h4 class="miktar-yuzde" style="color: rgb(217, 227, 73)">%80</h4>
                </div>
            </div>
        </div>

        <div class="custom-col card m-1">
            <div class="card-header">ŞASE</div>
            <div class="card-body  p-2 m-3">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Haftalık Plan</h6>
                    <h4 class="miktar" style="color: limegreen">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Üretilen</h6>
                    <h4 class="miktar" style="color: rgb(139, 136, 236)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Kalan</h6>
                    <h4 class="miktar" style="color: rgb(210, 138, 138)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Anlık Performans</h6>
                    <h4 class="miktar-yuzde" >%80</h4>
                </div>
            </div>
        </div>

        <div class="custom-col card m-1">
            <div class="card-header">SİFON</div>
            <div class="card-body  p-2 m-3">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Haftalık Plan</h6>
                    <h4 class="miktar" style="color: limegreen">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Üretilen</h6>
                    <h4 class="miktar" style="color: rgb(139, 136, 236)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Kalan</h6>
                    <h4 class="miktar" style="color: rgb(210, 138, 138)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Anlık Performans</h6>
                    <h4 class="miktar-yuzde" style="color: rgb(217, 227, 73)">%80</h4>
                </div>
            </div>
        </div>

        <div class="custom-col card m-1">
            <div class="card-header">VENTURİ</div>
            <div class="card-body  p-2 m-3">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Haftalık Plan</h6>
                    <h4 class="miktar" style="color: limegreen">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Üretilen</h6>
                    <h4 class="miktar" style="color: rgb(139, 136, 236)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Kalan</h6>
                    <h4 class="miktar" style="color: rgb(210, 138, 138)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Anlık Performans</h6>
                    <h4 class="miktar-yuzde" style="color: rgb(217, 227, 73)">%80</h4>
                </div>
            </div>
        </div>

        <div class="custom-col card m-1">
            <div class="card-header">GALVANİZ</div>
            <div class="card-body  p-2 m-3">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Haftalık Plan</h6>
                    <h4 class="miktar" style="color: limegreen">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Üretilen</h6>
                    <h4 class="miktar" style="color: rgb(139, 136, 236)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Kalan</h6>
                    <h4 class="miktar" style="color: rgb(210, 138, 138)">0</h4>
                </div>
                <div class="border rounded p-2 mt-3">
                    <h6 class="text-center baslik">Anlık Performans</h6>
                    <h4 class="miktar-yuzde" style="color: rgb(217, 227, 73)">%80</h4>
                </div>
            </div>
        </div>




    </div>

    <div class="custom-row">
        <div class="custom-col card m-1" style="height: 150px; ">
            <div class="card-body p-1 m-1">
                <div class="border rounded p-2">
                    <h6 class="text-center baslik">Gnl Performans</h6>
                    <h4 id="text-container" class="fade-text text-center mt-4 pt-3"
                        style="color: limegreen;
                    font-size: 70px; font-weight: bold;">%70
                    </h4>
                </div>
            </div>
        </div>
        <div class="custom-col-yazi card m-1">
            <div class="card-body  p-1 m-1">
                <div class="border rounded p-2">
                    <div class="marquee">
                        <h1 class="marquee-text">Sevgili arkadaşlar verilerimizi zamanında, eksiksiz ve doğru girelim!</h1>
                    </div>
                </div>
            </div>

        </div>
    </div>




    <div id="text-container" class="fade-text">
        <!-- Dinamik olarak yazılar burada değişecek -->
    </div>
@endsection
