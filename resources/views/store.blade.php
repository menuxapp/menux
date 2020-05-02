@extends('layout/main')

@section('container')

@php
    $urlForm = url('/estabelecimento');
    $method = 'POST';

    if($store->id)
    {
        $urlForm .= "/$store->id";
        $method = 'PUT';
    }
@endphp

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Estabelecimento</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    {{-- <li class="breadcrumb-item"><a href="#">Início</a></li> --}}
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <div class="container-fluid">
    <div class="d-flex justify-content-center align-items-center">
        {{ Form::open(array('url' => $urlForm, 'method' => $method, 'id' => 'form')) }}
        <div class="col-lg-5 m-auto">
            <!-- general form elements disabled -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Informações</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Nome</label>
                                <input id="name" type="text" class="form-control" placeholder="Nome" name="name" value="{{ $store->name }}">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>CEP</label>
                                <input id="cep" type="text" class="form-control" placeholder="CEP" maxlength="8" name="cep" value="{{ $store->cep }}">
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <div class="form-group">
                                <label>Endereço</label>
                                <input id="logradouro" type="text" class="form-control" placeholder="Endereço" name="address" value="{{ $store->address }}">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Número</label>
                                <input id="address_number" type="text" class="form-control" placeholder="Nº" name="address_number" value="{{ $store->address_number }}">
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <div class="form-group">
                                <label>Bairro</label>
                                <input id="bairro" type="text" class="form-control" placeholder="Barrio" name="district" value="{{ $store->district }}">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>UF</label>
                                <input id="uf" type="text" class="form-control" placeholder="UF" name="uf" value="{{ $store->uf }}">
                            </div>
                        </div>

                        <input id="localidade" type="text" class="form-control" name="city" value="{{ $store->city }}" hidden>
                        
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
    </div>



</div>
<!-- /.content-wrapper -->
    
@endsection


@section('script')

<script>

$(document).ready(function() {

    $('#cep').keyup(function() {

        const cep = $(this).val();

        if(cep.length >= 8) {
            consultCEP(cep);
        }


    });

    $('#form').submit(function(e) {
        e.preventDefault();

        $(".is-invalid").map(function() {
			$(this).removeClass('is-invalid');
		});

        $.ajax({
            method: "POST",
            url: $(this).attr('action'),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: new FormData(this),
			contentType: false,
            processData: false, 
        }).done(function(res) {

            showMessage('Estabelecimento salvo com sucesso!');

            setInterval(function(){ location.reload(); }, 1000);

        }).fail(function(err) {

            if(err.status == 401) {
				showMessage('Falha ao salvar estabelecimento, verifique as informações, e tente novamente.', 'warning');
			} else {
				showMessage('Falha ao salvar estabelecimento, tente novamente mais tarde!', 'error');
			}

            const errors = err.responseJSON;

            for(error in errors) {
                const input = $(`input[name="${error}"]`);

                if(input) {
                    input.addClass('is-invalid');
                }
            }
        });
    });
});

function consultCEP(cep) {

    cep = cep.replace(/\D/g, '');

    $.ajax({
        url: `https://viacep.com.br/ws/${cep}/json`
    }).done(function(address) {

        console.log(address)
        
        for (data in address) {
            const input = $(`#${data}`);

            if(input) {
                input.val(address[data]);
            }
        }

    }).fail(function(err) {
        showMessage('Falha ao consultar CEP!', 'error');
    });
}

</script>
    
@endsection