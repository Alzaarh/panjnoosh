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
    });
    $router->group(['prefix' => '/products'], function () use ($router) {
        $router->get('/', ['as' => 'products.index','uses' => 'ProductsController@index']);
    });
});

$router->group(['prefix' => '/users'], function () use ($router) {
    $router->get('/', ['as' => 'users.index', 'uses' => 'UsersController@index']);
    $router->post('/', ['as' => 'users.create', 'uses' => 'UsersController@create']);
});

$router->post('/contact_us', ['as' => 'contactUs.create', 'uses' => 'ContactUsController@create']);



