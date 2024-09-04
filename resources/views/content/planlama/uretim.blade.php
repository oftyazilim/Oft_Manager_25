@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Üretim Girişleri')

<!-- Vendor Styles -->
@section('vendor-style')

    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss',
    'resources/assets/vendor/libs/animate-css/animate.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/moment/moment.js',
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js',
    'resources/assets/vendor/libs/cleavejs/cleave.js',
    'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/uretimler.js'])
@endsection


@vite(['resources/assets/vendor/libs/jquery/jquery.js'])

@section('content')



    <div class="card mt-2">
        <div class="card">
            <div class="card-datatable table-responsive-sm  text-nowrap">
                <table style="font-size: 14px" class="datatables-uretimler table-sm">
                    <thead class="border-top">
                        <tr>
                            <th>GÖSTER</th>
                            <th>İSTASYON</th>
                            <th>İŞ EMRİ NO</th>
                            <th>STOKID</th>
                            <th>MAMUL KODU</th>
                            <th>MAMUL</th>
                            <th>ANA MAMUL</th>
                            <th>MİKTAR</th>
                            <th>TARİH</th>
                            <th>NOT</th>
                            <th>ID</th>
                            <th>EYLEM</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

@endsection
