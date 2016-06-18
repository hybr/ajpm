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

	private function emailVarification($emailToCheck, $userRecord) {
		$rStr = 'User exists';
		if (empty($userRecord)) {
			$rStr = 'User ' . $emailToCheck . ' does not exists';
		} else {
			if (isset($userRecord['email_address'])) {
				if ($emailToCheck != $userRecord['email_address']) {
					$rStr = 'User email is invalid';
				}				
			} else {
				$rStr = 'User ' . $emailToCheck . ' email_address field missing';
			}
			if (isset($userRecord['verified'])) {
				if ($userRecord['verified'] != 1) {
					$rStr = 'User is not verified yet';
				}
			} else {
				$rStr = 'User ' . $emailToCheck . ' verification field missing';
			}
		}
		return $rStr;	
	}
	
	private function passwordVarification(
		$passwordToCheck, $userRecord
	) {
		$rStr = $this->emailVarification($userRecord['email_address'], $userRecord);
		
		if ($rStr == 'User exists') {
			if ($userRecord['password'] == '') {
				$rStr = 'Password is empty';
			} else if ($passwordToCheck != $userRecord['password']) {
				$rStr = 'Password does not match ' . $userRecord['password'];
			}
		}
		
		if ($rStr == 'User exists') {
			$rStr = 'Password OK';
		}
		return $rStr;
	}	
	
	/* public function exsistance($urlArgsArray) { */
	public function e($urlArgsArray) {
		$response = array();
		
		/**
		 * get the email address from post/get
		 */
		$emailToCheck = getParamValue('e', $urlArgsArray);

		/**
		 *  read the record from database
		 */
		$userRecord = $_SESSION ['mongo_database']->user->findOne ( array (
				'email_address' => $emailToCheck
		) );

		/**
		 * Verificacion logic
		 */
		$response['status'] = $this->emailVarification(
			$emailToCheck, $userRecord
		);
		
		/**
		 * Return the response
		 */
		$sessionId = session_id();
		if ($response['status'] == 'User exists') {
			if ($this->validSessionId($sessionId)) {
				
				/* get current server side session id */
				$response ['session_id' ] = $sessionId;
				$_SESSION ['user'] = $userRecord;
				
				/* Save the login attempt in database */
				$userRecord = $_SESSION ['mongo_database']->logins->save (array (
						'session_id' => $sessionId,
						'email_address' => $emailToCheck
				));				
			} else {
				array_push ( $this->errorMessage, 'Invalid session id' );
			}
		} else {
			$_SESSION ['user'] = 'NULL';
			array_push ( $this->errorMessage, $response['status'] );
		}

		return json_encode($response);

	} /* public function exsistance($urlArgsArray) { */
	
	/* public function password($urlArgsArray) { */
	public function p($urlArgsArray) {
		$response = array();
	
		/**
		 * get the email address from post/get
		*/
		$sessionId = getParamValue('s', $urlArgsArray);
		$passwordToCheck = getParamValue('p', $urlArgsArray);

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
		$response['status'] = $this->passwordVarification(
			$passwordToCheck, $userRecord
		);
	
		
		/**
		 * Return the response
		 */
		if ($response['status'] == 'Password OK') {
			$_SESSION ['user'] = $userRecord;
			$_SESSION ['session_id'] = $sessionId;
			
			/**
			 * Read person record for this user and get the roles
			 */
			$personRecord = $_SESSION ['mongo_database']->person->findOne ( array (
					'_id' => $userRecord['person']
			) );
			$response ['email_address'] = $userRecord['email_address'];
			$response ['person_record'] = $personRecord;
		} else {
			$_SESSION ['user'] = 'NULL';
			$_SESSION ['session_id'] = 'NULL';
			array_push ( $this->errorMessage, $response['status'] );
		}
	
		return json_encode($response);
	
	} /* public function exsistance($urlArgsArray) { */

}
?>
