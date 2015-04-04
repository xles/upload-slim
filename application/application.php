<?php
namespace Uploader;
use Slim\Slim;
use PDO;
use PDOException;

require_once('../vendor/autoload.php');

$app = new Slim();



$app->response->headers->set('Content-Type', 'application/json');

$app->uploaderConfig = [
	'hide' => ['.', '..'],
	'userDir' => '../userfiles'
];

$app->jwtKey = file_get_contents(__DIR__.'/private.pem');

require_once('stdlib.php');
require_once('middleware.php');

require_once('authentication.php');
require_once('user.php');
require_once('file.php');

try {
	$app->dbh = new PDO('pgsql:host=localhost;port=5432;dbname=lhl', 'lhl', '');
	$app->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$app->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch (PDOException $e) {
	err(500, $e->getMessage());
	//$app->response->setStatus(500);
	//die($e->getMessage());
}

require_once('routes.php');

$app->notFound(function () use ($app) {
    err(404);
});

$app->run();
