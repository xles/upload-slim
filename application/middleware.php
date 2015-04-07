<?php
namespace Uploader;
use JWT;

$jwt = function() use ($app) {
	$key = $app->jwtKey;
	if (!isset($app->request->headers['Authorization'])) {
		err(401);
	} else {
		$token = $app->request->headers['Authorization'];
		$token = explode(' ',$token)[1];
		try {
			$app->authToken = JWT::decode($token, $key, array('HS256'));
		} catch (\DomainException $e) {
			err(400, 'DomainException: '.$e->getMessage());
		} catch (\UnexpectedValueException $e) {
			err(400, 'UnexpectedValueException: '.$e->getMessage());
		} catch (\SignatureInvalidException $e) {
			err(400, 'SignatureInvalidException: '.$e->getMessage());
		} catch (\BeforeValidException $e) {
			err(400, 'BeforeValidException: '.$e->getMessage());
		} catch (\ExpiredException $e) {
			err(400, 'ExpiredException: '.$e->getMessage());
		}
	}
};
