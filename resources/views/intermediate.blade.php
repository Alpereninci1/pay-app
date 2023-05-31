
<h1>Anasayfa</h1>

@if(session('token'))
    <p>Token alındı: {{ session('token') }}</p>
@endif

@if(session('error'))
    <p>Hata: {{ session('error') }}</p>
@endif

<p>Lütfen yapılacak işlemi seçin:</p>
<a href="{{ route('payment.payment-3d-view') }}">3D Ödeme</a>
<a href="{{ route('payment.payment-2d-view') }}">2D Ödeme</a>
<a href="{{ route('payment.get-installment') }}">Get Installment</a>
<a href="{{ route('payment.get-pos-view') }}">Get Pos</a>
<a href="{{ route('payment.pay-by-card-token-view') }}">Pay By Card Token</a>


