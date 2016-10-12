<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_ItemOrder extends Base {

	function __construct() {
		$this->collectionName = 'item_order';
	} /* __construct */

	public $fields = array (
		'item' => array (
			'help' => 'Item which will be distributed',
			'type' => 'foreign_key',
			'required' => 1,
			'show_in_list' => 1,
			'foreign_collection' => 'item',
			'foreign_search_fields' => 'title,summary',
			'foreign_title_fields' => 'type,title',
		),
		'order_date' => array (
			'type' => 'date',
			'required' => 1,
			'show_in_list' => 1,
		),
		'order_by' => array (
			'type' => 'foreign_key',
			'foreign_collection' => 'person',
			'foreign_search_fields' => 'name.first,name.middle,name.last,name.suffix',
			'foreign_title_fields' => 'name,gender',
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
		'delivery_location' => array (
			'type' => 'string',
			'required' => 1,
			'show_in_list' => 1,
		),
		'delivery_start_date' => array (
			'help' => 'Date when the distribution will start. If delivery frequency is <ul><li>Month or Quater then day part of this time is used</li><li>Year then month and day part of this time is used</li></ul>',
			'type' => 'date',
			'required' => 1,
		),
		'one_time_delivery' => array (
			'help' => 'If customer want delivery only once then select True',
			'type' => 'list',
			'list_class' => 'Boolean',
			'input_mode' => 'clicking',
			'default' => 'False',
			'required' => 1,
		),		
		'delivery_frequency' => array (
			'type' => 'list',
			'list_class' => 'TimeRepeatFrequency',
			'input_mode' => 'selecting',
			'default' => 'Day',
		),
		'delivery_days' => array (
			'help' => 'If delivery frequency is Week or Working Day then select which days of week',
			'type' => 'list',
			'list_class' => 'Weekday',
			'multiple' => 1,
			'input_mode' => 'clicking',
			'default' => 'Mon',
		),
		'distribution_time' => array (
			'help' => 'If delivery frequency is <ul><li>Hour then minute part of this time is used</li><li>Other then hour and minute part of this time is used</li></ul>',
			'type' => 'time',
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
		'do_ring_bell' => array (
			'help' => 'If customer do not want to be disturbed at delivery time then select False',
			'type' => 'list',
			'list_class' => 'Boolean',
			'input_mode' => 'clicking',
			'default' => 'True',
			'required' => 1,
		),		
		'payment_collection_date' => array (
			'help' => 'Date when the payment will be collected. If delivery frequency is <ul><li>Month or Quater then day part of this time is used</li><li>Year then month and day part of this time is used</li></ul>',
			'type' => 'date',
			'required' => 1,
		),
		'payment_frequency' => array (
			'type' => 'list',
			'list_class' => 'TimeRepeatFrequency',
			'input_mode' => 'selecting',
			'default' => 'Day',
		),
		'instructions' => array (
			'type' => 'string',
		),
		'remote_addresses' => array(
		),
		'distributor_sms_number' => array(
			'help' => 'Comma seperated SMS numbers of this item distributors',
		),
	); /* fields */	

} /* class */
?>
