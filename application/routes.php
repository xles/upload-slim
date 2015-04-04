<?php
namespace Uploader;

/**
 * GET    /
 * 403 Forbidden
 */
$app->get('/', function() use ($app) {
	err(403);
});

/**
 * POST   /login
 * {
 *     "username": "joe",
 *     "password": "pass"
 * }
 *
 * success:
 * 201 Created
 * {
 *     "token":  "<jwt-token>"
 * }
 *
 * error:
 * 401 Unauthorized
 */
$app->post('/login', function() use ($app) {
	if ($token = Authentication::validateUser()) {
		send($token, 201);
	} else {
		err(403, 'password verification failed');
	}
});

$app->get('/test/:foo', $jwt, function($bar) use ($app) {
	var_dump($app->authToken);
	send(['foo' => $bar]);
});

$app->get('/users', $jwt, function() use ($app) {
	send(User::getUsers());
});



 /**
 * GET    /user
 * 200 OK
 *
 * POST   /user
 * 201 Created
 *
 * PATCH  /user
 * [
 *     { "op": "replace", "path": "/email", "value": "new.email@example.org" }
 * ]
 *
 * DELETE /user
 * 204 No Content
 *
 * GET    /users
 * 200 OK
 *
 * GET    /file/:file
 * 200 OK
 *
 * POST   /file/:file
 * 201 Created
 *
 * PATCH  /file/:file
 * [
 *     { "op": "move", "from": "/a/b/c", "path": "/a/b/d" },
 * ]
 *
 * DELETE /file/:file
 * 204 No Content
 *
 * GET    /files
 * 200 OK
 *
 */
