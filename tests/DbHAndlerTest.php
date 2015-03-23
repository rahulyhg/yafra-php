<?php

require_once __DIR__ .'/../backend/DbHandler.php';

use backend\DbHandler as DbHandler;

/**
 * Class to handle DbHandler tests
 *
 * @author MCB, Martin Weber
 * @link www.maertplatz-clique.ch
 */
class DbHandlerTest extends PHPUnit_Framework_TestCase {


	/*
	 * Array:
	 * dump:     var_dump($result));
	 *	variable: $result["loginemail"];
	 *
	 * Object:
	 * $result->loginemail
	 *
	 * JSON (both array and object)
	 *	json_encode($result);
	 */



	function __construct()
	{
		$this->testlogmail1 = "jon1.demo@gmail.com";
	}


	// ------------------------ Users -----------------------------------

	public function testPersons()
	{
		$db = new DbHandler();

		$result = $db->getAllPersons();
		$this->assertNotNull($result);

	}
}

?>
