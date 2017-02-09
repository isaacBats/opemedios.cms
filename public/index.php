<?php

use Illuminate\Container\Container;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new \Opemedios\Application(
    new \Illuminate\Container\Container()
);

$app->run();
