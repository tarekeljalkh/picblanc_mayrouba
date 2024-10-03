@extends('layouts.master')

@section('title', 'Rental Details for ' . $product->name)

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">Rental Details</li>
        </ol>
    </nav>

    <div class="col-md">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">Rental Details for {{ $product->name }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Rented Quantity</th>
                            <th>Rental Start Date</th>
                            <th>Rental End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rentals as $rental)
                            <tr>
                                <td>{{ $rental->invoice->customer->name }}</td> <!-- Customer Name -->
                                <td>{{ $rental->quantity }}</td> <!-- Quantity Rented -->
                                <td>{{ $rental->invoice->rental_start_date }}</td> <!-- Rental Start Date -->
                                <td>{{ $rental->invoice->rental_end_date }}</td> <!-- Rental End Date -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
