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

$router->group(['namespace' => 'Auth', 'prefix' => 'auth'], function () use ($router) {

    $router->post('/register', ['as' => 'auth.register', 'uses' => 'RegisterController@registerUser']);

    $router->post('/login', ['as' => 'auth.login', 'uses' => 'LoginController@loginUser']);
});
