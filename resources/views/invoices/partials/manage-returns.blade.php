<form action="{{ route('invoices.process-returns', $invoice->id) }}" method="POST">
    @csrf
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Select</th>
                <th>Product</th>
                <th>Total Quantity</th>
                <th>Remaining Quantity</th>
                <th>Returned Quantity</th>
                <th>Return Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr class="{{ $errors->has("returns.{$item->id}") ? 'table-danger' : '' }}">
                    <td>
                        <input type="checkbox"
                               class="form-check-input return-checkbox"
                               name="returns[{{ $item->id }}][selected]"
                               value="1"
                               {{ old("returns.{$item->id}.selected") ? 'checked' : '' }}>
                    </td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->quantity - $item->returned_quantity }}</td>
                    <td>
                        <input
                            type="number"
                            class="form-control return-quantity {{ $errors->has("returns.{$item->id}.quantity") ? 'is-invalid' : '' }}"
                            name="returns[{{ $item->id }}][quantity]"
                            max="{{ $item->quantity - $item->returned_quantity }}"
                            placeholder="Enter returned quantity"
                            value="{{ old("returns.{$item->id}.quantity") }}"
                            disabled
                        >
                        @error("returns.{$item->id}.quantity")
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </td>
                    <td>
                        <input
                            type="datetime-local"
                            class="form-control return-date {{ $errors->has("returns.{$item->id}.return_date") ? 'is-invalid' : '' }}"
                            name="returns[{{ $item->id }}][return_date]"
                            min="{{ $invoice->rental_start_date }}"
                            max="{{ $invoice->rental_end_date }}"
                            value="{{ old("returns.{$item->id}.return_date") }}"
                            disabled
                        >
                        @error("returns.{$item->id}.return_date")
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-end mt-3">
        <button type="submit" class="btn btn-warning">Process Returns</button>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.return-checkbox');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const row = this.closest('tr');
                const quantityInput = row.querySelector('.return-quantity');
                const dateInput = row.querySelector('.return-date');

                if (this.checked) {
                    quantityInput.disabled = false;
                    dateInput.disabled = false;
                } else {
                    quantityInput.disabled = true;
                    dateInput.disabled = true;
                    quantityInput.value = ''; // Clear the input
                    dateInput.value = ''; // Clear the input
                }
            });
        });

        // Validate return quantity
        const quantityInputs = document.querySelectorAll('.return-quantity');
        quantityInputs.forEach(input => {
            input.addEventListener('input', function () {
                const maxQuantity = parseInt(this.getAttribute('max'), 10);
                if (this.value > maxQuantity) {
                    alert(`Returned quantity cannot exceed the remaining quantity (${maxQuantity}).`);
                    this.value = maxQuantity; // Reset to maximum allowed
                }
            });
        });

        // Validate return date
        const dateInputs = document.querySelectorAll('.return-date');
        dateInputs.forEach(input => {
            input.addEventListener('change', function () {
                const minDate = new Date(this.min);
                const maxDate = new Date(this.max);
                const selectedDate = new Date(this.value);

                if (selectedDate < minDate || selectedDate > maxDate) {
                    alert(`Return date must be between ${minDate.toLocaleDateString()} and ${maxDate.toLocaleDateString()}.`);
                    this.value = ''; // Clear invalid date
                }
            });
        });
    });
</script>
@endpush
