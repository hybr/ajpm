<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";

class public_MoneyTransaction extends Base {
		
	function __construct() {
		$this->collectionName = 'money_transaction';
	} /* __construct */

	public $fields = array (
		'money' => array (
			'type' => 'list',
			'list_class' => 'MoneyTransactionType',
			'input_mode' => 'clicking',
			'default' => 'Money Paid',
			'required' => 1,
		),			
		'date' => array (
			'type' => 'date' ,
			'required' => 1,
		),
		'amount' => array (
			'help' => 'Advance amount paid to receive the item',
			'type' => 'number',
			'show_in_list' => 1,
			'required' => 1,
		),
		'amount_currency' => array (
			'type' => 'currency',
			'required' => 1,
			'default' => 'INR',
		),
		'bank_account' => array (
			'help' => 'Bank account which is involved in this transection',
			'type' => 'foreign_key',
			'show_in_list' => 1,
			'foreign_collection' => 'bank_account',
			'foreign_search_fields' => 'number,ifsc',
			'foreign_title_fields' => 'type,title',
		),
		'item' => array (
			'help' => 'Item which is involved in this transection',
			'type' => 'foreign_key',
			'show_in_list' => 1,
			'foreign_collection' => 'item',
			'foreign_search_fields' => 'title,summary',
			'foreign_title_fields' => 'type,title',
		),
		'person' => array (
			'type' => 'foreign_key',
			'foreign_collection' => 'person',
			'foreign_search_fields' => 'name.first,name.middle,name.last,name.suffix',
			'foreign_title_fields' => 'name,gender',
			'show_in_list' => 1,
		),		
		'note' => array (
			'type' => 'string',
		),
	); /* fields */	

} /* class */
?>
