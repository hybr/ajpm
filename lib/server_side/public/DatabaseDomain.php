<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_DatabaseDomain extends Base {
	function __construct() {
		$this->collectionName = 'database_domain';
	} /* __construct */
	public $fields = array (
		'name' => array (
			'type' => 'string',
			'help' => 'Name of the database domain',
			'show_in_list' => 1,
			'required' => 1
		),
		'about' => array(),
		'parent_domain' => array (
			'help' => 'This database domain is part of which other database domain',
			'type' => 'foreign_key',
			'foreign_collection' => 'database_domain',
			'foreign_search_fields' => 'name',
			'foreign_title_fields' => 'name'
		),
		'mandatory' => array (
			'type' => 'list',
			'help' => 'Is thiis will be part of website by default?',
			'list_class' => 'Boolean',
			'input_mode' => 'clicking',
			'show_in_list' => 1,
			'default' => 'False',
                        ),

	); /* fields */
} /* class */
?>
