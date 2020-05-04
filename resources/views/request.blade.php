@extends('layout/main')

@section('css')

<style>

/* width */
.listItems::-webkit-scrollbar {
	width: 7px;
}

/* Track */
.listItems::-webkit-scrollbar-track {
	background: #f1f1f1;
}

/* Handle */
.listItems::-webkit-scrollbar-thumb {
	background: #888;
}

/* Handle on hover */
.listItems::-webkit-scrollbar-thumb:hover {
	background: #555;
}

.delivery {
	border: 1px solid #EEE;
	border-radius: 15px;
	padding: 15px 15px;
}

.delivery .informations {
	display: flex;
	justify-content: space-around;
	align-items: center;
	margin-top: 10px;
}

.informations p {
	font-size: 22px;
	font-weight: bold;
	line-height: 22px;
	margin: 0px;
}

.informations span {
	font-size: 18px;
	font-weight: 300;
	line-height: 18px;
}

.listItems {
	max-height: 300px;
	padding: 8px 20px;
	overflow: auto;
}

.listItems h5 {
	font-weight: 200;
}

.listItems .item {
	border: none;
	border-radius: 0px;
	height: 30px;
}

</style>

@endsection

@section('container')

	@php
		// dd(config('menux.form_of_payment'));
	@endphp

    <!-- Content Wrapper. Contains page content -->
  	<div class="content-wrapper">
    <!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Dashboard</h1>
					</div><!-- /.col -->
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="#">Início</a></li>
						</ol>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</div>
    <!-- /.content-header -->

		<!-- Main content -->
		<div class="content">
			<div class="container-fluid">
				<div class="row">

					<div class="col-12 col-sm-6 col-md-3">
						<div class="info-box">
						<span class="info-box-icon bg-success elevation-1"><i class="fas fa-cog"></i></span>

						<div class="info-box-content">
							<span class="info-box-text">Pedidos em aberto</span>
							<span class="info-box-number delivery-received">
							
							</span>
						</div>
						<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>

					<div class="col-12 col-sm-6 col-md-3">
						<div class="info-box">
						<span class="info-box-icon bg-warning elevation-1"><i class="fas fa-cog"></i></span>

						<div class="info-box-content">
							<span class="info-box-text">Pedidos sendo preparados</span>
							<span class="info-box-number delivery-making">
							
							</span>
						</div>
						<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>

					<div class="col-12 col-sm-6 col-md-3">
						<div class="info-box">
						<span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

						<div class="info-box-content">
							<span class="info-box-text">Pedidos em entrega</span>
							<span class="info-box-number delivery-sent">
							
							</span>
						</div>
						<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>

					<div class="col-12 col-sm-6 col-md-3">
						<div class="info-box">
						<span class="info-box-icon bg-danger elevation-1"><i class="fas fa-cog"></i></span>

						<div class="info-box-content">
							<span class="info-box-text">Pedidos cancelados</span>
							<span class="info-box-number delivery-canceled">
							
							</span>
						</div>
						<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
				</div>
				<!-- /.row -->


				<div id="listReceived" class="row">
				</div>
			</div><!-- /.container-fluid -->
		</div>
    <!-- /.content -->
  	</div>
  <!-- /.content-wrapper -->
@endsection

@section('script')


<script>

	const FORM_PAYMENT = @JSON(config('menux.form_of_payment'));

	$(document).ready(function() {


		getDeliverys();

		setInterval(function() {
			getDeliverys();
		}, 15000);
	});

	function getDeliverys() {
		$.ajax({
			method: 'GET',
            url: `${URL}/pedido`,
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        }).done(function(res) {

			const { delivery } = res;

			drawDeliverys(delivery);

        }).fail(function(err) {
			console.log(err)
        });
	}

	function drawDeliverys(deliverys) {

		const { deliveryReceived, deliveryMaking } = deliverys;

		drawReceived(deliveryReceived)
	}

	function drawReceived(deliveryReceived) {
		const containerReceived = $('#listReceived');

		containerReceived.empty();

		$('.delivery-received').text(deliveryReceived.length);

		deliveryReceived.forEach(delivery => {
			
			const container = jQuery('<div />', {
				class: 'col-12 col-sm-6 col-md-3'
			}).appendTo(containerReceived);

			const deliveryContainer = jQuery('<div />', {
				class: 'delivery bg-success'
			}).appendTo(container);

			const informations = jQuery('<div />', {
				class: 'informations',
			}).appendTo(deliveryContainer);

			const containerValue = jQuery('<div />', {
				class: 'container-information'
			}).appendTo(informations);

			const valueText = jQuery('<p />', {
				text: 'Valor:',
			}).appendTo(containerValue);

			const value = jQuery('<span />', {
				text: `R$ ${delivery.value}`,
			}).appendTo(containerValue);

			const containerFormPayment = jQuery('<div />', {
				class: 'container-information'
			}).appendTo(informations);

			const formPaymentText = jQuery('<p />', {
				text: 'Forma de pagmt:',
			}).appendTo(containerFormPayment);

			const formPayment = jQuery('<span />', {
				text: FORM_PAYMENT[delivery.payment_method],
			}).appendTo(containerFormPayment);

			const products = delivery.delivery_items;
			
			const listItems = jQuery('<ul />', {
				class: 'listItems'
			}).appendTo(deliveryContainer);
			
			products.forEach(product => {
				const item = jQuery('<li />', {
					class: 'item',
					html: `<span style="font-weight: bold; font-size: 18px">${product.quantity}x</span>
							<span style="font-size: 20px"> &nbsp;--- ${product.product.name}</span>`
				}).appendTo(listItems);

			});
		});
	}
</script>
	
@endsection