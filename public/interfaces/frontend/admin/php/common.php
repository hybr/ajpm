<?php

/**
 * Common function used by other php files
 */

/**
 * Convert a string in to title format
 * @param string $title string to be converted
 * @return string converted string in title format
 */

/* constants */
$currentDate = new DateTime('today');
$hours_in_day   = 24;
$minutes_in_hour= 60;
$seconds_in_mins= 60;

function debug_to_console( $data ) {
    if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;
}

function getTitle($title) {
	$titleWordArray = split ( '_', $title );
	$returnString = '';
	foreach ( $titleWordArray as $titleWord ) {
		$returnString .= ' ' . ucfirst ( strtolower ( $titleWord ) );
	}
	return $returnString;
}

function getRecordFieldValue($doc, $fieldName, $defaultValue = '') {
	if ($fieldName == '') return '';
	if (array_key_exists($fieldName,$doc) && isset($doc[$fieldName]) ){
		return $doc[$fieldName];
	}
	return $defaultValue;
}

function getParamValue($key, $args) {
	return getRecordFieldValue($_POST, $key);
	return getRecordFieldValue($_GET, $key);
	return getRecordFieldValue($args, $key);
	return null;
}

function getLink($link = '', $text = '') {
        return '<a target="_blank" href="' . $link . '">' . $text . '</a>';
}

function getCopyRecordLink($collectionName, $documentId, $text, $args = '') {
	return getLink(
		'http://admin.' . $_SESSION['url_domain'] . '/' . $collectionName.'/copy/All?id=' . $documentId . $args,
		$text
	);
}
function getUpdateRecordLink($collectionName, $documentId, $text, $args = '') {
	return getLink(
		'http://admin.' . $_SESSION['url_domain'] . '/' . $collectionName.'/update/All?id=' . $documentId . $args,
		$text
	);
}

function getCreateRecordLink($collectionName, $text, $args = '') {
	return getLink(
		'http://admin.' . $_SESSION['url_domain'] . '/' . $collectionName.'/create/All' . $args,
		$text
	);
}

function getCellStart($contentType = '') {
        if ($contentType == 'currency') {
                return '<td  style="text-align: right;" >';
        } else {
                return '<td>';
        }
}

function getSmsLink($to = '', $body = '', $linkText = '') {
        $href = 'sms:';
        if ($to != '') { $href .= $to; }
        if ($body != '') { $href .= '?body=' . $body; }
        if ($linkText == '') { $linkText = 'SMS'; }
        return getLink($href, $linkText);
}

function getSmsLinkSpaced($to = '', $body = '', $linkText = '') {
        return '<br /><br />SMS: ' . getSmsLink($to, $body, $linkText) . '<br /><br />';
}


function getCellEnd() {
        return '</td>';
}

function getRowStart() {
        return '<tr>';
}

function getRowEnd() {
        return '</tr>';
}

function getAsCell($text = '', $contentType = '') {
        return getCellStart($contentType) . $text . getCellEnd();
}


function getManyDocumentsCursor($collection, $field, $value, $myOrg = false, $isFieldId = false) {
	$newValue = $value;
	if ($isFieldId) {
		$newValue = getMongoId($value);
		if ($newValue == null) { return array(); }
	}
	$cond = array($field => $newValue);
	if ($myOrg) {
		$cond = array(
			'$and' => array (
				$cond,
				array (
					'for_org' => new MongoId ( $_SESSION ['url_domain_org'] ['_id'] )
				)
			)
		);

	}
	return $_SESSION ['mongo_database']->{$collection}->find($cond);
}

function getOneDocument($collection, $field, $value, $myOrg = false, $isFieldId = false) {
	if ($field == '') { return array(); }

	$newValue = $value;
	if ($field == '_id' || $isFieldId) {
		$newValue = getMongoId($value);
		if ($newValue == null) { return array(); }
	}
	debugPrintArray(array($collection, $field, $newValue), 'getOneDocument collection');
	$cond = array($field => $newValue);
	if ($myOrg) {
		$cond = array(
			'$and' => array (
				$cond,
				array (
					'for_org' => new MongoId ( $_SESSION ['url_domain_org'] ['_id'] )
				)
			)
		);

	}
	return $_SESSION ['mongo_database']->{$collection}->findOne($cond);
}

