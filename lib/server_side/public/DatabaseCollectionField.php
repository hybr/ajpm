<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_DatabaseCollectionField extends Base {
	function __construct() {
		$this->collectionName = 'database_collection_field';
	} /* __construct */
	public $fields = array (
		'form_sequence' => array (
				'type' => 'number',
				'help' => 'When showing the form view what is field sequence number',
				'show_in_list' => 1,
		),
		'list_sequence' => array (
				'type' => 'number',
				'help' => 'When showing the list view what is column sequence number',
				'show_in_list' => 1,
		),
		'collection' => array (
				'type' => 'foreign_key',
				'foreign_collection' => 'database_collection',
				'foreign_search_fields' => 'name,about.domain',
				'foreign_title_fields' => 'name',
				'show_in_list' => 1,
				'required' => 1 
		),
		'parent_field' => array (
				'type' => 'foreign_key',
				'foreign_collection' => 'database_collection_field',
				'foreign_search_fields' => 'collection,parent_field,name',
				'foreign_title_fields' => 'collection,parent_field,name',
				'show_in_list' => 1,
		),			
		'name' => array (
				'help' => 'Field name in collection',
				'show_in_list' => 1,
				'required' => 1 
		),
		'title' => array (
				'help' => 'Title to be shown as label in input form',
		),
		'type' => array (
				'type' => 'list',
				'list_class' => 'FieldType',
				'input_mode' => 'selecting',
				'default' => 'String',
				'required' => 1 
		),
		'foreign_collection' => array (
				'help' => 'If field type is foreign' 
		),
		'foreign_search_fields' => array (
				'help' => 'If field type is foreign. Comma seperated list' 
		),
		'foreign_title_fields' => array (
				'help' => 'If field type is foreign. Comma seperated list' 
		),
		'help' => array (
				'help' => 'Help to be shown along with field' 
		),
		'input_mode' => array (),
		'placeholder' => array (),
		'help' => array (),
		'default' => array (
				'help' => 'Default value',
				'required' => 1 
		),
		'required' => array (
				'help' => 'Is this a required field',
				'type' => 'list',
				'list_class' => 'Boolean',
				'input_mode' => 'clicking',
				'default' => 'False',
				'required' => 1 
		),
		'minlength' => array (),
		'maxlength' => array (),
		'input_tag_length' => array (),
		'select_tag_hight' => array (),
		'list_class' => array (),
		'multiple' => array (),
		'unique' => array (),
		'show_in_list' => array (),
		'default' => array (),
		'sub_tasks' => array (),
	); /* main fields */
} /* class */
?>
