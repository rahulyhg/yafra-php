<?php
namespace backend;

use backend\DbConnect as DbConnect;
use PDO as PDO;

/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 *
 * oauthprovider enum 0=debug, 1=google, 2=linkedin, 3=dropbox
 *
 * @author yafra.org, Martin Weber
 * @link www.yafra.org
 */
class DbHandler
{

	private $conn;
	private $dtformat;
	private $dformat;

	function __construct()
	{
		require_once dirname(__FILE__) . '/DbConnect.php';
		// opening db connection
		$db = new DbConnect();
		$this->conn = $db->connect();

		// set date / time format to ISO 8601
		date_default_timezone_set('Europe/Zurich');
		$this->dtformat = "%Y-%m-%dT%H:%i";
		$this->dformat = "%Y-%m-%d";
		// We want the database to handle all strings as UTF-8.
		//$this->conn->query('SET NAMES utf8');
	}

	/**
	 * Fetching all users
	 */
	public function getAllPersons()
	{
		try {
			$stmt = $this->conn->query("SELECT * FROM person ORDER BY name");
			$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $users;
		} catch (PDOException $e) {
			$this->errorLog($e->getMessage());
			return null;
		}
	}




	/* ------------- INTERNAL functions ------------------ */

	/**
	 * get autoincrement id
	 * @return object|null last number
	 */
	public
	function getLastId()
	{
		$lastId = $this->conn->lastInsertId();
		return $lastId;
/*
		$sql = "SELECT LAST_INSERT_ID()";
		try {
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_OBJ);
			$lastId = $result.LAST_INSERT_ID;
			return $lastId;
		} catch (PDOException $e) {
			$this->errorLog($e->getMessage());
			return NULL;
		}
*/
	}

	private function errorLog($message)
	{
		error_log("yafra server PDO MySQL error: " . $message);
	}

	private function validateEmail($email)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return false;
		}
		return true;
	}

}

?>
