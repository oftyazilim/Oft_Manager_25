@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', '2. Kalite Listesi')

<!-- Vendor Styles -->
@section('vendor-style')

    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/stoklar-kalite2-list.js'])

@endsection


@vite(['resources/assets/vendor/libs/jquery/jquery.js'])

@section('content')

    <div class="row g-6 mb-6">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Toplam Paket Adedi</span>
                            <h4 id="toplamPaket" class="mb-0 me-2">0<span style="font-size: 14px;"> Kg</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Toplam Ağırlık (Genel)</span>
                            <h4 id="toplamGenel" class="mb-0 me-2">0<span style="font-size: 14px;"> Kg</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Toplam Ağırlık (HR)</span>
                            <h4 id="toplamHr" class="mb-0 me-2">0<span style="font-size: 14px;"> Kg</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Toplam Ağırlık (Diğer)</span>
                            <h4 id="toplamDiger" class="mb-0 me-2">0<span style="font-size: 14px;"> Kg</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-2">
        <div class="card">
            <div class="card-datatable table-responsive-sm  text-nowrap">
                <table style="font-size: 14px" class="datatables-kalite2 table-sm">
                    <thead class="border-top">
                        <tr>
                            <th>GÖSTER</th>
                            <th>MAMÜL</th>
                            <th>BOY</th>
                            <th>GERÇ.KG</th>
                            <th>ADET</th>
                            <th>TEOR.KG</th>
                            <th>NEVİ</th>
                            <th style="text-align: center;">PAKET NO</th>
                            <th>HAT</th>
                            <th>TARİH</th>
                            <th>SAAT</th>
                            <th>PERSONEL</th>
                            <th>MAMUL KODU</th>
                            <th>BASILDI</th>
                            <th>ID</th>
                            <th>EYLEM</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th colspan="2">Genel Toplam :</th>
                            <th style="font-weight: bold; font-size: 14px; text-align: right;"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>


        <!-- Offcanvas to add new user -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddRecord" aria-labelledby="offcanvasAddLabel">
            <div class="offcanvas-header border-bottom">
                <h5 id="offcanvasAddLabel" class="offcanvas-title"></h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
                <form class="add-new-record pt-0" id="addNewRecordForm">
                    <input type="hidden" name="id" id="record_id">
                    <div class="mb-6">
                        <label class="form-label" for="mamul">Mamul</label>
                        <select id="mamul" class="form-select dt-input" name="mamul">
                            <option value="">Seçiniz</option>
                            @foreach ($mamullers as $mamuls)
                                <option value="{{ $mamuls->mamul }}">{{ $mamuls->mamul }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="boy">Boy</label>
                        <input type="number" id="boy" step="1" class="form-control dt-input" placeholder="6000"
                            aria-label="6000" name="boy" />
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="adet">Adet</label>
                        <input type="number" id="adet" step="1" class="form-control" placeholder="0"
                            aria-label="0" name="adet" />
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="kantarkg">Kantar Kg</label>
                        <input type="number" id="kantarkg" step="0.01" class="form-control" placeholder="0"
                            aria-label="0" name="kantarkg" />
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="nevi">Nevi</label>
                        <select id="nevi" class="form-select dt-input" name="nevi">
                            <option value="">Seçiniz</option>
                            <option value="HR">HR</option>
                            <option value="CR">CR</option>
                            <option value="DOV">DOV</option>
                            <option value="BYL">BYL</option>
                            <option value="GAL">GAL</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="hat">Hat</label>
                        <select id="hat" class="form-select" name="hat">
                            <option value="">Seçiniz</option>
                            <option value="MA-1">MA-1</option>
                            <option value="MA-2">MA-2</option>
                            <option value="MA-3">MA-3</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="form-check m-0">
                            <input id="kayit-basildi" type="checkbox" name="basildi" class="form-check-input"
                                value="" />
                            <span class="form-check-label">Basıldı</span>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary me-3 data-submit">Gönder</button>
                    <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Vazgeç</button>
                </form>
            </div>
        </div>


    </div>

@endsection
