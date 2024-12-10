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
                <th>Days of Use</th>
            </tr>
        </thead>
        <tbody>
            <!-- Original Invoice Items -->
            @foreach ($invoice->items as $item)
                <tr>
                    <td>
                        <input type="checkbox"
                               class="form-check-input return-checkbox"
                               name="returns[original][{{ $item->id }}][selected]"
                               value="1"
                               {{ old("returns.original.{$item->id}.selected") ? 'checked' : '' }}>
                    </td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->quantity - $item->returned_quantity }}</td>
                    <td>
                        <input type="number"
                               class="form-control return-quantity"
                               name="returns[original][{{ $item->id }}][quantity]"
                               max="{{ $item->quantity - $item->returned_quantity }}"
                               value="{{ old("returns.original.{$item->id}.quantity") }}"
                               {{ old("returns.original.{$item->id}.selected") ? '' : 'disabled' }}>
                    </td>
                    <td>
                        <input type="datetime-local"
                               class="form-control return-date"
                               name="returns[original][{{ $item->id }}][return_date]"
                               min="{{ $invoice->rental_start_date }}"
                               max="{{ $invoice->rental_end_date }}"
                               value="{{ old("returns.original.{$item->id}.return_date") }}"
                               {{ old("returns.original.{$item->id}.selected") ? '' : 'disabled' }}>
                    </td>
                    <td>
                        <input type="text"
                               class="form-control days-of-use"
                               name="returns[original][{{ $item->id }}][days_of_use]"
                               value=""
                               readonly>
                    </td>
                </tr>
            @endforeach

            <!-- Additional Items -->
            @foreach ($invoice->additionalItems as $addedItem)
                <tr>
                    <td>
                        <input type="checkbox"
                               class="form-check-input return-checkbox"
                               name="returns[additional][{{ $addedItem->id }}][selected]"
                               value="1"
                               {{ old("returns.additional.{$addedItem->id}.selected") ? 'checked' : '' }}>
                    </td>
                    <td>{{ $addedItem->product->name }}</td>
                    <td>{{ $addedItem->quantity }}</td>
                    <td>{{ $addedItem->quantity - $addedItem->returned_quantity }}</td>
                    <td>
                        <input type="number"
                               class="form-control return-quantity"
                               name="returns[additional][{{ $addedItem->id }}][quantity]"
                               max="{{ $addedItem->quantity - $addedItem->returned_quantity }}"
                               value="{{ old("returns.additional.{$addedItem->id}.quantity") }}"
                               {{ old("returns.additional.{$addedItem->id}.selected") ? '' : 'disabled' }}>
                    </td>
                    <td>
                        <input type="datetime-local"
                               class="form-control return-date"
                               name="returns[additional][{{ $addedItem->id }}][return_date]"
                               min="{{ $invoice->rental_start_date }}"
                               max="{{ $invoice->rental_end_date }}"
                               value="{{ old("returns.additional.{$addedItem->id}.return_date") }}"
                               {{ old("returns.additional.{$addedItem->id}.selected") ? '' : 'disabled' }}>
                    </td>
                    <td>
                        <input type="text"
                               class="form-control days-of-use"
                               name="returns[additional][{{ $addedItem->id }}][days_of_use]"
                               value=""
                               readonly>
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
                const daysOfUseInput = row.querySelector('.days-of-use');

                if (this.checked) {
                    quantityInput.disabled = false;
                    dateInput.disabled = false;
                } else {
                    quantityInput.disabled = true;
                    dateInput.disabled = true;
                    daysOfUseInput.value = '';
                    quantityInput.value = '';
                    dateInput.value = '';
                }
            });
        });

        const dateInputs = document.querySelectorAll('.return-date');
        dateInputs.forEach(input => {
            input.addEventListener('change', function () {
                const row = this.closest('tr');
                const rentalStartDate = new Date(this.getAttribute('min'));
                const returnDate = new Date(this.value);
                const daysOfUseInput = row.querySelector('.days-of-use');

                if (returnDate >= rentalStartDate) {
                    const daysUsed = Math.max(1, Math.floor((returnDate - rentalStartDate) / (1000 * 60 * 60 * 24)) + 1);
                    daysOfUseInput.value = daysUsed;
                } else {
                    daysOfUseInput.value = '';
                }
            });
        });
    });
</script>
@endpush
