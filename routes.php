<?php

declare(strict_types=1);

use Illuminate\Routing\Router;
use Vdlp\RssFetcher\Http\Controllers\FeedController;

/** @var Router $router */
$router = resolve(Router::class);
$router->get('/feeds/{path}', [FeedController::class, 'all'])->where('path', '.*');
