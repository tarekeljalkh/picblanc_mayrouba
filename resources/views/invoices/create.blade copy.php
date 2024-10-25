@extends('layouts.master')

@push('scripts')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-invoice.css') }}" />
@endpush

@section('content')
    <!-- Form to handle invoice creation -->
    <form action="{{ route('invoices.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row invoice-add">
            <!-- Invoice Add-->
            <div class="col-lg-9 col-12 mb-lg-0 mb-6">
                <div class="card invoice-preview-card p-sm-12 p-6">
                    <div class="card-body invoice-preview-header rounded">
                        <div class="d-flex flex-wrap flex-column flex-sm-row justify-content-between text-heading">
                            <div class="mb-md-0 mb-6">
                                <div class="d-flex svg-illustration mb-6 gap-2 align-items-center">
                                    <span class="app-brand-logo demo">
                                        <!-- SVG Logo -->
                                    </span>
                                    <span class="app-brand-text demo fw-bold ms-50">sneat</span>
                                </div>
                                <p class="mb-2">Office 149, 450 South Brand Brooklyn</p>
                                <p class="mb-2">San Diego County, CA 91905, USA</p>
                                <p class="mb-3">+1 (123) 456 7891, +44 (876) 543 2198</p>
                            </div>
                            <div class="col-md-5 col-8 pe-0 ps-0 ps-md-2">
                                <dl class="row mb-0 gx-4">
                                    <dt class="col-sm-5 mb-2 d-md-flex align-items-center justify-content-end">
                                        <span class="h5 text-capitalize mb-0 text-nowrap">Invoice</span>
                                    </dt>
                                    <dd class="col-sm-7">
                                        <input type="text" class="form-control" disabled placeholder="#3905"
                                            value="#3905" id="invoiceId" />
                                    </dd>
                                    <!-- Rental Start Date (Default to Today) -->
                                    <dt class="col-sm-5 mb-1 d-md-flex align-items-center justify-content-end">
                                        <span class="fw-normal">Date Issued:</span>
                                    </dt>
                                    <dd class="col-sm-7">
                                        <input type="date" id="rental_start_date" name="rental_start_date"
                                            class="form-control">

                                    </dd>

                                    <!-- Rental End Date -->
                                    <dt class="col-sm-5 d-md-flex align-items-center justify-content-end">
                                        <span class="fw-normal">Date Due:</span>
                                    </dt>
                                    <dd class="col-sm-7 mb-0">
                                        <input type="date" id="rental_end_date" name="rental_end_date"
                                            class="form-control">
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-0">
                        <div class="row">
                            <div class="col-md-6 col-sm-5 col-12 mb-sm-0 mb-6">
                                <h6>Invoice To:</h6>
                                <select class="form-select mb-4 w-50" id="select_customer" name="customer_id">
                                    <option value="">Select Existing Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                            data-address="{{ $customer->address }}" data-phone="{{ $customer->phone }}"
                                            data-email="{{ $customer->email }}">
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <p class="mb-1"><strong>Name:</strong> <span id="customer_name">Customer Name</span></p>
                                <p class="mb-1"><strong>Address:</strong> <span id="customer_address">Customer
                                        Address</span></p>
                                <p class="mb-1"><strong>Phone:</strong> <span id="customer_phone">Customer Phone</span>
                                </p>
                                <p class="mb-0"><strong>Email:</strong> <span id="customer_email">Customer Email</span>
                                </p>
                            </div>
                            <div class="col-md-6 col-sm-7">
                                <h6>Or Add a new Customer:</h6>
                                <!-- Button to open modal for new customer -->
                                <a class="btn btn-primary ms-2" data-bs-toggle="modal"
                                    data-bs-target="#newCustomerModal">Create New Customer</a>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-0 mb-6">

                    {{-- Products section --}}
                    <div class="card-body pt-0 px-0">
                        <form class="source-item">
                            <div class="mb-4" data-repeater-list="group-a">
                                <div class="repeater-wrapper pt-0 pt-md-9" data-repeater-item="">
                                    <div class="d-flex border rounded position-relative pe-0">
                                        <div class="row w-100 p-6 g-6">
                                            <div class="col-md-6 col-12 mb-md-0 mb-4">
                                                <p class="h6 repeater-title">Item</p>
                                                <select class="form-select product-select mb-6" name="products[]">
                                                    <option value="">Select Product</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}"
                                                            data-price="{{ $product->price }}">
                                                            {{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3 col-12 mb-md-0 mb-4">
                                                <p class="h6 repeater-title">Qty</p>
                                                <input type="number" class="form-control invoice-item-qty"
                                                    name="quantities[]" value="1" placeholder="1" min="1" />
                                                <div class="text-heading">
                                                    <div class="mb-1">Discount:</div>
                                                    <span class="discount-display me-2">0%</span>
                                                    <span class="vat-display me-2" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Vat">0%</span>
                                                </div>
                                            </div>

                                            <div class="col-md-1 col-12 pe-0 mt-8">
                                                <p class="h6 repeater-title">Price</p>
                                                <p class="mb-0 text-heading total-price">$0.00</p>
                                            </div>
                                        </div>

                                        <div
                                            class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                                            <i class="bx bx-x bx-lg cursor-pointer" data-repeater-delete=""></i>
                                            <div class="dropdown">
                                                <i class="bx bx-cog bx-lg cursor-pointer more-options-dropdown"
                                                    role="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                    data-bs-auto-close="outside" aria-expanded="false"></i>
                                                <div class="dropdown-menu dropdown-menu-end w-px-300 p-4"
                                                    aria-labelledby="dropdownMenuButton">
                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <label for="discountInput"
                                                                class="form-label">Discount(%)</label>
                                                            <input type="number" class="form-control discount"
                                                                name="discount[]" min="0" max="100"
                                                                value="0" />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="vatInput" class="form-label">VAT (%)</label>
                                                            <input type="number" class="form-control vat" name="vat[]"
                                                                min="0" max="100" value="0" />
                                                        </div>
                                                    </div>
                                                    <div class="dropdown-divider my-4"></div>
                                                    <button type="button"
                                                        class="btn btn-label-primary btn-apply-changes">Apply</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-sm btn-primary" data-repeater-create><i
                                            class="bx bx-plus bx-xs me-1_5"></i>Add Item</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- End Products section --}}

                    <hr class="my-0">
                    <div class="card-body px-0">
                        <div class="row row-gap-4">
                            <div class="col-md-6 mb-md-0 mb-4">
                                <div class="d-flex align-items-center mb-4">
                                    <label for="salesperson" class="me-2 fw-medium text-heading">Salesperson:</label>
                                    <input type="text" class="form-control" id="salesperson" name="salesperson"
                                        value="{{ auth()->user()->name }}" />
                                </div>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <div class="invoice-calculations">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="w-px-100">Subtotal:</span>
                                        <span class="fw-medium text-heading subtotal-amount">$0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="w-px-100">Discount:</span>
                                        <span class="fw-medium text-heading discount-amount">$0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="w-px-100">VAT:</span>
                                        <span class="fw-medium text-heading vat-amount">$0.00</span>
                                    </div>
                                    <hr class="my-2" />
                                    <div class="d-flex justify-content-between">
                                        <span class="w-px-100">Total:</span>
                                        <span class="fw-medium text-heading total-amount">$0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0">
                    <div class="card-body px-0 pb-0">
                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <label for="note" class="text-heading mb-1 fw-medium">Note:</label>
                                    <textarea class="form-control" rows="2" id="note" placeholder="Invoice note">It was a pleasure working with you. We hope you will keep us in mind for future projects. Thank You!</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Invoice Add-->

            <!-- Invoice Actions-->
            <div class="col-lg-3 col-12 invoice-actions">
                <div class="card mb-6">
                    <div class="card-body">
                        <button class="btn btn-primary d-grid w-100 mb-4" data-bs-toggle="offcanvas"
                            data-bs-target="#sendInvoiceOffcanvas">
                            <span class="d-flex align-items-center justify-content-center text-nowrap"><i
                                    class="bx bx-paper-plane bx-xs me-2"></i>Send Invoice</span>
                        </button>
                        <button type="submit" class="btn btn-label-secondary d-grid w-100">Save</button>
                    </div>
                </div>
            </div>
            <!-- /Invoice Actions -->
        </div>

        <!-- Offcanvas -->
        <!-- Send Invoice Sidebar -->
        <div class="offcanvas offcanvas-end" id="sendInvoiceOffcanvas" aria-hidden="true">
            <div class="offcanvas-header mb-6 border-bottom">
                <h5 class="offcanvas-title">Send Invoice</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body pt-0 flex-grow-1">
                <form>
                    <div class="mb-6">
                        <label for="invoice-from" class="form-label">From</label>
                        <input type="text" class="form-control" id="invoice-from" value="shelbyComapny@email.com"
                            placeholder="company@email.com" />
                    </div>
                    <div class="mb-6">
                        <label for="invoice-to" class="form-label">To</label>
                        <input type="text" class="form-control" id="invoice-to" value="qConsolidated@email.com"
                            placeholder="company@email.com" />
                    </div>
                    <div class="mb-6">
                        <label for="invoice-subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="invoice-subject"
                            value="Invoice of purchased Admin Templates" placeholder="Invoice regarding goods" />
                    </div>
                    <div class="mb-6">
                        <label for="invoice-message" class="form-label">Message</label>
                        <textarea class="form-control" name="invoice-message" id="invoice-message" cols="3" rows="8">Dear Queen Consolidated,
                        Thank you for your business, always a pleasure working with you!
                        We have generated a new invoice in the amount of $95.59
                        We would appreciate payment of this invoice by 05/11/2021
                    </textarea>
                    </div>
                    <div class="mb-6">
                        <span class="badge bg-label-primary">
                            <i class="bx bx-link bx-xs"></i>
                            <span class="align-middle">Invoice Attached</span>
                        </span>
                    </div>
                    <div class="mb-6 d-flex flex-wrap">
                        <button type="button" class="btn btn-primary me-4" data-bs-dismiss="offcanvas">Send</button>
                        <button type="button" class="btn btn-label-secondary"
                            data-bs-dismiss="offcanvas">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /Send Invoice Sidebar -->

        <!-- Modal for creating a new customer -->
        <div class="modal fade" id="newCustomerModal" tabindex="-1" aria-labelledby="newCustomerModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newCustomerModalLabel">Create New Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('invoices.customer.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="customer_name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="customer_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="customer_email" name="email">
                            </div>
                            <div class="mb-3">
                                <label for="customer_phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="customer_phone" name="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="customer_address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="customer_address" name="address">
                            </div>
                            <div class="mb-3">
                                <label for="deposit_card" class="form-label">Deposit Card</label>
                                <input type="file" class="form-control" id="deposit_card" name="deposit_card">
                            </div>
                            <button type="submit" class="btn btn-primary">Save Customer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Check if there's a new customer ID passed from the session
            var newCustomerId = "{{ session('new_customer_id') }}";

            if (newCustomerId) {
                // Pre-select the newly created customer in the dropdown
                $('#select_customer').val(newCustomerId).change();

                // Optionally, you can also populate the customer details (name, email, etc.) if needed
                var selectedCustomer = $('#select_customer').find(':selected');
                $('#customer_name').text(selectedCustomer.data('name') || 'Customer Name');
                $('#customer_email').text(selectedCustomer.data('email') || 'Customer Email');
                $('#customer_address').text(selectedCustomer.data('address') || 'Customer Address');
                $('#customer_phone').text(selectedCustomer.data('phone') || 'Customer Phone');
            }

            // Handle customer selection (this remains unchanged)
            $('#select_customer').on('change', function() {
                var selectedCustomer = $(this).find(':selected');
                var customerName = selectedCustomer.data('name') || 'Customer Name';
                var customerEmail = selectedCustomer.data('email') || 'Customer Email';
                var customerAddress = selectedCustomer.data('address') || 'Customer Address';
                var customerPhone = selectedCustomer.data('phone') || 'Customer Phone';

                $('#customer_name').text(customerName);
                $('#customer_email').text(customerEmail);
                $('#customer_address').text(customerAddress);
                $('#customer_phone').text(customerPhone);
            });

            // Function to calculate total price per item
            function calculateTotalPrice(row) {
                let quantity = parseFloat(row.find('.invoice-item-qty').val()) || 0;
                let price = parseFloat(row.find('.product-select option:selected').data('price')) || 0;
                let discount = parseFloat(row.find('.discount').val()) || 0;
                let vat = parseFloat(row.find('.vat').val()) || 0;

                let subtotal = quantity * price;
                let discountAmount = (subtotal * discount) / 100;
                let totalBeforeVAT = subtotal - discountAmount;
                let vatAmount = (totalBeforeVAT * vat) / 100;
                let total = totalBeforeVAT + vatAmount;

                row.find('.total-price').text('$' + total.toFixed(2));

                // Update the displayed discount and VAT percentage in the item
                row.find('.discount-display').text(discount + '%');
                row.find('.vat-display').text(vat + '%');

                updateInvoiceTotals();
            }

            // Function to update overall totals
            function updateInvoiceTotals() {
                let subtotal = 0;
                let totalDiscount = 0;
                let totalVAT = 0;

                $('.repeater-wrapper').each(function() {
                    let row = $(this);
                    let price = parseFloat(row.find('.product-select option:selected').data('price')) || 0;
                    let quantity = parseFloat(row.find('.invoice-item-qty').val()) || 0;
                    let discount = parseFloat(row.find('.discount').val()) || 0;
                    let vat = parseFloat(row.find('.vat').val()) || 0;

                    let rowSubtotal = quantity * price;
                    let discountAmount = (rowSubtotal * discount) / 100;
                    let totalBeforeVAT = rowSubtotal - discountAmount;
                    let vatAmount = (totalBeforeVAT * vat) / 100;

                    subtotal += totalBeforeVAT + vatAmount;
                    totalDiscount += discountAmount;
                    totalVAT += vatAmount;
                });

                // Update the displayed values in the summary section
                $('.subtotal-amount').text('$' + subtotal.toFixed(2));
                $('.discount-amount').text('$' + totalDiscount.toFixed(2));
                $('.vat-amount').text('$' + totalVAT.toFixed(2));
                $('.total-amount').text('$' + (subtotal + totalVAT - totalDiscount).toFixed(2));
            }

            // Event for product selection and quantity change (input and change events)
            $(document).on('input change', '.product-select, .invoice-item-qty', function() {
                let row = $(this).closest('.repeater-wrapper');
                calculateTotalPrice(row);
            });

            // Event when discount or VAT is applied
            $(document).on('click', '.btn-apply-changes', function() {
                let row = $(this).closest('.repeater-wrapper');
                calculateTotalPrice(row);
            });

            // Repeater for adding new items
            $('[data-repeater-create]').on('click', function() {
                let repeaterList = $('[data-repeater-list="group-a"]');
                let newItem = repeaterList.find('.repeater-wrapper:first').clone();

                // Reset input fields for the new item
                newItem.find('input').val('');
                newItem.find('.total-price').text('$0.00');
                newItem.find('.discount-display').text('0%');
                newItem.find('.vat-display').text('0%');

                repeaterList.append(newItem);
            });

            // Remove item functionality
            $(document).on('click', '[data-repeater-delete]', function() {
                $(this).closest('.repeater-wrapper').remove();
                updateInvoiceTotals();
            });
        });

        // Initialize Flatpickr for both date inputs
        flatpickr('#rental_start_date', {
            dateFormat: "d-m-Y"
        });
        flatpickr('#rental_end_date', {
            dateFormat: "d-m-Y"
        });
    </script>
@endpush
