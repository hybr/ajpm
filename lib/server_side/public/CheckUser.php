<?php
require_once DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_CheckUser extends Base {
	function __construct() {
		$this->collectionName = 'user';
	}

	public $fields = array ();

	private function validSessionId($session_id) {
		return preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $session_id) > 0;
	}

	public function exsistance($urlArgsArray) {
		/* $this->debugPrintArray($_POST); */ 

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
		} else {
			$_SESSION ['user'] = 'NULL';
			array_push ( $this->errorMessage, $response['status'] );
		}

		return json_encode($response);

	} /* public function exsistance($urlArgsArray) { */
}
?>
