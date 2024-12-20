<form action="{{ route('invoices.addPayment', $invoice->id) }}" method="POST">
    @csrf

    <div class="mb-3">
        <!-- Total Amount -->
        <label for="totalAmount" class="form-label">Total Amount ($)</label>
        <input type="text" id="totalAmount" class="form-control"
               value="{{ number_format($totals['finalTotal'], 2) }}" readonly>
    </div>

    <div class="mb-3">
        <!-- Paid Amount -->
        <label for="paidAmount" class="form-label">Amount Already Paid ($)</label>
        <input type="text" id="paidAmount" class="form-control"
               value="{{ number_format($invoice->paid_amount + $invoice->deposit, 2) }}" readonly>
    </div>

    <div class="mb-3">
        <!-- Remaining Amount -->
        <label for="remainingAmount" class="form-label">Remaining Amount ($)</label>
        <input type="text" id="remainingAmount" class="form-control"
               value="{{ number_format($totals['balanceDue'], 2) }}" readonly>
    </div>

    @if ($totals['balanceDue'] <= 0)
        <!-- Fully Paid Message -->
        <div class="alert alert-success">
            This invoice is fully paid. No further payments are required.
        </div>
        <div class="mb-3">
            <label for="newPayment" class="form-label">New Payment Amount ($)</label>
            <input type="number" id="newPayment" name="new_payment" class="form-control" disabled>
        </div>
        <button type="submit" class="btn btn-success" disabled>Add Payment</button>
    @else
        <div class="mb-3">
            <label for="newPayment" class="form-label">New Payment Amount ($)</label>
            <input type="number" id="newPayment" name="new_payment" class="form-control"
                   min="0"
                   max="{{ $totals['balanceDue'] }}" required>
        </div>
        <button type="submit" class="btn btn-success">Add Payment</button>
    @endif
</form>
