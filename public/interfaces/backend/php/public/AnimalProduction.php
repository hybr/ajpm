<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_AnimalProduction extends Base {
		
	function __construct() {
		$this->collectionName = 'animal_production';
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
                        'list_class' => 'AnimalProductionType',
                        'input_mode' => 'selecting',
                        'show_in_list' => 1,
                        'default' => 'Milk',
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
		'quantity' => array (
			'type' => 'number',
			'show_in_list' => 1,
		),
		'quantity_unit' => array (
			'type' => 'string',
			'show_in_list' => 1,
		),			
                'worker' => array (
			'help' => 'Persons who helped or worked for this production',
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
                'used_for' => array (
			'help' => 'This product of animal will be used for making of following items',
                        'type' => 'container',
                        'fields' => array (
                		'item' => array (
		                        'help' => 'Item which will be produced from this product',
		                        'type' => 'foreign_key',
		                        'show_in_list' => 1,
		                        'foreign_collection' => 'item',
		                        'foreign_search_fields' => 'title,summary',
		                        'foreign_title_fields' => 'type,title',
		                ),
                        )
                ),


	); /* fields */	

} /* class */
?>
