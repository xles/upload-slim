<?php
function send($arr)
{
	echo json_encode($arr, JSON_PRETTY_PRINT);
}

function err($err, $msg = false)
{
	$app = \Slim\Slim::getInstance();
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
			if ($msg)
				$r = ['error' => $msg];
			else
				$r = ['error' => 'no idea'];
			break;
	}
	$app->halt($err, json_encode($r, JSON_PRETTY_PRINT));
}
