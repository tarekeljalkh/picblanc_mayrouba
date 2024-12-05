@extends('layouts.master')

@section('title', 'POS')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <!-- Left Column: Products -->
            <div class="col-md-8">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Real-Time Search Input -->
                    <input type="text" id="search-product" class="form-control" placeholder="Search products...">
                </div>

                <!-- Products Grid -->
                <div class="row" id="product-list">
                    @foreach ($products as $product)
                        <div class="col-md-3 col-sm-6 col-12 mb-4 product-card-container">
                            <div class="card product-card clickable-card" data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}" data-price="{{ $product->price }}">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text">${{ number_format($product->price, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Column: Customer Selection and Cart -->
            <div class="col-md-4" style="background-color: #ffffff;">
                <h4 class="my-4">Select Customer</h4>
                <div class="mb-6 d-flex justify-content-between">
                    <!-- Customer Dropdown with Search -->
                    <select id="customer-select" class="select2 form-select form-select-lg" data-allow-clear="true">
                        <option value="" disabled selected>Select a customer</option>
                        @foreach ($customers as $customer)
                            <option
                                value="{{ $customer->id }}"
                                data-phone="{{ $customer->phone }}"
                                {{ session('new_customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->phone }})
                            </option>
                        @endforeach
                    </select>

                    <!-- Button to Create New Customer -->
                    <button class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#newCustomerModal">
                        Create New Customer
                    </button>
                </div>

                <!-- Cart Section -->
                <h4>Your Cart</h4>
                <div id="cart" class="border p-3 mb-4" style="min-height: 200px;">
                    <p>No items in the cart.</p>
                </div>

                <!-- Rental Dates and Days Calculation -->
                <div class="mb-3">
                    <label for="rental-start-date" class="form-label">Rental Start Date</label>
                    <input type="date" class="form-control" id="rental-start-date">
                </div>

                <div class="mb-3">
                    <label for="rental-end-date" class="form-label">Rental End Date</label>
                    <input type="date" class="form-control" id="rental-end-date">
                </div>

                <div class="mb-3">
                    <label for="rental-days" class="form-label">Days</label>
                    <input type="number" class="form-control" id="rental-days" readonly>
                </div>

                <!-- Discount, and Total Amount -->
                <div class="mb-3">
                    <label for="total-discount" class="form-label">Total Discount (%)</label>
                    <input type="number" class="form-control" id="total-discount" value="0">
                </div>

                <!-- Payment Status -->
                <div class="mb-4">
                    <label class="form-label">Payment Status</label><br>
                    <input class="form-check-input" type="radio" name="payment_status" id="paid-radio" value="1"
                        checked>
                    <label class="form-check-label" for="paid-radio">Paid</label>

                    <input class="form-check-input ms-2" type="radio" name="payment_status" id="unpaid-radio"
                        value="0">
                    <label class="form-check-label" for="unpaid-radio">Unpaid</label>
                </div>

                <!-- Payment Method -->
                <div class="mb-4">
                    <label class="form-label">Payment Method</label><br>
                    <input class="form-check-input" type="radio" name="payment_method" id="cash-radio" value="cash"
                        checked>
                    <label class="form-check-label" for="cash-radio">Cash</label>

                    <input class="form-check-input ms-2" type="radio" name="payment_method" id="credit-radio"
                        value="credit_card">
                    <label class="form-check-label" for="credit-radio">Credit Card</label>
                </div>

                <!-- Total Amount -->
                <div class="mb-3">
                    <label for="total-amount" class="form-label">Total Amount</label>
                    <input type="text" class="form-control" id="total-amount" readonly>
                </div>

                <!-- Note -->
                <div class="mb-3">
                    <label for="note" class="form-label">Note</label>
                    <input type="text" class="form-control" id="note">
                </div>

                <!-- Checkout Button -->
                <button class="btn w-100" id="checkout-btn" disabled>Checkout</button>
            </div>
        </div>
    </div>

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
                    <form action="{{ route('pos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="customer_name" name="name" required>
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

    @push('scripts')
        <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
        <script src="{{ asset('assets/js/forms-selects.js') }}"></script>

        <script>
            let cart = [];

            $(document).ready(function() {
                $('#customer-select').select2({
                    placeholder: 'Select A Customer',
                    allowClear: true
                });

                // Disable checkout if no rental dates are selected
                function validateCheckoutButton() {
                    const startDate = $('#rental-start-date').val();
                    const endDate = $('#rental-end-date').val();
                    const isCartEmpty = cart.length === 0;

                    $('#checkout-btn').prop('disabled', !(startDate && endDate && !isCartEmpty));
                }

                // Change button appearance based on payment status
                $('input[name="payment_status"]').on('change', function() {
                    const paymentStatus = $('input[name="payment_status"]:checked').val();
                    const checkoutBtn = $('#checkout-btn');

                    if (paymentStatus === '1') {
                        checkoutBtn.removeClass('btn-danger').addClass('btn-success').text('Checkout');
                    } else if (paymentStatus === '0') {
                        checkoutBtn.removeClass('btn-success').addClass('btn-danger').text('Save as Draft');
                    }
                });

                // Trigger button change on page load
                $('input[name="payment_status"]:checked').trigger('change');

                $('#rental-start-date, #rental-end-date').on('change', function() {
                    calculateDays();
                    validateCheckoutButton();
                });

                $('#search-product').on('input', function() {
                    const searchTerm = $(this).val().toLowerCase();
                    $('#product-list .product-card-container').each(function() {
                        const productName = $(this).find('.card-title').text().toLowerCase();
                        $(this).toggle(productName.includes(searchTerm));
                    });
                });

                $('.clickable-card').on('click', function() {
                    const productId = $(this).data('id');
                    const productName = $(this).data('name');
                    const productPrice = parseFloat($(this).data('price'));

                    let productInCart = cart.find(item => item.id === productId);
                    if (productInCart) {
                        productInCart.quantity += 1;
                    } else {
                        cart.push({
                            id: productId,
                            name: productName,
                            price: productPrice,
                            quantity: 1
                        });
                    }
                    renderCart();
                    validateCheckoutButton();
                });

                function calculateDays() {
                    const startDate = new Date($('#rental-start-date').val());
                    const endDate = new Date($('#rental-end-date').val());

                    if (!isNaN(startDate) && !isNaN(endDate) && startDate <= endDate) {
                        const days = (endDate - startDate) / (1000 * 60 * 60 * 24) + 1;
                        $('#rental-days').val(days);
                    } else {
                        $('#rental-days').val(0);
                    }
                    calculateTotalAmount();
                }

                function calculateTotalAmount() {
                    let total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                    const days = parseInt($('#rental-days').val()) || 1;
                    const totalDiscount = parseFloat($('#total-discount').val()) || 0;

                    total *= days;
                    const discountAmount = (total * totalDiscount) / 100;
                    const grandTotal = total - discountAmount;

                    $('#total-amount').val(grandTotal.toFixed(2));
                }

                function renderCart() {
                    let cartHtml = '';
                    if (cart.length > 0) {
                        cart.forEach((item, index) => {
                            cartHtml += `
                            <div class="cart-item mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><strong>${item.name}</strong></span>
                                    <button class="btn btn-danger btn-sm remove-from-cart" data-index="${index}">Remove</button>
                                </div>
                                <div class="form-row mt-2">
                                    <div class="d-flex justify-content-between">
                                        <div class="col">
                                            <label>Qty</label>
                                            <input type="number" class="form-control form-control-sm quantity" data-index="${index}" value="${item.quantity}" />
                                        </div>
                                        <div class="col">
                                            <label>Price</label>
                                            <input type="text" class="form-control form-control-sm price" value="${(item.price * item.quantity).toFixed(2)}" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        });
                    } else {
                        cartHtml = '<p>No items in the cart.</p>';
                    }

                    $('#cart').html(cartHtml);
                    attachInputListeners();
                    calculateTotalAmount();
                }

                function attachInputListeners() {
                    $('.quantity').on('input', function() {
                        const index = $(this).data('index');
                        cart[index].quantity = parseFloat($(this).val()) || 0;
                        renderCart();
                    });

                    $('.remove-from-cart').on('click', function() {
                        const index = $(this).data('index');
                        cart.splice(index, 1);
                        renderCart();
                        validateCheckoutButton();
                    });
                }

                $('#checkout-btn').on('click', function() {
                    const selectedCustomer = $('#customer-select').val();
                    const paymentStatus = $('input[name="payment_status"]:checked').val();
                    const paymentMethod = $('input[name="payment_method"]:checked').val();

                    const startDateWithTime = $('#rental-start-date').val() + 'T00:00:00';
                    const endDateWithTime = $('#rental-end-date').val() + 'T23:59:59';

                    $.ajax({
                        url: '{{ route('pos.checkout') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            cart: cart,
                            customer_id: selectedCustomer,
                            total_discount: $('#total-discount').val(),
                            status: paymentStatus,
                            payment_method: paymentMethod,
                            rental_days: $('#rental-days').val(),
                            rental_start_date: startDateWithTime,
                            rental_end_date: endDateWithTime,
                            total_amount: $('#total-amount').val(),
                            note: $('#note').val()
                        },
                        success: function(response) {
                            window.location.href = '{{ route('invoices.show', ':id') }}'.replace(
                                ':id', response.invoice_id);
                        },
                        error: function() {
                            alert('Error processing checkout.');
                        }
                    });
                });

                validateCheckoutButton();
            });
        </script>
    @endpush
@endsection
