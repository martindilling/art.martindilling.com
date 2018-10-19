<?php

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
    return view('welcome');
});

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
//Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
//Route::post('password/reset', 'Auth\ResetPasswordController@reset');



Route::get('/home', 'HomeController@index')->name('home');

// Products
Route::get('products', 'ProductsController@index')->name('products.index');
Route::get('p', function () { return redirect()->route('products.index'); });
Route::get('p/{slug}', 'ProductsController@show')->name('products.show');
Route::post('p/{slug}/buy', 'ProductsController@buy')->name('products.buy');

// Admin area
Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {
    // Products
    Route::get('products', 'Admin\ProductsController@index')->name('admin.products.index');
    Route::get('products/create', 'Admin\ProductsController@create')->name('admin.products.create');
    Route::post('products', 'Admin\ProductsController@store')->name('admin.products.store');
    Route::get('products/{id}', 'Admin\ProductsController@show')->name('admin.products.show');

    // Orders
    Route::get('orders', 'Admin\OrdersController@index')->name('admin.orders.index');
    Route::post('orders/{id}/mark-shipped', 'Admin\OrdersController@markShipped')->name('admin.orders.mark-shipped');
    Route::post('orders/{id}/mark-fulfilled', 'Admin\OrdersController@markFulfilled')->name('admin.orders.mark-fulfilled');
});


