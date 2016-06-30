<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "UserLib.php";
class public_CheckUser extends UserLib {

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
			$_SESSION['person'] = $_SESSION ['mongo_database']->person->findOne ( array (
				'_id' => $userRecord['person']
			) );
			$_SESSION['org_worker'] = $_SESSION ['mongo_database']->organization_worker->findOne ( array (
				'person' => $userRecord['person']
			) );
			$response ['email_address'] = $userRecord['email_address'];
			$response ['person_record'] = $_SESSION['person'];
			$response ['org_worker_record'] = $_SESSION['org_worker'];
		} else {
			$_SESSION ['user'] = 'NULL';
			$_SESSION ['session_id'] = 'NULL';
			array_push ( $this->errorMessage, $response['status'] );
		}
	
		return json_encode($response);
	
	} /* public function exsistance($urlArgsArray) { */

}
?>
