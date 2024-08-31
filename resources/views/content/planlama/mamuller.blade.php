@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Mamul Listesi')

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
    @vite(['resources/assets/js/mamuller.js'])
@endsection


@vite(['resources/assets/vendor/libs/jquery/jquery.js'])

@section('content')



    <div class="card mt-2">
        <div class="card">
            <div class="card-datatable table-responsive-sm  text-nowrap">
                <table style="font-size: 14px" class="datatables-mamuller table-sm">
                    <thead class="border-top">
                        <tr>
                            <th>GÖSTER</th>
                            <th>MAMUL KODU</th>
                            <th>MAMUL</th>
                            <th>GRUP KODU</th>
                            <th>ANA MAMUL</th>
                            <th>SINIF</th>
                            <th>AKTİF</th>
                            <th>ID</th>
                            <th>EYLEM</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>


    <!-- Offcanvas to add record -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddRecord" aria-labelledby="offcanvasAddLabel">
        <div class="offcanvas-header border-bottom">
            <h5 id="offcanvasAddLabel" class="offcanvas-title"></h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
            <form class="add-new-record pt-0" id="addNewRecordForm">
                <input type="hidden" name="ID" id="record_id">
                <div class="mb-6">
                    <label class="form-label" for="KOD">Kod</label>
                    <input type="text" id="KOD" class="form-control" name="KOD"></input>
                </div>
                <div class="mb-6">
                    <label class="form-label" for="TANIM">Tanım</label>
                    <input type="text" id="TANIM" class="form-control" name="TANIM" />
                </div>
                <div class="mb-6">
                    <label class="form-label" for="STGRPKOD">Grup</label>
                    <select id="STGRPKOD" class="form-select form-select" name="STGRPKOD">
                        <option value="">Seçiniz</option>
                        @foreach ($stGrup as $mam)
                            <option value="{{ $mam->STGRPKOD }}">{{ $mam->STGRPKOD }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-6">
                    <label class="form-label" for="MMLGRPKOD">Ana Mamul</label>
                    <select id="MMLGRPKOD" class="form-select form-select" name="MMLGRPKOD">
                        <option value="">Seçiniz</option>
                        @foreach ($anaGrup as $mamuls)
                            <option value="{{ $mamuls->MMLGRPKOD }}">{{ $mamuls->MMLGRPKOD }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-6">
                    <label class="form-label" for="SINIF">Sınıf</label>
                    <select id="SINIF" class="form-select dt-input" name="SINIF">
                        @foreach ($sinif as $snf)
                            <option value="{{ $snf->SINIF }}">{{ $snf->SINIF }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-6">
                    <label class="form-check m-0">
                        <input id="AKTIF" type="checkbox" name="AKTIF" class="form-check-input" value="" />
                        <span class="form-check-label">Aktif</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary me-3 data-submit">Kaydet</button>
                <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Vazgeç</button>
            </form>
        </div>
    </div>


@endsection
