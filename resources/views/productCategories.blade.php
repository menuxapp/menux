@extends('layout/main')

@section('container')

<!-- Modal -->
<div class="modal fade" id="category" tabindex="-1" role="dialog" aria-labelledby="categoryLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="categoryModalTitle">Cadastrar Categoria</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				{{ Form::open(array('url' => 'categorias', 'method' => 'POST', 'id' => 'form', 'enctype'=> 'multipart/form-data')) }}
				<img class="image-item" alt="">
				<div class="form-group">
					<label for="image">Foto da categoria</label>
					<div class="input-group">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="categoryImage" name="image">
							<label class="custom-file-label" for="image">Escolher arquivo</label>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="">Descrição da categoria</label>
					<input type="text" name="description" id="nameCategory" class="form-control" placeholder="Descrição (Ex: Lanches) ">
				</div>

				<div class="form-group">
					<label>Informações da categoria</label>
					<textarea id="information" name="information" class="form-control" rows="3" placeholder="Informações ..."></textarea>
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
			<h1 class="m-0 text-dark">Categorias de produtos</h1>
		</div><!-- /.col -->
		<div>
			<ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item">
					<button class="btn btn-primary" data-toggle="modal" data-target="#category" onclick="showModal()">Cadastrar</button>
				</li>
			</ol>
		</div><!-- /.col -->
		</div><!-- /.row -->

		<div class="content">
			<div class="container-fluid">
				<div id="loading">
				</div>
				<div id="categoriesList" class="row">
				</div>
			</div>
		</div>
    </div>

@endsection


@section('script')

<script>

let CATEGORIES = [];

$(document).ready(function() {

	$("#categoryImage").change(function () {
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

			getCategories();
			
			showMessage('Categoria salva com sucesso!');

        }).fail(function(err) {		
            const errors = err.responseJSON;
			            
            for(error in errors) {
                const input = $(`input[name="${error}"]`);

				const textarea = $(`textarea[name="${error}"]`);

                if(input) {
                    input.addClass('is-invalid');
                }
				
				if(textarea) {
					textarea.addClass('is-invalid');
				}
            }
        });
    });


	getCategories();
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

function getCategories() {

	const container = jQuery('#categoriesList');

	$.ajax({
		method: "GET",
		url: `${URL}/categorias`
	}).done(function(res) {

		const { categories } = res;

		$('#loading').hide();

		if(categories.length >= 1) {

			CATEGORIES = categories;

			drawCategories(categories, container);
		} else {

			container.empty();

			const msg = jQuery('<h3 />', {
				class: 'warning-msg',
				text: 'Nenhuma categoria cadastrada.'
			}).appendTo(container);

		} 

	}).fail(function(err) {
		showMessage('Falha ao carregar as categorias de predutos, tente novamente mais tarde!', 'error');
	});

}

function drawCategories(categories, container) {

	container.empty();
	categories.forEach(category => {

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

		const imageURL = `${URL}/${category.image}`;

		const img = jQuery('<img />', {
			class: 'img-fluid',
			src: imageURL,
			alt: category.description
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
			text: category.description
		}).appendTo(informations);

		const valueInfo = jQuery('<p />', {
			class: 'value information',
			text: category.information
		}).appendTo(informations);

		item.on('click', function() {

			showModal(category)
			$('#category').modal('show');
		})
	});
}

function showModal(category = undefined) {

	$('#form').attr('method', 'POST');
	$('#form').attr('action', `${URL}/categorias`);

	$('input[name="_method"]').remove();

	$('#form').trigger("reset");

	$('.image-item').removeAttr('src');

	if(category) {

		const inputMethod = jQuery('<input />', {
			name: '_method',
			type: 'hidden',
			value: 'PUT'
		}).appendTo($('#form'));

		$('#form').attr('action', `${URL}/categorias/${category.id}`);

		for(data in category) {
			
			const input = $(`input[name=${data}`);

			if(input) {
				// input.val(category[data]);

				if(data != 'image' && data != 'information')
				{
					input.val(category[data]);

				} else if(data == 'information') {
					const textarea = $(`textarea[name=${data}`);
					
					textarea.val(category[data]);
				} else {
					const imageURL = `${URL}/${category.image}`;

					$('.image-item').attr('src', imageURL)
				}
			}

		}
	}

	$('#category').modal('show');
}

</script>
	
@endsection
