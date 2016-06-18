<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_Logins extends Base {
	function __construct() {
		$this->collectionName = 'logins';
	}
	public $fields = array (
		'email_address' => array (
			'type' => 'email',
			'placeholder' => 'Email Address',
			'required' => 1,
			'unique' => 1,
			'show_in_list' => 1 
		),
		'session_id' => array (
			'type' => 'string',
			'placeholder' => 'Browser session id',
			'required' => 1
		)
	);
	
} /* class public_Logins */
?>
