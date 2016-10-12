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
			'searchable' => 1,
		),
		'tag_number' => array (
			'type' => 'number',
			'show_in_list' => 1,
			'required' => 1,
			'searchable' => 1,
		),
                'mother' => array (
                        'type' => 'foreign_key',
                        'foreign_collection' => 'animal',
                        'foreign_search_fields' => 'name,tag_number,type',
                        'foreign_title_fields' => 'name,tag_number,type',
                        'show_in_list' => 1,
                        'searchable' => 1,
                ),
                'father' => array (
                        'type' => 'foreign_key',
                        'foreign_collection' => 'animal',
                        'foreign_search_fields' => 'name,tag_number,type',
                        'foreign_title_fields' => 'name,tag_number,type',
                        'show_in_list' => 1,
                        'searchable' => 1,
                ),
		'type' => array (
				'help' => 'Select the gender and type of animal',
				'type' => 'list',
				'list_class' => 'AnimalType',
				'input_mode' => 'selecting',
				'default' => 'Cow',
				'show_in_list' => 1,
				'required' => 1,
				'searchable' => 1,
		),
		'color' => array(),
		'breed' => array(),
		'photo' => array (
				'type' => 'container',
				'show_in_list' => 0,
				'fields' => array (
						'caption' => array (
								'searchable' => 1,
						),
						'file_name' => array (
								'type' => 'file_list',
								'searchable' => 1,
								'required' => 1
						),
						'click_link_url' => array (
								'searchable' => 1,
								'type' => 'url'
						)
				)
		) ,
                'owners' => array (
                        'help' => 'Persons who own this animal',
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
