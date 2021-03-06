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
                        'show_in_list' => 1,
		),
		'time' => array (
			'type' => 'time' ,
                        'show_in_list' => 1,
		),
                'type' => array (
                        'type' => 'list',
			'required' => 1,
                        'list_class' => 'AnimalEventType',
                        'input_mode' => 'selecting',
                        'show_in_list' => 1,
                        'default' => 'Health',
			'searchable' => 1,				
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
		'crossed_by_animal' => array (
			'help' => 'If this is Got Crossed record add male animal here',
			'type' => 'foreign_key',
			'foreign_collection' => 'animal',
			'foreign_search_fields' => 'name,tag_number,type',
			'foreign_title_fields' => 'name,tag_number,type',
			'searchable' => 1,				
		),
		'delivered_animal' => array (
			'help' => 'If this is Delivered Baby record add child animal here',
			'type' => 'foreign_key',
			'foreign_collection' => 'animal',
			'foreign_search_fields' => 'name,tag_number,type',
			'foreign_title_fields' => 'name,tag_number,type',
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
			'default' => 'INR'
		),
                'providers' => array (
			'help' => 'Persons who helped or worked for this event',
                        'type' => 'container',
                        'fields' => array (
				'name' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'person',
					'foreign_search_fields' => 'name.first,name.middle,name.last',
					'foreign_title_fields' => 'name,gender',
					'searchable' => 1,
				),		
                        )
                ),

	); /* fields */	

} /* class */
?>
