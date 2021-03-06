<?php
namespace Uploader;
use Slim\Slim;
use PDO;
use PDOException;
use JWT;

class Authentication {

	public static function createUser()
	{
		$app = Slim::getInstance();
	}

	public static function validateUser()
	{
		$app = Slim::getInstance();

		try {
			$sth = $app->dbh->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
			$sth->bindParam(1, $_POST['username']);
			$sth->execute();
			$user = $sth->fetch();
		} catch (PDOException $e) {
			err(500, $e->getMessage());
		}

		if (password_verify($_POST['password'], $user->password)) {
			if (password_needs_rehash($user->password, PASSWORD_DEFAULT)) {
				$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
				try {
					$sth = $app->dbh->prepare('UPDATE users SET password = ? WHERE id = ?');
					$sth->bindParam(1, $hash);
					$sth->bindParam(2, $user->id);
					$sth->execute();
				} catch (PDOException $e) {
					err(500, $e->getMessage());
				}
			}

			$token = [
			    "iss" => "http://example.org",
			    "aud" => "http://example.com",
			    "exp" => time()+3600,
			    "userId" => $user->id
			];

			return [
				'token' => JWT::encode($token, $app->jwtKey),
				'decoded' => JWT::decode(JWT::encode($token, $app->jwtKey), $app->jwtKey, array('HS256'))
			];
		} else {
			return false;
		}
	}
}
