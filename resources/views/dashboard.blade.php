@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
        <!-- Card Widgets -->
        <div class="card mb-6">
            <div class="card-widget-separator-wrapper">
                <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                        <!-- Customers Card -->
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-center card-widget-1 border-end pb-4 pb-sm-0">
                                <div>
                                    <h4 class="mb-0">{{ $customersCount }}</h4> <!-- Dynamic Customers Count -->
                                    <p class="mb-0">Customers</p>
                                </div>
                                <div class="avatar me-sm-6">
                                    <span class="avatar-initial rounded bg-label-secondary text-heading">
                                        <i class="bx bx-user bx-26px"></i>
                                    </span>
                                </div>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-6">
                        </div>

                        <!-- Invoices Card -->
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-center card-widget-2 border-end pb-4 pb-sm-0">
                                <div>
                                    <h4 class="mb-0">{{ $invoicesCount }}</h4> <!-- Dynamic Invoices Count -->
                                    <p class="mb-0">Invoices</p>
                                </div>
                                <div class="avatar me-lg-6">
                                    <span class="avatar-initial rounded bg-label-secondary text-heading">
                                        <i class="bx bx-file bx-26px"></i>
                                    </span>
                                </div>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none">
                        </div>

                        <!-- Paid Invoices Card -->
                        <div class="col-sm-6 col-lg-3">
                            <a href="{{ route('invoices.index', ['start_date' => '2024-01-01', 'end_date' => '2090-01-01', 'payment_status' => 'fully_paid']) }}"
                                class="d-flex justify-content-between align-items-center card-widget-3 border-end pb-4 pb-sm-0">
                                <div>
                                    <h4 class="mb-0">{{ $totalPaid }}</h4>
                                    <p class="mb-0">Paid Invoices</p>
                                </div>
                                <div class="avatar me-sm-6">
                                    <span class="avatar-initial rounded bg-label-secondary text-heading">
                                        <i class="bx bx-check-double bx-26px"></i>
                                    </span>
                                </div>
                            </a>
                            <hr class="d-none d-sm-block d-lg-none">
                        </div>

                        <!-- Unpaid Invoices Card -->
                        <div class="col-sm-6 col-lg-3">
                            <a href="{{ route('invoices.index', ['start_date' => '2024-01-01', 'end_date' => '2090-01-01', 'payment_status' => 'unpaid']) }}"
                                class="d-flex justify-content-between align-items-center card-widget-3 border-end pb-4 pb-sm-0">
                                <div>
                                    <h4 class="mb-0">{{ $totalUnpaid }}</h4>
                                    <p class="mb-0">Unpaid Invoices</p>
                                </div>
                                <div class="avatar me-sm-6">
                                    <span class="avatar-initial rounded bg-label-secondary text-heading">
                                        <i class="bx bx-time bx-26px"></i>
                                    </span>
                                </div>
                            </a>
                            <hr class="d-none d-sm-block d-lg-none">
                        </div>


                        <!-- Active Invoices Card -->
                        <div class="col-sm-6 col-lg-3">
                            <a href="{{ route('invoices.index', ['start_date' => '2024-01-01', 'end_date' => '2090-01-01', 'status' => 'not_returned']) }}"
                                class="d-flex justify-content-between align-items-center card-widget-4 border-end pb-4 pb-sm-0">
                                <div>
                                    <h4 class="mb-0">{{ $notReturnedCount }}</h4>
                                    <p class="mb-0">Not Returned Invoices</p>
                                </div>
                                <div class="avatar me-sm-6">
                                    <span class="avatar-initial rounded bg-label-secondary text-heading">
                                        <i class="bx bx-undo bx-26px"></i>
                                    </span>
                                </div>
                            </a>
                            <hr class="d-none d-sm-block d-lg-none">
                        </div>


                        <!-- Returned Invoices Card -->
                        <div class="col-sm-6 col-lg-3">
                            <a href="{{ route('invoices.index', ['start_date' => '2024-01-01', 'end_date' => '2090-01-01', 'status' => 'returned']) }}"
                                class="d-flex justify-content-between align-items-center card-widget-5 border-end pb-4 pb-sm-0">
                                <div>
                                    <h4 class="mb-0">{{ $returnedCount }}</h4>
                                    <p class="mb-0">Returned Invoices</p>
                                </div>
                                <div class="avatar me-sm-6">
                                    <span class="avatar-initial rounded bg-label-secondary text-heading">
                                        <i class="bx bx-error bx-26px"></i>
                                    </span>
                                </div>
                            </a>
                            <hr class="d-none d-sm-block d-lg-none">
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
