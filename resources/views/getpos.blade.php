
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


{{--<script type="text/javascript">--}}

{{--    function getPosInstallments() {--}}

{{--        var data = {};--}}
{{--        data['credit_card'] = $("#CardNumber").val().substr(0, 6);--}}
{{--        data['amount'] = $("#amount").val();--}}
{{--        data['is_2d'] = $("#is_2d").val();--}}
{{--        data['currency_code'] = "TRY";--}}
{{--        data['merchant_key'] = "";--}}

{{--        $.ajax({--}}
{{--            type: 'POST',--}}
{{--            dataType: 'json',--}}
{{--            data: JSON.stringify(data),--}}
{{--            url: {{route('payment.get-pos')}},--}}
{{--            contentType: 'application/json',--}}
{{--            processData : false,--}}
{{--            success: function (result) {--}}
{{--                if (result.success) {--}}

{{--                    var table = '<table class="table table-sm table-hover table-bordered" style="width: 40%;"><thead><tr><th scope="col">#</th><th scope="col">Taksit</th><th scope="col">Taksitli Tutarı</th></tr></thead><tbody>';--}}
{{--                    $.each(result.data.data, function (index, value) {--}}

{{--                        table += '<tr><th scope="row"><input onclick="getTotal();" class="form-check-input" type="radio" id="installment_'+ index +'" name="installment" value="' + value.installmentsNumber + '"' + (value.installmentsNumber == 1 ? 'checked' : '') + ' data-total="' + value.amountToBePaid.toString().replace('.', ',') + '"></th><td>' + (value.installmentsNumber == 1 ? 'Tek Çekim' : + value.installmentsNumber + ' Taksit') + '</td><td>' + value.amountToBePaid + ' ₺</td > </tr><tr>';--}}
{{--                    });--}}

{{--                    table += '</tbody></table>';--}}

{{--                    $("#installment_table").html(table);--}}
{{--                    var total = $("input[name='installment']:checked").data("total");--}}
{{--                    $('#total').val(total);--}}
{{--                }--}}
{{--                else {--}}
{{--                    $("#formInfo").html('<div class="alert ' + (result.result ? 'alert-success' : 'alert-danger') + '" role="alert">' + result.message + '<button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button></div>');--}}
{{--                }--}}
{{--            },--}}
{{--            error: function (xhr) {--}}
{{--                $("#formInfo").html('<div class="alert alert-danger" role="alert">UNKOWN ERROR!<button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button></div>');--}}
{{--            }--}}
{{--        });--}}

{{--    }--}}

{{--</script>--}}
