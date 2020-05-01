@extends('layout/auth')

@section('title')
    Menux - Login
@endsection


@section('container')

    {{ Form::open(array('url' => '/entrar', 'method' => 'post')) }}

        {{ Form::text('email', null, array('class' => 'form-control', 'placeholder' => 'E-mail')) }}

        {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Senha')) }}

        {{ Form::submit('Entrar', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

    <span>Não possui uma conta? <a href="{{ url('/cadastrar') }}">Clique aqui!</a></span>
    
@endsection