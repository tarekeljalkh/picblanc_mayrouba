@extends('layouts.master')

@section('title', 'POS')

@section('content')
    <div class="container">
        <div class="row">
            <!-- Left Column: Products -->
            <div class="col-md-8">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="my-4">POS - Select Products</h2>
                    <!-- Real-Time Search Input -->
                    <input type="text" id="search-product" class="form-control w-50" placeholder="Search products...">
                </div>

                <!-- Products Grid -->
                <div class="row" id="product-list">
                    @foreach($products as $product)
                        <div class="col-md-3 col-sm-6 col-12 mb-4 product-card-container">
                            <div class="card product-card clickable-card"
                                 data-id="{{ $product->id }}"
                                 data-name="{{ $product->name }}"
                                 data-price="{{ $product->price }}">
                                <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
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
            <div class="col-md-4">
                <h4 class="my-4">Select Customer</h4>
                <div class="mb-4 d-flex justify-content-between">
                    <select id="customer-select" class="form-select">
                        <option value="" disabled selected>Select a customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ session('new_customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    <!-- Button to open modal for new customer -->
                    <button class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#newCustomerModal">Create New Customer</button>
                </div>

                <!-- Cart Section -->
                <h4>Your Cart</h4>
                <div id="cart" class="border p-3 mb-4" style="min-height: 200px;">
                    <p>No items in the cart.</p>
                </div>

                <!-- Checkout Button -->
                <button class="btn btn-success w-100" id="checkout-btn" disabled>Checkout</button>
            </div>
        </div>
    </div>

    <!-- Modal for creating a new customer -->
    <div class="modal fade" id="newCustomerModal" tabindex="-1" aria-labelledby="newCustomerModalLabel" aria-hidden="true">
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
@endsection


@push('scripts')
    <script>
        let cart = [];
        let selectedCustomer = null;

        $(document).ready(function() {
            // Pre-select customer if passed through session
            let newCustomerId = "{{ session('new_customer_id') }}";
            if (newCustomerId) {
                $('#customer-select').val(newCustomerId);
                selectedCustomer = newCustomerId; // Set the selected customer
                enableCheckoutButton(); // Enable the checkout button if a customer is selected
            }

            // Enable checkout button on customer selection
            $('#customer-select').on('change', function() {
                selectedCustomer = $(this).val();
                enableCheckoutButton(); // Enable checkout if a customer is selected
            });

            // Make the full product card clickable
            $('.clickable-card').on('click', function() {
                const productId = $(this).data('id');
                const productName = $(this).data('name');
                const productPrice = parseFloat($(this).data('price'));

                // Check if product is already in the cart
                let productInCart = cart.find(item => item.id === productId);

                if (productInCart) {
                    productInCart.quantity += 1;
                } else {
                    cart.push({
                        id: productId,
                        name: productName,
                        price: productPrice,
                        quantity: 1,
                        vat: 10, // Default VAT
                        discount: 0 // Default discount
                    });
                }

                renderCart(); // Render cart immediately after adding a product
            });

            // Render the cart and attach event listeners
            function renderCart() {
                let cartHtml = '';
                let total = 0;

                if (cart.length > 0) {
                    cart.forEach((item, index) => {
                        let priceWithVat = item.price + (item.price * (item.vat / 100)); // Apply VAT
                        let priceAfterDiscount = priceWithVat - (priceWithVat * (item.discount / 100)); // Apply discount
                        let itemTotal = priceAfterDiscount * item.quantity;

                        total += itemTotal;

                        cartHtml += `
                            <div class="cart-item mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><strong>${item.name}</strong></span>
                                    <button class="btn btn-danger btn-sm remove-from-cart" data-index="${index}">Remove</button>
                                </div>
                                <div class="form-row mt-2">
                                    <!-- First Row: Quantity and VAT -->
                                    <div class="d-flex justify-content-between">
                                        <div class="col">
                                            <label>Qty</label>
                                            <input type="number" class="form-control form-control-sm quantity" data-index="${index}" value="${item.quantity}" />
                                        </div>
                                        <div class="col">
                                            <label>VAT (%)</label>
                                            <input type="number" class="form-control form-control-sm vat" data-index="${index}" value="${item.vat}" />
                                        </div>
                                    </div>
                                    <!-- Second Row: Discount and Price -->
                                    <div class="d-flex justify-content-between mt-2">
                                        <div class="col">
                                            <label>Discount (%)</label>
                                            <input type="number" class="form-control form-control-sm discount" data-index="${index}" value="${item.discount}" />
                                        </div>
                                        <div class="col">
                                            <label>Price</label>
                                            <input type="text" class="form-control form-control-sm price" value="${itemTotal.toFixed(2)}" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    cartHtml += `<p class="text-end"><strong>Total: $${total.toFixed(2)}</strong></p>`;
                } else {
                    cartHtml = '<p>No items in the cart.</p>';
                }

                $('#cart').html(cartHtml);
                enableCheckoutButton(); // Enable the checkout button if the cart is not empty

                // Attach event listeners to input fields after rendering cart
                attachInputListeners();
            }

            // Attach event listeners for quantity, VAT, discount changes
            function attachInputListeners() {
                $('.quantity').on('input', function() {
                    const index = $(this).data('index');
                    cart[index].quantity = parseFloat($(this).val()) || 0;
                    renderCart(); // Re-render the cart to update prices
                });

                $('.vat').on('input', function() {
                    const index = $(this).data('index');
                    cart[index].vat = parseFloat($(this).val()) || 0;
                    renderCart(); // Re-render the cart to update prices
                });

                $('.discount').on('input', function() {
                    const index = $(this).data('index');
                    cart[index].discount = parseFloat($(this).val()) || 0;
                    renderCart(); // Re-render the cart to update prices
                });

                // Handle remove item
                $('.remove-from-cart').on('click', function() {
                    const index = $(this).data('index');
                    cart.splice(index, 1);
                    renderCart();
                });
            }

            // Function to enable/disable checkout button
            function enableCheckoutButton() {
                if (cart.length > 0 && selectedCustomer) {
                    $('#checkout-btn').prop('disabled', false); // Enable button
                } else {
                    $('#checkout-btn').prop('disabled', true); // Disable button
                }
            }

            // Handle real-time search for products
            $('#search-product').on('input', function() {
                const query = $(this).val().toLowerCase();
                $('#product-list .product-card-container').each(function() {
                    const productName = $(this).find('.product-card').data('name').toLowerCase();
                    $(this).toggleClass('d-none', !productName.includes(query)); // Hide products that don't match the query
                });
            });

            // Handle checkout
            $('#checkout-btn').on('click', function() {
                if (!selectedCustomer) {
                    alert('Please select a customer before checking out.');
                    return;
                }

                // Send cart data and selected customer to the backend for processing
                $.ajax({
                    url: '{{ route("pos.checkout") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        cart: cart,
                        customer_id: selectedCustomer
                    },
                    success: function(response) {
                        // Redirect to the new invoice page after checkout is successful
                        window.location.href = '/invoices/' + response.invoice_id;
                    },
                    error: function() {
                        alert('Error processing checkout.');
                    }
                });
            });
        });
    </script>
@endpush
