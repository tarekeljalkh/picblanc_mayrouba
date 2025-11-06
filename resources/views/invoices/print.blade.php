@extends('layouts.printLayout')

@section('title', 'Print Invoice')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-invoice.css') }}" />

<style>
    @media print {
        html,
        body {
            width: 80mm;
            margin: 0;
            padding: 0;
            font-family: "Courier New", monospace;
            font-size: 14px; /* increased from 12–16px */
            line-height: 1.3;
        }

        .print-invoice {
            width: 80mm;
            margin: 0 auto;
            max-height: none;
            overflow: visible;
        }

        @page {
            size: 80mm auto;
            margin: 0;
        }

        .no-break,
        .print-invoice,
        .invoice-preview-card,
        table,
        tr,
        td,
        th {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 3px 0;
            font-size: 13px; /* bump up text for better printer visibility */
        }

        h5, h6, p {
            font-size: 14px;
            margin: 2px 0;
        }

        .invoice-preview-header img {
            width: 120px !important; /* slightly larger logo */
        }

        /* optional: stronger separation between sections */
        .table, table {
            border-top: 1px dashed #000;
        }

        .invoice-preview-card {
            padding-bottom: 10px;
        }
    }

    /* also improve screen preview */
    .invoice-preview {
        width: 80mm;
        margin: 0 auto;
        font-size: 14px;
        line-height: 1.3;
    }
</style>

@endpush

@section('content')

    <div class="invoice-preview" style="width: 72mm; margin: 0 auto; font-size: 12px; line-height: 1.1;">
        <!-- Invoice -->
        <div class="mb-6">
            <div class="invoice-preview-card">
                <!-- Invoice Header -->
                <div class="invoice-preview-header" style="padding: 6px;">
                    <div class="d-flex justify-content-between flex-column align-items-start">
                        <div class="text-heading">
                            <div class="svg-illustration mb-6 gap-2 align-items-center">
                                <span class="app-brand-logo demo">
                                    <img src="{{ asset('logo_croped.png') }}" alt="Logo" width="100">
                                </span>
                            </div>
                            <p class="mb-2">Mayrouba Rental Shop</p>
                            <p class="mb-2">Tel: 71721236</p>
                        </div>
                        <div>
                            <h5 class="mb-6">Rental Agreement #{{ $invoice->id }}</h5>
                            <div class="text-heading">
                                <p>Date Created: {{ $invoice->created_at->format('M d, Y') }}</p>
                                @if ($invoice->category->name === 'daily')
                                    <p>Rental Start: {{ $invoice->rental_start_date->format('d/m/Y h:i A') }}</p>
                                    <p>Rental End: {{ $invoice->rental_end_date->format('d/m/Y h:i A') }}</p>
                                    <p>Rental Days: {{ $invoice->days }} day(s)</p>
                                @else
                                    <p>Category: Season</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer and Rental Details -->
                <div style="padding: 10px;">
                    <h6>Invoice To:</h6>
                    <p>{{ $invoice->customer->name }}</p>
                    <p>{{ $invoice->customer->address }}</p>
                    <p>{{ $invoice->customer->phone }}</p>
                    <p>{{ $invoice->customer->email }}</p>
                </div>

                <!-- Invoice Items -->
                <div class="table-responsive" style="margin-top: 4px;">
                    <table class="table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice->invoiceItems as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                            @foreach ($invoice->customItems as $customItem)
                                <tr>
                                    <td>{{ $customItem->name }}</td>
                                    <td>{{ $customItem->quantity }}</td>
                                    <td>${{ number_format($customItem->price * $customItem->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Additional Items -->
                @if ($invoice->additionalItems->isNotEmpty())
                    <div style="margin-top: 4px;">
                        <h6>Additional Items:</h6>
                        <table class="table" style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->additionalItems as $addedItem)
                                    <tr>
                                        <td>{{ $addedItem->product->name }}</td>
                                        <td>{{ $addedItem->quantity }}</td>
                                        <td>${{ number_format($addedItem->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- Returned Items -->
                @if ($invoice->returnDetails->isNotEmpty())
                    <div style="margin-top: 4px;">
                        <h6>Returned Items:</h6>
                        <table class="table" style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->returnDetails as $return)
                                    @php
                                        $cost =
                                            $return->days_used *
                                            $return->returned_quantity *
                                            ($return->invoiceItem->price ?? ($return->additionalItem->price ?? 0));
                                    @endphp
                                    <tr>
                                        <td>{{ $return->invoiceItem->product->name ?? ($return->additionalItem->product->name ?? ($return->customItem->name ?? 'N/A')) }}
                                        </td>
                                        <td>{{ $return->returned_quantity }}</td>
                                        <td>${{ number_format($cost, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- Summary -->
                <div style="margin-top: 4px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tbody>
                            <tr>
                                <td>Base Total (All Items):</td>
                                <td style="text-align: right;">${{ number_format($totals['subtotalForDiscount'], 2) }}</td>
                            </tr>
                            <tr>
                                <td>Discount:</td>
                                <td style="text-align: right;">-${{ number_format($totals['discountAmount'], 2) }}</td>
                            </tr>
                            <tr>
                                <td>Additional Items Cost:</td>
                                <td style="text-align: right;">${{ number_format($totals['additionalItemsCost'], 2) }}</td>
                            </tr>
                            <tr>
                                <td>Refund for Unused Days:</td>
                                <td style="text-align: right;">-${{ number_format($totals['refundForUnusedDays'], 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td>Deposit:</td>
                                <td style="text-align: right;">${{ number_format($invoice->deposit, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Final Total:</td>
                                <td style="text-align: right;">${{ number_format($totals['finalTotal'], 2) }}</td>
                            </tr>
                            <tr>
                                <td>Paid Amount:</td>
                                <td style="text-align: right;">${{ number_format($invoice->paid_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; color: red;">Balance Due:</td>
                                <td style="text-align: right; font-weight: bold; color: red;">
                                    ${{ number_format($totals['balanceDue'], 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer -->
                <div style="text-align: center; margin-top: 10px; font-size: 10px;">
                    <p>I declare having received the merchandise mentioned above in good condition and I agree to return it
                        on time. I will reimburse the value of any missing, damaged, or broken article.</p>
                    <p>Mayrouba - Tel: 03715757</p>
                    <p>Warde - Tel: 70100015</p>
                    <p>Mzaar Intercontinental Hotel - Tel: 03788733</p>
                </div>
            </div>
        </div>
    </div>

@endsection
