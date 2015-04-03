<?php

require_once('../vendor/autoload.php');
$app = new \Slim\Slim();

$app->response->headers->set('Content-Type', 'application/json');

require_once('stdlib.php');
require_once('middleware.php');

require_once('routes.php');

$app->notFound(function () use ($app) {
    err(404);
});

$app->jwtKey = file_get_contents(__DIR__.'/private.pem');

$app->run();