function getDocumentById($collection,$id) {
	$mid = getMongoId($id);
	if ($mid == null) { return array(); }
	return $_SESSION ['mongo_database']->{$collection}->findOne(array('_id' => $mid));
}

function getPersonsCursor($doc, $fieldName = 'persons', $subFieldName = 'name') {
	$persons = getRecordFieldValue($doc, $fieldName, array());
	if(!$persons) { return false; }

	$_ids = array();
	foreach($persons as $person){
		$id = getRecordFieldValue($person, $subFieldName);
		if ($id != '') {
			array_push($_ids, ($id instanceof MongoId) ? $id : new MongoId($id));
		}
	}
	return $_SESSION ['mongo_database']->person->find(array(
		'_id' => array( '$in' => $_ids)
	));

	return false;
}

function getWorkeSalaryComponentTitle($doc) {
	$rStr = '';
	#$val = getRecordFieldValue($doc, 'country');
	#if ($val != '') { $rStr .= ' ' . $val; }
	#$val = getRecordFieldValue($doc, 'state_or_province');
	#if ($val != '') { $rStr .= ' ' . $val; }
	#$val = getRecordFieldValue($doc, 'district_or_county');
	#if ($val != '') { $rStr .= ' ' . $val; }
	$val = getRecordFieldValue($doc, 'payslip_text');
	if ($val != '') { $rStr .= ' ' . $val; }
	return $rStr;
} 

function getWorkerSalaryComponentById($id) {
	if ($id == '') { return ''; }
	return getWorkeSalaryComponentTitle(getOneDocument('worker_salary_component', '_id',  $id));
}


function getNameOfPersonFromSingleRecord($doc) {
	$names = '';
	foreach($doc['name'] as $name) {
		$val = getRecordFieldValue($name, 'prefix');
		if ($val != '') { $names .= ' ' . $val; }
		$val = getRecordFieldValue($name, 'first');
		if ($val != '') { $names .= ' ' . $val; }
		$val = getRecordFieldValue($name, 'middle');
		if ($val != '') { $names .= ' ' . $val; }
		$val = getRecordFieldValue($name, 'last');
		if ($val != '') { $names .= ' ' . $val; }
		$val = getRecordFieldValue($name, 'suffix');
		if ($val != '') { $names .= ' ' . $val; }
		$names .= ', ';
	}
	return $names;
}

function getPersonNamesById($id) {
	if ($id == '') { return ''; }
	return getNameOfPersonFromSingleRecord(getOneDocument('person', '_id',  $id));
}


function getNamesOfPersonsInOneDocument($personsCursor) {
	$names = '';
	if (isset($personsCursor) && $personsCursor != null && $personsCursor != false) {
		foreach($personsCursor as $person) {
			$names .= getNameOfPersonFromSingleRecord($person);
		}
	}
	return $names;
}

function getNamesOfPersons($doc, $personContainerFieldName) {
	return getNamesOfPersonsInOneDocument(
		getPersonsCursor($doc, $personContainerFieldName, 'name')
	);
}

$_SESSION['authorization_message'] = '';

function validDatabaseDomain($databaseDomainDoc) {
	/* check if this domain is either mandatory or assigned to organization */
	if ($databaseDomainDoc['mandatory'] == 'True') {
		return true;
	}
	foreach ($_SESSION['mongo_database']->organization_database_domain->find() as $allowedDomain ) {
		if ( (string) $allowedDomain['organization'] == (string) $_SESSION ['url_domain_org']['_id']
			&& (string) $allowedDomain['domain'] == (string) $databaseDomainDoc['_id']
		) {
			return  true;
		}
	}
	
	$_SESSION['authorization_message'] = 'Module ' . $databaseDomainDoc['name'] . ' is not authorized';
	return false;
}


