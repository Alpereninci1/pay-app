<div class="container mt-5">
    <form action="{{route('payment.payment-3d')}}" method="post">
        @csrf
        <div class="form-group">
            <label>Card Name</label>
            <input type="text" class="form-control" name="cc_holder_name">
        </div>
        <div class="form-group">
            <label>Card No</label>
            <input type="text" class="form-control" name="cc_no">
        </div>
        <div class="form-group">
            <label>Expiry Month</label>
            <input type="text" class="form-control" name="expiry_month">
        </div>
        <div class="form-group">
            <label>Expiry Year</label>
            <input type="text" class="form-control" name="expiry_year">
        </div>
        <div class="form-group">
            <label>Currency Code</label>
            <input type="text" class="form-control" name="currency_code">
        </div>
        <div class="form-group">
            <label>Installment Number</label>
            <input type="text" class="form-control" name="installments_number">
        </div>
        <div class="form-group">
            <label>Invoice Description</label>
            <input type="text" class="form-control" name="invoice_description">
        </div>
        <div class="form-group">
            <label>Total</label>
            <input type="text" class="form-control" name="total">
        </div>
        <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" name="name">
        </div>
        <div class="form-group">
            <label>Surname</label>
            <input type="text" class="form-control" name="surname">
        </div>
        <div class="form-group">
            <label>Return Url</label>
            <input type="text" class="form-control" name="return_url">
        </div>
        <div class="form-group">
            <label>Cancel Url</label>
            <input type="text" class="form-control" name="cancel_url">
        </div>
        <button type="submit" class="btn btn-primary">Gönder</button>
    </form>
</div>

