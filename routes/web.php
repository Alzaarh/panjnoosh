<?php

$router->group(['namespace' => 'Auth', 'prefix' => '/auth'], function () use ($router) {
    $router->post('/signup', 'AuthController@signup');
    $router->post('/signin', 'AuthController@signin');
});
$router->group(['namespace' => 'Catalogue'], function () use ($router) {
    $router->group(['prefix' => '/categories'], function () use ($router) {
        $router->get('/', 'CategoriesController@index');
        $router->get('/{id}', 'CategoriesController@show');
        $router->get('/{id}/products', 'CategoriesController@indexProducts');
    });
    $router->group(['prefix' => '/products'], function () use ($router) {
        $router->get('/top', 'ProductsController@topProducts');
        $router->get('/', 'ProductsController@index');
        $router->get('/{id}', ['as' => 'products.show', 'uses' => 'ProductsController@show']);
    });
});
$router->group(['namespace' => 'User', 'prefix' => '/users'], function () use ($router) {
    $router->group(['prefix' => '/addresses'], function () use ($router) {

        $router->get('/', 'UserAddressesController@index');

        $router->get('/{id}', 'UserAddressesController@show');

        $router->post('/', 'UserAddressesController@create');

        $router->patch('/{id}', 'UserAddressesController@update');

        $router->delete('/{id}', 'UserAddressesController@delete');
    });

    $router->group(['prefix' => '/purchases'], function () use ($router) {
        $router->get('/', 'PurchasesController@index');
        $router->get('/{id}', 'PurchasesController@show');
        $router->post('/', 'PurchasesController@create');
    });

    $router->get('/', 'UsersController@index');

    $router->get('/me', 'UsersController@showSelf');

    $router->get('/{id}', 'UsersController@show');

    $router->post('/', 'UsersController@create');

    $router->patch('/{id}', 'UsersController@update');

    $router->delete('/{id}', 'UsersController@delete');
});

$router->get('/states', 'ShopInfoController@getStates');

$router->get('/cities', 'ShopInfoController@getCities');
