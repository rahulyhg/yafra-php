<?php
namespace backend;

/**
 * Class to handle push notifications
 * Supported: APNS, GCM
 *
 * This is very simple to implement APNS and GCM. Please follow the steps and you will feel its so simple.
 *
 * When APNS (iOS Devices) and GCM (Android Device) registers for Push Notification on Apple and Google Server it generates a unique token for every device.
 * After that, you need to save that device token, with your device id or user id (unique id on your server for device) and the OS of device.
 * An iOS device is sending this information on your server (backend) you can use this JSON format- {"token":"abcdedfgehik2bd3d3ff3sffssdff","os":"iOS","userid":34}
 * For android device it will be - {"token":"erydnfbdbdjskd76ndjs7nnshdjs","os":"Android","userid":35}
 * By this you can identify the OS of device, as well as user information and unique token which will be used for sending push notification.
 *
 * @author yafra.org, Martin Weber
 * @link www.yafra.org
 */
class PushHandler
{

	private $pushserverapple;
	private $pushservergoogle;
	private $pushtext;

	function __construct()
	{
	}


	/* ------------- generic functions ------------------ */


	/* ------------- Google ------------------ */

	/**
	 * Push a message to a messaging server
	 * @param Array $tokens an array of registration ID's
	 * @param String $message message to send
	 * @param String $message message to send
	 * @param String $message message to send
	 * @param String $firstName sender's first name
	 * @return boolean User login status success/fail
	 */
	public function pushGoogle($tokens, $message, $grpId, $userId, $firstName)
	{
		if ($tokens == null) {
			return false;
		}

		//Sending Push Notification
		$icon='http://www.yafra.org/images/logo48.jpg';
		$pushMsgId = "YAFRA_" + $grpId + $userId + "_" + rand(0,10000);

		// Set POST variables
		$url = 'https://android.googleapis.com/gcm/send';
		if (strlen($message) > 50)
			$limitedMessage = substr($message, 0, 46) . '...';
		else
			$limitedMessage = $message;
		//$composedMessage = sprintf('%s: %s', $firstName, $limitedMessage);
		$title = sprintf('Chat vom: %s', $firstName);
		$payload = array(
			'message' => $limitedMessage,
			'title' => $title,
			'notId'=>$pushMsgId,
			'icon'=>$icon,
			'group' => $grpId,
			'user' => $userId,
			'username' => $firstName
		);
		$fields = array(
			'registration_ids' => $tokens,
			'data' => $payload
			);

		// add your server push token here
		$headers = array(
			'Authorization: key=xxx',
			'Content-Type: application/json'
			);

		// Open connection
		$ch = curl_init();
		// Set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Disabling SSL Certificate support temporarly
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		// Execute post
		$result = curl_exec($ch);
		if ($result === FALSE) {
			error_log("yafra server push - google error curl: " + curl_error($ch));
			curl_close($ch);
			return false;
			}

		// Close connection
		curl_close($ch);
		return(true);
	}

	/* ------------- Apple ------------------ */

	/**
	 * Push a message to a messaging server
	 * @param Array $tokens an array of registration ID's
	 * @param String $message message to send
	 * @return boolean User login status success/fail
	 */
	public function pushApple($tokens, $message, $grpId, $userId, $firstName)
	{
		if ($tokens == null) {
			return false;
		}
		//Sending Push Notification
		//error_log("yafra server push - sending message: " + $message + " to tokens " + $tokens);

		// Set POST variables
		//$url = 'ssl://gateway.sandbox.push.apple.com:2195';
		//$cert = dirname(__FILE__) . '/yafrapush-dev2015.pem';
		$url = 'ssl://gateway.push.apple.com:2195';
		$cert = dirname(__FILE__) . '/yafrapush-prod2015.pem';
		$passphrase = 'yafraphrase';

		// Create the payload body
		if (strlen($message) > 50)
			$limitedMessage = substr($message, 0, 46) . '...';
		else
			$limitedMessage = $message;
		$composedMessage = sprintf('%s: %s', $firstName, $limitedMessage);
		$body['aps'] = array(
			//'alert' => array(
			//	'body' => $composedMessage
			//),
			'alert' => $composedMessage,
			'badge' => 1,
			'group' => $grpId,
			'user' => $userId,
			'username' => $firstName
		);

		// Create tcp stream
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $cert);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		// Open a connection to the APNS server
		$fp = stream_socket_client($url, $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		if (!$fp) {
			error_log("yafra server push - google error curl: " + curl_error($ch));
			return false;
		}
		// Encode the payload as JSON
		$payload = json_encode($body);
		// Build the binary notification
		foreach ($tokens as $oneToken) {
			$msg = chr(0) . pack('n', 32) . pack('H*', $oneToken) . pack('n', strlen($payload)) . $payload;
			// Send it to the server
			$result = fwrite($fp, $msg, strlen($msg));
			//if (!$result)
			//	return false;
		}
		// Close the connection to the server
		fclose($fp);
		return true;
	}

}

?>
