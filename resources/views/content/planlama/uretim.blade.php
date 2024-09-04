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


    <!-- Modal -->
    <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <input type="hidden" name="ID" id="rec_id">
              <input type="hidden" name="URUNID" id="urun_id">
              <input type="hidden" name="isemriid" id="isemriid">
              <input type="hidden" name="miktarTemp" id="miktarTemp">
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
                <table style="font-size: 14px" class="datatables-uretimler table-sm table-striped">
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
