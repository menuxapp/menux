@extends('layout/auth')

@section('title')
    Menux - Login
@endsection


@section('container')

    <div class="card card-info w-100 h-100 text-center">
        {{ Form::open(array('url' => '/entrar', 'method' => 'post')) }}

            <div class="card-header text-center dark-content">
                <img src="{{ asset('assets/logo.png') }}" alt="MenuX" width="180">
            </div>
            <div class="card-body">
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
                <button type="submit" class="btn btn-primary">Entrar</button>
            </div>

        {{ Form::close() }}

        <span>Não possui uma conta? <a href="{{ url('/cadastrar') }}">Clique aqui!</a></span>
    </div>
    
@endsection