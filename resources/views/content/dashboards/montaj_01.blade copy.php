<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Grid with Decimal Widths</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .custom-row {
          display: flex;
          flex-wrap: wrap;
          margin-right: 0px;
          margin-left: 0px;
        }

        .custom-col {
            flex: 0 0 16.2%;
            max-width: 16.2%;
            flex-grow: 1;
        }
    </style>
</head>

<body>

        <div class="custom-row">

          <div class="custom-col card m-1">
                <div class="card-body  p-2">
                    <div class="border rounded p-2">
                        <h6 class="mb-0 fw-normal">Müşteri Siparişleri</h6>
                        <h4 class="my-2">0 Ton</h4>
                    </div>
                </div>
            </div>
          <div class="custom-col card m-1">
                <div class="card-body  p-2">
                    <div class="border rounded p-2">
                        <h6 class="mb-0 fw-normal">Müşteri Siparişleri</h6>
                        <h4 class="my-2">0 Ton</h4>
                    </div>
                </div>
            </div>
          <div class="custom-col card m-1">
                <div class="card-body  p-2">
                    <div class="border rounded p-2">
                        <h6 class="mb-0 fw-normal">Müşteri Siparişleri</h6>
                        <h4 class="my-2">0 Ton</h4>
                    </div>
                </div>
            </div>
          <div class="custom-col card m-1">
                <div class="card-body  p-2">
                    <div class="border rounded p-2">
                        <h6 class="mb-0 fw-normal">Müşteri Siparişleri</h6>
                        <h4 class="my-2">0 Ton</h4>
                    </div>
                </div>
            </div>
          <div class="custom-col card m-1">
                <div class="card-body  p-2">
                    <div class="border rounded p-2">
                        <h6 class="mb-0 fw-normal">Müşteri Siparişleri</h6>
                        <h4 class="my-2">0 Ton</h4>
                    </div>
                </div>
            </div>
          <div class="custom-col card m-1">
                <div class="card-body  p-2">
                    <div class="border rounded p-2">
                        <h6 class="mb-0 fw-normal">Müşteri Siparişleri</h6>
                        <h4 class="my-2">0 Ton</h4>
                    </div>
                </div>
            </div>



          </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>



{{-- @isset($pageConfigs)
    {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

@php
    $configData = Helper::appClasses();

    /* Display elements */
    $customizerHidden = $customizerHidden ?? '';

@endphp
@extends('layouts/commonMaster')

@section('layoutContent') --}}






{{-- @endsection --}}
