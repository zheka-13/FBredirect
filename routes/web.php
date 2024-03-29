<?php

/** @var Router $router */

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

use Laravel\Lumen\Routing\Router;

$router->group(['middleware' => 'auth'], function () use ($router) {

    $router->get('/home', [
        "as" => "home", "uses" => 'HomeController@home'
    ]);
    $router->group(['middleware' => 'admin', 'namespace' => 'Admin', 'prefix' => 'admin'], function () use ($router){
        $router->get('/users', [
            "as" => "admin.users", "uses" => 'UsersController@index'
        ]);
        $router->get('/users/add', [
            "as" => "admin.users.add", "uses" => 'UsersController@create'
        ]);
        $router->post('/users/store', [
            "as" => "admin.users.store", "uses" => 'UsersController@store'
        ]);
        $router->post('/users/{user_id}/update', [
            "as" => "admin.users.update", "uses" => 'UsersController@update'
        ]);
        $router->get('/users/{user_id}/edit', [
            "as" => "admin.users.edit", "uses" => 'UsersController@edit'
        ]);
        $router->post('/users/{user_id}/delete', [
            "as" => "admin.users.delete", "uses" => 'UsersController@delete'
        ]);
    });

    $router->group(['namespace' => 'User', 'prefix' => 'user'], function () use ($router){
        $router->get('/links', [
            "as" => "user.links", "uses" => 'LinksController@index'
        ]);
        $router->get('/links/add', [
            "as" => "user.links.add", "uses" => 'LinksController@create'
        ]);
        $router->post('/links/store', [
            "as" => "user.links.store", "uses" => 'LinksController@store'
        ]);
        $router->post('/links/{link_id}/update', [
            "as" => "user.links.update", "uses" => 'LinksController@update'
        ]);
        $router->get('/links/{link_id}/edit', [
            "as" => "user.links.edit", "uses" => 'LinksController@edit'
        ]);
        $router->get('/links/{link_id}/edit_picture', [
            "as" => "user.links.edit_picture", "uses" => 'LinksController@edit_picture'
        ]);
        $router->post('/links/{link_id}/store_picture', [
            "as" => "user.links.store_picture", "uses" => 'LinksController@store_picture'
        ]);
        $router->post('/links/{link_id}/delete', [
            "as" => "user.links.delete", "uses" => 'LinksController@delete'
        ]);

    });
});

$router->get('/', [
    "as" => "root", "uses" => 'RootController@root'
]);

$router->post('/hash-hmac', [
    "as" => "hash-hmac", "uses" => 'HashHmacController@hash_hmac'
]);

$router->get('/login', [
    "as" => "login_form", "uses" => 'LoginController@show_login_form'
]);

$router->post('/login', [
    "as" => "login", "uses" => 'LoginController@login'
]);

$router->get('/logout', [
    "as" => "logout", "uses" => 'LoginController@logout'
]);

$router->get('/redirect/{hash}', [
    "as" => "redirect", "uses" => 'RedirectController@redirect'
]);

$router->get('/img/{hash}', [
    "as" => "image", "uses" => 'RedirectController@image'
]);
