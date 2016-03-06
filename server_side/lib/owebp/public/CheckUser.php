<?php
require_once DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_CheckUser extends Base {
	function __construct() {
		$this->collectionName = 'user';
	}
	public $fields = array ();

	public function exsistance($urlArgsArray) {
		/* $this->debugPrintArray($_POST); */ 

		$userEmail = '';
		if (isset($_POST ['email_address'])) {
			$userEmail = $_POST ['email_address'];
		}
		if (!$userEmail && isset($_GET ['email_address'])) {
			$userEmail = $_GET ['email_address'];
		}
		
		/* read the record */
		$_SESSION ['user'] = $_SESSION ['mongo_database']->user->findOne ( array (
				'email_address' => $userEmail
		) );
		
		if ($_SESSION['debug']) {
			echo '<pre>SESSION user : '; print_r($_SESSION['user']); echo '</pre>';
		}
				
		/* $this->debugPrintArray($_SESSION ['user']); */
		
		if (empty($_SESSION ['user'])) {
			array_push ( $this->errorMessage, 'User does not exists' );
			unset($_SESSION['user']);
			return $this->showError ();
		}

		if (isset($_SESSION ['user']['verified'])) {
			if ($_SESSION ['user']['verified'] != 1) {
				array_push ( $this->errorMessage, 'User is not verified yet' );
				unset($_SESSION['user']);
				return $this->showError ();
			}
		} else {
			array_push ( $this->errorMessage, $userEmail . 'User needs verification' );
			unset($_SESSION['user']);
			return $this->showError ();
		}
 
		return 'User exists';
	} /* authenticate */
}
?>
