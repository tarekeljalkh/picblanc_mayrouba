@extends('layouts.master')

@section('title', 'Edit Invoice')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Invoices</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Invoice</li>
        </ol>
    </nav>

    <div class="row g-6">
        <div class="col-md">
            <div class="card">
                <h5 class="card-header">Edit Invoice</h5>
                <div class="card-body">
                    @if ($errors->any())
    <div class="alert alert-danger">
        <p>There were some problems with your input. Please check the form below:</p>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                    <ul class="nav nav-tabs" id="editInvoiceTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="returns-tab" data-bs-toggle="tab" data-bs-target="#returns"
                                type="button" role="tab" aria-controls="returns" aria-selected="true">Manage Returns</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="additions-tab" data-bs-toggle="tab" data-bs-target="#additions"
                                type="button" role="tab" aria-controls="additions" aria-selected="false">Add New Items</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="editInvoiceTabsContent">
                        {{-- Manage Returns Tab --}}
                        <div class="tab-pane fade show active" id="returns" role="tabpanel" aria-labelledby="returns-tab">
                            @include('invoices.partials.manage-returns')
                        </div>

                        {{-- Add New Items Tab --}}
                        <div class="tab-pane fade" id="additions" role="tabpanel" aria-labelledby="additions-tab">
                            @include('invoices.partials.add-new-items')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Include Scripts --}}
    @push('scripts')
        <script>
            // Optional: Add custom JavaScript for interactivity
        </script>
    @endpush
@endsection
