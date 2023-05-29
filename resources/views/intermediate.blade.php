
<h1>Anasayfa</h1>

@if(session('token'))
    <p>Token alındı: {{ session('token') }}</p>
@endif

@if(session('error'))
    <p>Hata: {{ session('error') }}</p>
@endif

<p>Lütfen ödeme yöntemini seçin:</p>
<a href="{{ route('payment.payment-3d') }}">3D Ödeme</a>
<a href="{{ route('payment.payment-2d') }}">2D Ödeme</a>
