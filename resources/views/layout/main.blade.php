<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @yield('title')
    </title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/global.css') }}">

    @yield('css')
</head>
<body>

    @php
        $user = Auth::user();
    @endphp


    <div class="container text-center">

        @yield('container')

    </div>

    <script src="{{ asset('js/jquery-3.5.0.min.js') }}"></script>
    
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    
    @yield('script')
    
</body>
</html>