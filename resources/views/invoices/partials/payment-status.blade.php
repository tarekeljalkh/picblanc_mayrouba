<form action="{{ route('invoices.updatePaymentStatus', $invoice->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="mb-3">
        <label for="paymentStatus" class="form-label">Payment Status</label>
        <select id="paymentStatus" name="paid" class="form-select">
            <option value="1" {{ $invoice->paid ? 'selected' : '' }}>Paid</option>
            <option value="0" {{ !$invoice->paid ? 'selected' : '' }}>Not Paid</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Update Payment Status</button>
</form>