function validDatabaseCollection ($collectionName) {
	
	/* get the record of the collection */
	$_SESSION['collection'] = getOneDocument('database_collection', 'name',  $collectionName);

	if (empty($_SESSION['collection'])) {
		/* no such collection exists */
		$_SESSION['authorization_message'] = 'common:validDatabaseCollection: Collection ' . $collectionName . ' does not exists';
		return false;
	}

	foreach( $_SESSION['collection']['domain'] as $assignedDatabaseDomain) {
		/* get the name of module which is associated with collection to be authorized */
		/* find the database_domain record for the assigned database domains */
 		$databaseDomainDoc = getOneDocument('database_domain', '_id', $assignedDatabaseDomain['name']);

		if (empty($databaseDomainDoc)) {
			/* assigned domain does not exists */
			$_SESSION['authorization_message'] = 'common:validDatabaseCollection: Assigned module ' . $assignedDatabaseDomain['name'] . ' does not exists';
			return false;
		}

		
		if (validDatabaseDomain($databaseDomainDoc)) {
			return true;
		}
	} /* foreach( $databaseCollectionDoc['domain'] as $assignedDatabaseDomain) */

	$_SESSION['authorization_message'] = 'common:validDatabaseCollection: Collection ' . $collectionName . ' is not authorized';
	return false;
} /* function validDatabaseCollection */


function getMongoId($str) {
	if (gettype($str) == 'object') {
                /* if ($str instanceof MongoId) { */
		if (get_class($str) == 'MongoId') {
			return $str;
		}
	} else if (gettype($str) == 'string') {
		$str = trim($str);
		/* A valid Object Id must be 24 hex characters */
		if (preg_match ( '/^[0-9a-fA-F]{24}$/', $str )) {
			return new MongoId($str);
		}
	}
	return null;
}

function isValidMongoObjectID($str) {
	if (gettype($str) == 'object') {
		if (get_class($str) == 'MongoId') {
			return true;
		}
	} else {
		// A valid Object Id must be 24 hex characters
		return preg_match ( '/^[0-9a-fA-F]{24}$/', $str );
	}
}

function getColoredText ($text, $color) {
        if ($text == '') return '';
        return ' <span style="color: '.$color.';">' . $text . '</span> ';
}

function getAlertText($text, $redBooleanCondition) {
	if ($redBooleanCondition) {
		return getColoredText($text, 'red');
	} else {
		return getColoredText($text, 'green');
	}
}

function getAnimalName ($field, $value) {
        if ($value == '') return getColoredText('No Animal Defined', 'blue');
        $animalRecord = getOneDocument('animal', $field, $value);
        if (isset($animalRecord)) {
                return $animalRecord['name'] . ' (Tag ' . $animalRecord['tag_number'] . ')';
        } else {
                return  getColoredText('Missing Animal Record', 'blue');
        }
}

function getAnimalNameUsingTagNumber ($tagNumber) {
        if ($tagNumber == '') return getColoredText('No Animal Defined', 'blue');
        $animalRecord = getOneDocument('animal', 'tag_number', $tagNumber);
        if (isset($animalRecord)) {
                return $animalRecord['name'] . ' (Tag ' . $animalRecord['tag_number'] . ')';
        } else {
                return  getColoredText('Missing Animal Record', 'blue');
        }
}

function getAnimalNameUsingId ($id) {
        if ($id == '') return getColoredText('No Animal Defined', 'blue');
        if(!isValidMongoObjectID($id)) return  getColoredText('Wrong Animal Defined', 'blue');
        $animalRecord = getOneDocument('animal', '_id', $id);
        if (isset($animalRecord)) {
                return $animalRecord['name'] . ' (Tag ' . $animalRecord['tag_number'] . ')';
        } else {
                return  getColoredText('Missing Animal Record', 'blue');
        }
}

