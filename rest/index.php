<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

require_once dirname(__FILE__) . '/../backend/PushHandler.php';
require_once dirname(__FILE__) . '/../backend/DbHandler.php';
//require_once '../include/PassHash.php';
require_once dirname(__FILE__) . '/../vendor/autoload.php';
use backend\DbHandler as DbHandler;
use backend\PushHandler as  PushHandler;

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

// User id from db - Global Variable
$user_id = NULL;

/**
 * Adding Middle Layer to authenticate every request
 * Checking if the request has valid api key in the 'Authorization' header
 */
function authenticate(\Slim\Route $route)
{

	$app = \Slim\Slim::getInstance();
	$response = array();

	// Verifying Authorization Header - over environment as PHP in CGI is cutting Authorization header - see .htaccess rewrite how this gets set
	$apikey = getAPIkey();
	if ($apikey != null) {
		$db = new DbHandler();
		// validating api key
		if ($db->isValidApiKey($apikey)) {
			global $user_id;
			// get user primary key id
			$userdev = $db->getUserDeviceByApiKey($apikey);
			$user_id = $userdev->user;
		} else {
			errorResponse("API key not valid " + var_dump($_SERVER['HTTP_AUTHORIZATION']), 400);
			$app->stop();
		}
	} else {
		errorResponse("Authorization string wrong or empty " + var_dump($_SERVER['HTTP_AUTHORIZATION']), 400);
		$app->stop();
	}
}

/**
 * PUBLIC
 * List all users
 * method GET
 * url /tasks
 */
$app->get('/persons', function () {
	$db = new DbHandler();
	// fetching all user tasks
	$result = $db->getAllPersons(100, 1);
	sendResponse(200, $result);
});

/* --------------------------------------------- internal functions ---------------------------

/**
 * Validating email address
 */
function validateEmail($email)
{
	$app = \Slim\Slim::getInstance();
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$response["error"] = true;
		$response["message"] = 'Email address is not valid';
		sendResponse(400, $response);
		$app->stop();
	}
}

/**
 * Get HTTP Authorization APIKEY
 */
function getAPIkey()
{
	// Getting request headers
	// $headers = apache_request_headers();
	//	if (isset($headers['Authorization'])) {

	// get API KEY via environment variable
	$auth = $_SERVER['HTTP_AUTHORIZATION'];
	if (isset($auth)) {
		$parts = explode(" ", $auth);
		if ($parts[0] == "YAFRA") {
			return ($parts[1]);
		} else {
			error_log ("YAFRA server authorization wrong");
			return null;
		}
	} else {
		error_log ("YAFRA server NO authorization header");
		return null;
	}
}

/**
 * Error message
 * @param Int $code Http response code
 * @param String $message response message - error message
 */
function errorResponse($code, $message)
{
	error_log("YAFRA server error - sending error page: " + $message);
	sendResponse($code, $message);
}

/**
 * Send json response to client
 * @param Int $status_code Http response code
 * @param String $response Json response
 */
function sendResponse($status_code, $response)
{
	$app = \Slim\Slim::getInstance();
	// Http response code
	$app->status($status_code);

	// setting response content type to json
	$app->contentType('application/json');

	echo json_encode($response,JSON_NUMERIC_CHECK);
}

$app->run();
?>
