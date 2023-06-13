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
                        <form action="{{route('payment')}}" method="post"  id="submitForm" name="submitForm">
                            @csrf
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
                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                    <h5 class="info-text"> Kart Bilgileri </h5>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Kart Sahibi <small>*</small></label>
                                                <input name="cc_holder_name" id="cc_holder_name" type="text" class="form-control" placeholder="Kart Sahibi...">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Kart Numarası <small>*</small></label>
                                                <input name="cc_no" id="cc_no" type="text" maxlength="16" class="form-control" placeholder="Kart Numarası...">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group col-sm-4">
                                                <label>Son Kullanma Ayı <small>*</small></label>
                                                <input name="expiry_month" id="expiry_month" type="number" max="12" min="1" maxlength="2" class="form-control" placeholder="Son Kullanma Ayı...">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label>Son Kullanma Yılı <small>*</small></label>
                                                <input name="expiry_year" id="expiry_year" type="number" maxlength="4" class="form-control" placeholder="Son Kullanma Yılı...">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label>Güvenlik Numarası <small>*</small></label>
                                                <input name="cvv" id="cvv" type="number" maxlength="3" class="form-control" placeholder="Güvenlik Numarası...">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group col-sm-4">
                                                <input class="form-check-input" type="checkbox" id="3d_checkbox" name="3d_checkbox" checked>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">

    // $("#phone").change(function() {
    //     var re = /^(\+?\d{1,3}[-.\s]?)?(\d{3}[-.\s]?)?\d{3}[-.\s]?\d{4}$/;
    //     var no = $("#phone").val();
    //     if (!re.test(no) || no.length < 10) {
    //         console.log('Lütfen geçerli bir telefon numarası giriniz !!');
    //     }
    // });

    $("#3d_checkbox").val(1);
    $("#3d_checkbox").change(function () {
        if (this.checked) {
            $("#3d_checkbox").val(1);
        }
        else {
            $("#3d_checkbox").val(0);
        }
    });

    var checked = false;
    var bin =null;
    $('#cc_no').keyup(function(){

        if ($('#cc_no').val().length < 6) {
            $('#installment_table').html('');
            checked=false;
        }

        if ($("#cc_no").val().length >= 6){
            if(!checked || bin != $("#cc_no").val().substring(0,6)){
                bin = $("#cc_no").val().substring(0, 6);
                getPosInstallments();
                checked=true;
            }
        }
    });

    $('#amount').change(function () {

        if ($("#amount").val() == 0) {
            $("#installment_table").html('');
        }
        if ($("#amount").val() != 0 && $("#cc_no").val().length >= 6) {
            getPosInstallments();
        }

    });

    function getTotal() {
        var total = $("input[name='installments_number']:checked").data("total");
        console.log("total : " + total);
        $('#total').val(total);
        console.log($('#total').val());
    };


    function getPosInstallments() {

        var data = {};
        data['credit_card'] = $("#cc_no").val().substr(0, 6);
        data['amount'] = $("#amount").val();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify(data),
            url: '{{route('payment.get-pos')}}',
            contentType: 'application/json',
            processData : false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                if (result.status_code === 100) {
                    var table = '<table class="table table-sm table-hover table-bordered" style="width: 40%;"><thead><tr><th scope="col">#</th><th scope="col">Taksit</th><th scope="col">Taksitli Tutarı</th></tr></thead><tbody>';
                    $.each(result.data, function (index, value) {
                        table += '<tr><th scope="row"><input onclick="getTotal();" class="form-check-input" type="radio" id="installments_number_'+ index +'" name="installments_number" value="' + value.installments_number + '"' + (value.installments_number == 1 ? 'checked' : '') + ' data-total="' + value.amount_to_be_paid.toString().replace('.', ',') + '"></th><td>' + (value.installments_number == 1 ? 'Tek Çekim' : + value.installments_number + ' Taksit') + '</td><td>' + value.amount_to_be_paid + ' ₺</td > </tr><tr>';
                    });

                    table += '</tbody></table>';

                    $("#installment_table").html(table);
                    var total = $("input[name='installments_number']:checked").data("total");
                    $('#total').val(total);
                }
                else {
                    $("#formInfo").html('<div class="alert ' + (result.result ? 'alert-success' : 'alert-danger') + '" role="alert">' + result.message + '<button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                }
            },
            error: function (xhr) {
                $("#formInfo").html('<div class="alert alert-danger" role="alert">UNKOWN ERROR!<button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            }
        });
    }



</script>
