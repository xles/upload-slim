<?php

$app->get('/', function() use ($app) {
	err(403);
});

$app->post('/login', function() use ($app) {

	$token = [
	    "iss" => "http://example.org",
	    "aud" => "http://example.com",
	    "exp" => time()+3600
	];

	send([
		'token' => JWT::encode($token, $app->jwtKey),
		'decoded' => JWT::decode(JWT::encode($token, $app->jwtKey), $app->jwtKey, array('HS256'))
	]);
});

$app->get('/test/:foo', $jwt, function($foo) use ($app) {
	var_dump($app->authToken);
	send(['foo' => $foo]);
});


/**
 * /login
 * /user
 * /users
 * /file/:file
 * /files
 *
 */
