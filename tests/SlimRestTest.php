<?php
/**
 * Created by PhpStorm.
 * User: mwn
 * Date: 17/11/14
 * Time: 16:00
 */

//require_once __DIR__ .'/../backend/DbHandler.php';

use backend\DbHandler as DbHandler;

\Slim\Slim::registerAutoloader();

use Slim\Environment;

class SlimRestTest extends PHPUnit_Framework_TestCase
{

	public function request($method, $path, $options = array(), $authflag)
	{
		// Capture STDOUT
		ob_start();

		// Prepare a mock environment
		if ($authflag)
		{
			Environment::mock(array_merge(array(
				'REQUEST_METHOD' => $method,
				'PATH_INFO' => $path,
				'SERVER_NAME' => 'http://192.168.1.1/rest',
				'Authorization' => 'YAFRA xkjdkflkasjf'
			), $options));

		} else {
			Environment::mock(array_merge(array(
				'REQUEST_METHOD' => $method,
				'PATH_INFO' => $path,
				'SERVER_NAME' => 'http://192.168.1.1/rest'
			), $options));
		}

		$app = new \Slim\Slim();
		$this->app = $app;
		$this->request = $app->request();
		$this->response = $app->response();

		// Return STDOUT
		return ob_get_clean();
	}

	public function getAuth($path, $options = array())
	{
		$this->request('GET', $path, $options, true);
	}

	public function get($path, $options = array())
	{
		$this->request('GET', $path, $options, false);
	}

	public function testWrongAccess()
	{
		$this->get('/');
		$this->assertEquals('200', $this->response->status());
	}

	public function testUsers()
	{
		$this->get('/users');
		$this->assertEquals('200', $this->response->status());
		//print_r($this->response->status());
		//print_r($this->response);
	}

}
