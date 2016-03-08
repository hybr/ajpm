<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_OrganizationTeam extends Base {
	function __construct() {
		$this->collectionName = 'organization_team';
	} /* __construct */
	public $fields = array (
		'abbreviation' => array (
			'type' => 'string',
			'show_in_list' => 1,
		),
		'name' => array (
			'type' => 'string',
			'help' => 'Name of the organization team',
			'show_in_list' => 1,
			'required' => 1
		),
		'about' => array(),
		'parent_team' => array (
			'type' => 'foreign_key',
			'foreign_collection' => 'organization_team',
			'foreign_search_fields' => 'abbreviation,name',
			'foreign_title_fields' => 'abbreviation,name'
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
		) 
	); /* fields */
} /* class */
?>
