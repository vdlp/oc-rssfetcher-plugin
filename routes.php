<?php

declare(strict_types=1);

use Illuminate\Routing\Router;

/** @var Router $router */
$router = resolve(Router::class);
$router->get('/feeds/{path}', 'Vdlp\RssFetcher\Http\Controllers\FeedController@all');
