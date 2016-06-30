<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "UserLib.php";
class public_UserPassword extends UserLib {

	/* public function forget($urlArgsArray) { */
	public function sae($urlArgsArray) {
		$response = array();
		
		/**
		 * get the email address from post/get
		 */
		$userEmailAddress = getParamValue('e', $urlArgsArray);

		/**
		 *  read the record from database
		 */
		$userRecord = $_SESSION ['mongo_database']->user->findOne ( array (
			'email_address' => $userEmailAddress
		) );

		/**
		 * Verificacion logic
		 */
		$response['status'] .= $this->emailVarification(
			$userEmailAddress, $userRecord
		);
		
		debugPrintArray($response, 'emailVarification response');

		/**
		 * Return the response
		 */
		$sessionId = session_id();
		if ($response['status'] == 'User exists' || $response['status'] == 'User is not verified yet') {

			if ($this->validSessionId($sessionId)) {
				$response['status'] = $this->sendActivationEmail($userEmailAddress);
			} else {
				array_push ( $this->errorMessage, 'Invalid session id' );
			}
		} else {
			$_SESSION ['user'] = 'NULL';
			array_push ( $this->errorMessage, $response['status'] );
		}


		return json_encode($response);

	} /* public function exsistance($urlArgsArray) { */

}
?>
