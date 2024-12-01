@extends('layouts.printLayout')

@section('title', 'Print Invoice')

@section('content')

<div class="row invoice-preview">
    <!-- Invoice -->
    <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-6">
        <div class="card invoice-preview-card p-sm-12 p-6">
            <!-- Invoice Header -->
            <div class="card-body invoice-preview-header rounded">
                <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column align-items-xl-center align-items-md-start align-items-sm-center align-items-start">
                    <div class="mb-xl-0 mb-6 text-heading">
                        <div class="d-flex svg-illustration mb-6 gap-2 align-items-center">
                            <span class="app-brand-logo demo">
                                <img src="{{ asset('logo_croped.png') }}" alt="Logo" width="150">
                            </span>
                        </div>
                        <p class="mb-2">Mayrouba Rental Shop</p>
                        <p class="mb-2">Tel: 03 71 57 57</p>
                    </div>
                    <div>
                        <h5 class="mb-6">Rental Agreement #{{ $invoice->id }}</h5>
                        <div class="mb-1 text-heading">
                            <span>Date Issued:</span>
                            <span class="fw-medium">{{ $invoice->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="text-heading">
                            <p>Rental Start: {{ $invoice->rental_start_date->format('d/m/Y') }}</p>
                            <p>Rental End: {{ $invoice->rental_end_date->format('d/m/Y') }}</p>
                            <p>Rental Days: {{ $invoice->days }} day(s)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer and Rental Details -->
            <div class="card-body px-0">
                <div class="row">
                    <div class="col-xl-6 col-md-12 col-sm-5 col-12 mb-xl-0 mb-md-6 mb-sm-0 mb-6">
                        <h6>Invoice To:</h6>
                        <p class="mb-1">{{ $invoice->customer->name }}</p>
                        <p class="mb-1">{{ $invoice->customer->address }}</p>
                        <p class="mb-1">{{ $invoice->customer->phone }}</p>
                        <p class="mb-0">{{ $invoice->customer->email }}</p>
                    </div>
                    <br>
                    <div class="col-xl-6 col-md-12 col-sm-7 col-12">
                    </div>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="table-responsive border border-bottom-0 border-top-0 rounded">
                <table class="table m-0">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td class="text-nowrap text-heading">{{ $item->product->name }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Additional Items -->
            @if ($invoice->additionalItems->isNotEmpty())
                <div class="table-responsive border border-bottom-0 rounded mt-4">
                    <h6 class="px-3 py-2 bg-light">Additional Items</h6>
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Unit Price</th>
                                <th>Qty</th>
                                <th>Total Price</th>
                                <th>Added Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice->additionalItems as $addedItem)
                                <tr>
                                    <td class="text-nowrap text-heading">{{ $addedItem->product->name }}</td>
                                    <td>${{ number_format($addedItem->price, 2) }}</td>
                                    <td>{{ $addedItem->quantity }}</td>
                                    <td>${{ number_format($addedItem->total_price, 2) }}</td>
                                    <td>{{ optional($addedItem->added_date)->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Returned Items -->
            @if ($invoice->returnDetails->isNotEmpty())
                <div class="table-responsive border border-bottom-0 rounded mt-4">
                    <h6 class="px-3 py-2 bg-light">Returned Items</h6>
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Days Used</th>
                                <th>Cost</th>
                                <th>Return Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice->returnDetails as $return)
                                <tr>
                                    <td class="text-nowrap text-heading">{{ $return->invoiceItem->product->name }}</td>
                                    <td>{{ $return->returned_quantity }}</td>
                                    <td>{{ $return->days_used }}</td>
                                    <td>${{ number_format($return->cost, 2) }}</td>
                                    <td>{{ optional($return->return_date)->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Invoice Summary -->
            <div class="table-responsive">
                <table class="table m-0 table-borderless">
                    <tbody>
                        <tr>
                            <td class="align-top pe-6 ps-0 py-6 text-body">
                                <p class="mb-1">
                                    <span class="me-2 h6">Salesperson:</span>
                                    <span>{{ $invoice->user->name ?? 'N/A' }}</span>
                                </p>
                                <span>Thanks for your business</span>
                            </td>
                            <td class="px-0 py-6 w-px-100">
                                <p class="mb-2">Subtotal:</p>
                                <p class="mb-2">Additional Costs:</p>
                                <p class="mb-2 text-danger">Returned Costs:</p>
                                <p class="mb-2">Discount ({{ $invoice->total_discount }}%):</p>
                                <p class="mb-0">Total:</p>
                            </td>
                            <td class="text-end px-0 py-6 w-px-100 fw-medium text-heading">
                                @php
                                    $subtotal = $invoice->subtotal;
                                    $addedCost = $invoice->added_cost;
                                    $returnedCost = $invoice->returned_cost;
                                    $discountAmount = ($subtotal + $addedCost - $returnedCost) * ($invoice->total_discount / 100);
                                    $total = $subtotal + $addedCost - $returnedCost - $discountAmount;
                                @endphp
                                <p class="fw-medium mb-2">${{ number_format($subtotal, 2) }}</p>
                                <p class="fw-medium mb-2">${{ number_format($addedCost, 2) }}</p>
                                <p class="fw-medium mb-2 text-danger">- ${{ number_format($returnedCost, 2) }}</p>
                                <p class="fw-medium mb-2">- ${{ number_format($discountAmount, 2) }}</p>
                                <p class="fw-medium mb-0">${{ number_format($total, 2) }}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr class="mt-0 mb-6">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-12">
                        <span class="fw-medium text-heading">CONDITION:</span>
                        <span>I declare having received the merchandise mentioned above in good condition and I agree to return it on time. I will reimburse the value of any missing, damaged, or broken article.</span>
                        <br>
                        <hr>
                        <span>Mayrouba - Tel: 03 71 57 57 | Warde - Tel: 70 100 015 | Mzaar Intercontinental Hotel - Tel: 03 788 733</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
