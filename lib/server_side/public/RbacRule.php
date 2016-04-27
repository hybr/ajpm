<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_RbacRule extends Base {
	function __construct() {
		$this->collectionName = 'rbac_rule';
	} /* __construct */
	public $fields = array (
			'module' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'database_domain',
					'foreign_search_fields' => 'name',
					'foreign_title_fields' => 'name',
					'show_in_list' => 1,
					'required' => 1 
			),
			'organization_role' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'organization_role',
					'foreign_search_fields' => 'abbreviation,name',
					'foreign_title_fields' => 'abbreviation,name',
					'show_in_list' => 1,
					'required' => 1 
			),
			'permission' => array (
					'type' => 'list',
					'list_class' => 'RbacPermission',
					'input_mode' => 'selecting',
					'show_in_list' => 1,
					'multiple' => 1,
					'default' => 'Show'
			)
	); /* fields */
	
	public function presentDocument($subTaskKeyToSave, $fields, $doc) {
		$rStr = '';
		
		return $rStr;
	}	
} /* class */
?>
