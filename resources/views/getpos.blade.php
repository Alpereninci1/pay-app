
<div class="container mt-5">
    <form action="{{route('payment.get-pos')}}" method="post">
        @csrf
        <div class="form-group">
            <label>Credit Card</label>
            <input type="text" class="form-control" name="credit_card">
        </div>
        <div class="form-group">
            <label>Amount</label>
            <input type="text" class="form-control" name="amount">
        </div>
        <div class="form-group">
            <label>Is_2d</label>
            <input type="checkbox" class="form-control" name="is_2d">
        </div>
        <button type="submit" class="btn btn-primary">Gönder</button>
    </form>
</div>

