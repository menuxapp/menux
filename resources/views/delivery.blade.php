<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Menux - Pedido</title>

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('/css/toastr.min.css') }}" rel="stylesheet">

    <style>
        
        * {
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;

            font-family: 'Source Sans Pro';
        }

        header {
            display: flex;
            justify-content: center;
            align-items: center;

            width: 100%;
            height: 55px;

            background: #343a40;
        }

        #loading {
            width: 100%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .categories {
            margin: auto;
            width: 100%;
            min-width: 300px;
            height: calc(100vh - 55px);
            padding-top: 20px;
        }
        
        .category {
            width: 320px;
            height: 220px;
            margin: auto;
            margin-bottom: 20px;

            background-repeat: no-repeat;
            background-origin: content-box;

            position: relative;

            overflow: hidden;

            border-radius: 18px;
        }

        .category img {
            position: absolute;
            width: 100%;
            height: 100%;

            z-index: -1;
        }

        .category .informations {
            width: 100%;
            height: 100%;

            padding: 10px;
            color: #FFF;
        }

        .category .description {
            font-size: 42px;
            font-weight: bold;
        }

        .category .information {
            font-size: 22px;
            line-height: 22px;
        }

        #products {
            margin: auto;
            width: 100%;
            min-width: 300px;
            height: calc(100vh - 55px);
            padding-top: 20px;

            display: none;
        }

        #products .productsList {
            list-style: none;
        }

        .productsList li {
            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-top: 10px;
        }

        .productsList img {
            width: 75px;
            height: 75px;

            border-radius: 50%;
        }

        .information p {
            margin-bottom: 0px;
        }
        
        .information .product-name {
            font-size: 18px;
            font-weight: 600;
        }

        /* CART */
        #cartModal .modal-body {
            padding: 0px;
        }

        .btn-cart {
            width: 80%;
            height: 45px;
            position: fixed;
            left: 50%;
            bottom: 0px;
            transform: translate(-50%, -50%);
            margin: 0 auto;
            border: none;
            border-radius: 12px;
            background: #343a40;
            color: #FFF;
            font-size: 18px;
            font-weight: bold;
        }

        .list-cart {
            list-style: none;
            padding-top: 20px;
            max-height: 240px;
            overflow:auto;
        }
        
        .list-cart li {
            display: flex; 
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #EEE;
            padding: 15px 20px
        }

        .list-cart span {
            line-height: 50%;
        }

        .list-cart .price span {
            color: rgb(146, 146, 146);
        }

        .list-cart .price p {
            margin-bottom: 5px;
        }

        .total-value {
            font-size: 28px;
            font-weight: 600;
            margin: auto;
            text-align: center;
        }

        .form-payment span{
            color: rgb(146, 146, 146);
 
        }

        .success {
            width: 100vw;
            height: 100vh;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            display: none;
        }

        .success img {
            width: 200px;
            height: 200px;
        }

    </style>

