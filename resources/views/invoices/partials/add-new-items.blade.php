<form action="{{ route('invoices.add-items', $invoice->id) }}" method="POST" id="addItemsForm">
    @csrf
    <table class="table" id="itemsTable">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
                <th>Rental Start Date</th>
                <th>Rental End Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <select name="products[0][product_id]" class="form-select product-select" required>
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="products[0][quantity]" class="form-control quantity-input" value="1" min="1" required>
                </td>
                <td>
                    <input type="text" name="products[0][price]" class="form-control price-input" value="0.00" readonly>
                </td>
                <td>
                    <input type="text" class="form-control total-price" value="0.00" readonly>
                </td>
                <td>
                    <input type="date" name="products[0][rental_start_date]" class="form-control" value="{{ now()->toDateString() }}" required>
                </td>
                <td>
                    <input type="date" name="products[0][rental_end_date]" class="form-control" value="{{ now()->addDays(1)->toDateString() }}" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-row">Remove</button>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                <td>
                    <input type="text" id="grandTotal" class="form-control" value="0.00" readonly>
                </td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>
    <div class="d-flex justify-content-between mt-3">
        <button type="button" class="btn btn-success" id="addRow">Add Row</button>
        <button type="submit" class="btn btn-primary">Add Items</button>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let rowIndex = 1;

        // Add a new row
        document.getElementById('addRow').addEventListener('click', function () {
            const tableBody = document.querySelector('#itemsTable tbody');
            const newRow = `
                <tr>
                    <td>
                        <select name="products[${rowIndex}][product_id]" class="form-select product-select" required>
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="products[${rowIndex}][quantity]" class="form-control quantity-input" value="1" min="1" required>
                    </td>
                    <td>
                        <input type="text" name="products[${rowIndex}][price]" class="form-control price-input" value="0.00" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control total-price" value="0.00" readonly>
                    </td>
                    <td>
                        <input type="date" name="products[${rowIndex}][rental_start_date]" class="form-control" value="{{ now()->toDateString() }}" required>
                    </td>
                    <td>
                        <input type="date" name="products[${rowIndex}][rental_end_date]" class="form-control" value="{{ now()->addDays(1)->toDateString() }}" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row">Remove</button>
                    </td>
                </tr>`;
            tableBody.insertAdjacentHTML('beforeend', newRow);
            rowIndex++;
        });

        // Remove a row
        document.getElementById('itemsTable').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
                calculateGrandTotal();
            }
        });

        // Update price and total dynamically
        document.getElementById('itemsTable').addEventListener('input', function (e) {
            const row = e.target.closest('tr');
            if (!row) return;

            const productSelect = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');
            const priceInput = row.querySelector('.price-input');
            const totalPriceInput = row.querySelector('.total-price');

            // Update price based on selected product
            if (productSelect) {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                priceInput.value = price.toFixed(2);
            }

            // Calculate total price
            const quantity = parseFloat(quantityInput.value) || 1;
            const price = parseFloat(priceInput.value) || 0;
            totalPriceInput.value = (quantity * price).toFixed(2);

            calculateGrandTotal();
        });

        // Calculate grand total
        function calculateGrandTotal() {
            let grandTotal = 0;
            document.querySelectorAll('.total-price').forEach(input => {
                grandTotal += parseFloat(input.value) || 0;
            });
            document.getElementById('grandTotal').value = grandTotal.toFixed(2);
        }
    });
</script>
@endpush
