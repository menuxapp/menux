<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('assets/logo2.png') }}" />

    <title>
        @yield('title')
    </title>

    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <link href="{{ asset('/css/global.css') }}" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('/css/toastr.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    @yield('css')
</head>
<body>

    <div class="container">

        <div class="content">
            @yield('container')
        </div>

    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    
    <script src="{{ asset('/js/fontawesome.min.js') }}" rel="stylesheet"></script>
    
    <script src="{{ asset('js/toastr.min.js') }}"></script>

<script>
    function showMessage(message, type = 'success') {
        toastr.options = {  "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-full-width",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut" }

        switch (type) {
            case 'warning':
                toastr.warning(message);
                break;

            case 'error':
                toastr.error(message);
                break;
        
            default:
                toastr.success(message);
                break;
        }
        
    }
</script>


    @yield('script')
    
</body>
</html>