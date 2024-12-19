<form action="{{ route('invoices.addPayment', $invoice->id) }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="totalAmount" class="form-label">Total Amount ($)</label>
        <input type="text" id="totalAmount" class="form-control" value="{{ number_format($invoice->total_amount, 2) }}" readonly>
    </div>

    <div class="mb-3">
        <label for="paidAmount" class="form-label">Amount Already Paid ($)</label>
        <input type="text" id="paidAmount" class="form-control" value="{{ number_format($invoice->paid_amount, 2) }}" readonly>
    </div>

    <div class="mb-3">
        <label for="remainingAmount" class="form-label">Remaining Amount ($)</label>
        <input type="text" id="remainingAmount" class="form-control"
               value="{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}" readonly>
    </div>

    @if ($invoice->paid_amount >= $invoice->total_amount)
        <!-- Fully Paid Message -->
        <div class="alert alert-success">
            This invoice is fully paid. No further payments are required.
        </div>
        <!-- Disable Payment Input and Button -->
        <div class="mb-3">
            <label for="newPayment" class="form-label">New Payment Amount ($)</label>
            <input type="number" id="newPayment" name="new_payment" class="form-control" disabled>
        </div>
        <button type="submit" class="btn btn-success" disabled>Add Payment</button>
    @else
        <!-- Show Payment Input and Button if Not Fully Paid -->
        <div class="mb-3">
            <label for="newPayment" class="form-label">New Payment Amount ($)</label>
            <input type="number" id="newPayment" name="new_payment" class="form-control"
                   min="0" max="{{ $invoice->total_amount - $invoice->paid_amount }}" required>
        </div>
        <button type="submit" class="btn btn-success">Add Payment</button>
    @endif
</form>
