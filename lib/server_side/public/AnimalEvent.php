<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_AnimalEvent extends Base {
		
	function __construct() {
		$this->collectionName = 'animal_event';
	} /* __construct */
	public $fields = array (
		'date' => array (
			'type' => 'date' ,
			'required' => 1,
		),
		'time' => array (
			'type' => 'time' ,
			'required' => 1,
		),
		'animal' => array (
			'type' => 'foreign_key',
			'foreign_collection' => 'animal',
			'foreign_search_fields' => 'name,tag_number,type',
			'foreign_title_fields' => 'name,tag_number,type',
			'show_in_list' => 1,
			'required' => 1,
			'searchable' => 1,				
		),
		'detail' => array (
			'type' => 'string',
			'searchable' => 1,
			'show_in_list' => 1,
		),
		'medicin' => array (
			'type' => 'string',
			'searchable' => 1,
			'show_in_list' => 1,
		),			
		'cost' => array (
			'type' => 'number',
		),
		'currency' => array (
			'type' => 'currency',
			'required' => 1,
			'default' => 'INR'
		),
		'provider' => array (
			'help' => 'Person who helped or worked for this event',
			'type' => 'foreign_key',
			'foreign_collection' => 'person',
			'foreign_search_fields' => 'name.first,name.middle,name.last',
			'foreign_title_fields' => 'name,gender',
			'searchable' => 1,
		),		
	); /* fields */	

} /* class */
?>
