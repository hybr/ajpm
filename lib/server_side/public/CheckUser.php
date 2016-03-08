<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_CheckUser extends Base {
	function __construct() {
		$this->collectionName = 'user';
	}

	public $fields = array ();

	private function validSessionId($session_id) {
		return preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $session_id) > 0;
	}

	public function exsistance($urlArgsArray) {
		$response = array();
		
		/**
		 * get the email address from post/get
		 */
		$userEmail = '';
		if (isset($_POST ['email_address'])) {
			$userEmail = $_POST ['email_address'];
		}
		if (isset($_GET ['email_address'])) {
			$userEmail = $_GET ['email_address'];
		}
		if (isset($urlArgsArray ['email_address'])) {
			$userEmail = $urlArgsArray ['email_address'];
		}
		
		/**
		 *  read the record from database
		 */
		$userRecord = $_SESSION ['mongo_database']->user->findOne ( array (
				'email_address' => $userEmail
		) );

		/**
		 * Verificacion logic
		 */
		$response['status'] = 'User exists';
		if (empty($userRecord)) {
			$response['status'] = 'User ' . $userEmail . ' does not exists';
		} else {
			if (isset($userRecord['verified'])) {
				if ($userRecord['verified'] != 1) {
					$response['status'] = 'User is not verified yet';
				}
			} else {
				$response['status'] = 'User ' . $userEmail . ' verification field missing';
			}
		}
		
		/**
		 * Return the response
		 */
		if ($response['status'] == 'User exists') {
			if ($this->validSessionId(session_id())) {
				$response ['session_id' ] = session_id();
			}
			$_SESSION ['user'] = $userRecord;
			
			/* Save the login attempt in database */
		} else {
			$_SESSION ['user'] = 'NULL';
			array_push ( $this->errorMessage, $response['status'] );
		}

		return json_encode($response);

	} /* public function exsistance($urlArgsArray) { */
	
	public function password($urlArgsArray) {
		$response = array();
	
		/**
		 * get the email address from post/get
		*/
		$sessionId = '';
		$passwordToCheck = '';
		if (isset($_POST ['session_id'])) {
			$sessionId = $_POST ['session_id'];
			$passwordToCheck = $_POST[ 'password'];
		}
		if (isset($_GET ['session_id'])) {
			$sessionId = $_GET ['session_id'];
			$passwordToCheck = $_GET[ 'password'];
		}
		if (isset($urlArgsArray ['session_id'])) {
			$sessionId = $urlArgsArray ['session_id'];
			$passwordToCheck = $urlArgsArray[ 'password'];
		}
	
		/**
		 *  read the record from database
		 */
		$loginsRecord = $_SESSION ['mongo_database']->logins->findOne ( array (
				'session_id' => $sessionId
		) );
		$userRecord = $_SESSION ['mongo_database']->user->findOne ( array (
				'email_address' => $loginsRecord['email_address']
		) );		
	
		/**
		 * Verificacion logic
		*/
		$response['status'] = 'Password OK';
		if (empty($userRecord)) {
			$response['status'] = 'User ' . $userEmail . ' does not exists';
		} else {
			if (isset($userRecord['verified'])) {
				if ($userRecord['verified'] != 1) {
					$response['status'] = 'User is not verified yet';
				}
			} else {
				$response['status'] = 'User ' . $userEmail . ' verification field missing';
			}
		}
		if ($response['status'] == 'Password OK') {
			if ($userRecord['password'] == '') {
				$response['status'] = 'Password is empty';
			} else if ($passwordToCheck != $userRecord['password']) {
				$response['status'] = 'Password does not match';
			}
		}
	
		
		/**
		 * Return the response
		 */
		if ($response['status'] == 'Password OK') {
			if ($this->validSessionId(session_id())) {
				$response ['session_id' ] = session_id();
			}
			$_SESSION ['user'] = $userRecord;
			
			
		} else {
			$_SESSION ['user'] = 'NULL';
			array_push ( $this->errorMessage, $response['status'] );
		}
	
		return json_encode($response);
	
	} /* public function exsistance($urlArgsArray) { */

}
?>