</head>
<body>

    <div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Carrinho</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <div class="modal-body">
                <ul class="list-cart">
                </ul>

                
                <div class="form-payment text-center pb-4">
                    <p class="total-value"></p>
                    <span>Selecione a forma de pagamento</span>
                    <div class="btn-group btn-group-lg w-75 mb-2" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-success" onclick="selectedPayment('money')">
                            <i class="far fa-money-bill-alt fa-lg"></i>
                        </button>
                        <button type="button" class="btn btn-primary"  onclick="selectedPayment('card')">
                            <i class="fas fa-credit-card fa-lg"></i>
                        </button>
                    </div>

                    <div class="cash-payment form-group" style="display: none">                        
                        <label for="">Informe o valor a ser pago <em class="money-change">(TROCO: R$ 0): </em></label>
                        <div class="input-group w-75 m-auto">
                            <div class="input-group-prepend">
                                <span class="input-group-text">R$</span>
                            </div>
                            <input type="text" class="form-control money" placeholder="R$">
                        </div>
                    </div>

                    <div class="card-payment form-group ml-5 mr-5" style="display: none">
                        <label>Informe o tipo do cartão:</label>
                        <select class="custom-select" name="product-category">
                            <option value="">Selecione ...</option>
                            <option value="1">Crédito</option>
                            <option value="2">Débito</option>
                        </select>
                    </div>
                </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button id="checkOut" type="button" class="btn btn-primary" disabled data-loading-text="Enviando">Finalizar Pedido</button>
                </div>
            </div>
        </div>
      </div>

      
        <header>
            <img src="{{ asset('/assets/logo.png') }}" alt="Menux" srcset="" width="120">
        </header>
        
        <div id="loading"></div>
        <div class="container" style="display: none;">

        <div id="categories">
            <h4 class="text-center mt-4 mb-4">Selecione uma categoria</h4>
            @foreach ($categories as $item)
                <div class="category" data-id="{{ $item->id }}" data-description="{{ $item->description }}">
                    <img src="{{ url($item->image) }}" alt="{{ $item->description }}" srcset="">
                    <div class="informations">
                        <p class="description">{{ $item->description }}</p>

                        <p class="information">{{ $item->information }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="products">
        </div>

        
        <button class="btn-cart" onclick="showCart()">Carrinho</button>
    </div>
    
    <div class="success">
        <img src="{{ asset('assets/success.gif') }}" alt="">
        <h3 class="text-success">Pedido enviado com sucesso!</h3>
    </div>

<!-- Bootstrap core JavaScript -->
<script src="{{ asset('js/jquery.min.js') }}"></script>

<script src="{{ asset('js/jquery.mask.min.js') }}"></script>

<script src="{{ asset('js/bootstrap.min.js') }}"></script>

<script src="{{ asset('/js/fontawesome.min.js') }}" rel="stylesheet"></script>

<script src="{{ asset('js/toastr.min.js') }}"></script>

<script>

    const URL = "{{ url('') }}";

    let cart = {
        products: [],
        value: 0,
        cash: undefined,
        card: undefined,
        store: "{{ $store->id }}"
    }

    startLoading();

    $(document).ready(function() {

        $('.money').mask("#####,##", {reverse: true});


        $('.category').on('click', function() {
            
            getProducts($(this).attr('data-id'), $(this).attr('data-description'));

        });

        $('#checkOut').on('click', function() {

            $(this).prop("disabled",true);
            $(this).html("<i class='fas fa-spinner fa-spin '></i> Finalizando");

            $.ajax({
                method: "post",
                url: `${URL}/pedido`,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: cart
            }).done(function(res) {

                $('.container').hide();

                $('#cartModal').modal('hide');

                $('.success').css('display', 'flex');

                $(this).prop("disabled", false);


            }).fail(function(err) {
                
                showMessage('Falha ao enviar pedido, tente novamente mais tarde!', 'error');

                $(this).prop("disabled", false);
            });
        });


        $('#loading').hide();
        $('.container').show();
    });

    function startLoading() {
        const spinnersArray = ['text-muted', 'text-primary', 'text-success', 'text-info', 'text-warning', 'text-danger', 'text-secondary', 'text-dark'];

        const loadingContainer = $('#loading');

        spinnersArray.forEach(spinner => {
            const spinnerElement = jQuery('<div />', {
                class: `spinner-grow ${spinner}`
            }).appendTo(loadingContainer);
        });
    }

    function getProducts(id, category) {

        const container = jQuery('#categories');

        const data = { category: id };

        $.ajax({
            method: "GET",
            url: `${URL}/produtos`,
            data
        }).done(function(res) {

            const { products } = res;

            container.hide();

            drawProducts(products, category);            

        }).fail(function(err) {
            showMessage('Falha ao carregar as produtos, tente novamente mais tarde!', 'error');
        });

    }

    function drawProducts(products, category) {

        const container = jQuery('#products');

        container.empty();

        const productsList = jQuery('<ul />', {
            class: 'productsList'
        }).appendTo(container);

        const header = jQuery('<div />', {
            class: 'd-flex justify-content-between align-items-center'
        }).appendTo(productsList);
        
        const back = jQuery('<button />', {
            class: 'btn btn-white',
            html: '<i class="fas fa-chevron-left"></i> Voltar categorias',
            style: 'color: rgb(146, 146, 146)'
        }).appendTo(header)

        const productCatergory = jQuery('<h4 />', {
            class: 'text-center mb-0',
            text: category,
            style: 'font-weight: bold'
        }).appendTo(header);

        back.on('click', function() {
            container.hide();

            jQuery('#categories').show();
        });


        products.forEach(product => {
            
            const productItem = jQuery('<li />').appendTo(productsList);

            const containerImageName = jQuery('<div />', {
                class: 'd-flex align-items-center'
            }).appendTo(productItem);

            const productImage = jQuery('<img />', {
                src: `${URL}/${product.image}`,
                alt: product.name
            }).appendTo(containerImageName);

            const containerInfomation = jQuery('<div />', {
                class: 'information ml-1'
            }).appendTo(containerImageName);


            const productName = jQuery('<p />', {
                class: 'product-name',
                text: product.name
            }).appendTo(containerInfomation);

            const productValue = jQuery('<p />', {
                class: 'product-value',
                text: `R$ ${product.value.toLocaleString('pt-BR')}`
            }).appendTo(containerInfomation);

            const btnGroup = jQuery('<div />', {
                class: 'btn-group',
                role: 'group',
                style: 'margin-left: 15px;'
            }).appendTo(productItem);

            const btnRemove = jQuery('<button />', {
                type: 'button',
                class: 'btn btn-danger',
                html: '<i class="fas fa-minus"></i>'
            }).appendTo(btnGroup);

            let prodQtd = 0;

            cart.products.forEach(prod => {

                if(prod.id == product.id) {
                    prodQtd = prod.qtd;
                }

            });

            const qtdProduct = jQuery('<button />', {
                type: 'button',
                class: 'btn btn-white',
                text: prodQtd,
                id: `product-${product.id}`,
                disabled: 'true'
            }).appendTo(btnGroup);

            const btnAdd = jQuery('<button />', {
                type: 'button',
                class: 'btn btn-success',
                html: '<i class="fas fa-plus"></i>'
            }).appendTo(btnGroup);

            btnAdd.on('click', function() {
                setProducts(product, 'add');
            });

            btnRemove.on('click', function() {
                setProducts(product, 'remove');
            });

            
        });
        
        container.show();
    }


    function setProducts(product, action = 'add') {

        if(cart.products.some(productCart => productCart.id === product.id)){
            
            const { products, value } = cart;

            products.forEach((prod, index, object) => {
                if(prod.id == product.id) {
                    if(action == 'add')
                    {
                        prod.qtd++;

                        cart.value += prod.value;

                    } else if(prod.qtd >= 1) {
                        prod.qtd--;

                        cart.value -= prod.value;
                    }

                    if(prod.qtd == 0) {
                        products.splice(index, 1);
                    }
                }

                cart.products = products;

                $(`#product-${prod.id}`).text(prod.qtd);

            });

        } else{

            if(action != 'add') return;

            product.qtd = 1;

            $(`#product-${product.id}`).text(product.qtd);

            cart.products.push(product);

            cart.value += product.value;
        }
    }

    function showCart() {

        const { products, value } = cart;

        const cartListItens = $('.list-cart');

        cartListItens.empty();

        $('.total-value').text(`Total: R$ ${value.toString().replace('.', ',')}`);

        products.forEach(product => {
            
            const productContainer = jQuery('<li />').appendTo(cartListItens);

            const productNameContainer = jQuery('<div />', {
                class: 'd-flex align-items-center',
            }).appendTo(productContainer);

            const productName = jQuery('<span />', {
                html: `${product.qtd}x - <span style="font-size: 18px; font-weight: bold" >${product.name}</span>`,
            }).appendTo(productNameContainer);

            const priceContainer = jQuery('<div />', {
                class: 'price',
            }).appendTo(productContainer);

            // const priceUn = jQuery('<span />', {
            //     text: `${product.qtd}x - ${product.value.toString().replace('.', ',')}`,
            // }).appendTo(priceContainer);

            const priceTl = product.value * product.qtd

            const priceTlText = jQuery('<p />', {
                text: `R$ ${priceTl.toLocaleString('pt-BR')}`,
            }).appendTo(priceContainer);
        });

        $('.form-payment').show();

        if(products.length == 0) {
            $('.form-payment').hide();

            const emptyCart = jQuery('<p />', {
                class: 'w-75 text-center m-auto',
                text: 'Adicione algum item no carrinho para finalizar o pedido.'
            }).appendTo(cartListItens);
        }

        $('#cartModal').modal('show');
    }

    function selectedPayment(type = 'money') {

        $('.cash-payment').hide();
        $('.card-payment').hide();

        if(type == 'money') {
            cashPayment();
        } else {
            cardPayment();
        }

        function cashPayment() {
            $('.cash-payment').show();
            $('#checkOut').prop("disabled",false);

            $('.cash-payment input').val(cart.value)

            $('.cash-payment input').keyup(function() {

                const value = $(this).val();

                const moneyChange = parseFloat(value) - parseFloat(cart.value);

                if(moneyChange >= 0) {                    
                    $('.money-change').text(`(TROCO: R$ ${moneyChange.toLocaleString('pt-BR')}):`);
                    $('#checkOut').prop("disabled",false);
                    
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');

                    cart.card = undefined;
                    cart.cash = $(this).val();
                } else {
                    $('#checkOut').prop("disabled",true);
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                }

            });
        }

        function cardPayment() {
            $('.card-payment').show();
            $('#checkOut').prop("disabled",true);

            $('.card-payment select').change(function() {
                const value = $(this).val();

                if(!value) {
                    $('#checkOut').prop("disabled",true);
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                } else {
                    $('#checkOut').prop("disabled",false);
                    $(this).addClass('is-valid');
                    $(this).removeClass('is-invalid');

                    cart.card = $(this).val();
                    cart.cash = undefined;
                }
            });
        }
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

</body>
</html>