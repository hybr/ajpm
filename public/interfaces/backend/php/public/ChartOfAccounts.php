<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_ChartOfAccounts extends Base {
	function __construct() {
		$this->collectionName = 'chart_of_accounts';
	} /* __construct */

	private $startDate = null;
	private $endDate = null;
	private $showTransections = false;
	public $fields = array (
			'number' => array (
				'type' => 'number',
				'required' => 1, 
				'show_in_list' => 1,
			),
			'title' => array (
				'required' => 1,
				'show_in_list' => 1,
			),
			'summary' => array (),
			'parent_account' => array (
				'type' => 'foreign_key',
				'foreign_collection' => 'chart_of_accounts',
				'foreign_search_fields' => 'number,title,summary',
				'foreign_title_fields' => 'number,title',
				'show_in_list' => 1,
			),
	); /* fields */
	public function presentDocument($subTaskKeyToSave, $fields, $doc) {
		$rStr = '';
		
		$rStr .= '<table class="ui-widget">';
		$rStr .= '<tr><td class="ui-widget-header" colspan="2"><h2>' . $doc ['title'] . '</h2></td></tr>';
		$rStr .= '<tr class="ui-widget-content"><td colspan="2">' . $doc ['summary'] . '</td></tr>';
		$rStr .= '<tr class="ui-widget-content"><td>Number</td><td>' . $doc ['number'] . '</td></tr>';
		if ((isset($doc ['parent_account']) && (string) $doc ['parent_account'] != '')) {
			$parentAccount = getOneDocument ( 'chart_of_accounts', '_id', $doc ['parent_account'] );
			$rStr .= '<tr class="ui-widget-content"><td>Parent Account</td><td>' . $parentAccount ['title'] . '</td></tr>';
		}
		$rStr .= '</table>';
		return $rStr;
	}


	private function getTransectionInfo($amount, $currency, $date, $title, $quantity, $quantityUnit, $personName, $note, $buttons) {
		return 
			number_format($amount,2,'.',',') 
			. ' ' . $currency 
			. ' | '  . date('Y-M-d', $date->sec) 
			. ' | ' . $title
			. ' | ' . $quantity
			. ' | ' . $quantityUnit
			. ' | ' . $personName
			. ' | ' . $note
			. ' | ' . $buttons
		;
	}

	private function printReport() {
		$print = false;
		if (isset($_SESSION['url_args_array']['print'])) {
			$print = true;
		}
		return $print;
	}

	private function getTransectionButtons($id, $collectionName) {
		$buttons = '';
		if (!$this->printReport()) {
			$buttons .= ' | ' . getLink(
				'http://admin.' . $_SESSION['url_domain'] . '/'.$collectionName.'/copy/All?id=' . (string)($id),
				'Copy'
			);
			$buttons .= getLink(
				'http://admin.' . $_SESSION['url_domain'] . '/' . $collectionName . '/update/All?id=' . (string)($id),
				'Edit'
			);
		}
		return $buttons;
	}

	private function getMoneyTransectionInfo($doc, $collectionName) {
		$title = '';
		$date = '';
		$names = '';
		$amount = 0;
		$amountCurrency = 'INR';
		$quantity = '';
		$quantityUnit = '';
		$note = '';

		if ($collectionName == 'animal_event') {
			$date = getRecordFieldValue($doc, 'date', 0);
			$amount = getRecordFieldValue($doc, 'cost', 0);
			$amountCurrency = getRecordFieldValue($doc, 'currency', 'INR');
			$title = getRecordFieldValue($doc, 'type');
			$animal = getRecordFieldValue($doc, 'animal');
			if ($animal != '') {
				$quantity = getAnimalNameUsingId($animal);
			}
			$animal = getRecordFieldValue($doc, 'crossed_by_animal');
			if ($animal != '') {
				$quantityUnit = getAnimalNameUsingId($animal);
			}
			$animal = getRecordFieldValue($doc, 'delivered_animal');
			if ($animal != '') {
				$quantityUnit = getAnimalNameUsingId($animal);
			}
                        $names = getNamesOfPersons($doc, 'providers');
			$note = getRecordFieldValue($doc, 'detail') . ' | ' . getRecordFieldValue($doc, 'medicin');
		} else if ($collectionName == 'worker_salary') {
			$date = getRecordFieldValue($doc, 'paid_date', 0);
			$amount = getRecordFieldValue($doc, 'amount', 0);
			$amountCurrency = getRecordFieldValue($doc, 'amount_currency', 'INR');
			$title = getWorkerSalaryComponentById(getRecordFieldValue($doc, 'component'));
			$quantity = date('Y-M-d', getRecordFieldValue($doc, 'salary_period_start', 0)->sec) . ' to ' 
				. date('Y-M-d', getRecordFieldValue($doc, 'salary_period_end', 0)->sec);
			$names = getPersonNamesById(getRecordFieldValue($doc, 'worker'));
			$note = getRecordFieldValue($doc, 'note');
		} else if ($collectionName == 'money_transaction') {
			$date = getRecordFieldValue($doc, 'date', 0);
			$amount = getRecordFieldValue($doc, 'amount', 0);
			$amountCurrency = getRecordFieldValue($doc, 'amount_currency', 'INR');
			$itemRec = getOneDocument('item', '_id', getRecordFieldValue($doc, 'item'));
			if (!empty($itemRec)) {
				$title = $itemRec['title'];
			}
			$quantity = getRecordFieldValue($doc, 'quantity');
			$quantityUnit = getRecordFieldValue($doc, 'quantity_unit'); 
                        $names = getNamesOfPersons($doc, 'persons');
			$note = getRecordFieldValue($doc, 'note');
		}
		return $this->getTransectionInfo(
			$amount,
			$amountCurrency,
			$date,
			$title,
			$quantity,
			$quantityUnit,
			$names,
			$note,
			$this->getTransectionButtons($doc['_id'], $collectionName)
		);
	}

	private function getTransectionsForItem ($doc) {
		$rStr = '';
		$total = 0;

		/* item accounts */
                $cond = array(
                        '$and' => array (
				array(
					'date' => array('$lte' => $this->endDate)
				),
				array(
					'date' => array('$gte' => $this->startDate)
				),
                                array (
                                        'item_type' => new MongoId ( (string)($doc['_id']) )
                                ),
                                array (
                                        'for_org' => new MongoId ( $_SESSION ['url_domain_org'] ['_id'] )
                                ),
                        )
                );
		debugPrintArray($cond, 'getChartAccountInfo cond');
		$moneyCursor = $_SESSION ['mongo_database']->money_transaction->find($cond);
		$moneyCursor->sort(array('date' => 1));
		foreach($moneyCursor as $mDoc) {
			if (($mDoc['money'] == 'Money Paid' || $mDoc['money'] == 'Money To Be Paid')
				&& (in_array($doc['number'], array(50, 500, 600)) || $doc['number'] > 5000)
			) {
				$mDoc['amount'] = -$mDoc['amount'];
			}
			$total += $mDoc['amount'];
			if ($this->showTransections) {
				$rStr .= '<li>MT: ' . $this->getMoneyTransectionInfo($mDoc, 'money_transaction') . '</li>';
			}
		}

		return array($rStr, $total);

	} /* private function getTransectionsForItem ($doc) */

	private function getTransectionsForMoney ($doc) {
		$rStr = '';
		$total = 0;

		/* money accounts */
                $cond = array(
                        '$and' => array (
				array(
					'date' => array('$lte' => $this->endDate)
				),
				array(
					'date' => array('$gte' => $this->startDate)
				),
                                array (
                                        'money_type' => new MongoId ( (string)($doc['_id']) )
                                ),
                                array (
                                        'for_org' => new MongoId ( $_SESSION ['url_domain_org'] ['_id'] )
                                ),
                        )
                );
		$moneyCursor = $_SESSION ['mongo_database']->money_transaction->find($cond);
		$moneyCursor->sort(array('date' => 1));
		foreach($moneyCursor as $mDoc) {
			if ($mDoc['money'] == 'Money Paid' || $mDoc['money'] == 'Money To Be Paid') {
				$mDoc['amount'] = -$mDoc['amount'];
			}
			$total += $mDoc['amount'];
			if ($this->showTransections) {
				$rStr .= '<li>MT: ' . $this->getMoneyTransectionInfo($mDoc, 'money_transaction') . '</li>';
			}
		}

		return array($rStr, $total);

	} /* private function getTransectionsForMoney ($doc) */


	private function getTransectionsForWorkerSalary ($doc) {  
                $rStr = '';
                $total = 0;
                /* get transections from animal events for purchase/sale/health etc ... */
                $cond = array(
                         '$and' => array (
                                array(
                                        'paid_date' => array('$lte' => $this->endDate)
                                ),
                                array(
                                        'paid_date' => array('$gte' => $this->startDate)
                                ),
                                 array (
                                        'amount' => array('$gte' => 0)
                                 ),
                                 array (
                                         'for_org' => new MongoId ( $_SESSION ['url_domain_org'] ['_id'] )
                                 ),
                         )
                 );

                $workerSalaryCursor = $_SESSION ['mongo_database']->worker_salary->find($cond);
                $workerSalaryCursor->sort(array('paid_date' => 1));
                foreach($workerSalaryCursor as $wsDoc) {
                        if ($wsDoc['amount'] > 0) {
                                $recordIt = 0;
                                        if (in_array($doc['number'], array(7300, 1030))) {
                                                /* 1030 CASH Petty Cash */
                                                /* 410 Return of Purchased Assets */
                                                $recordIt = 1;
						$wsDoc['amount'] = - $wsDoc['amount'];
                                        }
                                if ($recordIt) {
                                        $total += $wsDoc['amount'];
                                        if ($this->showTransections) {
                                                $rStr .= '<li>WS: ' . $this->getMoneyTransectionInfo($wsDoc, 'worker_salary') . '</li>';
                                        }
                                }
                        }
                } /* foreach */

                return array($rStr, $total);

	} /* private function getTransectionsForWorkerSalary ($doc) */

	private function getTransectionsForItemSale ($doc) {  
                $rStr = '';
                $total = 0;
                /* get transections from item order */
                $cond = array(
                         '$and' => array (
                                array(
                                        'paid_date' => array('$lte' => $this->endDate)
                                ),
                                array(
                                        'paid_date' => array('$gte' => $this->startDate)
                                ),
                                 array (
                                        'amount' => array('$gte' => 0)
                                 ),
                                 array (
                                         'for_org' => new MongoId ( $_SESSION ['url_domain_org'] ['_id'] )
                                 ),
                         )
                 );

                $workerSalaryCursor = $_SESSION ['mongo_database']->worker_salary->find($cond);
                $workerSalaryCursor->sort(array('paid_date' => 1));
                foreach($workerSalaryCursor as $wsDoc) {
                        if ($wsDoc['amount'] > 0) {
                                $recordIt = 0;
                                        if (in_array($doc['number'], array(7300, 1030))) {
                                                /* 1030 CASH Petty Cash */
                                                /* 410 Return of Purchased Assets */
                                                $recordIt = 1;
						$wsDoc['amount'] = - $wsDoc['amount'];
                                        }
                                if ($recordIt) {
                                        $total += $wsDoc['amount'];
                                        if ($this->showTransections) {
                                                $rStr .= '<li>WS: ' . $this->getMoneyTransectionInfo($wsDoc, 'worker_salary') . '</li>';
                                        }
                                }
                        }
                } /* foreach */

                return array($rStr, $total);

	} /* private function getTransectionsForWorkerSalary ($doc) */

	private function getTransectionsForAnimalEvents ($doc) { 
		$rStr = '';
		$total = 0;
		/* get transections from animal events for purchase/sale/health etc ... */
                $cond = array(
       	                 '$and' => array (
				array(
					'date' => array('$lte' => $this->endDate)
				),
				array(
					'date' => array('$gte' => $this->startDate)
				),
       	                         array (
					'cost' => array('$gte' => 0)
       	                         ),
       	                         array (
       	                                 'for_org' => new MongoId ( $_SESSION ['url_domain_org'] ['_id'] )
       	                         ),
       	                 )
       	         );
		$animalEventCursor = $_SESSION ['mongo_database']->animal_event->find($cond);
		$animalEventCursor->sort(array('date' => 1));
		foreach($animalEventCursor as $aeDoc) {
			if ($aeDoc['cost'] > 0) {
				$recordIt = 0;
				if ($aeDoc['type'] == 'Sale' || $aeDoc['type'] == 'Returned') {
					/* income */
					if (in_array($doc['number'], array(410, 1030))) {
						/* keep cost as positive as it is income */
						/* 1030 CASH Petty Cash */
						/* 410 Return of Purchased Assets */
						$recordIt = 1;
					}
				} else if ($aeDoc['type'] == 'Purchase') {
					if (in_array($doc['number'], array(1030))) {
						/* 1030 CASH Petty Cash */
						$aeDoc['cost'] = -$aeDoc['cost'];
						$recordIt = 1;
					}
					if (in_array($doc['number'], array(110))) {
						/* 110 Fixed Asset */
						$recordIt = 1;
					}
				} else {
					/* expense */
					if (in_array($doc['number'], array(500, 1030))) {
						/* 1030 CASH Petty Cash */
						/* 500 Cost of Godds Sold */
						$aeDoc['cost'] = -$aeDoc['cost'];
						$recordIt = 1;
					}
				}
				if ($recordIt) {
					$total += $aeDoc['cost'];
					if ($this->showTransections) {
						$rStr .= '<li>AE: ' . $this->getMoneyTransectionInfo($aeDoc, 'animal_event') . '</li>';
					}
				}
			}
		} /* foreach */
		return array($rStr, $total);
	} /* private function getTransectionsForAnimalEvents ($doc) */

	private function getChartAccountInfo($doc) {
		$allRStr = '';
		$allTotal = 0;

		/* each money transection needs to be added two places */
		/* one for item side and one for money side */
		list($rStr, $total) =  $this->getTransectionsForItem($doc);
		$allRStr .= $rStr;
		$allTotal += $total;

		list($rStr, $total) =  $this->getTransectionsForMoney($doc);
		$allRStr .= $rStr;
		$allTotal += $total;

		/* animal event function takes cares for double entry itself */
		list($rStr, $total) =  $this->getTransectionsForAnimalEvents($doc);
		$allRStr .= $rStr;
		$allTotal += $total;

		/* worker salary  function takes cares for double entry itself */
		list($rStr, $total) =  $this->getTransectionsForWorkerSalary($doc);
		$allRStr .= $rStr;
		$allTotal += $total;

		if ($allTotal != 0) {
			$allRStr .= ' ( <b>Transections Total</b>: ' . number_format($allTotal, 2, '.',',') . ' )';
		}
		if ($allRStr != '') {
			$allRStr = '<ol>' . $allRStr . '</ol>';
		}

		return array($doc['number'] . ' ' . $doc['title'] .  $allRStr, $allTotal);
	}

	private function getChartAccountInfoByParentId($pid, $total = 0) {
		$rStr = '';
		$docCursor = getManyDocumentsCursor('chart_of_accounts', 'parent_account', $pid, false, true);
		$docCursor->sort(array('number' => 1));
		foreach($docCursor as $doc) {
			$rv = $this->getChartAccountInfo($doc);
			$prv = $this->getChartAccountInfoByParentId($doc['_id']);
			$total += $rv[1];
			if (in_array($doc['number'], array(2,20,30))) {
				/* 10 Asset - 20 Liability - 30 Equity - 2 Profit & Loss = 0 */
				$prv[1] = -$prv[1];
			}
			$total += $prv[1];
			$rStr .= '<li>' 
				. $rv[0]
				. $prv[0]
			. '</li>';
		}
		if ($total != 0) {
			$rStr .= ' ( <b>Chart Total</b>: ' .  number_format($total,2,'.',',') . ' ) ';
		}
		if ($rStr != '') {
			$rStr = '<ul>' . $rStr . '</ul>';
		}
		return array($rStr, $total);
	}

	public function presentAllDocument($subTaskKeyToSave, $fields, $docCursor) {
		if (isset($_SESSION['url_args_array']['st'])) {
			$this->showTransections = true;
		}
		$sd = date('Y-01-01');
		if (isset($_SESSION['url_args_array']['sd'])) {
			$sd = $_SESSION['url_args_array']['sd'];
		}
		$ed = date('Y-12-31');
		if (isset($_SESSION['url_args_array']['ed'])) {
			$ed = $_SESSION['url_args_array']['ed'];
		}
		debugPrintArray($sd, 'getChartAccountInfo startDate');
		debugPrintArray($ed, 'getChartAccountInfo endDate');
		$this->startDate = new MongoDate(strtotime($sd));
		$this->endDate = new MongoDate(strtotime($ed));
		$rpv = $this->getChartAccountInfoByParentId('57d2e075a934999a068e90ae');

		$monthsStr = '<a href="/chart_of_accounts/presentAll?sd=2016-01-01&ed=2016-01-31&st">2016 Jan</a>';
		$monthsStr .= '<a href="/chart_of_accounts/presentAll?sd=2016-02-01&ed=2016-02-31&st">2016 Feb</a>';
		$monthsStr .= '<a href="/chart_of_accounts/presentAll?sd=2016-03-01&ed=2016-03-31&st">2016 Mar</a>';
		$monthsStr .= '<a href="/chart_of_accounts/presentAll?sd=2016-08-01&ed=2016-08-31&st">2016 Aug</a>';
		$monthsStr .= '<a href="/chart_of_accounts/presentAll?sd=2016-09-01&ed=2016-09-31&st">2016 Sep</a>';
		$monthsStr .= '<a href="/chart_of_accounts/presentAll?sd=2016-02-01&ed=2016-03-31&st">2016</a>';
		return
			$monthsStr  
			. '<br />Start Date: ' . date('r', $this->startDate->sec)
			. '<br />End Date: ' . date('r', $this->endDate->sec)
			. '<hr />All Total: ' . $rpv[1] . ' ' . $rpv[0]
		;
	}
} /* class */
?>
