@extends('layout/auth')


@section('container')

    {{ Form::open(array('url' => '/cadastrar', 'method' => 'post')) }}

        {{ Form::text('name', null, array('class' => 'form-control', 'placeholder' => 'Nome Completo')) }}

        {{ Form::text('email', null, array('class' => 'form-control', 'placeholder' => 'E-mail')) }}

        {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Senha')) }}

        {{ Form::submit('Entrar', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

    <span>Já possui uma conta? <a href="{{ url('/entrar') }}">Clique aqui!</a></span>
    
@endsection