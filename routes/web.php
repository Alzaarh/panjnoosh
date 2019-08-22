<?php

$router->group(['namespace' => 'Auth','prefix' => 'auth'], function () use ($router) {
    $router->post('/signup', ['as' => 'auth.signup', 'uses' => 'AuthController@signup']);
    $router->post('/signin', ['as' => 'auth.signin', 'uses' => 'AuthController@signin']);
});

$router->group(['namespace' => 'Catalogue'], function () use ($router) {
    $router->group(['prefix' => '/categories'], function () use ($router) {
        $router->get('/most_viewed',
        ['as' => 'categories.mostViewed', 'uses' => 'CategoriesController@mostViewedCategories']);
        $router->get('/', ['as' => 'categories.index', 'uses' => 'CategoriesController@index']);
        $router->get('/{id}', ['as' => 'categories.show', 'uses' => 'CategoriesController@show']);
        $router->get('/{id}/products', ['as' => 'categories.products', 'uses' => 'CategoriesController@indexProducts']);
        $router->post('/', ['as' => 'categories.create', 'uses' => 'CategoriesController@create']);
        $router->patch('/{id}', ['as' => 'categories.update', 'uses' => 'CategoriesController@update']);
        $router->delete('/{id}', ['as' => 'categories.delete', 'uses' => 'CategoriesController@delete']);
    });
    $router->group(['prefix' => '/products'], function () use ($router) {
        $router->get('/', ['as' => 'products.index','uses' => 'ProductsController@index']);
        $router->get('/{id}', ['as' => 'products.show', 'uses' => 'ProductsController@show']);
        $router->post('/', ['as' => 'products.create', 'uses' => 'ProductsController@create']);
        // $router->patch('/{id}', ['as' => 'products.update', 'uses' => 'ProductsController@update']);
        // $router->delete('/{id}'. ['as' => 'products.delete', 'uses' => 'ProductsController@delete']);
    });
});

$router->group(['prefix' => '/users'], function () use ($router) {
    // $router->get('/', ['as' => 'users.index', 'uses' => 'UsersController@index']);
    // $router->post('/', ['as' => 'users.create', 'uses' => 'UsersController@create']);
    // $router->get('/{id}', ['as' => 'users.show', 'uses' => 'UsersController@show']);
    // $router->patch('/{id}', ['as' => 'users.update', 'uses' => 'UsersController@update']);
    // $router->delete('/{id}', ['as' => 'users.delete', 'uses' => 'UsersController@delete']);
});

$router->post('/contact_us', ['as' => 'contactUs.create', 'uses' => 'ContactUsController@create']);



