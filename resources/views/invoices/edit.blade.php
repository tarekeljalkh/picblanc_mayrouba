@extends('layouts.master')

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

                        <input type="hidden" id="customer_id" name="customer_id" value="{{ $invoice->customer->id }}">

                        {{-- Customer Inputs --}}
                        <div class="mb-4 row">
                            <label for="customer_name" class="col-md-2 col-form-label">Customer Name</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="customer_name" name="customer_name" value="{{ old('customer_name', $invoice->customer->name) }}" placeholder="Enter Customer Name" />
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="customer_email" class="col-md-2 col-form-label">Customer Email</label>
                            <div class="col-md-10">
                                <input class="form-control" type="email" id="customer_email" name="customer_email" value="{{ old('customer_email', $invoice->customer->email) }}" placeholder="Enter Customer Email" />
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="customer_phone" class="col-md-2 col-form-label">Customer Phone</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $invoice->customer->phone) }}" placeholder="Enter Customer Phone" />
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="customer_address" class="col-md-2 col-form-label">Customer Address</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="customer_address" name="customer_address" value="{{ old('customer_address', $invoice->customer->address) }}" placeholder="Enter Customer Address" />
                            </div>
                        </div>

                        {{-- Deposit Card Image --}}
                        <div class="mb-4 row">
                            <label for="deposit_card" class="col-md-2 col-form-label">Deposit Card</label>
                            <div class="col-md-10">
                                @if($invoice->customer->deposit_card)
                                    <div id="existing_deposit_card_section">
                                        <img id="existing_deposit_card" src="{{ asset($invoice->customer->deposit_card) }}" alt="Deposit Card" style="max-width: 200px; margin-bottom: 10px;" />
                                    </div>
                                @endif
                                <input class="form-control" type="file" id="deposit_card" name="deposit_card" accept="image/*" />
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
                                            <th>VAT (%)</th>
                                            <th>Discount (%)</th>
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
                                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" {{ $product->id == $item->product_id ? 'selected' : '' }}>
                                                                {{ $product->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="number" class="form-control quantity" name="quantities[]" value="{{ $item->quantity }}" /></td>
                                                <td><input type="text" class="form-control price" name="prices[]" value="{{ number_format($item->price, 2) }}" readonly /></td>
                                                <td><input type="number" class="form-control vat" name="vat[]" value="{{ $item->vat }}" /></td>
                                                <td><input type="number" class="form-control discount" name="discount[]" value="{{ $item->discount }}" /></td>
                                                <td><input type="text" class="form-control total-price" name="total_price[]" value="{{ number_format($item->total_price, 2) }}" readonly /></td>
                                                <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-success" id="add-item">Add Item</button>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.product-select').select2({
                placeholder: 'Select Product',
                allowClear: true
            });
        });

        function calculateTotalPrice(row) {
            let quantity = parseFloat(row.querySelector('.quantity').value) || 0;
            let price = parseFloat(row.querySelector('.price').value) || 0;
            let vat = parseFloat(row.querySelector('.vat').value) || 0;
            let discount = parseFloat(row.querySelector('.discount').value) || 0;

            let subtotal = quantity * price;
            let vatAmount = (subtotal * vat) / 100;
            let discountAmount = (subtotal * discount) / 100;
            let total = subtotal + vatAmount - discountAmount;

            row.querySelector('.total-price').value = total.toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.product-select').forEach(function(select) {
                select.addEventListener('change', function() {
                    let price = parseFloat(select.options[select.selectedIndex].getAttribute('data-price')) || 0;
                    let row = select.closest('tr');
                    row.querySelector('.price').value = price.toFixed(2);
                    calculateTotalPrice(row);
                });
            });

            document.querySelectorAll('.quantity, .vat, .discount').forEach(function(input) {
                input.addEventListener('input', function() {
                    let row = input.closest('tr');
                    calculateTotalPrice(row);
                });
            });

            document.getElementById('add-item').addEventListener('click', function () {
                var tableBody = document.querySelector('#invoice-items-table tbody');
                var newRow = document.createElement('tr');
                newRow.innerHTML = `
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
                    <td><input type="number" class="form-control vat" name="vat[]" value="10" /></td>
                    <td><input type="number" class="form-control discount" name="discount[]" value="0" /></td>
                    <td><input type="text" class="form-control total-price" name="total_price[]" value="0.00" readonly /></td>
                    <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
                `;
                tableBody.appendChild(newRow);

                newRow.querySelector('.product-select').addEventListener('change', function () {
                    let price = parseFloat(newRow.querySelector('.product-select').options[newRow.querySelector('.product-select').selectedIndex].getAttribute('data-price')) || 0;
                    newRow.querySelector('.price').value = price.toFixed(2);
                    calculateTotalPrice(newRow);
                });

                newRow.querySelectorAll('.quantity, .vat, .discount').forEach(function(input) {
                    input.addEventListener('input', function() {
                        calculateTotalPrice(newRow);
                    });
                });

                newRow.querySelector('.remove-item').addEventListener('click', function () {
                    newRow.remove();
                });
            });

            document.querySelectorAll('.remove-item').forEach(function(button) {
                button.addEventListener('click', function() {
                    this.closest('tr').remove();
                });
            });
        });
    </script>
@endsection
