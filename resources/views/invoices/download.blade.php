<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Show Invoice</title>

    <!-- Inline CSS for Responsive and Print View -->
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .invoice-print {
            padding: 12px;
            max-width: 1000px;
            margin: auto;
        }

        .d-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        .table th,
        .table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 12px;
        }

        .text-end {
            text-align: right;
        }

        .fw-medium {
            font-weight: 500;
        }

        .fw-bold {
            font-weight: bold;
        }

        .border {
            border: 1px solid #ddd;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .mb-6 {
            margin-bottom: 24px;
        }

        h5 {
            font-size: 18px;
            font-weight: bold;
        }

        h6 {
            font-size: 16px;
            font-weight: bold;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .d-flex {
                flex-direction: column;
                align-items: flex-start;
            }

            .table th,
            .table td {
                font-size: 10px;
            }

            .invoice-print {
                padding: 8px;
            }
        }

        /* Print-specific styling */
        @media print {
            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .invoice-print {
                padding: 0;
                margin: 0 auto;
            }

            .table th,
            .table td {
                font-size: 10px;
                padding: 4px;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="invoice-print">

        <div class="d-flex justify-content-between">
            <div class="mb-6">
                <img src="{{ public_path('logo_croped.png') }}" alt="Logo" width="150">
                <p class="mb-1">Office 149, 450 South Brand Brooklyn</p>
                <p class="mb-1">San Diego County, CA 91905, USA</p>
                <p class="mb-0">+1 (123) 456 7891, +44 (876) 543 2198</p>
            </div>
            <div>
                <h5 class="mb-6">Invoice #{{ $invoice->id }}</h5>
                <div class="mb-1">
                    <span>Date Issued:</span>
                    <span class="fw-medium">{{ $invoice->created_at->format('M d, Y') }}</span>
                </div>
                <div>
                    <span>Date Due:</span>
                    <span class="fw-medium">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}</span>
                </div>
            </div>
        </div>

        <hr class="mb-6" />

        <div class="d-flex justify-content-between mb-6">
            <div>
                <h6>Invoice To:</h6>
                <p class="mb-1">{{ $invoice->customer->name }}</p>
                <p class="mb-1">{{ $invoice->customer->address }}</p>
                <p class="mb-1">{{ $invoice->customer->phone }}</p>
                <p class="mb-0">{{ $invoice->customer->email }}</p>
            </div>
        </div>

        <div class="table-responsive border rounded">
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Description</th>
                        <th>Cost</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>VAT</th>
                        <th>Discount</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->items as $item)
                        @php
                            // Calculations if not precomputed
                            $itemSubtotal = $item->price * $item->quantity;
                            $itemVatAmount = ($itemSubtotal * ($item->vat ?? $invoice->total_vat)) / 100;
                            $itemDiscountAmount = ($itemSubtotal * ($item->discount ?? $invoice->total_discount)) / 100;
                            $itemTotalPrice = $itemSubtotal + $itemVatAmount - $itemDiscountAmount;
                        @endphp
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->product->description ?? 'N/A' }}</td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($itemSubtotal, 2) }}</td>
                            <td>{{ number_format($item->vat ?? $invoice->total_vat, 2) }}% (${{ number_format($itemVatAmount, 2) }})</td>
                            <td>{{ number_format($item->discount ?? $invoice->total_discount, 2) }}% (${{ number_format($itemDiscountAmount, 2) }})</td>
                            <td>${{ number_format($itemTotalPrice, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="table-responsive">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td>
                            <p class="mb-1">
                                <span class="fw-medium">Salesperson:</span>
                                <span>{{ $invoice->user->name ?? 'N/A' }}</span>
                            </p>
                            <span>Thanks for your business</span>
                        </td>
                        <td class="text-end">
                            <p class="mb-2">Subtotal:</p>
                            <p class="mb-2">Discount:</p>
                            <p class="mb-2">Tax:</p>
                            <p class="mb-0 pt-2">Total:</p>
                        </td>
                        <td class="text-end">
                            @php
                                // Overall totals
                                $subtotal = $invoice->items->sum(fn($item) => $item->price * $item->quantity);
                                $discountTotal = $subtotal * ($invoice->total_discount / 100);
                                $vatTotal = $subtotal * ($invoice->total_vat / 100);
                                $total = $subtotal + $vatTotal - $discountTotal;
                            @endphp
                            <p class="fw-medium mb-2">${{ number_format($subtotal, 2) }}</p>
                            <p class="fw-medium mb-2">${{ number_format($discountTotal, 2) }}</p>
                            <p class="fw-medium mb-2 border-bottom pb-2">${{ number_format($vatTotal, 2) }}</p>
                            <p class="fw-medium mb-0">${{ number_format($total, 2) }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr class="mt-0 mb-6">
        <div class="row">
            <div class="col-12">
                <span class="fw-medium">CONDITION:</span>
                <span>I declare having received the merchandise mentioned above in good condition and I agree to
                    return it on time. I will reimburse the value of any missing, damaged, or broken
                    article.</span>
                <br>
                <hr>
                <span>Mayrouba - Tel: 03 71 57 57 | Warde - Tel: 70 100 015 | Mzaar Intercontinental Hotel -
                    Tel: 03 788 733</span>
            </div>
        </div>
    </div>
</body>

</html>
