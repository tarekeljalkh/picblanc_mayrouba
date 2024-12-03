@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <div class="card-widget-separator-wrapper">
                <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                        <!-- customers Card -->
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
                            <a href="{{ route('invoices.index', ['status' => 'paid']) }}"
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
                        </div>

                        <!-- Unpaid Invoices Card -->
                        <div class="col-sm-6 col-lg-3">
                            <a href="{{ route('invoices.index', ['status' => 'unpaid']) }}"
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
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice List Table -->
        <div class="card">
            <div class="card-datatable table-responsive">
                <table class="invoice-list-table table border-top">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Total</th>
                            <th>Issued Date</th>
                            <th>Invoice Status</th>
                            <th class="cell-fit">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->id }}</td>
                                <td>{{ $invoice->customer->name ?? 'N/A' }}</td>
                                <td>${{ number_format($invoice->total_amount ?? 0, 2) }}</td>
                                <!-- Ensure correct total field -->
                                <td>{{ $invoice->created_at->format('d/m/Y') }}</td> <!-- Date format adjusted to d/m/y -->
                                </td> <!-- Correct balance calculation -->
                                <td>
                                    <span class="badge {{ $invoice->paid ? 'bg-success' : 'bg-danger' }}">
                                        {{ $invoice->paid ? 'Paid' : 'Unpaid' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">
                    <!-- Custom Pagination Styling -->
                    <nav>
                        <ul class="pagination justify-content-center">
                            {{ $invoices->onEachSide(1)->links('pagination::bootstrap-4') }}
                            <!-- Bootstrap styled pagination -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- End Invoice List Table -->


    </div>
@endsection
