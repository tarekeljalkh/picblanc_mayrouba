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
                        <p class="mb-1"><strong>Date Issued:</strong> {{ $invoice->created_at->format('M d, Y') }}</p>
                        <p class="mb-1"><strong>Rental Start:</strong> {{ $invoice->rental_start_date->format('d/m/Y') }}</p>
                        <p><strong>Rental End:</strong> {{ $invoice->rental_end_date->format('d/m/Y') }}</p>
                        <p><strong>Rental Days:</strong> {{ $invoice->days }} day(s)</p>
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
                <div class="table-responsive mt-4">
                    <h6 class="px-3 py-2 bg-light">Additional Items</h6>
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
                <div class="table-responsive mt-4">
                    <h6 class="px-3 py-2 bg-light">Returned Items</h6>
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

            <!-- Invoice Summary -->
            <div class="table-responsive mt-4">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                <strong>Salesperson:</strong> {{ $invoice->user->name ?? 'N/A' }}<br>
                                Thanks for your business!
                            </td>
                            <td class="text-end">
                                @php
                                    $subtotal = $totals['subtotal'];
                                    $addedCost = $totals['additionalCost'];
                                    $returnedCost = $totals['returnedCost'];
                                    $discountAmount = $totals['discountAmount'];
                                    $total = $totals['total'];
                                @endphp
                                <p>Subtotal: ${{ number_format($subtotal, 2) }}</p>
                                @if ($addedCost > 0)
                                    <p>Additional Costs: ${{ number_format($addedCost, 2) }}</p>
                                @endif
                                @if ($returnedCost > 0)
                                    <p class="text-danger">Returned Costs: - ${{ number_format($returnedCost, 2) }}</p>
                                @endif
                                <p>Discount ({{ $invoice->total_discount }}%): - ${{ number_format($discountAmount, 2) }}</p>
                                <p><strong>Total: ${{ number_format($total, 2) }}</strong></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr>
            <div>
                <p>I declare having received the merchandise mentioned above in good condition and agree to return it on time.</p>
                <hr>
                <p>Mayrouba - Tel: 03 71 57 57 | Warde - Tel: 70 100 015 | Mzaar Intercontinental Hotel - Tel: 03 788 733</p>
            </div>
        </div>
    </div>
</div>

@endsection
