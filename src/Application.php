<?php

namespace Opemedios;

use Illuminate\Contracts\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Opemedios\Http\Controllers\FontTVController;
use Opemedios\Http\Controllers\HomeController;
use Opemedios\Http\Controllers\NewController;

class Application
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function run()
    {
        $router = new Router(
            new Dispatcher($this->container),
            $this->container
        );

        $router->get('/panel', HomeController::class . '@index');
        $router->get('/panel/new/add', NewController::class . '@add');
        
        //rutas Fuentes
        $router->get('panel/font/add/font-tv', FontTVController::class . '@add');
        

        $response = $router->dispatch(Request::capture());
        $response->send();
    }
}