@extends('layout/auth')

@section('title')
    Menux - Cadastrar
@endsection

@section('container')

    <div class="card card-info w-100 h-100 text-center">
        {{ Form::open(array('url' => '/cadastrar', 'method' => 'post', 'id' => 'form')) }}

            <div class="card-header text-center dark-content">
                <img src="{{ asset('assets/logo.png') }}" alt="MenuX" width="180">
            </div>
            <div class="card-body">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="text" class="form-control" placeholder="Name" name="name">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" placeholder="E-mail" name="email">
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-unlock-alt"></i></span>
                    </div>
                    <input type="password" class="form-control" placeholder="password" name="password">
                </div>

            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </div>

        {{ Form::close() }}

        <span>Já possui uma conta? <a href="{{ url('/entrar') }}">Clique aqui!</a></span>
    </div>
    
@endsection


@section('script')

<script>

const URL = "{{ url('') }}";

$(document).ready(function() {


    $('#form').submit(function(e) {
        e.preventDefault();


		$(".is-invalid").map(function() {
			$(this).removeClass('is-invalid');
		});

        $.ajax({
			method: 'POST',
            url: $(this).attr('action'),
            data: new FormData(this),
			contentType: false,
            processData: false,
        }).done(function(res) {

            window.location.href = `${URL}/dashboard`;
			
        }).fail(function(err) {

			if(err.status == 401) {
                const errors = err.responseJSON;
                            
                for(error in errors) {

                    const input = $(`input[name="${error}"]`);
    
                    if(input) {
                        input.addClass('is-invalid');

                        showMessage(errors[error], 'error');
                    }
                }
			} else {
				showMessage('Falha ao se cadastrar, tente novamente mais tarde!', 'error');
			}

        });
    });

})


</script>

@endsection