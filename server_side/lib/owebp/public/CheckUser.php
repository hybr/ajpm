<?php
require_once DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_CheckUser extends Base {
	function __construct() {
		$this->collectionName = 'user';
	}
	public $fields = array ();

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
		if (!$userEmail && isset($_GET ['email_address'])) {
			$userEmail = $_GET ['email_address'];
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
			$response['status'] = 'User does not exists';
		} else {
			if (isset($userRecord['verified'])) {
				if ($userRecord['verified'] != 1) {
					$response['status'] = 'User is not verified yet';
				}
			} else {
				$response['status'] = 'User verification field missing';
			}
		}
		
		/**
		 * Return the response
		 */
		if ($response['status'] == 'User exists') {
			$_SESSION ['user'] = $userRecord;
		} else {
			$_SESSION ['user'] = 'NULL';
			array_push ( $this->errorMessage, $response['status'] );
		}

		if ($_SESSION['debug']) {
			echo '<pre>SESSION user : '; print_r($_SESSION['user']); echo '</pre>';
		}
		
		return json_encode($response);
	} /* authenticate */
}
?>
