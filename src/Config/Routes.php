<?php

namespace Raydragneel\HerauthLib\Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

$routes->group('herauth',function($routes){
    $routes->namespace = 'Raydragneel\HerauthLib\Controllers\Api';
    $routes->setPrioritize(true);
    $routes->group('web/{locale}', ['namespace' => $routes->namespace], function ($routes) {
        require __DIR__.'./Routes/ApiRoutes.php';
    });
    $routes->group('api/{locale}', ['namespace' => $routes->namespace], function ($routes) {
        require __DIR__.'./Routes/ApiRoutes.php';
    });
    $routes->setPrioritize(false);
    $routes->namespace = 'Raydragneel\HerauthLib\Controllers';
    $routes->group('', ['namespace' => $routes->namespace], function ($routes) {
        $routes->get('assets/(:any)','Assets::file/$1');
        $routes->addRedirect('', 'herauth/id');
        $routes->group('{locale}', ['namespace' => $routes->namespace,'filter' => 'auth_filter'], function ($routes) {
            $routes->get('logout','Auth::logout');
            $routes->get('login','Auth::login');
            $routes->group('master', ['namespace' => $routes->namespace."\Master",'filter' => 'auth_filter'], function ($routes) {
                $routes->group('group', function ($routes) {
                    $routes->get('','Group::index');
                    $routes->post('list','Group::list');
                });
            });
            $routes->get('','Home::index');
            $routes->get('(:any)','Home::index/$1');
        });
    });
    
});
