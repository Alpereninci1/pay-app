<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<form id="myForm">
    <div class="form-group">
        <label>Card Token</label>
        <input type="text" name="card_token" id="card_token">
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
    <div class="form-group">
        <label>Customer Number</label>
        <input type="text" name="customer_number" id="customer_number">
    </div>
    <div class="form-group">
        <label>Customer Email</label>
        <input type="text" name="customer_email" id="customer_email">
    </div>
    <div class="form-group">
        <label>Customer Name</label>
        <input type="text" name="customer_name" id="customer_name">
    </div>
    <div class="form-group">
        <label>Customer Phone</label>
        <input type="text" name="customer_phone" id="customer_phone">
    </div>
    <div class="form-group">
        <label>Is Remote Card Token</label>
        <input type="checkbox" name="is_remote_card_token" id="is_remote_card_token">
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
                card_token: $('#card_token').val(),
                currency_code :$('#currency_code').val(),
                installments_number :$('#installments_number').val(),
                invoice_description: $('#invoice_description').val(),
                total: $('#total').val(),
                name: $('#name').val(),
                surname: $('#surname').val(),
                customer_number: $('#customer_number').val(),
                customer_email: $('#customer_email').val(),
                customer_name: $('#customer_name').val(),
                customer_phone: $('#customer_phone').val(),
                is_remote_card_token: $('#is_remote_card_token').val()
            };

            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Add the CSRF token to the form data
            formData._token = csrfToken;


            // Send AJAX request to the controller
            $.ajax({
                type: 'POST',
                url: '{{ route('payment.pay-by-card-token') }}',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include CSRF token in the request header
                },
                success: function(response) {
                    // Handle the response from the controller
                    var resultHtml = '<p>Status Code: ' + response.status_code + '</p>' +
                        '<p>Status Description: ' + response.status_description + '</p>';
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

