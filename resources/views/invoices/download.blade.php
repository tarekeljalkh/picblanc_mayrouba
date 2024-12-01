<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice #{{ $invoice->id }}</title>

    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .invoice-print {
            padding: 10px;
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

        h5 {
            font-size: 16px;
            font-weight: bold;
        }

        h6 {
            font-size: 14px;
            font-weight: bold;
        }

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
                padding: 6px;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-print">
        <!-- Invoice Header -->
        <div class="d-flex justify-content-between">
            <div>
                <img src="{{ public_path('logo_croped.png') }}" alt="Logo" width="150">
                <p>Office 149, 450 South Brand Brooklyn</p>
                <p>San Diego County, CA 91905, USA</p>
                <p>+1 (123) 456 7891, +44 (876) 543 2198</p>
            </div>
            <div>
                <h5>Rental Agreement #{{ $invoice->id }}</h5>
                <p><strong>Date Issued:</strong> {{ $invoice->created_at->format('M d, Y') }}</p>
                <p><strong>Rental Start:</strong> {{ $invoice->rental_start_date->format('d/m/Y') }}</p>
                <p><strong>Rental End:</strong> {{ $invoice->rental_end_date->format('d/m/Y') }}</p>
                <p><strong>Rental Days:</strong> {{ $invoice->days }} day(s)</p>
</div>
        </div>

        <hr />

        <!-- Customer Details -->
        <div class="d-flex justify-content-between mb-4">
            <div>
                <h6>Invoice To:</h6>
                <p>{{ $invoice->customer->name }}</p>
                <p>{{ $invoice->customer->address }}</p>
                <p>{{ $invoice->customer->phone }}</p>
                <p>{{ $invoice->customer->email }}</p>
            </div>
        </div>

        <!-- Invoice Items -->
        <h6>Invoice Items</h6>
        <div class="table-responsive">
            <table class="table">
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
                            <td>{{ $item->product->name }}</td>
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
            <h6>Additional Items</h6>
            <div class="table-responsive">
                <table class="table">
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
                                <td>{{ $addedItem->product->name }}</td>
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
            <h6>Returned Items</h6>
            <div class="table-responsive">
                <table class="table">
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
                                <td>{{ $return->invoiceItem->product->name }}</td>
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

        <!-- Summary -->
        <h6>Invoice Summary</h6>
        <table class="table">
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td class="text-end">${{ number_format($totals['subtotal'], 2) }}</td>
            </tr>
            <tr>
                <td><strong>Additional Costs:</strong></td>
                <td class="text-end">${{ number_format($totals['additionalCost'], 2) }}</td>
            </tr>
            <tr>
                <td><strong>Returned Costs:</strong></td>
                <td class="text-end text-danger">- ${{ number_format($totals['returnedCost'], 2) }}</td>
            </tr>
            <tr>
                <td><strong>Discount:</strong></td>
                <td class="text-end">- ${{ number_format($totals['discountAmount'], 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td class="text-end">${{ number_format($totals['total'], 2) }}</td>
            </tr>
        </table>

        <hr />
        <div>
            <p><strong>CONDITION:</strong> I declare having received the merchandise mentioned above in good condition and agree to return it on time.</p>
        </div>
    </div>
</body>

</html>
