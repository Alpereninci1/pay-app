<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<form id="myForm">
    <div class="form-group">
        <label>Card Name</label>
        <input type="text" name="cc_holder_name" id="cc_holder_name">
    </div>
    <div class="form-group">
        <label>Card Number</label>
        <input type="text" name="cc_no" id="cc_no">
    </div>
    <div class="form-group">
        <label>Expiry Month</label>
        <input type="text" name="expiry_month" id="expiry_month">
    </div>
    <div class="form-group">
        <label>Expiry Year</label>
        <input type="text" name="expiry_year" id="expiry_year">
    </div>
    <div class="form-group">
        <label>CVV</label>
        <input type="text" name="cvv" id="cvv">
    </div>
    <div class="form-group">
        <label>Currency Code</label>
        <input type="text" name="currency_code" id="currency_code">
    </div>
    <div class="form-group">
        <label>Installment Number</label>
        <input type="number" name="installments_number" id="installments_number">
    </div>
    <div class="form-group">
        <label>Invoice Description</label>
        <input type="text" name="invoice_description" id="invoice_description">
    </div>
    <div class="form-group">
        <label>Total</label>
        <input type="text" name="total" id="total">
    </div>
    <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" id="name">
    </div>
    <div class="form-group">
        <label>Surname</label>
        <input type="text" name="surname" id="surname">
    </div>
    <button type="button" id="submitBtn">Submit</button>
</form>

<div id="result">

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Button click event handler
        $('#submitBtn').click(function() {
            // Capture form data as JSON object
            var formData = {
                cc_holder_name: $('#cc_holder_name').val(),
                cc_no: $('#cc_no').val(),
                expiry_month: $('#expiry_month').val(),
                expiry_year: $('#expiry_year').val(),
                cvv: $('#cvv').val(),
                currency_code :$('#currency_code').val(),
                installments_number :$('#installments_number').val(),
                invoice_description: $('#invoice_description').val(),
                total: $('#total').val(),
                name: $('#name').val(),
                surname: $('#surname').val(),
            };

            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Add the CSRF token to the form data
            formData._token = csrfToken;


            // Send AJAX request to the controller
            $.ajax({
                type: 'POST',
                url: '{{ route('payment.payment-2d') }}',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include CSRF token in the request header
                },
                success: function(response) {
                    // Handle the response from the controller
                    var resultHtml = '<p>Status Code: ' + response.status_code + '</p>' +
                        '<p>Status Description: ' + response.status_description + '</p>' +
                        '<p>Invoice ID: ' + response.data.invoice_id + '</p>' +
                        '<p>Order No: ' + response.data.order_no + '</p>';
                    $('#result').html(resultHtml);
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.log(error);
                }
            });
        });
    });
</script>
