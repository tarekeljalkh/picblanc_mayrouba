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
                    <form action="{{ route('invoices.update', $invoice->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Customer Inputs --}}
                        <div class="mb-4 row">
                            <label for="customer_name" class="col-md-2 col-form-label">Customer Name</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="customer_name" name="customer_name"
                                       value="{{ old('customer_name', $invoice->customer->name) }}" placeholder="Enter Customer Name" />
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="customer_phone" class="col-md-2 col-form-label">Customer Phone</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="customer_phone" name="customer_phone"
                                       value="{{ old('customer_phone', $invoice->customer->phone) }}" placeholder="Enter Customer Phone" />
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="customer_address" class="col-md-2 col-form-label">Customer Address</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="customer_address" name="customer_address"
                                       value="{{ old('customer_address', $invoice->customer->address) }}" placeholder="Enter Customer Address" />
                            </div>
                        </div>

                        {{-- Invoice Items --}}
                        <div class="mb-4 row">
                            <label for="items" class="col-md-2 col-form-label">Invoice Items</label>
                            <div class="col-md-10">
                                <table class="table" id="invoice-items-table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoice->items as $item)
                                            <tr>
                                                <td>
                                                    <select class="form-select product-select" name="products[]">
                                                        <option value="">Select Product</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                                                {{ $product->id == $item->product_id ? 'selected' : '' }}>{{ $product->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="number" class="form-control quantity" name="quantities[]" value="{{ $item->quantity }}" /></td>
                                                <td><input type="text" class="form-control price" name="prices[]" value="{{ number_format($item->price, 2) }}" readonly /></td>
                                                <td><input type="text" class="form-control total-price" name="total_price[]" value="{{ number_format($item->total_price, 2) }}" readonly /></td>
                                                <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-success" id="add-item">Add Item</button>
                            </div>
                        </div>

                        {{-- Total VAT, Discount, and Grand Total --}}
                        <div class="mb-4 row">
                            <label for="total_vat" class="col-md-2 col-form-label">Total VAT (%)</label>
                            <div class="col-md-10">
                                <input type="number" class="form-control" id="total_vat" name="total_vat" value="{{ $invoice->total_vat }}" />
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="total_discount" class="col-md-2 col-form-label">Total Discount (%)</label>
                            <div class="col-md-10">
                                <input type="number" class="form-control" id="total_discount" name="total_discount" value="{{ $invoice->total_discount }}" />
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="grand_total" class="col-md-2 col-form-label">Total Amount</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="grand_total" name="grand_total" value="{{ number_format($invoice->total, 2) }}" readonly />
                            </div>
                        </div>

                        {{-- Payment Status --}}
                        <div class="mb-4 row">
                            <label class="col-md-2 col-form-label">Payment Status</label>
                            <div class="col-md-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="paid" id="paid" value="1" {{ $invoice->paid ? 'checked' : '' }}>
                                    <label class="form-check-label" for="paid">Paid</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="paid" id="unpaid" value="0" {{ !$invoice->paid ? 'checked' : '' }}>
                                    <label class="form-check-label" for="unpaid">Unpaid</label>
                                </div>
                            </div>
                        </div>

                        {{-- Update Button --}}
                        <div class="mt-4 row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">Update Invoice</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Include jQuery and Select2 CDN --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for existing products and dynamically added rows
            $('.product-select').select2({
                placeholder: 'Select Product',
                allowClear: true
            });
        });

        // Calculate the total price for each item row
        function calculateRowTotal(row) {
            let quantity = parseFloat(row.find('.quantity').val()) || 0;
            let price = parseFloat(row.find('.price').val()) || 0;
            let total = quantity * price;
            row.find('.total-price').val(total.toFixed(2));
            calculateInvoiceTotal();
        }

        // Calculate grand total for the invoice with VAT and Discount
        function calculateInvoiceTotal() {
            let subtotal = 0;

            $('#invoice-items-table tbody tr').each(function() {
                subtotal += parseFloat($(this).find('.total-price').val()) || 0;
            });

            let vat = parseFloat($('#total_vat').val()) || 0;
            let discount = parseFloat($('#total_discount').val()) || 0;
            let vatAmount = (subtotal * vat) / 100;
            let discountAmount = (subtotal * discount) / 100;
            let grandTotal = subtotal + vatAmount - discountAmount;

            $('#grand_total').val(grandTotal.toFixed(2));
        }

        // Event listeners for product selection, quantity input, VAT, and discount
        $(document).on('change', '.product-select', function() {
            let row = $(this).closest('tr');
            let price = parseFloat($(this).find('option:selected').data('price')) || 0;
            row.find('.price').val(price.toFixed(2));
            calculateRowTotal(row);
        });

        $(document).on('input', '.quantity', function() {
            calculateRowTotal($(this).closest('tr'));
        });

        $('#total_vat, #total_discount').on('input', calculateInvoiceTotal);

        // Add new item row
        $('#add-item').on('click', function() {
            let newRow = `
                <tr>
                    <td>
                        <select class="form-select product-select" name="products[]">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" class="form-control quantity" name="quantities[]" value="1" /></td>
                    <td><input type="text" class="form-control price" name="prices[]" value="0.00" readonly /></td>
                    <td><input type="text" class="form-control total-price" name="total_price[]" value="0.00" readonly /></td>
                    <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
                </tr>`;
            $('#invoice-items-table tbody').append(newRow);

            // Initialize Select2 for the new row
            $('.product-select').last().select2({
                placeholder: 'Select Product',
                allowClear: true
            });
        });

        // Remove item row
        $(document).on('click', '.remove-item', function() {
            $(this).closest('tr').remove();
            calculateInvoiceTotal();
        });
    </script>
@endsection
