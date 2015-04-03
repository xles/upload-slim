<?php
class Authentication {

	private $app;

	public function __construct()
	{
		$this->app = \Slim\Slim::getInstance();
	}
	

	public function validateToken()
	{
		return $token;
	}
}
