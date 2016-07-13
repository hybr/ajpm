<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "UserLib.php";
class public_UserJoin extends UserLib {

	public function add($urlArgsArray) {
		$response = array();
		
		/**
		 * get the email address from post/get
		 */
		$userEmailAddress = getParamValue('e', $urlArgsArray);
		$userPassword = getParamValue('p', $urlArgsArray);
		$userName = getParamValue('n', $urlArgsArray);

		/**
		 *  read the record from database
		 */
		$userRecord = $_SESSION ['mongo_database']->user->findOne ( array (
			'email_address' => $userEmailAddress
		) );

		/**
		 * Verificacion logic
		 */
		$response['status'] = $this->emailVarification(
			$userEmailAddress, $userRecord
		);
		
		debugPrintArray($response, 'emailVarification response');

		/**
		 * Return the response
		 */
/*
		if ($response['status'] == 'User exists') {
			$response['status'] = $userEmailAddress	+ ' already exists';
			array_push ( $this->errorMessage, $response['status'] );
		} else if ($response['status'] == 'User is not verified yet') {
			$response['status'] = $userEmailAddress	+ ' already exists and not verified by email';
			array_push ( $this->errorMessage, $response['status'] );
		} else {
*/

			/* save the new user account in user table and person table */


			/* Create person record */
			$name =explode(" ", $userName); 
			$personRecord = array();
			if (array_key_exists(0, $name)) {
				$personRecord['name']['first'] = $name[0];
			}; 
			if (array_key_exists(1, $name)) {
				$personRecord['name']['last'] = $name[1];
			}
			if (array_key_exists(2, $name)) {
				$personRecord['name']['middle'] = $name[1];
				$personRecord['name']['last'] = $name[2];
			}; 
			$personRecord['login_credential']['primary'] = 'True';
			$personRecord['check_duplicate'] = 'True';

			debugPrintArray($personRecord, 'personRecord');
			$WriteResultPerson = $_SESSION ['mongo_database']->person->save ( $personRecord);
			$newPersonId = $personRecord['_id'];

			debugPrintArray($WriteResultPerson, 'WriteResultPerson');
			debugPrintArray($newPersonId, 'newPersonId');
			/* Create user record */
			$userRecord = array(
				'email_address' => $userEmailAddress,
				'password' => $userPassword,
				'provider' => 'Local',
				'verified' => 1,
				'person' => new MongoId((string)(trim($newPersonId))) 
			);
			$WriteResultUser = $_SESSION ['mongo_database']->user->save ( $userRecord );
			$newUserId = $userRecord['_id'];
			debugPrintArray($newUserId, 'newUserId');
			debugPrintArray($WriteResultUser, 'WriteResultUser');


			/* Update the person record with user ID */
			$personRecord['login_credential']['email_address'] = new MongoId((string)(trim($newUserId))) ;
			$WriteResultPerson = $_SESSION ['mongo_database']->person->save ( $personRecord);

			/* $response['status'] = $this->sendActivationEmail($userEmailAddress); */
			$response['status'] = 'Account created successfully';
/*
			$response = array (
				'WriteResultPerson' => $WriteResultPerson,
				'WriteResultUser' => $WriteResultUser
			);
*/
			debugPrintArray($response, 'account add response');
/*		} */


		return json_encode($response);

	} /* public function exsistance($urlArgsArray) { */

}
?>
