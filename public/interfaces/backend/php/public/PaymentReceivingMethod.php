<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_PaymentReceivingMethod extends Base {
	function __construct() {
		$this->collectionName = 'payment_receiving_method';
	} /* __construct */
	public $dataAccess = array (
		/* table */	
		1/*0*/,1/*1*/,1/*2*/,/*read yes, write yes, present yes for person's own at table level */
		1/*3*/,1/*4*/,1/*5*/,/*read yes, write yes, present yes for org's own at table level */
		1/*6*/,0/*7*/,1/*8*/,/*read no, write no, present no for public at table level */
		/* record */	
		1/*9*/,1/*10*/,1/*11*/,/*read yes, write yes, present yes for person's own at record level */
		1/*12*/,1/*13*/,1/*14*/,/*read yes, write yes, present yes for org's own at record level */
		1/*15*/,0/*16*/,1/*17*/,/*read no, write no, present no for public at record level */			
	);
	
	public $fields = array (
		'by' => array (
			'type' => 'list',
			'list_class' => 'PaymentReceivingOption',
			'input_mode' => 'clicking',
			'show_in_list' => 1,
			'default' => 'Cash',
		),		
		'note' => array (
			'help' => 'This is needed to view the daily distribution report of this item',
			'show_in_list' => 1,
			'required' => 1,
		),
	); /* fields */
	
} /* class */
?>
