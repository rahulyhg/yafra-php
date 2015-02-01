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
	public function getAllUsers()
	{
		try {
			$stmt = $this->conn->query("SELECT `id`, `firstname`, `lastname`, DATE_FORMAT(`joined_at`,'$this->dformat') as `joined_at`, `type` FROM user ORDER BY lastname, firstname");
			$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $users;
		} catch (PDOException $e) {
			$this->errorLog($e->getMessage());
			return null;
		}
	}


	/* ------------- `userdevice` table method ------------------ */

	/**
	 * register user login and apikey (create new userdevice entry)
	 * @param String $regemail email as registered in user table (must be available)
	 * @param String $loginemail email as given by OAuth provider
	 * @param String $apikey OAuth 2 API Key
	 * @return Boolean true or false
	 */
	public
	function registerDevice($regemail, $loginemail, $apikey, $loginprovider, $deviceid, $deviceos)
	{
		if (!$this->validateEmail($regemail))
		{
			$this->errorLog("registerDevice - wrong regemail");
			return false;
		}
		if (!$this->validateEmail($loginemail))
		{
			$this->errorLog("registerDevice - wrong loginemail");
			return false;
		}
		if (!settype($apikey, "string"))
		{
			$this->errorLog("registerDevice - apikey not string");
			return false;
		}
		$user = $this->getUserByEmail($regemail);
		if ($user == null)
		{
			$this->errorLog("registerDevice - user by regemail not found");
			return false;
		}

		try {
			// INSERT INTO `userdevice`(`id`, `deviceid`, `token`, `deviceos`, `parameter`, `user`, `apikey`, `registered`, `loginprovider`, `loginemail`, `logindate`, `loggedin`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12])
			$stmt = $this->conn->prepare("INSERT INTO `userdevice`(`deviceid`, `deviceos`, `user`, `apikey`, `registered`, `loginprovider`, `loginemail`, `loggedin`)
 				VALUES (:deviceid, :deviceos, :user, :apikey, :registered, :loginprovider, :loginemail, :loggedin)");
			$stmt->bindValue(':deviceid', $deviceid, PDO::PARAM_STR);
			$stmt->bindValue(':deviceos', $deviceos, PDO::PARAM_STR);
			$stmt->bindValue(':user', $user->id, PDO::PARAM_INT);
			$stmt->bindValue(':apikey', $apikey, PDO::PARAM_STR);
			$stmt->bindValue(':registered', 1, PDO::PARAM_INT);
			$stmt->bindValue(':loginprovider', $loginprovider, PDO::PARAM_INT);
			$stmt->bindValue(':loginemail', $loginemail, PDO::PARAM_STR);
			$stmt->bindValue(':loggedin', 0, PDO::PARAM_INT);

			$result = $stmt->execute();
			if (!$result) {
				$this->errorLog("registerDevice - stmt is " . implode(":",$stmt->errorInfo()));
				$this->errorLog("registerDevice - most likely APIKEY already defined - must be unique " . $result);
			}
			return $result;
		} catch (PDOException $e) {
			$this->errorLog($e->getMessage());
			return false;
		}
	}

	/**
	 * Fetching all public messages
	 * @param Int Limit result set - 0 to get all
	 * @param Int Messages from which group - FK of messagegroup
	 * @return array|null array of all messages
	 */
	public
	function getAllTokensForGroup($groupid)
	{
		settype($groupid, 'integer');
		try {
			$stmt = $this->conn->query("SELECT umg.group, ud.deviceid, ud.token, ud.deviceos, ud.user, ud.registered, ud.loggedin FROM `usermsggroup` umg LEFT JOIN `userdevice` ud ON ud.user = umg.user WHERE umg.group = {$groupid}");
			$msgs = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$this->errorLog($e->getMessage());
		}
		return $msgs;
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
