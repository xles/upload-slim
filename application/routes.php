<?php
namespace Uploader;

$app->group('/user', function () use ($app, $jwt) {
	$app->get('/', $jwt, function () use ($app) {
		send(User::getUser());
	});
	$app->patch('/:id', $jwt, function () use ($app) {
		send(User::updateUser());
	});
	$app->delete('/:id', $jwt, function () use ($app) {
		if (!User::isAuthorized(User::LVL_ADMIN))
			err(403);
		send(User::removeUser());
	});

	/**
	 * @api {post} /user/authenticate Authenticate user
	 * @apiName AuthenticateUser
	 * @apiGroup User
	 *
	 * @apiParam {String} username Username of user.
	 * @apiParam {String} password Password of user.
	 *
	 * @apiSuccess {String} token JSON Web Token.
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "token": "eyJhbGciOiJIUzI1NiIsImV4cCI6IiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9.r7cnQt0KDJOya9jiTnRxJbif1c6UYT1_HnfcssmjS30"
	 *     }
	 *
	 * @apiError Unauthorized User authentication failed.
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 401 Unauthorized
	 *     {
	 *       "error": "unauthorized"
	 *     }
	 */
	$app->post('/authentciate', function () use ($app) {
		if ($token = User::authenticateUser()) {
			send($token, 201);
		} else {
			err(403, 'password verification failed');
		}
	});
	$app->get('/list', $jwt, function () use ($app) {
		send(User::getUsers());
	});
	$app->post('/register', function () use ($app) {
		send(User::createUser());
	});
});
$app->group('/file', function () use ($app, $jwt) {
	$app->post('/', $jwt, function () use ($app) {
		send(File::createFile());
	});
	$app->get('/:id', $jwt, function () use ($app) {
		send(File::getFile());
	});
	$app->patch('/:id', $jwt, function () use ($app) {
		send(File::updateFile());
	});
//	$app->put('/:id', $jwt, function () use ($app) {
//		send(File::updateFile());
//	});
	$app->delete('/:id', $jwt, function () use ($app) {
		send(File::removeFile());
	});
	$app->get('/list', $jwt, function () use ($app) {
		send(File::getFiles());
	});
});

$app->group('/files', function () use ($app, $jwt) {
	/**
	 * GET    /files/:user
	 * 403 Forbidden
	 */
	$app->get('/:user', function($user) use ($app) {
		err(403);
	});

	/**
	 * GET    /files/:user/:file+
	 * 200 OK
	 */
	$app->get('/:user/:file+', function($user, $file) use ($app) {
	//	var_dump($app->authToken);
		send([
			'user' => $user,
			'file' => $file
		]);
	});
});

/**
 * POST   /login
 * {
 *     "username": "<username>",
 *     "password": "<password>"
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
	if ($token = User::validateCredentials()) {
		send($token, 201);
	} else {
		err(403, 'password verification failed');
	}
});

 /**
 * GET    /user
 * 200 OK
 */
/**
 * POST   /user
 * 201 Created
 */
/**
 * PATCH  /user
 * [
 *     { "op": "replace", "path": "/email", "value": "new.email@example.org" }
 * ]
 */
/**
 * DELETE /user
 * 204 No Content
 */
/**
 * GET    /users
 * 200 OK
 */
$app->get('/users', $jwt, function() use ($app) {
	send(User::getUsers());
});

/**
 * GET    /file/:file
 * 200 OK
 */
/**
 * POST   /file/:file
 * 201 Created
 */
/**
 * PATCH  /file/:file
 * [
 *     { "op": "move", "from": "/a/b/c", "path": "/a/b/d" },
 * ]
 */
/**
 * DELETE /file/:file
 * 204 No Content
 */
