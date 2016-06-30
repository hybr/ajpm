<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class UserLib extends Base {
	function __construct() {
		$this->collectionName = 'user';
	}

	public $fields = array ();

	public function validSessionId($session_id) {
		return preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $session_id) > 0;
	}

	public function emailVarification($emailToCheck, $userRecord) {
		$rStr = 'User exists';
		if (empty($userRecord)) {
			$rStr = 'User ' . $emailToCheck . ' does not exists';
		} else {
			if (isset($userRecord['email_address'])) {
				debugPrintArray($userRecord, 'UserLib:emailVarification:userRecord');
				debugPrintArray($emailToCheck, 'UserLib:emailVarification:emailToCheck');
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
	
	public function passwordVarification(
		$passwordToCheck, $userRecord
	) {
		$rStr = $this->emailVarification($userRecord['email_address'], $userRecord);
		
		if ($rStr == 'User exists') {
			if ($userRecord['password'] == '') {
				$rStr = 'Password is empty';
			} else if ($passwordToCheck != $userRecord['password']) {
				$rStr = 'Wrong password';
			}
		}
		
		if ($rStr == 'User exists') {
			$rStr = 'Password OK';
		}
		return $rStr;
	}	
	
	public function sendAccountVerificationEmailToUser($user) {
		$message = '<html><head><title>Account Verification Email</title></head><body>';
		$message .= '<b>Hello</b>,<br /><p>Please use '. $user['verified'] .' as temporary password.';
		$message .= '</body></html>';

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		$headers .= 'To: '.$user['email_address'].'' . "\r\n";
		$headers .= 'From: noreply@'.$_SESSION['url_domain'].'' . "\r\n";
		$headers .= 'Reply-To: noreply@'.$_SESSION['url_domain'].'' 
			. "\r\n" .  'X-Mailer: PHP/' . phpversion();
		mail($user['email_address'], $_SESSION['url_domain'] . " Please accept your login password", $message, $headers);
	}

	public function randomPassword() {
		$alphabet = 'abcdefghijklmnopqrstuvwxyz!@#$%^&ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); 
		$alphaLength = strlen($alphabet) - 1;
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[$i] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}	
	public function va($urlArgsArray) {
		/* va = varify account */
		/* read the record */

		$user = $_SESSION ['mongo_database']->user->findOne ( array (
			'verified' => $urlArgsArray ['c']
		) );
		if (empty($user)) {
			array_push ( $this->errorMessage, 'Invalid activation code' );
			return $this->showError ();
		}
		if (md5($user['email_address']) !=  $urlArgsArray ['e']) {
			array_push ( $this->errorMessage, 'Invalid activation email' );
			return $this->showError ();
		}
		$user['password'] = $user['verified'];
		$user['verified'] = 1;
		$user['session_id'] = session_id();
		$_SESSION ['mongo_database']->user->save ( $user );
		if ($rStr == ' Saved successfully ') {
			$rStr = "Account verified and password updated";
		}
		return $rStr;
	}

	public function sendActivationEmail($emailAddress) {

		/* read the record */
		$userRecord = $_SESSION ['mongo_database']->user->findOne ( array (
			'email_address' => $emailAddress
		) );

		$rStr = $this->emailVarification($emailAddress, $userRecord);
		
		debugPrintArray($rStr, 'UserLib:sendActivationEmail:rStr emailVerification response');
		if ($rStr == 'User exists' || $rStr == 'User is not verified yet') {
			/* generate a unique code */
			$userRecord['verified'] = $this->randomPassword();
			$userRecord['password'] = md5($userRecord['verified']);
			$rStr = $this->sendAccountVerificationEmailToUser($userRecord);
			$userRecord['verified'] = 1;
			$_SESSION ['mongo_database']->user->save ( $userRecord );
			debugPrintArray($userRecord, 'UserLib:sendActivationEmail:userRecord');
			/* TODO: The password verification process needs to be impeoved. Sending password in email is not good */
		} else {
			array_push ( $this->errorMessage, $emailAddress . ' does not exists' );
		}

		unset($userRecord);
		return $rStr;
	}

}
?>
