<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_DatabaseCollection extends Base {
	function __construct() {
		$this->collectionName = 'database_collection';
	} /* __construct */
	public $fields = array (
		'name' => array (
			'type' => 'string',
			'help' => 'Name of the database collection in database',
			'show_in_list' => 1,
			'required' => 1,
		),
		'about' => array(),
                'domain' => array (
                        'type' => 'container',
                        'required' => 1,
                        'fields' => array (
							'name' => array (
								'help' => 'The database domain it belongs to',
								'type' => 'foreign_key',
								'foreign_collection' => 'database_domain',
								'foreign_search_fields' => 'name',
								'foreign_title_fields' => 'name'
							),
                        ),
                ),
                'field' => array (
                        'type' => 'container',
                        'required' => 1,
                        'fields' => array (
						'title' => array(),
                        'type' => array (
                        	'type' => 'list',
                            'list_class' => 'FieldType',
                            'input_mode' => 'selecting',
                            'default' => 'String'
                        ),
						'input_mode' => array(),
						'placeholder' => array(),
						'help' => array(),
						'name' => array(),
						'value' => array(),
						'required' => array(),
						'minlength' => array(),
						'maxlength' => array(),
						'input_tag_length' => array(),
						'select_tag_hight' => array(),
						'list_class' => array(),
						'multiple' => array(),
						'unique' => array(),
						'show_in_list' => array(),
						'default' => array(),
						'sub_tasks' => array(),
						'is_search_field_level1_embaded' => array(),
						'foreign_collection' => array(),
						'foreign_search_fields' => array(),
						'foreign_title_fields' => array(),
						'sub_tasks' => array(),
				                        )
                ),
	); /* fields */
} /* class */

/*
                        'type' => 'string',
                        'input_mode' => 'typeing', /* clicking selecting
                        'sub_tasks' => array (
                                        'All'
                        ),
*/
?>
