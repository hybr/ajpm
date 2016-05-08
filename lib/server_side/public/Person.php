<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_Person extends Base {
	function __construct() {
		$this->collectionName = 'person';
	} /* __construct */
	public $fields = array (
		'name' => array (
			'type' => 'container',
			'required' => 1,
			'show_in_list' => 1,
			'fields' => array (
				'type' => array (
					'type' => 'list',
					'list_class' => 'PersonNameType',
					'input_mode' => 'clicking',
					'default' => 'Official' 
				),
				'prefix' => array ('show_in_list' => 1,),
				'first' => array ( 'show_in_list' => 1, 'required' => 1 ),
				'middle' => array ('show_in_list' => 1,),
				'last' => array ('show_in_list' => 1,),
				'suffix' => array ('show_in_list' => 1,) 
			) 
		),
		'gender' => array (
			'type' => 'list',
			'list_class' => 'PersonGender',
			'input_mode' => 'clicking',
			'show_in_list' => 1,
			'default' => 'Male' 
		),
		'login_credential' => array (
			'type' => 'container',
			'show_in_list' => 1,
			'fields' => array (
				'primary' => array (
					'type' => 'list',
					'list_class' => 'Boolean',
					'input_mode' => 'clicking',
					'default' => 'False'
				),							
				'email_address' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'user',
					'foreign_search_fields' => 'email_address',
					'foreign_title_fields' => 'email_address,provider',
					'show_in_list' => 1,
				) 
			) 
		),			
		'relative' => array (
			'type' => 'container',
			'fields' => array (
				'relation' => array (
					'type' => 'list',
					'list_class' => 'PersonRelation',
					'input_mode' => 'clicking' 
				),
				'person' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'person',
					'foreign_search_fields' => 'name.first,name.middle,name.last',
					'foreign_title_fields' => 'name,gender' 
				) 
			) 
		),
		'photo' => array (
			'type' => 'container',
			'show_in_list' => 0,
			'fields' => array (
				'caption' => array (),
				'file_name' => array (
					'type' => 'file_list',
					'required' => 1 
				),
				'click_link_url' => array (
					'type' => 'url' 
				) 
			) 
		 ) ,
		'check_duplicate' => array (
			'type' => 'list',
			'list_class' => 'Boolean',
			'input_mode' => 'clicking',
			'default' => 'True'
		),
	); /* fields */
	public function getFullName($type = 'Official', $relatives = false) {
		if (!isset($this->record ['name'])) {
			return 'No name defined';
		}
		$rStr = '';
		foreach ( $this->record ['name'] as $name ) {
			if (isset($name['type']) && $name ['type'] == $type) {
				$rStr .= $name['prefix'] . ' '
					. $name['first'] . ' '
					. $name['middle'] . ' '
					. $name['last'] . ' '
					. $name['suffix'] . ' '
				;
				if ($relatives && isset($this->record ['relative'])) {
					foreach ( $this->record ['relative'] as $relative ) {
						$relativeDoc = $this->getDocumentById('person', (string)$relative['person']);
						foreach ( $relativeDoc['name'] as $relativeName ) {
							if (isset($relativeName ['type']) && $relativeName ['type'] == $type) {
								$rStr .= '<br />' . $relative['relation'] . ': ' 
									. $relativeName['prefix'] . ' '
									. $relativeName['first'] . ' '
									. $relativeName['middle'] . ' '
									. $relativeName['last'] . ' '
									. $relativeName['suffix'] . ' ';
							} /* if ($relativeName ['type'] == $type) */
						} /* foreach ( $relativeDoc['name'] as $relativeName ) */ 
					} /* foreach ( $this->record ['relative'] as $relative ) */
				} /* if ($relatives) */
			} /* if ($name ['type'] == $type) */
		}
		return $rStr;
	} /* public function getFullName($type = 'Official', $relatives = false)  */
	public function getEmailAddress($primary = true) {
		if (!isset($this->record ['login_credential'])) {
			return 'No email defined';
		}
		foreach ( $this->record ['login_credential'] as $loginCredential ) {
			if ($loginCredential['primary'] == $primary) {
				$userDoc = $this->getDocumentById('user', (string)$loginCredential['email_address']);
				return $userDoc['email_address'];
			}
		}
		return '';
	} /* public function getEmailAddress($primary = true) */
	public function getOfficialFullName() {
		return $this->getFullName('Official', false);
	} /* public function getOfficialFullName() */
} /* class */
?>
