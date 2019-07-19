<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () {
    return response()->json(['data' => 'working'], 200);
});

$router->group(['namespace' => 'Auth', 'prefix' => 'auth'], function () use ($router) {

    $router->post('/register', ['as' => 'auth.register', 'uses' => 'RegisterController@registerUser']);

    $router->post('/login', ['as' => 'auth.login', 'uses' => 'LoginController@loginUser']);
});

$router->group(['namespace' => 'Catalogue', 'prefix' => 'categories'], function () use ($router) {

    $router->get('/top', 'CategoriesController@topCategories');

    $router->get('/', ['as' => 'categories.index', 'uses' => 'CategoriesController@index']);

    $router->get('/{id}', ['as' => 'categories.show', 'uses' => 'CategoriesController@show']);

    $router->post('/', ['as' => 'categories.create', 'uses' => 'CategoriesController@create']);

    $router->put('/{id}', ['as' => 'categories.update', 'uses' => 'CategoriesController@update']);

    $router->delete('/{id}', ['as' => 'categories.delete', 'uses' => 'CategoriesController@delete']);
});

$router->group(['namespace' => 'Catalogue', 'prefix' => 'products'], function () use ($router) {

    $router->get('/top', 'ProductsController@topProducts');

    $router->get('/', ['as' => 'products.index', 'uses' => 'ProductsController@index']);

    $router->get('/{id}', 'ProductsController@show');

    $router->post('/', ['middleware' => ['auth', 'is.admin', 'add.day'], 'ProductsController@create']);

    $router->put('/{id}', 'ProductsController@update');

    $router->delete('/{id}', 'ProductsController@delete');
});


