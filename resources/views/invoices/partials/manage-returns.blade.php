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
                @if ($item->quantity - $item->returned_quantity > 0)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input return-checkbox"
                                name="returns[original][{{ $item->id }}][selected]" value="1"
                                {{ old("returns.original.{$item->id}.selected") ? 'checked' : '' }}>
                        </td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->quantity - $item->returned_quantity }}</td>
                        <td>
                            <input type="number"
                                class="form-control return-quantity {{ $errors->has("returns.original.{$item->id}.quantity") ? 'is-invalid' : '' }}"
                                name="returns[original][{{ $item->id }}][quantity]"
                                max="{{ $item->quantity - $item->returned_quantity }}"
                                value="{{ old("returns.original.{$item->id}.quantity") }}"
                                {{ old("returns.original.{$item->id}.selected") ? '' : 'disabled' }}>
                            @error("returns.original.{$item->id}.quantity")
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="datetime-local"
                                class="form-control return-date {{ $errors->has("returns.original.{$item->id}.return_date") ? 'is-invalid' : '' }}"
                                name="returns[original][{{ $item->id }}][return_date]"
                                data-start-date="{{ $item->rental_start_date }}"
                                data-end-date="{{ $item->rental_end_date }}"
                                value="{{ old("returns.original.{$item->id}.return_date") }}"
                                {{ old("returns.original.{$item->id}.selected") ? '' : 'disabled' }}>
                            @error("returns.original.{$item->id}.return_date")
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="text" class="form-control days-of-use"
                                name="returns[original][{{ $item->id }}][days_of_use]" value="" readonly>
                        </td>
                    </tr>
                @endif
            @endforeach

            <!-- Additional Items -->
            @foreach ($invoice->additionalItems as $addedItem)
                @if ($addedItem->quantity - $addedItem->returned_quantity > 0)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input return-checkbox"
                                name="returns[additional][{{ $addedItem->id }}][selected]" value="1"
                                {{ old("returns.additional.{$addedItem->id}.selected") ? 'checked' : '' }}>
                        </td>
                        <td>{{ $addedItem->product->name }}</td>
                        <td>{{ $addedItem->quantity }}</td>
                        <td>{{ $addedItem->quantity - $addedItem->returned_quantity }}</td>
                        <td>
                            <input type="number"
                                class="form-control return-quantity {{ $errors->has("returns.additional.{$addedItem->id}.quantity") ? 'is-invalid' : '' }}"
                                name="returns[additional][{{ $addedItem->id }}][quantity]"
                                max="{{ $addedItem->quantity - $addedItem->returned_quantity }}"
                                value="{{ old("returns.additional.{$addedItem->id}.quantity") }}"
                                {{ old("returns.additional.{$addedItem->id}.selected") ? '' : 'disabled' }}>
                            @error("returns.additional.{$addedItem->id}.quantity")
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="datetime-local"
                                class="form-control return-date {{ $errors->has("returns.additional.{$addedItem->id}.return_date") ? 'is-invalid' : '' }}"
                                name="returns[additional][{{ $addedItem->id }}][return_date]"
                                data-start-date="{{ $addedItem->rental_start_date }}"
                                data-end-date="{{ $addedItem->rental_end_date }}"
                                value="{{ old("returns.additional.{$addedItem->id}.return_date") }}"
                                {{ old("returns.additional.{$addedItem->id}.selected") ? '' : 'disabled' }}>
                            @error("returns.additional.{$addedItem->id}.return_date")
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="text" class="form-control days-of-use"
                                name="returns[additional][{{ $addedItem->id }}][days_of_use]" value="" readonly>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-end mt-3">
        <button type="submit" class="btn btn-warning">Process Returns</button>
    </div>
</form>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function initializeFlatpickr() {
                document.querySelectorAll('.return-date').forEach(input => {
                    const startDate = input.getAttribute('data-start-date');
                    const endDate = input.getAttribute('data-end-date');

                    flatpickr(input, {
                        enableTime: true,
                        dateFormat: 'Y-m-d H:i',
                        minDate: startDate,
                        maxDate: endDate,
                        onChange: function() {
                            calculateDaysOfUse(input);
                        },
                    });
                });
            }

            initializeFlatpickr();

            document.querySelectorAll('.return-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
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


            function calculateDaysOfUse(input) {
                const row = input.closest('tr');
                const rentalStartDate = new Date(input.getAttribute('data-start-date'));
                const returnDate = new Date(input.value);
                const daysOfUseInput = row.querySelector('.days-of-use');

                if (!isNaN(rentalStartDate) && !isNaN(returnDate) && returnDate >= rentalStartDate) {
                    const startMidnight = new Date(rentalStartDate);
                    const endMidnight = new Date(returnDate);

                    startMidnight.setHours(0, 0, 0, 0); // Midnight of start day
                    endMidnight.setHours(0, 0, 0, 0); // Midnight of end day

                    const fullDays = (endMidnight - startMidnight) / (1000 * 60 * 60 * 24); // Full days in between
                    let daysUsed = fullDays; // Total full days

                    if (rentalStartDate.getHours() >= 12) {
                        daysUsed++; // Include start day if it begins after noon
                    }

                    if (returnDate.getHours() >= 12) {
                        daysUsed++; // Include end day if it ends after noon
                    }

                    daysOfUseInput.value = Math.max(1, daysUsed); // Ensure at least 1 day
                } else {
                    daysOfUseInput.value = '';
                }
            }

            // function calculateDaysOfUse(input) {
            //     const row = input.closest('tr');
            //     const rentalStartDate = new Date(input.getAttribute('data-start-date'));
            //     const returnDate = new Date(input.value);
            //     const daysOfUseInput = row.querySelector('.days-of-use');

            //     if (returnDate >= rentalStartDate) {
            //         const hoursUsed = (returnDate - rentalStartDate) / (1000 * 60 * 60);
            //         const daysUsed = Math.floor(hoursUsed / 24) + 1;
            //         const remainingHours = hoursUsed % 24;

            //         daysOfUseInput.value = remainingHours > 12 ? daysUsed + 1 : daysUsed;
            //     } else {
            //         daysOfUseInput.value = '';
            //     }
            // }
        });
    </script>
@endpush
