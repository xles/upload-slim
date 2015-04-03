<?php

$jwt = function() use ($app) {
	$key = $app->jwtKey;
	if (!isset($app->request->headers['Authorization'])) {
		err(401);
	} else {
		$token = $app->request->headers['Authorization'];
		$token = explode(' ',$token)[1];
		try {
			$app->authToken = JWT::decode($token, $key, array('HS256'));
			var_dump($app->authToken);
		} catch (DomainException $e) {
			err(500, 'DomainException: '.$e->getMessage());
		} catch (UnexpectedValueException $e) {
//			err(500, 'UnexpectedValueException: '.$e->getMessage());
		} catch (SignatureInvalidException $e) {
			err(500, 'SignatureInvalidException: '.$e->getMessage());
		} catch (BeforeValidException $e) {
			err(500, 'BeforeValidException: '.$e->getMessage());
		} catch (ExpiredException $e) {
			err(500, 'ExpiredException: '.$e->getMessage());
		}
	}
};
