<?php
namespace Uploader;
use Slim\Slim;

function send($arr, $status = false)
{
	$app = Slim::getInstance();
	if ($status)
		$app->response->setStatus($status);
	echo json_encode($arr, JSON_PRETTY_PRINT);
}

function err($err, $msg = false)
{
	$app = Slim::getInstance();
	switch ($err) {
		case 401:
			$r = ['error' => 'unauhtorized'];
			break;
		case 403:
			$r = ['error' => 'forbidden'];
			break;
		case 404:
			$r = ['error' => 'not found'];
			break;
		default:
			$r = ['error' => 'no idea'];
			break;
	}
	if ($msg)
		$r = ['error' => $msg];
	try {
		$app->halt($err, json_encode($r, JSON_PRETTY_PRINT));
	} catch (\Exception $e) {
		http_response_code($err);
		die (json_encode($r, JSON_PRETTY_PRINT));
	}
}

function dbQuery($query, $params = false)
{
	$app = Slim::getInstance();

	try {
		$sth = $app->dbh->prepare($query);

		if ($params) {
			foreach ($params as $key => $value) {
				if (is_numeric($key))
					$key = (int) $key+1;
				$sth->bindParam($key, $value);
			}
		}
		$sth->execute();
		return $sth->fetchAll();
	} catch (\PDOException $e) {
		err(500, $e->getMessage());
	}

}
