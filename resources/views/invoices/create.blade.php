@extends('layouts.master')

@section('title', 'Create Invoice')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-invoice.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

                        {{-- Select Existing Customer --}}
                        <div class="mb-4 row">
                            <label for="select_customer" class="col-md-2 col-form-label">Select Existing Customer</label>
                            <div class="col-md-10">
                                <select class="form-select" id="select_customer" name="customer_id">
                                    <option value="">Select Existing Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                            data-phone="{{ $customer->phone }}" data-address="{{ $customer->address }}">
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-warning mt-2" id="clear_customer_form">Clear Form</button>
                            </div>
                        </div>

                        {{-- Customer Information --}}
                        <div class="mb-4 row">
                            <label for="customer_name" class="col-md-2 col-form-label">Customer Name</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="customer_name" name="customer_name" />
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

                        {{-- Rental Dates --}}
                        <div class="mb-4 row">
                            <label for="rental_start_date" class="col-md-2 col-form-label">Rental Start Date</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="rental_start_date" name="rental_start_date"
                                    placeholder="Enter Rental Start Date" autocomplete="off" required />
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="rental_end_date" class="col-md-2 col-form-label">Rental End Date</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="rental_end_date" name="rental_end_date"
                                    placeholder="Enter Rental End Date" autocomplete="off" required />
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
                                        <tr>
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
                                            <td><input type="text" class="form-control total-price" name="total_price[]" value="0.00" readonly /></td>
                                            <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-success" id="add-item">Add Item</button>
                            </div>
                        </div>

                        {{--Discount, Amount Per Day --}}

                        <div class="mb-4 row">
                            <label for="total_discount" class="col-md-2 col-form-label">Total Discount (%)</label>
                            <div class="col-md-10">
                                <input type="number" class="form-control" id="total_discount" name="total_discount" value="0" min="0" max="100"/>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="amount_per_day" class="col-md-2 col-form-label">Amount Per Day</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="amount_per_day" name="amount_per_day" value="0.00" readonly />
                            </div>
                        </div>

                        {{-- Days and Total Amount --}}
                        <div class="mb-4 row">
                            <label for="days" class="col-md-2 col-form-label">Days</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="days" name="days" value="0" readonly />
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="total_amount" class="col-md-2 col-form-label">Total Amount</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="total_amount" name="total_amount" value="0.00" readonly />
                            </div>
                        </div>

                        {{-- Payment Status --}}
                        <div class="mb-4 row">
                            <label class="col-md-2 col-form-label">Payment Status</label>
                            <div class="col-md-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="paid" id="paid" value="1" {{ old('paid', 0) == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="paid">Paid</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="paid" id="unpaid" value="0" {{ old('paid', 0) == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="unpaid">Unpaid</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="note" class="col-md-2 col-form-label">Note</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="note" name="note" />
                            </div>
                        </div>


                        {{-- Create Button --}}
                        <div class="mt-4 row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary" id="create-invoice-button" disabled>Create Invoice</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Include jQuery, Flatpickr, and Select2 CDN --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        flatpickr("#rental_start_date, #rental_end_date", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d-m-Y",
            allowInput: true,
            onChange: function(selectedDates, dateStr, instance) {
                calculateInvoiceTotal();
            }
        });

        $('#select_customer').select2({
            placeholder: 'Select Existing Customer',
            allowClear: true
        });

        $('#select_customer').on('change', function() {
            let selectedCustomer = $('#select_customer option:selected');
            $('#customer_name').val(selectedCustomer.data('name') || '');
            $('#customer_phone').val(selectedCustomer.data('phone') || '');
            $('#customer_address').val(selectedCustomer.data('address') || '');
            checkFormValidity();
        });

        $('#clear_customer_form').on('click', function() {
            $('#select_customer').val(null).trigger('change');
            $('#customer_name, #customer_phone, #customer_address').val('');
            checkFormValidity();
        });

        function calculateRowTotal(row) {
            let quantity = parseFloat(row.find('.quantity').val()) || 0;
            let price = parseFloat(row.find('.price').val()) || 0;
            row.find('.total-price').val((quantity * price).toFixed(2));
            calculateInvoiceTotal();
        }

        function calculateInvoiceTotal() {
    let subtotal = 0;

    // Calculate the subtotal of invoice items
    $('#invoice-items-table tbody tr').each(function() {
        subtotal += parseFloat($(this).find('.total-price').val()) || 0;
    });

    // Calculate the amount per day based on the subtotal
    let amountPerDay = subtotal;
    $('#amount_per_day').val(amountPerDay.toFixed(2));

    // Calculate the rental duration in days
    let startDate = new Date($('#rental_start_date').val());
    let endDate = new Date($('#rental_end_date').val());
    let days = (startDate && endDate) ? Math.floor((endDate - startDate) / (1000 * 60 * 60 * 24) + 1) : 0;
    $('#days').val(days);

    // Calculate discount for the total amount
    let discount = parseFloat($('#total_discount').val()) || 0;

    let discountAmount = (subtotal * discount) / 100;

    // Calculate the final total amount
    let totalAmount = (subtotal - discountAmount) * days;
    $('#total_amount').val(totalAmount.toFixed(2));

    checkFormValidity();
}


        function checkFormValidity() {
            let hasCustomer = $('#select_customer').val() || ($('#customer_name').val() && $('#customer_phone').val() && $('#customer_address').val());
            let hasProducts = false;

            $('#invoice-items-table .product-select').each(function() {
                if ($(this).val() && parseFloat($(this).closest('tr').find('.quantity').val()) > 0) {
                    hasProducts = true;
                    return false;
                }
            });

            $('#create-invoice-button').prop('disabled', !(hasCustomer && hasProducts));
        }

        $(document).on('change', '.product-select', function() {
            let row = $(this).closest('tr');
            let price = parseFloat($(this).find('option:selected').data('price')) || 0;
            row.find('.price').val(price.toFixed(2));
            calculateRowTotal(row);
        });

        $(document).on('input', '.quantity', function() {
            calculateRowTotal($(this).closest('tr'));
        });

        $('#add-item').on('click', function() {
            let newRow = `
                <tr>
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
                    <td><input type="text" class="form-control total-price" name="total_price[]" value="0.00" readonly /></td>
                    <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
                </tr>`;
            $('#invoice-items-table tbody').append(newRow);
            checkFormValidity();
        });

        $(document).on('click', '.remove-item', function() {
            $(this).closest('tr').remove();
            calculateInvoiceTotal();
        });

        $('#total_discount').on('input', calculateInvoiceTotal);
    </script>
@endsection
