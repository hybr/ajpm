<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class RbacPermission extends Base {
	public $titleValueConversionRequired = 0;
	public $fields = array (
			'value' => array (
					'type' => 'integer',
					'required' => 1 
			),
			'title' => array (
					'type' => 'text',
					'required' => 1 
			) 
	);
	public $dataLocation = DATA_LOCATION_SERVER_CODE;
	public $table = array (
			array (
					'value' => 'All',
					'title' => 'All' 
			),
			array (
					'value' => 'Create',
					'title' => 'Create' 
			),
			array (
					'value' => 'Remove',
					'title' => 'Remove' 
			),
			array (
					'value' => 'Update',
					'title' => 'Update'
			),
			array (
					'value' => 'List',
					'title' => 'List'
			),
			array (
					'value' => 'Show',
					'title' => 'Show'
			),
			array (
					'value' => 'Present',
					'title' => 'Present'
			),
			array (
					'value' => 'Present All',
					'title' => 'Present All'
			),
			array (
					'value' => 'Present Json',
					'title' => 'Present Json'
			)
	);
}
?>