@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Ana Sayfa')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss', 'resources/assets/vendor/libs/swiper/swiper.scss', 'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss'])
@endsection

@section('page-style')
    <!-- Page -->
    @vite(['resources/assets/vendor/scss/pages/cards-advance.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js', 'resources/assets/vendor/libs/swiper/swiper.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/home-page.js'])
@section('content')

    <div class="row g-6">

        <!-- Orders -->
        <div class="col-lg-2 col-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="badge rounded p-2 bg-label-danger mb-2"><i class="ti ti-briefcase ti-lg"></i></div>
                    <h5 class="card-title mb-1">97.8k</h5>
                    <p class="mb-0">Orders</p>
                </div>
            </div>
        </div>

        <!-- Sales last year -->
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h5 class="card-title mb-1">Sales</h5>
                    <p class="card-subtitle">Last Year</p>
                </div>
                <div id="salesLastYear"></div>
                <div class="card-body pt-0">
                    <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
                        <h4 class="mb-0">175k</h4>
                        <small class="text-danger">-16.2%</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profit last month -->
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h5 class="card-title mb-1">Profit</h5>
                    <p class="card-subtitle">Last Month</p>
                </div>
                <div class="card-body">
                    <div id="profitLastMonth"></div>
                    <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
                        <h4 class="mb-0">624k</h4>
                        <small class="text-success">+8.24%</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sessions Last month -->
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h5 class="card-title mb-1">Sessions</h5>
                    <p class="card-subtitle">Last Month</p>
                </div>
                <div class="card-body">
                    <div id="sessionsLastMonth"></div>
                    <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
                        <h4 class="mb-0">45.1k</h4>
                        <small class="text-success">+12.6%</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expenses -->
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card h-100">
                <div class="card-header pb-2">
                    <h5 class="card-title mb-1">82.5k</h5>
                    <p class="card-subtitle">Expenses</p>
                </div>
                <div class="card-body">
                    <div id="expensesChart"></div>
                    <div class="mt-3 text-center">
                        <small class="text-muted mt-3">$21k Expenses more than last month</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Impression -->
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h5 class="card-title mb-1">Impression</h5>
                    <p class="card-subtitle">This Week</p>
                </div>
                <div class="card-body">
                    <div id="impressionThisWeek"></div>
                    <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
                        <h4 class="mb-0">26.1k</h4>
                        <small class="text-danger">-24.5%</small>
                    </div>
                </div>
            </div>
        </div>



    </div>

@endsection