function showSelectedReadOnlyFields($allFields, $record, $isSubRecord = false, $from = '') {
	$newReadOnlyValue = array();
	if ($_SESSION['debug']) {
		echo 'AllFields = <pre>' . print_r($allFields, true) . '</pre>';
		echo 'Record = <pre>' . print_r($record, true) . '</pre>';
	}
	if (!is_array ($allFields)) {
		return $record[$allFields];
	}
	
	foreach ( $record as $oneField => $currentValue) {
			if (!$isSubRecord) {
				$show = false;
				foreach ( $allFields as $fieldName) {
					if ($fieldName == $oneField) {
						$show = true;
					}
				}
				if(!$show) { continue; }
			}
			debugPrintArray($from, 'From Query');
			if ($from == 'query') {
				$newReadOnlyValue[$oneField] = getTitle ( $oneField ) . ': ';
			} else {
				$newReadOnlyValue[$oneField] = '<b>' . getTitle ( $oneField ) . '</b>: ';
			}
			if (is_array ($currentValue )) {
	                        foreach ( $currentValue as $subField => $subRecord ) {
					$newReadOnlyValue[$oneField] .= showSelectedReadOnlyFields($allFields, $subRecord, true, $from) ;
				}
			} else {
				$type = gettype($currentValue);
				if ($type == 'object') { $type = get_class($currentValue); }
				if ($type == 'MongoDate') {
					$newReadOnlyValue[$oneField] .= date('Y-M-d D H:i',$currentValue->sec);
				} else {
					$newReadOnlyValue[$oneField] .= $currentValue;
				}
			} /* if (is_array ($currentValue )) */
	} /* foreach ( $allFields as $oneField ) */

	/* This is to arrange in allFields sequence */
	$rStr = '| ';
	if ($isSubRecord) {
		ksort($newReadOnlyValue);
		$rStr = '(' . join(', ', $newReadOnlyValue) . ')';
	} else {
		foreach($allFields as $value) {
			$rStr .= $newReadOnlyValue[$value] . ' | ';
		}
	}
	return $rStr;
} /* function showSelectedReadOnlyFields($allFields, $record) */

function showSelectedReadOnlyFieldsFromDocOfCollection($docId, $collection, $allShowFields) {
                $fkDoc = array ();
                $rStr = '';
                $rStr .= '<div>';
                try {
                        $frId = ( string ) $docId;
                        if ($frId == 'COMMON_ITEM') {
                                $rStr .= "Common Item";
                        } else {
                                if (! isValidMongoObjectID ( $frId )) {
                                        $rStr .= 'Invalid format of key';
                                } else {
                                        $frId = new MongoId ( trim ( $frId ) );
                                        $fkDoc = getOneDocument($collection, '_id', $frId);
                                        if (empty ( $fkDoc )) {
                                                $rStr .= 'No such record exists';
                                        } else {
                                                $fkTitleFields = split ( ",", $allShowFields );
                                                $rStr .= showSelectedReadOnlyFields ( $fkTitleFields, $fkDoc );
                                        }
                                }
                        }
                } catch ( MongoException $em ) {
                        $rStr .= ( string ) $docId . ' Invalid value of key';
                }
                $rStr .= '</div>';
                return $rStr;
} /* function showSelectedReadOnlyFieldsFromDocOfCollection($docId, $collection, $allShowFields) */ 


function getAnimalDefaults($type = 'Cow') {
	$defaults = array();
	$defaults['pregnancy_period_in_days'] = 0;
	$defaults['pregnancy_taking_care_before_days'] = 0;
	$defaults['re_crossed_days_start'] = 0;
	$defaults['re_crossed_days_end'] = 0;
	$defaults['good_age_in_days'] = 0;
	if ($type == 'Cow') {
		$defaults['pregnancy_period_in_days'] = 9 * 30; /* 9 months */
		$defaults['pregnancy_taking_care_before_days'] = 2 * 30; /* 2 months before delivery */
		$defaults['re_crossed_days_start'] = 2 * 30; /* 2 months */
		$defaults['re_crossed_days_end'] = 10 * 30; /* 10 months */
		$defaults['good_age_in_days'] = 8 * 365; /* 8 years */
	}
	if ($type == 'Ox (Breed)') {
		$defaults['good_age_in_days'] = 6 * 365; /* 6 years */
	}
	if ($type == 'Bull (Cart)') {
		$defaults['good_age_in_days'] = 6 * 365; /* 6 years */
	}
	if ($type == 'Bitch') {
		$defaults['good_age_in_days'] = 6 * 365; /* 6 years */
	}
	if ($type == 'Dog') {
		$defaults['good_age_in_days'] = 6 * 365; /* 6 years */
	}
	return $defaults;
}
?>
