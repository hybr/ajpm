<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";

class public_ItemPayment extends Base {
		
	function __construct() {
		$this->collectionName = 'item_payment';
	} /* __construct */

	public $fields = array (
		'item' => array (
			'help' => 'Item which will be distributed',
			'type' => 'foreign_key',
			'show_in_list' => 1,
			'foreign_collection' => 'item',
			'foreign_search_fields' => 'title,summary',
			'foreign_title_fields' => 'type,title',
		),
		'paid_as_advance' => array (
			'help' => 'True if amount paid is in advance',
			'type' => 'list',
			'list_class' => 'Boolean',
			'input_mode' => 'clicking',
			'default' => 'True',
			'required' => 1,
		),			
		'paid_amount' => array (
			'help' => 'Advance amount paid to receive the item. Type 0 if not paid as advance.',
			'type' => 'number',
			'show_in_list' => 1,
		),
		'paid_amount_currency' => array (
			'type' => 'currency',
			'default' => 'INR',
		),
		'paid_date' => array (
			'type' => 'date' ,
			'required' => 1,
		),
		'paid_by' => array (
			'type' => 'foreign_key',
			'foreign_collection' => 'person',
			'foreign_search_fields' => 'name.first,name.middle,name.last,name.suffix',
			'foreign_title_fields' => 'name,gender',
			'show_in_list' => 1,
		),		
                'other_amount' => array (
                        'type' => 'container',
			'show_in_list' => 1,
                        'fields' => array (
				'date' => array (
					'type' => 'date' ,
				),
				'received' => array (
					'help' => 'Other carried over amount received from customer',
					'type' => 'number',
				),
				'paid' => array (
					'help' => 'Other amount paid to customer already',
					'type' => 'number',
				),
				'to_be_received' => array (
					'help' => 'Other amount to be received from customer',
					'type' => 'number',
				),
				'to_be_paid' => array (
					'help' => 'Other amount to be paid to customer',
					'type' => 'number',
				),
				'explanation' => array (
				),
                        )
                ),
		'start_date' => array (
			'help' => 'The overall date when the distribution will start' ,
			'type' => 'date' ,
			'required' => 1,
			'show_in_list' => 1,
		),
                'delivery' => array (
                        'type' => 'container',
			'show_in_list' => 1,
                        'fields' => array (
				'start_date' => array (
					'help' => 'Date when the distribution will start for this delivery record' ,
					'type' => 'date' ,
					'required' => 1,
				),
				'end_date' => array (
					'help' => 'Date when the distribution will stop for this delivery record' ,
					'type' => 'date' ,
					'required' => 1,
				),
				'distribution_time' => array (
					'type' => 'time' ,
					'required' => 1,
					'show_in_list' => 1,
				),
				'is_urgent' => array (
					'help' => 'If customer want delivery as soon as possible then select True',
					'type' => 'list',
					'list_class' => 'Boolean',
					'input_mode' => 'clicking',
					'default' => 'False',
					'required' => 1,
				),		
				'do_ring_bell' => array (
					'help' => 'If customer do not want to be disturbed at delivery time then select False',
					'type' => 'list',
					'list_class' => 'Boolean',
					'input_mode' => 'clicking',
					'default' => 'True',
					'required' => 1,
				),		
				'quantity' => array (
					'type' => 'number',
					'required' => 1,
				),
				'quantity_unit' => array (
					'type' => 'string',
					'required' => 1,
					'default' => 'Liter',
				),
				'location' => array (
					'type' => 'string',
					'required' => 1,
				),
				'location_code' => array (
					'help' => 'Code will be used to generate SMS message',
					'type' => 'string',
					'required' => 1,
					'show_in_list' => 1,
				),
                        )
                ),
		'instructions' => array (
			'type' => 'string',
		),
		'recreate_records' => array (
			'help' => 'Recreate records once for past dates',
			'type' => 'list',
			'list_class' => 'Boolean',
			'input_mode' => 'clicking',
			'default' => 'False',
			'required' => 1,
		),
		'distribution_complete' => array (
			'help' => 'True if distribution is complete',
			'type' => 'list',
			'list_class' => 'Boolean',
			'input_mode' => 'clicking',
			'default' => 'False',
			'required' => 1,
			'show_in_list' => 1,
		),			
		'remote_addresses' => array(
		),
		'distributor_sms_number' => array(
			'help' => 'Comma seperated SMS numbers of this item distributors'
		),
	); /* fields */	

} /* class */
?>
