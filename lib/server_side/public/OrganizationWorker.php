<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_OrganizationWorker extends Base {
	function __construct() {
		$this->collectionName = 'organization_worker';
	} /* __construct */
	public $fields = array (
		'person' => array (
			'type' => 'foreign_key',
			'foreign_collection' => 'person',
			'foreign_search_fields' => 'name.first,name.middle,name.last',
			'foreign_title_fields' => 'name,gender' 
		),
		'position' => array (
			'type' => 'foreign_key',
			'foreign_collection' => 'rbac_rule',
			'foreign_search_fields' => 'module,organization_role,permission',
			'foreign_title_fields' => 'module,organization_role,permission' 
		),
	); /* fields */
} /* class */
?>
