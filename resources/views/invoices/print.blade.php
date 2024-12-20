@extends('layouts.printLayout')

@section('title', 'Print Invoice')

@section('content')

<div class="row invoice-preview">
    <!-- Invoice -->
    <div class="col-12">
        <div class="card invoice-preview-card">
            <!-- Invoice Header -->
            <div class="card-body invoice-preview-header">
                <div class="d-flex justify-content-between">
                    <div class="text-heading">
                        <div class="mb-4">
                            <img src="{{ asset('logo_croped.png') }}" alt="Logo" width="150">
                        </div>
                        <p class="mb-1">Mayrouba Rental Shop</p>
                        <p class="mb-1">Tel: 03 71 57 57</p>
                    </div>
                    <div>
                        <h5 class="mb-3">Rental Agreement #{{ $invoice->id }}</h5>
                        <p class="mb-1"><strong>Date Created:</strong> {{ $invoice->created_at->format('M d, Y') }}</p>
                        @if ($invoice->category->name === 'daily')
                            <p class="mb-1"><strong>Rental Start:</strong> {{ $invoice->rental_start_date->format('d/m/Y h:i A') }}</p>
                            <p><strong>Rental End:</strong> {{ $invoice->rental_end_date->format('d/m/Y h:i A') }}</p>
                            <p><strong>Rental Days:</strong> {{ $invoice->days }} day(s)</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer and Rental Details -->
            <div class="card-body">
                <h6>Invoice To:</h6>
                <p class="mb-1">{{ $invoice->customer->name }}</p>
                <p class="mb-1">{{ $invoice->customer->address }}</p>
                <p class="mb-1">{{ $invoice->customer->phone }}</p>
                <p class="mb-0">{{ $invoice->customer->email }}</p>
            </div>

            <!-- Invoice Items -->
            <div class="table-responsive">
                <h6 class="px-3 py-2 bg-light">Invoice Items</h6>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th>Total Price</th>
                            @if ($invoice->category->name === 'daily')
                                <th>From Date</th>
                                <th>To Date</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price * $item->quantity * ($invoice->category->name === 'daily' ? $item->days : 1), 2) }}</td>
                                @if ($invoice->category->name === 'daily')
                                    <td>{{ optional($item->rental_start_date)->format('d/m/Y h:i A') }}</td>
                                    <td>{{ optional($item->rental_end_date)->format('d/m/Y h:i A') }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Returned Items -->
            @if ($invoice->returnDetails->isNotEmpty())
                <div class="table-responsive mt-4">
                    <h6 class="px-3 py-2 bg-light">Returned Items</h6>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                @if ($invoice->category->name === 'daily')
                                    <th>Days Used</th>
                                @endif
                                <th>Cost</th>
                                @if ($invoice->category->name === 'daily')
                                    <th>Return Date</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice->returnDetails as $return)
                                @php
                                    $unitPrice = $return->invoiceItem?->price ?? ($return->additionalItem?->price ?? 0);
                                    $cost = $invoice->category->name === 'daily'
                                        ? $unitPrice * $return->days_used * $return->returned_quantity
                                        : $unitPrice * $return->returned_quantity;
                                @endphp
                                <tr>
                                    <td>{{ $return->invoiceItem->product->name ?? ($return->additionalItem->product->name ?? 'N/A') }}</td>
                                    <td>{{ $return->returned_quantity }}</td>
                                    @if ($invoice->category->name === 'daily')
                                        <td>{{ $return->days_used }}</td>
                                    @endif
                                    <td>${{ number_format($cost, 2) }}</td>
                                    <td>{{ optional($return->return_date)->format('d/m/Y h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Invoice Summary -->
            <div class="table-responsive mt-4">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                <strong>Salesperson:</strong> {{ $invoice->user->name ?? 'N/A' }}<br>
                                <span>
                                    @php
                                        $totalPaid = $invoice->paid_amount + $invoice->deposit;
                                    @endphp
                                    @if ($totals['balanceDue'] <= 0)
                                        <span class="badge bg-success">Payment: Fully Paid</span>
                                    @elseif ($totalPaid > 0)
                                        <span class="badge bg-warning">Payment: Partially Paid</span>
                                    @else
                                        <span class="badge bg-danger">Payment: Not Paid</span>
                                    @endif
                                </span><br>
                                @if ($invoice->note)
                                    <strong>NOTE:</strong> {{ $invoice->note }}
                                @endif
                            </td>
                            <td class="text-end">
                                @php
                                    $subtotal = $totals['subtotal'];
                                    $returnedCost = $totals['returnedItemsCost'];
                                    $refundForUnusedDays = $totals['refundForUnusedDays'];
                                    $finalTotal = $totals['finalTotal'];
                                    $balanceDue = $totals['balanceDue'];
                                @endphp
                                <p class="mb-1">Subtotal: ${{ number_format($subtotal, 2) }}</p>
                                <p class="mb-1 text-danger">Returned Items Cost: - ${{ number_format($returnedCost, 2) }}</p>
                                <p class="mb-1 text-success">Refund for Unused Days: - ${{ number_format($refundForUnusedDays, 2) }}</p>
                                <p class="mb-1">Final Total: ${{ number_format($finalTotal, 2) }}</p>
                                <p class="mb-1 text-danger fw-bold">Balance Due: ${{ number_format($balanceDue, 2) }}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr>
            <div>
                <p><strong>CONDITION:</strong> I declare having received the merchandise mentioned above in good condition and agree to return it on time.</p>
                <hr>
                <p>Mayrouba - Tel: 03 71 57 57 | Warde - Tel: 70 100 015 | Mzaar Intercontinental Hotel - Tel: 03 788 733</p>
            </div>
        </div>
    </div>
</div>

@endsection
