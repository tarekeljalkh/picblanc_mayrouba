<form action="{{ route('invoices.add-items', $invoice->id) }}" method="POST" id="addItemsForm">
    @csrf
    <table class="table" id="itemsTable">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                @if (session('category') === 'daily')
                    <th>Days</th>
                    <th>Rental Start Date</th>
                    <th>Rental End Date</th>
                @else
                    <th>Season</th>
                @endif
                <th>Total Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <select name="products[0][product_id]" class="form-select product-select" required>
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-type="{{ $product->type }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="products[0][quantity]" class="form-control quantity-input" value="1" min="1" required>
                </td>
                <td>
                    <input type="text" name="products[0][price]" class="form-control price-input" value="0.00" readonly>
                </td>
                @if (session('category') === 'daily')
                    <td>
                        <input type="number" name="products[0][days]" class="form-control days-input" value="1" readonly>
                    </td>
                    <td>
                        <input type="datetime-local" name="products[0][rental_start_date]" class="form-control rental-start-date" required>
                    </td>
                    <td>
                        <input type="datetime-local" name="products[0][rental_end_date]" class="form-control rental-end-date" required>
                    </td>
                @else
                    <td>
                        <input type="text" value="Seasonal Rental" class="form-control" readonly>
                    </td>
                @endif
                <td>
                    <input type="text" class="form-control total-price" value="0.00" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-row">Remove</button>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let rowIndex = 1;
        const isSeasonal = @json(session('category') === 'season');

        initializeFlatpickr();

        // Add a new row
        document.getElementById('addRow').addEventListener('click', function () {
            const tableBody = document.querySelector('#itemsTable tbody');
            let newRow = `
                <tr>
                    <td>
                        <select name="products[${rowIndex}][product_id]" class="form-select product-select" required>
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-type="{{ $product->type }}">
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="products[${rowIndex}][quantity]" class="form-control quantity-input" value="1" min="1" required>
                    </td>
                    <td>
                        <input type="text" name="products[${rowIndex}][price]" class="form-control price-input" value="0.00" readonly>
                    </td>`;

            if (isSeasonal) {
                newRow += `<td><input type="text" value="Seasonal Rental" class="form-control" readonly></td>`;
            } else {
                newRow += `
                    <td><input type="number" name="products[${rowIndex}][days]" class="form-control days-input" value="1" readonly></td>
                    <td><input type="datetime-local" name="products[${rowIndex}][rental_start_date]" class="form-control rental-start-date" required></td>
                    <td><input type="datetime-local" name="products[${rowIndex}][rental_end_date]" class="form-control rental-end-date" required></td>`;
            }

            newRow += `
                    <td><input type="text" class="form-control total-price" value="0.00" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>`;

            tableBody.insertAdjacentHTML('beforeend', newRow);
            initializeFlatpickr();
            rowIndex++;
        });

        // Update price and total dynamically
        document.getElementById('itemsTable').addEventListener('input', function (e) {
            const row = e.target.closest('tr');
            const productSelect = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');
            const priceInput = row.querySelector('.price-input');
            const daysInput = row.querySelector('.days-input');
            const totalPriceInput = row.querySelector('.total-price');

            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const price = parseFloat(selectedOption?.getAttribute('data-price')) || 0;
            const quantity = parseFloat(quantityInput.value) || 1;

            let days = 1;
            if (!isSeasonal) {
                const start = row.querySelector('.rental-start-date').value;
                const end = row.querySelector('.rental-end-date').value;
                days = calculateDays(new Date(start), new Date(end));
                daysInput.value = days;
            }

            const total = price * quantity * (isSeasonal ? 1 : days);
            priceInput.value = price.toFixed(2);
            totalPriceInput.value = total.toFixed(2);

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

        function initializeFlatpickr() {
            if (!isSeasonal) {
                flatpickr('.rental-start-date, .rental-end-date', {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                });
            }
        }

        function calculateDays(start, end) {
            if (isNaN(start) || isNaN(end) || start > end) return 1;
            const diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
            return Math.max(1, diff);
        }
    });
</script>
@endpush
