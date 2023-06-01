@extends('layouts.app')
@section('Styles')
    <style>
        .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
            background-color: #6bba9b;
        }

        #progress {
            width: 21%;
            background-color: #6bba9b;
        }

        .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
            border: 1px solid #6bba9b;
        }

        tbody, td, tfoot, th, thead, tr {
            border-color: #6bba9b;
            border-style: inherit;
            border-width: 0;
        }

        .table>thead>tr>th {
            vertical-align: bottom;
            border-bottom: 2px solid #6bba9b;
        }

        .table>:not(:last-child)>:last-child>* {
            border-bottom-color: #6bba9b;
        }
    </style>
@endsection

<div class="image-container set-full-height">

    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">

                <div class="wizard-container">

                    <div class="card wizard-card" data-color="orange" id="wizardProfile">

                        <form action="/payment" method="post"  id="submitForm" name="submitForm">

                            <div class="wizard-header text-center">
                                <h3 class="wizard-title">Vepara Tahsilat Sistemi</h3>
                            </div>

                            <div class="wizard-navigation">
                                <div class="progress-with-circle">
                                    <div class="progress-bar" id="progress" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="3"></div>
                                </div>
                                <ul>
                                    <li>
                                        <a href="#about" data-toggle="tab" style="max-width:initial">
                                            <div class="icon-circle">
                                                <i class="ti-user"></i>
                                            </div>
                                            Ödeme Formu
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#account" data-toggle="tab">
                                            <div class="icon-circle">
                                                <i class="ti-credit-card"></i>
                                            </div>
                                            Kredi Kartı
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <div class="row justify-content-md-center">
                                    <div class="col col-sm-12">
                                        <div id="formInfo"></div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="about">
                                    <div class="row">
                                        <h5 class="info-text"> Ödeme Formu Bilgileri</h5>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Tutar <small>*</small></label>
                                                <input name="amount" id="amount" type="number" min="1" class="form-control" placeholder="Tutar...">
                                            </div>
                                            <div class="form-group">
                                                <label>Cep Telefonu <small>*</small></label>
                                                <input name="phone" id="phone" type="text" class="form-control" placeholder="Cep Telefonu...">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Ad Soyad <small>*</small></label>
                                                <input name="name" id="name" type="text" class="form-control" placeholder="Ad Soyad...">
                                            </div>
                                            <div class="form-group">
                                                <label>TC Kimlik No <small>*</small></label>
                                                <input name="tckn" id="tckn" type="text" class="form-control" placeholder="TC Kimlik No...">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Açıklama <small>*</small></label>
                                                <textarea name="description" id="description" class="form-control" placeholder="Açıklama..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="account" name="account">
                                    <h5 class="info-text"> Kart Bilgileri </h5>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Kart Sahibi <small>*</small></label>
                                                <input name="CardHolderName" id="CardHolderName" type="text" class="form-control" placeholder="Kart Sahibi...">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Kart Numarası <small>*</small></label>
                                                <input name="CardNumber" id="CardNumber" type="text" class="form-control" placeholder="Kart Numarası...">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group col-sm-4">
                                                <label>Son Kullanma Ayı <small>*</small></label>
                                                <input name="ExpiryDateMonth" id="ExpiryDateMonth" type="number" class="form-control" placeholder="Son Kullanma Ayı...">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label>Son Kullanma Yılı <small>*</small></label>
                                                <input name="ExpiryDateYear" id="ExpiryDateYear" type="number" class="form-control" placeholder="Son Kullanma Yılı...">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label>Güvenlik Numarası <small>*</small></label>
                                                <input name="cvv" id="cvv" type="number" class="form-control" placeholder="Güvenlik Numarası...">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group col-sm-4">
                                                <input class="form-check-input" type="checkbox" id="ThreeDSecure" name="ThreeDSecure" checked>
                                                <label> 3D Secure</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center" id="installment_table">
                                    </div>
                                    <input name="total" id="total" type="hidden">
                                </div>
                            </div>
                            <div class="wizard-footer">
                                <div class="pull-right">
                                    <input type='button' class='btn btn-next btn-fill btn-success btn-wd' name='next' id="next" value='İleri'  />
                                    <input type='submit' class='btn btn-finish btn-fill btn-success btn-wd' name='finish' id="finish" value='Ödeme Yap' />
                                </div>

                                <div class="pull-left">
                                    <input type='button' class='btn btn-previous btn-default btn-wd btn-primary' name='previous' value='Geri' />
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </form>
                    </div>
                </div> <!-- wizard container -->
            </div>
        </div><!-- end row -->
    </div> <!--  big container -->
</div>

{{--@section('scripts') {--}}

{{--<script type="text/javascript">--}}

{{--    $("#ThreeDSecure").val(1);--}}
{{--    $("#ThreeDSecure").change(function () {--}}
{{--        if (this.checked) {--}}
{{--            $("#ThreeDSecure").val(1);--}}
{{--        }--}}
{{--        else {--}}
{{--            $("#ThreeDSecure").val(0);--}}
{{--        }--}}
{{--    });--}}

{{--    var checked = false;--}}
{{--    var bin =null;--}}
{{--    $('#CardNumber').keyup(function(){--}}

{{--        if ($("#CardNumber").val().length < 6) {--}}
{{--            $("#installment_table").html('');--}}
{{--            checked=false;--}}
{{--        }--}}

{{--        if ($("#CardNumber").val().length >= 6){--}}
{{--            if(!checked || bin != $("#CardNumber").val().substring(0,6)){--}}
{{--                bin = $("#CardNumber").val().substring(0, 6);--}}
{{--                getPosInstallments();--}}

{{--                checked=true;--}}
{{--            }--}}
{{--        }--}}
{{--    });--}}

{{--    $('#amount').change(function () {--}}

{{--        if ($("#amount").val() == 0) {--}}
{{--            $("#installment_table").html('');--}}
{{--        }--}}
{{--        if ($("#amount").val() != 0 && $("#CardNumber").val().length >= 6) {--}}
{{--            console.log("1");--}}
{{--            getPosInstallments();--}}
{{--        }--}}

{{--    });--}}

{{--    function getTotal() {--}}
{{--        //var total = parseFloat($("input[name='installment']:checked").data("total")).toFixed(2);--}}
{{--        var total = $("input[name='installment']:checked").data("total");--}}
{{--        $('#total').val(total);--}}
{{--    };--}}

{{--    function getPosInstallments() {--}}

{{--        var data = {};--}}
{{--        data['credit_card'] = $("#CardNumber").val().substr(0, 6);--}}
{{--        data['Amount'] = $("#amount").val();--}}
{{--        data['currency_code'] = "TRY";--}}
{{--        data['merchant_key'] = "asdadsasdasdasd";--}}

{{--        $.ajax({--}}
{{--            type: 'POST',--}}
{{--            dataType: 'json',--}}
{{--            data: JSON.stringify(data),--}}
{{--            url: '/GetPos',--}}
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
{{--}--}}
{{--@endsection--}}




