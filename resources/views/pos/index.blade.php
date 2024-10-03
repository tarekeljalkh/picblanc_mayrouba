<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ski Rental POS</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>Ski Rental POS</h1>

        <!-- Select Client -->
        <label for="customer">Select Client:</label>
        <select id="customer" name="customer">
            @foreach ($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
            @endforeach
        </select>

        <!-- Product Selection -->
        <div class="products">
            @foreach ($products as $product)
                <button class="product-button" data-id="{{ $product->id }}" data-price="{{ $product->price }}">
                    {{ $product->name }} ({{ $product->price }})
                </button>
            @endforeach
        </div>

        <!-- Invoice Summary -->
        <div id="invoice-summary">
            <h2>Invoice Summary</h2>
            <table id="invoice-items">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamic rows go here -->
                </tbody>
            </table>
            <h3>Total: <span id="total-amount">0.00</span></h3>
        </div>

        <!-- Submit Invoice -->
        <button id="submit-invoice">Submit Invoice</button>
    </div>

    <script>
        // JS for managing product selection and invoice summary
        let invoiceItems = [];
        const invoiceTableBody = document.querySelector('#invoice-items tbody');
        const totalAmountSpan = document.querySelector('#total-amount');

        document.querySelectorAll('.product-button').forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.getAttribute('data-id');
                const productName = button.textContent.trim();
                const productPrice = parseFloat(button.getAttribute('data-price'));

                let existingItem = invoiceItems.find(item => item.id == productId);
                if (existingItem) {
                    existingItem.quantity++;
                } else {
                    invoiceItems.push({ id: productId, name: productName, price: productPrice, quantity: 1 });
                }

                renderInvoiceItems();
            });
        });

        function renderInvoiceItems() {
            invoiceTableBody.innerHTML = '';
            let total = 0;

            invoiceItems.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>${(item.quantity * item.price).toFixed(2)}</td>
                    <td><button class="remove-item" data-id="${item.id}">Remove</button></td>
                `;

                total += item.quantity * item.price;
                invoiceTableBody.appendChild(row);
            });

            totalAmountSpan.textContent = total.toFixed(2);

            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', () => {
                    const itemId = button.getAttribute('data-id');
                    invoiceItems = invoiceItems.filter(item => item.id != itemId);
                    renderInvoiceItems();
                });
            });
        }

        document.querySelector('#submit-invoice').addEventListener('click', () => {
            // TODO: Implement AJAX to submit invoice data
        });
    </script>
</body>
</html>
