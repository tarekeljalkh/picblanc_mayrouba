<form action="{{ route('invoices.addPayment', $invoice->id) }}" method="POST">
    @csrf

    @php
        // Get calculated totals from the model
        $totals = $invoice->calculateTotals();

        // Extract individual totals for clarity
        $finalTotal = $totals['finalTotal'];
        $discountAmount = $totals['discountAmount'];
        $finalTotalCustom = $totals['finalTotalCustom'];
        $returnedItemsCost = $totals['returnedItemsCost'];
        $remainingBalance = $totals['balanceDue'];

        // Include previous payments in paid amount calculation
        $previousPayments = $invoice->payments->sum('amount'); // Sum of all previous payments
        $paidAmount = $previousPayments + $invoice->deposit + $invoice->paid_amount + $discountAmount;
    @endphp

    <!-- Total Amount -->
    <div class="mb-3">
        <label for="totalAmount" class="form-label">Total Amount ($)</label>
        <input type="text" id="totalAmount" class="form-control" value="{{ number_format($finalTotal, 2) }}" readonly>
    </div>

    <!-- Paid Amount -->
    <div class="mb-3">
        <label for="paidAmount" class="form-label">Amount Already Paid ($)</label>
        <input type="text" id="paidAmount" class="form-control" value="{{ number_format($paidAmount, 2) }}" readonly>
    </div>

    <!-- Remaining Amount -->
    <div class="mb-3">
        <label for="remainingAmount" class="form-label">Remaining Amount ($)</label>
        <input type="text" id="remainingAmount" class="form-control"
            value="{{ number_format($remainingBalance, 2) }}" readonly>
    </div>

    <!-- Show Previous Payments -->
    <div class="mb-3">
        <label for="previousPayments" class="form-label">Previous Payments</label>
        <ul class="list-group">
            @foreach ($invoice->payments as $payment)
                <li class="list-group-item">
                    <strong>{{ $payment->payment_method }}</strong>: ${{ number_format($payment->amount, 2) }}
                    ({{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y h:i A') }})
                </li>
            @endforeach
        </ul>
    </div>

    @if ($remainingBalance <= 0)
        <!-- Fully Paid Message -->
        <div class="alert alert-success">
            This invoice is fully paid. No further payments are required.
        </div>

        <!-- Payment Method Selection (Cash or Credit Card) -->
        <div class="mb-3">
            <label for="paymentMethod" class="form-label">Payment Method</label>
            <select name="payment_method" id="paymentMethod" class="form-control" disabled>
                <option value="cash" @if (old('payment_method', 'cash') == 'cash') selected @endif>Cash</option>
                <option value="credit_card" @if (old('payment_method', 'credit_card') == 'credit_card') selected @endif>Credit Card</option>
            </select>
        </div>

        <!-- Payment Input -->
        <div class="mb-3">
            <label for="newPayment" class="form-label">New Payment Amount ($)</label>
            <input type="number" id="newPayment" name="new_payment" class="form-control" disabled>
        </div>
        <button type="submit" class="btn btn-success" disabled>Add Payment</button>
    @else

            <!-- Payment Method Selection (Cash or Credit Card) -->
            <div class="mb-3">
                <label for="paymentMethod" class="form-label">Payment Method</label>
                <select name="payment_method" id="paymentMethod" class="form-control" required>
                    <option value="cash" @if (old('payment_method', 'cash') == 'cash') selected @endif>Cash</option>
                    <option value="credit_card" @if (old('payment_method', 'credit_card') == 'credit_card') selected @endif>Credit Card</option>
                </select>
            </div>


        <div class="mb-3">
            <label for="newPayment" class="form-label">New Payment Amount ($)</label>
            <input type="number" id="newPayment" name="new_payment" class="form-control" min="0"
                max="{{ $remainingBalance }}" required>
        </div>
        <button type="submit" class="btn btn-success">Add Payment</button>
    @endif
</form>
