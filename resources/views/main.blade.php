<!-- home.blade.php -->

<h1>Anasayfa</h1>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<button id="get-token-button">Token Al</button>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#get-token-button').click(function () {
            window.location.href = "{{ route('payment.get-token') }}";
        }).done(function () {
            window.location.href = "{{ route('payment.intermediate') }}";
        });
    });
</script>
