<?php

use Illuminate\Http\Request;
use Opemedios\Http\Controllers\HomeController;

require_once __DIR__ . '/../vendor/autoload.php';

/*$app = new \Opemedios\Application(
    new \Illuminate\Container\Container()
);

$app->run();*/

$request = Request::capture();
$controller = new HomeController();

$controller->index($request);