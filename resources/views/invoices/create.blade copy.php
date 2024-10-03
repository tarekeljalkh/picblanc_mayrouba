@extends('layouts.master')

@section('title', 'Create Invoice')

@push('styles')
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-invoice.css') }}" />
@endpush

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Invoices</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create Invoice</li>
        </ol>
    </nav>

    <div class="row g-6">
        <div class="col-md">
            <div class="card">
                <h5 class="card-header">Create New Invoice</h5>
                <div class="card-body">
                    <form action="{{ route('invoices.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Customer Inputs --}}
                        <div class="mb-4 row">
                            <label for="customer_name" class="col-md-2 col-form-label">Customer Name</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="customer_name" name="customer_name" />
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="customer_email" class="col-md-2 col-form-label">Customer Email</label>
                            <div class="col-md-10">
                                <input class="form-control" type="email" id="customer_email" name="customer_email" />
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="customer_phone" class="col-md-2 col-form-label">Customer Phone</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="customer_phone" name="customer_phone" />
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="customer_address" class="col-md-2 col-form-label">Customer Address</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="customer_address" name="customer_address" />
                            </div>
                        </div>

                        {{-- Rental Start Date --}}
                        <div class="mb-4 row">
                            <label for="rental_start_date" class="col-md-2 col-form-label">Rental Start Date</label>
                            <div class="col-md-10">
                                <input class="form-control" type="date" id="rental_start_date" name="rental_start_date"
                                    placeholder="Enter Rental Start Date" required />
                            </div>
                        </div>

                        {{-- Rental End Date --}}
                        <div class="mb-4 row">
                            <label for="rental_end_date" class="col-md-2 col-form-label">Rental End Date</label>
                            <div class="col-md-10">
                                <input class="form-control" type="date" id="rental_end_date" name="rental_end_date"
                                    placeholder="Enter Rental End Date" required />
                            </div>
                        </div>

                        {{-- Deposit Card Image --}}
                        <div class="mb-4 row">
                            <label for="deposit_card" class="col-md-2 col-form-label">Deposit Card</label>
                            <div class="col-md-10">
                                <div id="existing_deposit_card_section" style="display: none;">
                                    <img id="existing_deposit_card" src="" alt="Deposit Card"
                                        style="max-width: 200px; margin-bottom: 10px;" />
                                </div>
                                <input class="form-control" type="file" id="deposit_card" name="deposit_card"
                                    accept="image/*" capture="camera" />
                            </div>
                        </div>

                        {{-- Select Existing Customer --}}
                        <div class="mb-4 row">
                            <label for="select_customer" class="col-md-2 col-form-label">Select Existing Customer</label>
                            <div class="col-md-10">
                                <select class="form-select" id="select_customer" name="customer_id">
                                    <option value="">Select Existing Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                            data-email="{{ $customer->email }}" data-phone="{{ $customer->phone }}"
                                            data-address="{{ $customer->address }}"
                                            data-deposit-card="{{ $customer->deposit_card }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-warning mt-2" id="clear_customer_form">Clear
                                    Form</button>
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
                                        <tr>
                                            <td>
                                                <select class="form-select product-select" name="products[]">
                                                    <option value="">Select Product</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}"
                                                            data-price="{{ $product->price }}">{{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="number" class="form-control quantity" name="quantities[]"
                                                    value="1" /></td>
                                            <td><input type="text" class="form-control price" name="prices[]"
                                                    value="0.00" readonly /></td>
                                            <td><input type="number" class="form-control vat" name="vat[]"
                                                    value="10" /></td>
                                            <td><input type="number" class="form-control discount" name="discount[]"
                                                    value="0" /></td>
                                            <td><input type="text" class="form-control total-price"
                                                    name="total_price[]" value="0.00" readonly /></td>
                                            <td><button type="button" class="btn btn-danger remove-item">Remove</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-success" id="add-item">Add Item</button>
                            </div>
                        </div>

                        {{-- Create Button --}}
                        <div class="mt-4 row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">Create Invoice</button>
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
        // Initialize Select2 on the select_customer element
        $(document).ready(function() {
            $('#select_customer').select2({
                placeholder: 'Select Existing Customer',
                allowClear: true
            });
        });

        // Auto-fill customer data when selecting an existing customer
        document.getElementById('select_customer').addEventListener('change', function() {
            let selectedCustomer = this.options[this.selectedIndex];

            document.getElementById('customer_name').value = selectedCustomer.getAttribute('data-name') || '';
            document.getElementById('customer_email').value = selectedCustomer.getAttribute('data-email') || '';
            document.getElementById('customer_phone').value = selectedCustomer.getAttribute('data-phone') || '';
            document.getElementById('customer_address').value = selectedCustomer.getAttribute('data-address') || '';

            let depositCardUrl = selectedCustomer.getAttribute('data-deposit-card');
            let existingDepositCardSection = document.getElementById('existing_deposit_card_section');
            let existingDepositCard = document.getElementById('existing_deposit_card');

            if (depositCardUrl) {
                existingDepositCardSection.style.display = 'block';
                existingDepositCard.src = depositCardUrl;
            } else {
                existingDepositCardSection.style.display = 'none';
            }
        });

        // Clear customer form
        document.getElementById('clear_customer_form').addEventListener('click', function() {
            document.getElementById('customer_name').value = '';
            document.getElementById('customer_email').value = '';
            document.getElementById('customer_phone').value = '';
            document.getElementById('customer_address').value = '';
            $('#select_customer').val(null).trigger('change');
            document.getElementById('existing_deposit_card_section').style.display = 'none';
        });

        // Invoice item handling
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

            document.getElementById('add-item').addEventListener('click', function() {
                var tableBody = document.querySelector('#invoice-items-table tbody');
                var newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>
                        <select class="form-select product-select" name="products[]">
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
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

                newRow.querySelector('.product-select').addEventListener('change', function() {
                    let price = parseFloat(newRow.querySelector('.product-select').options[newRow
                        .querySelector('.product-select').selectedIndex].getAttribute('data-price')) || 0;
                    newRow.querySelector('.price').value = price.toFixed(2);
                    calculateTotalPrice(newRow);
                });

                newRow.querySelectorAll('.quantity, .vat, .discount').forEach(function(input) {
                    input.addEventListener('input', function() {
                        calculateTotalPrice(newRow);
                    });
                });

                newRow.querySelector('.remove-item').addEventListener('click', function() {
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
