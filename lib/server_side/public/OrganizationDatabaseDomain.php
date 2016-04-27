<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_OrganizationDatabaseDomain extends Base {
	function __construct() {
		$this->collectionName = 'organization_database_domain';
	} /* __construct */
	public $fields = array (
		'organization' => array (
			'show_in_list' => 1,
			'help' => 'Organization to which domain is assigned',
			'type' => 'foreign_key',
			'foreign_collection' => 'organization',
			'foreign_search_fields' => 'abbreviation,name',
			'foreign_title_fields' => 'abbreviation,name'
		),
		'domain' => array (
			'show_in_list' => 1,
			'help' => 'The assigned database domain',
			'type' => 'foreign_key',
			'foreign_collection' => 'database_domain',
			'foreign_search_fields' => 'name',
			'foreign_title_fields' => 'name'
		),
	); /* fields */
} /* class */
?>
