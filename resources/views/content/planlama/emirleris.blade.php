@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'İş Emirleri Listesi')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/cleavejs/cleave.js', 'resources/assets/vendor/libs/cleavejs/cleave-phone.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/emirleris.js'])
@endsection


@vite(['resources/assets/vendor/libs/jquery/jquery.js'])

@section('content')





    <!-- Modal -->
    <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <input type="hidden" name="ID" id="rec_id">
                <input type="hidden" name="URUNID" id="urun_id">
                <div class="modal-header">
                    <h3 class="modal-title" id="modalCenterTitle">Modal title</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label for="mamul" class="form-label">Mamül</label>
                            <input type="text" id="mamul" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row g-4 mb-4">
                        <div class="col mb-0">
                            <label for="TARIH" class="form-label">Tarih</label>
                            <input type="date" id="TARIH" class="form-control">
                        </div>
                        <div class="col mb-0">
                            <label for="URETIMMIKTAR" class="form-label">Üretim Miktarı</label>
                            <input type="number" id="URETIMMIKTAR" class="form-control" placeholder="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label for="NOTLAR1" class="form-label">Not</label>
                            <textarea id="NOTLAR1" class="form-control dt-input" name="NOTLAR1" rows="4"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="button" id="btnKaydet" class="btn btn-primary">Kaydet</button>
                </div>
            </div>
        </div>
    </div>





    <div class="card mt-2">
        <div class="card">
            <div class="card-datatable table-responsive-sm  text-nowrap">
                <table style="font-size: 14px" class="datatables-emirler table-sm table-striped">
                    <thead class="border-top">
                        <tr>
                            <th>GÖSTER</th>
                            <th>ÜRETİM SIRASI</th>
                            <th>İŞEMRİ ID</th>
                            <th>ISTASYONID</th>
                            <th>URUNID</th>
                            <th>KOD</th>
                            <th>MAMUL</th>
                            <th>ANA MAMUL</th>
                            <th>PLN MKTR</th>
                            <th>ÜRT MKTR</th>
                            <th>İLERLEME</th>
                            <th>DURUM</th>
                            <th>NOTLAR</th>
                            <th>PR. NOT</th>
                            <th>TARİH</th>
                            <th>ISTKOD</th>
                            <th>ISTTANIM</th>
                            <th>AKTİF</th>
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
                <div class="mb-3">
                    <label class="form-label" for="ISTKOD">İstasyon</label>
                    <select id="ISTKOD" class="form-select dt-input" name="ISTKOD">
                        @foreach ($istasyon as $ist)
                            <option value="{{ $ist->ID }}">{{ $ist->TANIM }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="TANIM">Mamul</label>
                    <select id="TANIM" class="form-select dt-input" name="TANIM" disabled></select>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="KOD">Kod</label>
                    <input type="text" id="KOD" class="form-control dt-input" name="KOD" disabled></input>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="MMLGRPKOD">Ana Mamul</label>
                    <input type="text" id="MMLGRPKOD" class="form-control dt-input" name="MMLGRPKOD" disabled></input>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="PLANLANANMIKTAR">Planlanan Miktar</label>
                    <input type="number" id="PLANLANANMIKTAR" class="form-control dt-input" name="PLANLANANMIKTAR"
                        disabled></input>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="DURUM">Durum</label>
                    <select id="DURUM" class="form-select dt-input" name="DURUM" disabled>
                        <option value="">Seçiniz...</option>
                        <option value="Beklemede">Beklemede</option>
                        <option value="Üretimde">Üretimde</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="NOTLAR" class="form-label">Not</label>
                    <textarea id="NOTLAR" class="form-control dt-input" name="NOTLAR" rows="4" cols="50"></textarea>
                </div>

                <div class="row  mb-3">
                    <div class="col-7">
                        <label class="form-label" for="URETIMSIRA">Üretim Sırası</label>
                        <input type="number" id="URETIMSIRA" class="form-control dt-input" name="URETIMSIRA"></input>
                    </div>
                    <div class="col-5 d-flex align-items-end">
                        <label class="form-check">
                            <input id="AKTIF" type="checkbox" name="AKTIF" class="form-check-input"
                                value="" />
                            <span class="form-check-label">Aktif</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary me-3 data-submit">Kaydet</button>
                <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Vazgeç</button>
            </form>
        </div>
    </div>






@endsection
