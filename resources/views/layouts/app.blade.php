<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{asset('lib/bootstrap/dist/css/bootstrap.min.css')}}" />
    <link href="{{asset('paper-bootstrap-wizard-master/assets/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('paper-bootstrap-wizard-master/assets/css/paper-bootstrap-wizard.css')}}" rel="stylesheet" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="{{asset('paper-bootstrap-wizard-master/assets/css/demo.css')}}" rel="stylesheet" />

    <!-- Fonts and Icons -->
    <link href="https://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="{{asset('paper-bootstrap-wizard-master/assets/css/themify-icons.css')}}" rel="stylesheet">

</head>
<body>
{{--    <script src="{{asset('paper-bootstrap-wizard-master/assets/js/jquery-2.2.4.min.js')}}" type="text/javascript"></script>--}}
    <script src="{{asset('lib/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('lib/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('paper-bootstrap-wizard-master/assets/js/jquery.validate.min.js') }}" type="text/javascript"></script>
{{--    <script src="{{ asset('paper-bootstrap-wizard-master/assets/js/paper-bootstrap-wizard.js') }}" type="text/javascript" ></script>--}}
    <script src="{{ asset('paper-bootstrap-wizard-master/assets/js/jquery.bootstrap.wizard.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    /*! =========================================================
 *
 Paper Bootstrap Wizard - V1.0.1
