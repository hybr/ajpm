<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_Animal extends Base {
		
	function __construct() {
		$this->collectionName = 'animal';
	} /* __construct */
	public $fields = array (
		'name' => array (
				'type' => 'string',
				'show_in_list' => 1,
		),
		'self_tag_number' => array (
				'type' => 'string',
				'show_in_list' => 1,
				'required' => 1,
		),
		'mother_tag_number' => array (
				'type' => 'string',
				'show_in_list' => 1,
		),
		'birth_date' => array (
				'type' => 'date' ,
		),
		'purchase_date' => array (
				'type' => 'date' ,
		),
		'purchase_cost' => array (
				'type' => 'number' ,
		),		
		'type' => array (
			'help' => 'Select the gender and type of animal',
			'type' => 'list',
			'list_class' => 'AnimalType',
			'input_mode' => 'selecting',
			'default' => 'Cow',
			'show_in_list' => 1,
			'required' => 1,
		),
		'provider' => array (
				'type' => 'foreign_key',
				'foreign_collection' => 'person',
				'foreign_search_fields' => 'name.first,name.middle,name.last',
				'foreign_title_fields' => 'name,gender',
				'show_in_list' => 1,
		),
		'sale_date' => array (
				'type' => 'date' ,
		),
		'sale_cost' => array (
				'type' => 'number' ,
		),			
	); /* fields */	

} /* class */
?>
