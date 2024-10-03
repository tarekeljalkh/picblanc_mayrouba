@extends('layouts.master')

@section('title', 'Trial Balance')

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Trial Balance</li>
        </ol>
    </nav>

    <div class="col-md">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">Trial Balance for {{ \Carbon\Carbon::parse($fromDate)->format('F j, Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('F j, Y') }}</h5>
            </div>
            <div class="card-body">

                <!-- Date Range Filter Form -->
                <form method="GET" action="{{ route('trialbalance.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" id="from_date" name="from_date" class="form-control" value="{{ $fromDate }}">
                        </div>
                        <div class="col-md-4">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" id="to_date" name="to_date" class="form-control" value="{{ $toDate }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>

                <!-- Trial Balance Table -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Amount (USD)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Total Income</td>
                            <td>${{ number_format($totalIncome, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Total Unpaid Invoices</td>
                            <td>${{ number_format($totalUnpaid, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Total Expenses</td>
                            <td>${{ number_format($totalExpenses, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Outstanding Balances</td>
                            <td>${{ number_format($outstandingBalances, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Net Income</strong></td>
                            <td><strong>${{ number_format($netIncome, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Initialize Flatpickr for both date inputs
        flatpickr('#from_date', {
            dateFormat: "Y-m-d"
        });
        flatpickr('#to_date', {
            dateFormat: "Y-m-d"
        });
    </script>
@endpush
