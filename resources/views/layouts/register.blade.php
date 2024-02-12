<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>RSIS Portal | Register</title>

        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{asset('public/assets/AdminLTE-3.0.0/plugins/fontawesome-free/css/all.min.css')}}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="{{asset('public/assets/ionicons/ionicons.min.css')}}">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="{{asset('public/assets/AdminLTE-3.0.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('public/assets/AdminLTE-3.0.0/dist/css/adminlte.min.css')}}">
        <!-- Google Font: Source Sans Pro -->
        <link href="{{asset('public/assets/fonts/sourcesanspro.css')}}" rel="stylesheet">

        <style>
            .register-box {
                width: 1000px;
            }

            .login-box-msg {
                font-size: 25px;
            }
        </style>
    </head>
    <body class="hold-transition login-page">
        @yield('content')

        <!-- jQuery -->
        <script src="{{asset('public/assets/AdminLTE-3.0.0/plugins/jquery/jquery.min.js')}}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{asset('public/assets/AdminLTE-3.0.0/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- AdminLTE App -->
        <script src="{{asset('public/assets/AdminLTE-3.0.0/dist/js/adminlte.min.js')}}"></script>
        <!-- InputMask -->
        <script src="{{asset('public/assets/AdminLTE-3.0.0/plugins/moment/moment.min.js')}}"></script>
        <script src="{{asset('public/assets/AdminLTE-3.0.0/plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>

        <script type="text/javascript">
            sessionStorage.removeItem("is_locked") // Set is_locked to 0 to remove lockscreen when session timed out

            let _token = "<?php echo csrf_token() ?>";

            // Inputmask
            $('.input_mask').inputmask()

            // Show province, municipality and barangay when selected ph as country
            $('#country').on('change', function() {
                if($(this).val() == "PH") {
                    $('#province_input').css('display', 'block')
                    $('#municipality_input').css('display', 'block')
                    $('#barangay_input').css('display', 'block')
                } else {
                    $('#province_input').css('display', 'none')
                    $('#municipality_input').css('display', 'none')
                    $('#barangay_input').css('display', 'none')
                }
            })

            // Get region when selected province
            $('#province').on('change', ()=>{
                var region_id = $('#province option:selected').attr('region_id')
                var province_id = $('#province option:selected').attr('province_id')

                $('#municipality').empty() // empty municipality
                $('#municipality').append(`<option selected disabled>Loading...</option>`)
                // Get region code
                $.ajax({
                    type: 'POST',
                    url: "{{route('register.regions.region_code')}}",
                    data: {
                        _token: _token,
                        region_id: region_id
                    },
                    dataType: 'json',
                    success: (res)=>{
                        $('#region').val(res)
                    }
                })

                // Get municipalities
                $.ajax({
                    type: 'POST',
                    url: "{{route('register.municipalities')}}",
                    data: {
                        _token: _token,
                        province_id: province_id
                    },
                    dataType: 'json',
                    success: (res)=>{
                        $('#municipality').empty() // empty municipality
                        var options = `<option value="0" selected disabled>Municipality</option>`
                        res.forEach((item)=> {
                            options += `<option value="`+item.mun_code+`">`+item.name+`</option>`
                        })
                        $('#municipality').append(options)
                    }
                })
            })

            // Show philrice stations dropdown and id no input when selected philrice as affiliation
            $('#affiliation').on('change', ()=>{
                var affiliation_id = $('#affiliation option:selected').val()
                if (affiliation_id == 1) {
                    $('#station_input').css('display', 'block')
                    $('#philrice_idno_input').css('display', 'block')
                } else {
                    $('#station_input').css('display', 'none')
                    $('#philrice_idno_input').css('display', 'none')
                }
            })

            /*
             * For Laravel validation
             *
             * If country selected is Philippines and province or municipality has value
             * and validation returned error on other fields
             * province, municipality and barangay fields must be displayed
             *
             * If affiliation selected is PhilRice and PhilRice station or PhilRice id no has value
             * and validation returned error on other fields
             * PhilRice station or PhilRice id no must be displayed
             *
             */
            $(document).ready(()=> {
                // Country
                var country = $('#country option:selected').val()
                if (country == "PH") {
                    $('#province_input').css('display', 'block')
                    $('#municipality_input').css('display', 'block')
                    $('#barangay_input').css('display', 'block')
                }

                // Affiliation
                var affiliation = $('#affiliation option:selected').val()
                if (affiliation == 1) {
                    $('#station_input').css('display', 'block')
                    $('#philrice_idno_input').css('display', 'block')
                }
            })
        </script>
    </body>
</html>