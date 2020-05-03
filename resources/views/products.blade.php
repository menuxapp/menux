@extends('layout/main')

@section('container')

<!-- Modal -->
<div class="modal fade" id="product" tabindex="-1" role="dialog" aria-labelledby="productLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="productModalTitle">Cadastrar Categoria</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				{{ Form::open(array('url' => 'produtos', 'method' => 'POST', 'id' => 'form', 'enctype'=> 'multipart/form-data')) }}
				<img class="image-item" alt="">
				<div class="form-group">
					<label for="image">Foto da produto</label>
					<div class="input-group">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="productImage" name="image">
							<label class="custom-file-label" for="image">Escolher arquivo</label>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="">Nome do produto</label>
					<input type="text" name="name" id="nameproduct" class="form-control" placeholder="Descrição (Ex: Batata frita) ">
				</div>

				<div class="form-group">
					<label>Descrição do produto</label>
					<textarea id="description" name="description" class="form-control" rows="3" placeholder="Descrição ..."></textarea>
                </div>

                <div class="form-group">
                    <label>Categoria do produto</label>
                    <select class="custom-select" name="product_category">
                        <option value="">Selecione ...</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->description }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="d-flex align-items-center mb-2">
                    <div class="w-50 mr-3 input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input type="text" class="form-control money" name="value">
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="form-group mb-0">
                            <div class="custom-control custom-switch">
                              <input type="checkbox" class="custom-control-input" id="customSwitch1" name="status">
                              <label class="custom-control-label" for="customSwitch1">Produto visivel?</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Salvar</button>
			{{ Form::close() }}
			</div>
	  </div>
	</div>
</div>
    
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
		<div class="d-flex justify-content-between align-items-center mb-2">
		<div >
			<h1 class="m-0 text-dark">Produtos</h1>
		</div><!-- /.col -->
		<div>
			<ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item">
					<button class="btn btn-primary" data-toggle="modal" data-target="#product" onclick="showModal()">Cadastrar</button>
				</li>
			</ol>
		</div><!-- /.col -->
		</div><!-- /.row -->

		<div class="content">
			<div class="container-fluid">
				<div id="loading">
				</div>
				<div id="productsList" class="row">
				</div>
			</div>
		</div>
    </div>

@endsection


@section('script')

<script>

let PRODUCTS = [];

$(document).ready(function() {

	$("#productImage").change(function () {
		filePreview(this);
	});

	$('#form').submit(function(e) {
        e.preventDefault();


		$(".is-invalid").map(function() {
			$(this).removeClass('is-invalid');
		});

        $.ajax({
			method: 'POST',
            url: $(this).attr('action'),
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: new FormData(this),
			contentType: false,
            processData: false,
        }).done(function(res) {

			showMessage('Produto salvo com sucesso!');

            getProducts();

        }).fail(function(err) {

			if(err.status == 401) {
				showMessage('Falha ao salvar produto, verifique as informações, e tente novamente.', 'warning');
			} else {
				showMessage('Falha ao salvar produto, tente novamente mais tarde!', 'error');
			}

            const errors = err.responseJSON;
			            
            for(error in errors) {
                const input = $(`input[name="${error}"]`);

                const textarea = $(`textarea[name="${error}"]`);
                
                const select = $(`select[name=${error}`);

                if(input) {
                    input.addClass('is-invalid');
                }
				
				if(textarea) {
					textarea.addClass('is-invalid');
                }
                
                if(select) {
					select.addClass('is-invalid');
				}
            }
        });
    });


	getProducts();
});

function filePreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
			
			const form = $('#form');

			$('.image-item').attr('src', e.target.result)
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function getProducts() {

	const container = jQuery('#productsList');

	$.ajax({
		method: "GET",
		url: `${URL}/produtos`
	}).done(function(res) {

		const { products } = res;

		$('#loading').hide();

		if(products.length >= 1) {

			PRODUCTS = products;

			drawProducts(products, container);
		} else {

			container.empty();

			const msg = jQuery('<h3 />', {
				class: 'warning-msg',
				text: 'Nenhuma produto cadastrado.'
			}).appendTo(container);

		} 

	}).fail(function(err) {
		showMessage('Falha ao carregar as produtos, tente novamente mais tarde!', 'error');
	});

}

function drawProducts(products, container) {

	container.empty();
	products.forEach(product => {

		const containerItem = jQuery('<div />', {
			class: ' col-lg-3',
			style: 'padding: 0px 5px'
		}).appendTo(container);

		const item = jQuery('<div />', {
			class: 'item',
		}).appendTo(containerItem);

		const imgContainer = jQuery('<div />', {
			class: 'img-container'
		}).appendTo(item);

		const imageURL = `${URL}/${product.image}`;

		const img = jQuery('<img />', {
			class: 'img-fluid',
			src: imageURL,
			alt: product.name
		}).appendTo(imgContainer);

		const informations = jQuery('<div />', {
			class: 'informations'
		}).appendTo(item);

		const paramName = jQuery('<p />', {
			class: 'param',
			text: 'Nome:'
		}).appendTo(informations);

		const valueName = jQuery('<span />', {
			class: 'value',
			text: product.name
		}).appendTo(informations);

		const valueInfo = jQuery('<p />', {
			class: 'value information',
			text: product.description
		}).appendTo(informations);

		item.on('click', function() {

			showModal(product)
			$('#product').modal('show');
		})
	});
}

function showModal(product = undefined) {

	$('#form').attr('method', 'POST');
	$('#form').attr('action', `${URL}/produtos`);

	$('input[name="_method"]').remove();

	$('#form').trigger("reset");

	$('.image-item').removeAttr('src');

	if(product) {

		const inputMethod = jQuery('<input />', {
			name: '_method',
			type: 'hidden',
			value: 'PUT'
		}).appendTo($('#form'));

		$('#form').attr('action', `${URL}/produtos/${product.id}`);

		for(data in product) {
			
			const input = $(`input[name=${data}`);
            const textarea = $(`textarea[name=${data}`);

            const select = $(`select[name=${data}`);


			if(input) {
                if(data == 'image') {
					const imageURL = `${URL}/${product.image}`;

					$('.image-item').attr('src', imageURL)
                } else if (data == 'value') {
					input.val(product[data].toString().replace('.', ','));
				} else {
                    input.val(product[data]);
                }
            }

            if(textarea) {
                textarea.val(product[data]);
            }

            if(select) {
                select.val(product[data]);
            }
		}
	}

	$('#product').modal('show');
}

</script>
	
@endsection
