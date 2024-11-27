<form action="{{ route('invoices.update', $invoice->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    {{-- Customer Inputs --}}
    <div class="mb-4 row">
        <label for="customer_name" class="col-md-2 col-form-label">Customer Name</label>
        <div class="col-md-10">
            <input class="form-control" type="text" id="customer_name" name="customer_name"
                value="{{ old('customer_name', $invoice->customer->name) }}" placeholder="Enter Customer Name" />
        </div>
    </div>

    {{-- Other Inputs from your existing form --}}
    {{-- ... --}}
</form>
