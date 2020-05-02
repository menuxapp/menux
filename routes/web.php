<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Auth::check())
    {
        return redirect('dashboard');
    }

    return redirect('entrar');
});

Route::get('/entrar', function () {
    if(Auth::check())
    {
        return redirect('dashboard');
    }

    return view('login');
});

Route::post('/entrar', 'AuthController@login');

Route::post('/cadastrar', 'AuthController@register');

Route::get('/cadastrar', function () {
    return view('register');
});

Route::get('/sair', function () {
    Auth::logout();
    return redirect('/entrar');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', 'DashboardController@dashboard');

    Route::get('/dashboard/estabelecimento', 'StoreController@show');

    Route::post('/dashboard/estabelecimento', 'StoreController@store');

    Route::put('/dashboard/estabelecimento/{id}', 'StoreController@update');

    Route::get('/dashboard/categorias', function() {
        return view('productCategories');
    });

    Route::get('/categorias', 'ProductCategoriesController@index');

    Route::post('/categorias', 'ProductCategoriesController@store');

    Route::put('/categorias/{id}', 'ProductCategoriesController@update');

    Route::get('/dashboard/produtos', function() {

        $user = Auth::user();

        $categories = $user->ProductCategories;

        $data = array('categories' => $categories);

        return view('products', $data);
    });

    Route::get('/produtos', 'ProductController@index');

    Route::post('/produtos', 'ProductController@store');

    Route::put('/produtos/{id}', 'ProductController@update');

    Route::get('/form', function() {
        return view('form');
    });

});
