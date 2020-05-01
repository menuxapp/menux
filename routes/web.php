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

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function() {
        
        $user = Auth::user();

        return $user;

    });
});
