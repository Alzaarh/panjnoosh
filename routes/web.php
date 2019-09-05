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

        $router->post('/', 'CategoriesController@create');

        $router->patch('/{id}', 'CategoriesController@update');

        $router->delete('/{id}', 'CategoriesController@delete');
    });

    $router->group(['prefix' => '/products'], function () use ($router) {
        $router->get('/top', 'ProductsController@topProducts');
        $router->get('/', 'ProductsController@index');
        $router->get('/{id}', ['as' => 'products.show', 'uses' => 'ProductsController@show']);

        $router->post('/', 'ProductsController@create');

        $router->patch('/{id}', 'ProductsController@update');

        $router->delete('/{id}', 'ProductsController@delete');
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

    $router->get('/me', 'UsersController@showSelf');

    $router->patch('/me', 'UsersController@updateSelf');

    $router->post('/me/profile', 'UsersController@updateProfilePicture');

    $router->get('/', 'UsersController@index');

    $router->get('/{id}', 'UsersController@show');

    $router->post('/', 'UsersController@create');

    $router->patch('/{id}', 'UsersController@update');

    $router->delete('/{id}', 'UsersController@delete');
});

$router->group(['prefix' => '/states'], function () use ($router) {
    $router->get('/', 'StatesController@index');

    $router->get('/{id}', 'StatesController@show');

    $router->post('/', 'StatesController@create');

    $router->patch('/{id}', 'StatesController@update');

    $router->delete('/{id}', 'StatesController@delete');
});

$router->group(['prefix' => '/cities'], function () use ($router) {
    $router->get('/', 'CitiesController@index');

    $router->get('/{id}', 'CitiesController@show');

    $router->post('/', 'CitiesController@create');

    $router->patch('/{id}', 'CitiesController@update');

    $router->delete('/{id}', 'CitiesController@delete');
});

$router->group(['prefix' => '/orders', 'namespace' => 'User'],
    function () use ($router) {
        $router->get('/', 'OrdersController@index');

        $router->get('/{id}', 'OrdersController@show');
    });
