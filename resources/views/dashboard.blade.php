@extends('layout/main')

@section('title')
    Dashboard - Home
@endsection

@section('container')
    <div class="btn-group mt-5" role="group">
        <button type="button" class="btn btn-secondary">Pedidos</button>
        <button type="button" class="btn btn-secondary">Estabelecimento</button>
        <button type="button" class="btn btn-secondary">Produtos</button>
        <button type="button" class="btn btn-secondary">Clientes</button>
    </div>
@endsection