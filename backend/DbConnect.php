<?php
namespace backend;
use PDO as PDO;

/**
 * Handling database connection
 *
 * @author yafra.org, Martin Weber
 * @link http://www.yafra.org
 */
class DbConnect {

	private $conn;
	private $dbdsn;
	private $dbpwd;
	private $dbuser;

	function __construct() {
		/**
		 * Database configuration
		 */
		$this->dbdsn = 'mysql:host=localhost;dbname=yafra;charset=utf8mb4;';
		$this->dbuser = 'yafraadmin';
		$this->dbpwd = 'yafra';
	}
	
	/**
	* Establishing database connection
	* @return database connection handler
	*/
	function connect() {
		// Connecting to mysql database
		try {
			$this->conn = new PDO($this->dbdsn, $this->dbuser, $this->dbpwd);
			}
		catch (PDOException $exception)
			{
			echo "\nyafra error: database connection error: " . $exception->getMessage();
			}
		
		// returning connection resource
		return $this->conn;
		}

}
?>
