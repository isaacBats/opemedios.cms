<?php

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Opemedios\Http\Controllers\HomeController;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new \Opemedios\Application(
    new \Illuminate\Container\Container()
);

$app->run();

/*$container = new Container();

$router = new \Illuminate\Routing\Router(
	new  \Illuminate\Events\Dispatcher($container),
	$container
);

$router->get('/', HomeController::class . '@index');
$response = $router->dispatch(Request::capture());
$response->send();*/



/*$request = Request::capture();
$controller = new HomeController();

$controller->index($request);*/