*
* =========================================================
*
* Copyright 2016 Creative Tim (http://www.creative-tim.com/product/paper-bootstrap-wizard)
 *
 *                       _oo0oo_
 *                      o8888888o
 *                      88" . "88
 *                      (| -_- |)
 *                      0\  =  /0
 *                    ___/`---'\___
 *                  .' \|     |// '.
 *                 / \|||  :  |||// \
 *                / _||||| -:- |||||- \
 *               |   | \\  -  /// |   |
 *               | \_|  ''\---/''  |_/ |
 *               \  .-\__  '-'  ___/-. /
 *             ___'. .'  /--.--\  `. .'___
 *          ."" '<  `.___\_<|>_/___.' >' "".
 *         | | :  `- \`.;`\ _ /`;.`/ - ` : | |
 *         \  \ `_.   \_ __\ /__ _/   .-` /  /
 *     =====`-.____`.___ \_____/___.-`___.-'=====
 *                       `=---='
 *
 *     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 *
 *               Buddha Bless:  "No Bugs"
 *
 * ========================================================= */


    jQuery.validator.addMethod("tcno", function (a) { var c = 0, d = 0, e = 0, b = 0, f = 0; if (a.charAt(0) == 0 || a.length != 11) return false; b = parseInt(a.charAt(9), 10); f = parseInt(a.charAt(10), 10); for (i = 0; i < 9; i++) { cd = parseInt(a.charAt(i), 10); i % 2 == 0 ? (c += cd) : (d += cd); e += cd } return (7 * c - d) % 10 == b && (e + b) % 10 == f ? true : false }, "L\u00fctfen ge\u00e7erli bir T.C Kimlik No girin.");

    searchVisible = 0;
    transparent = true;

    $(document).ready(function(){

        /*  Activate the tooltips      */
        $('[rel="tooltip"]').tooltip();

        // Code for the Validator
        var $validator = $('.wizard-card form').validate({
            rules: {
                amount: {
                    required: true
                },
                name: {
                    required: true
                },
                phone: {
                    required: true
                },
                tckn: {
                    required: true,
                    minlength: 11,
                    maxlength: 11,
                    tcno: true
                },
                description: {
                    required: true
                },
                CardHolderName: {
                    required: true
                },
                CardNumber: {
                    required: true,
                    creditcard: true
                },
                ExpiryDateMonth: {
                    required: true,
                    min: 1,
                    max: 12
                },
                ExpiryDateYear: {
                    required: true,
                    min: (new Date).getFullYear(),
                    max: (new Date).getFullYear() + 10
                },
                cvv: {
                    required: true
                }
            },
            messages: {
                name: "Lütfen Ad Soyad alanını giriniz",
                amount: "Lütfen Tutar alanını giriniz",
                phone: "Lütfen Cep Telefonu alanını giriniz",
                tckn: {
                    required: "Lütfen TCKN alanını giriniz",
                    minlength: "TCKN alanı en az 11 karakter uzunluğunda olmalıdır",
                    maxlength: "TCKN alanı en az 11 karakter uzunluğunda olmalıdır",
                    tcno: "Geçerli bir TC Kimlik No giriniz"
                },
                description: "Lütfen Açıklama alanını giriniz",
                CardHolderName: "Lütfen Kart Sahibi alanını giriniz",
                CardNumber: {
                    required: "Lütfen Kart Numarası alanını giriniz",
                    creditcard: "Geçerli bir kredi kartı numarası giriniz"
                },
                ExpiryDateMonth: {
                    required: "Lütfen Son Kullanma Ayı alanını giriniz",
                    min: "Son Kullanma Ayı alanı en az 1 olmalıdır",
                    max: "Son Kullanma Ayı alanı en fazla 12 olmalıdır"
                },
                ExpiryDateYear: {
                    required: "Lütfen Son Kullanma Yılı alanını giriniz",
                    min: "Son Kullanma Yılı alanı en az " + (new Date).getFullYear() + " olmalıdır",
                    max: "Son Kullanma Yılı alanı en fazla " + ((new Date).getFullYear() + 10) + " olmalıdır"
                },
                cvv: "Lütfen Güvenlik Numarası alanını giriniz"
            },

            submitHandler: function (form) {
                console.log("submit");
                form.submit();
            }
        });


        /*$('#submitForm').on('submit', function () {
            console.log("submit");

            if ($validator.valid()) {
                $('#submitForm').submit();
                return true;
            }
            else {
                console.log("not valid");
                return false;
            }
        });*/

        // Wizard Initialization
        $('.wizard-card').bootstrapWizard({
            'tabClass': 'nav nav-pills',
            'nextSelector': '.btn-next',
            'previousSelector': '.btn-previous',

            onNext: function(tab, navigation, index) {
                var $valid = $('.wizard-card form').valid();
                if(!$valid) {
                    $validator.focusInvalid();
                    return false;
                }
            },

            onInit : function(tab, navigation, index){

                //check number of tabs and fill the entire row
                var $total = navigation.find('li').length;
                $width = 100/$total;

                navigation.find('li').css('width',$width + '%');

            },

            onTabClick : function(tab, navigation, index){

                var $valid = $('.wizard-card form').valid();

                if(!$valid){
                    return false;
                } else{
                    return true;
                }

            },

            onTabShow: function(tab, navigation, index) {
                var $total = navigation.find('li').length;
                var $current = index+1;

                var $wizard = navigation.closest('.wizard-card');

                // If it's the last tab then hide the last button and show the finish instead
                if($current >= $total) {
                    $($wizard).find('.btn-next').hide();
                    $($wizard).find('.btn-finish').show();
                } else {
                    $($wizard).find('.btn-next').show();
                    $($wizard).find('.btn-finish').hide();
                }

                //update progress
                var move_distance = 100 / $total;
                move_distance = move_distance * (index) + move_distance / 2;

                $wizard.find($('.progress-bar')).css({width: move_distance + '%'});
                //e.relatedTarget // previous tab

                $wizard.find($('.wizard-card .nav-pills li.active a .icon-circle')).addClass('checked');

            }
        });


        // Prepare the preview for profile picture
        $("#wizard-picture").change(function(){
            readURL(this);
        });

        $('[data-toggle="wizard-radio"]').click(function(){
            wizard = $(this).closest('.wizard-card');
            wizard.find('[data-toggle="wizard-radio"]').removeClass('active');
            $(this).addClass('active');
            $(wizard).find('[type="radio"]').removeAttr('checked');
            $(this).find('[type="radio"]').attr('checked','true');
        });

        $('[data-toggle="wizard-checkbox"]').click(function(){
            if( $(this).hasClass('active')){
                $(this).removeClass('active');
                $(this).find('[type="checkbox"]').removeAttr('checked');
            } else {
                $(this).addClass('active');
                $(this).find('[type="checkbox"]').attr('checked','true');
            }
        });

        $('.set-full-height').css('height', 'auto');

    });



    //Function to show image before upload

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }


    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            }, wait);
            if (immediate && !timeout) func.apply(context, args);
        };
    };


    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-46172202-1', 'auto');
    ga('send', 'pageview');

</script>

</body>
</html>
