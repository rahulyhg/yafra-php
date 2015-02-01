<?php
/**
 * Created by PhpStorm.
 * User: mwn
 * Date: 18/11/14
 * Time: 21:58
 */
require_once __DIR__ .'/../backend/DbHandler.php';
require_once __DIR__ .'/../backend/PushHandler.php';

use backend\DbHandler as DbHandler;
use backend\PushHandler as PushHandler;


class testPushHandler extends PHPUnit_Framework_TestCase {

	public function testPushMessage()
	{
		$push = new PushHandler();
		$result = $push->pushGoogle(null, "test chat", 1, 1, "testy");
		$this->assertFalse($result);
		$result = $push->pushApple(null, "test chat", 1, 1, "testy");
		$this->assertFalse($result);
	}

	/*
	 * this will only work if some valid tokens will be in the database - the return should be true
	 */
	public function testPushServer()
	{
		$iosTokens = array();
		$androidTokens = array();
		$db = new DbHandler();
		$push = new PushHandler();
		$message = "phpunit test - test message to group 1";
		$iosToken = false;
		$androidToken = false;

		$tokens = $db->getAllTokensForGroup(1);
		foreach ($tokens as $oneToken) {
			switch ($oneToken["deviceos"]) {
				case "iOS":
					array_push($iosTokens, $oneToken["token"]);
					$iosToken = true;
					break;
				case "Android":
					array_push($androidTokens, $oneToken["token"]);
					$androidToken = true;
					break;
				default:
					break;
			}
		}

		if ($androidToken)
			{
			$result = $push->pushGoogle($androidTokens, $message, 1, 1, "Testy");
			$this->assertTrue($result);
			}
		if ($iosToken)
			{
			$result = $push->pushApple($iosTokens, $message, 1, 1, "Testy");
			$this->assertTrue($result);
			}
		}
}
?>