<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_OrganizationWorker extends Base {
	function __construct() {
		$this->collectionName = 'organization_worker';
	} /* __construct */
	public $fields = array (
		'person' => array (
			'help' => 'Name of worker',
			'type' => 'foreign_key',
			'foreign_collection' => 'person',
			'foreign_search_fields' => 'name.first,name.middle,name.last',
			'foreign_title_fields' => 'name,gender',
			'show_in_list' => 1,
		),
		'position' => array (
			'help' => 'Create multiple records for more than one position/rule of a person',
			'type' => 'foreign_key',
			'foreign_collection' => 'rbac_rule',
			'foreign_search_fields' => 'name,permission',
			'foreign_title_fields' => 'name,permission',
			'show_in_list' => 1,
		),
		'location' => array (
			'type' => 'container',
			'required' => 1,
			'show_in_list' => 1,
			'fields' => array (
				'seat' => array (
					'help' => 'Add one or more seat for above position',
					'type' => 'foreign_key',
					'foreign_collection' => 'organization_working_seat',
					'foreign_search_fields' => 'floor,room,seat',
					'foreign_title_fields' => 'floor,room,seat',
					'required' => 1,
					'show_in_list' => 1,
				)
			),
			
				
		),
		'service_hours' => array (
			'help' => 'Hours when worker is reporting to work for the above role',
			'type' => 'container',
			'show_in_list' => 0,
			'fields' => array (
				'every' => array (
					'type' => 'number' ,
					'required' => 1,
				),
				'frequency' => array (
					'type' => 'list',
					'list_class' => 'TimeRepeatFrequency',
					'input_mode' => 'selecting',
					'default' => 'Day',
					'required' => 1,
				),
				'start_date' => array (
					'type' => 'date' ,
					'required' => 1,
				),
				'start_time' => array (
					'type' => 'time' ,
					'required' => 1,
				),
				'duration' => array (
					'type' => 'number' ,
					'required' => 1,
				),
				'duration_unit' => array (
					'type' => 'list',
					'list_class' => 'TimeRepeatFrequency',
					'input_mode' => 'selecting',
					'default' => 'Hour',
					'required' => 1,
				),
				'end_date' => array (
					'type' => 'date' ,
					'required' => 1,
				),
				'end_time' => array (
					'type' => 'time' ,
					'required' => 1,
				),
			)
		),			
	); /* fields */
} /* class */
?>
