<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Show Invoice</title>

    <!-- Inline CSS for DomPDF -->
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
        }

        .invoice-print {
            padding: 12px;
        }

        .d-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
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
    </style>
</head>

<body>

    <div class="invoice-print p-12">

        <div class="d-flex justify-content-between flex-row">
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

        <div class="row d-flex justify-content-between mb-6">
            <div class="col-sm-6 w-50">
                <h6>Invoice To:</h6>
                <p class="mb-1">{{ $invoice->customer->name }}</p>
                <p class="mb-1">{{ $invoice->customer->address }}</p>
                <p class="mb-1">{{ $invoice->customer->phone }}</p>
                <p class="mb-0">{{ $invoice->customer->email }}</p>
            </div>
        </div>

        <div class="table-responsive border border-bottom-0 rounded">
            <table class="table m-0">
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
                        <tr>
                            <td class="text-nowrap">{{ $item->product->name }}</td>
                            <td class="text-nowrap">{{ $item->product->description ?? 'N/A' }}</td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->subtotal, 2) }}</td>
                            <td>{{ $item->vat }}% (${{ number_format($item->vatAmount, 2) }})</td>
                            <td>{{ $item->discount }}% (${{ number_format($item->discountAmount, 2) }})</td>
                            <td>${{ number_format($item->totalPrice, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="table-responsive">
            <table class="table m-0 table-borderless">
                <tbody>
                    <tr>
                        <td class="align-top px-6 py-6">
                            <p class="mb-1">
                                <span class="fw-medium">Salesperson:</span>
                                <span>{{ $invoice->salesperson->name ?? 'N/A' }}</span>
                            </p>
                            <span>Thanks for your business</span>
                        </td>
                        <td class="px-0 py-12 w-px-100">
                            <p class="mb-2">Subtotal:</p>
                            <p class="mb-2">Discount:</p>
                            <p class="mb-2">Tax:</p>
                            <p class="mb-0 pt-2">Total:</p>
                        </td>
                        <td class="text-end px-0 py-6 w-px-100">
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
                <span class="fw-medium">Note:</span>
                <span>It was a pleasure working with you. We hope you will keep us in mind. Thank You!</span>
            </div>
        </div>
    </div>
</body>

</html>
