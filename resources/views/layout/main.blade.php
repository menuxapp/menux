<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="icon" href="{{ asset('assets/logo2.png') }}" />

    <title>Menux - Dashboard</title>

    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">


    <link href="{{ asset('/css/toastr.min.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('/css/global.css') }}" rel="stylesheet">

</head>

<body>

    @php
        $user = Auth::user();
    @endphp

<div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-light border-right" id="sidebar-wrapper">
		<div class="sidebar-heading">
			<img src="{{ asset('/assets/logo.png') }}" alt="MenuX" width="130">
		</div>
		<div class="list-group list-group-flush">
            @if ($user->Store)
				<a href="{{ url('dashboard') }}" class="list-group-item list-group-item-action bg-light">
					<i class="nav-icon fas fa-tachometer-alt"></i>
					Dashboard
				</a>
				<a href="{{ url('/dashboard/produtos') }}" class="list-group-item list-group-item-action bg-light">
					<i class="fas fa-th"></i>
					Produtos
                </a>
				<a href="{{ url('/dashboard/categorias') }}" class="list-group-item list-group-item-action bg-light">
					<i class="nav-icon fas fa-th"></i>
					Categorias
				</a>
			@endif
			<a href="{{ url('dashboard/estabelecimento') }}" class="list-group-item list-group-item-action bg-light">
				<i class="fas fa-store-alt"></i>
				Estabelecimento
			</a>
		</div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <a class="nav-link" id="menu-toggle" href="javascript:;" role="button">
                <i class="fas fa-bars"></i>
            </a>

            <div class="dropdown">
                <button class="dropdown-toggle user" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span>{{ $user->name }}</span>
                    <img src="{{ asset('assets/user.jpg') }}" alt="Usuário">
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ url('/sair') }}">
                        <i class="fas fa-sign-out-alt"></i>
                        Sair
                    </a>
                </div>
              </div>
        </nav>

        <div class="container-fluid">

            @yield('container')

        </div>
    </div>
    <!-- /#page-content-wrapper -->

</div>
<!-- /#wrapper -->

<!-- Bootstrap core JavaScript -->
<script src="{{ asset('js/jquery.min.js') }}"></script>

<script src="{{ asset('js/jquery.mask.min.js') }}"></script>

<script src="{{ asset('js/bootstrap.min.js') }}"></script>

<script src="{{ asset('/js/fontawesome.min.js') }}" rel="stylesheet"></script>

<script src="{{ asset('js/toastr.min.js') }}"></script>

<!-- Menu Toggle Script -->
<script>

const URL = "{{ url('') }}";

window.onload = function() {

    $(".money").mask('000.000.000.000.000,00', {reverse: true});

	$("#menu-toggle").click(function(e) {
		e.preventDefault();
		$("#wrapper").toggleClass("toggled");
	});

    startLoading();
}

function startLoading() {

    const spinnersArray = ['text-muted', 'text-primary', 'text-success', 'text-info', 'text-warning', 'text-danger', 'text-secondary', 'text-dark', 'text-light'];

    const loadingContainer = $('#loading');

    spinnersArray.forEach(spinner => {
        const spinnerElement = jQuery('<div />', {
            class: `spinner-grow ${spinner}`
        }).appendTo(loadingContainer);
    });
}

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