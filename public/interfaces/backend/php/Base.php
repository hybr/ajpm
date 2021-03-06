<?php
/* location of class instences as data */
define ( "DATA_LOCATION_CLIENT_TEMPARARY", 1 );
define ( "DATA_LOCATION_CLIENT_PERMANENT", 2 );
define ( "DATA_LOCATION_CLIENT_CODE", 3 );
define ( "DATA_LOCATION_SERVER_TEMPARARY", 11 );
define ( "DATA_LOCATION_SERVER_PERMANENT", 12 );
define ( "DATA_LOCATION_SERVER_CODE", 13 );
class Base {
	/* error message */
	public $errorMessage = array ();
	
	/* data location */
	public $dataLocation = DATA_LOCATION_SERVER_PERMANENT;
	/* read, write, present for person,org, and public at table and record level */
	
	/* name of colection in progress */
	public $db = NULL;
	public $collectionName = '';
	public $findCursor = NULL;
	public $curlsMode = '';
	public $titleValueConversionRequired = 1;
	public $subTaskKeyToSave = 'sub_task';

	/* fiel definations */
	public $defaultFields = array (
			'created_on' => array (
				'type' => 'datetime',
				'show_in_list' => 0 
			),
			'updated_on' => array (
				'type' => 'datetime',
				'show_in_list' => 0 
			),
			'created_by' => array (
				'type' => 'foreign_key',
				'foreign_collection' => 'person',
				'foreign_search_fields' => 'name.first,name.middle,name.last',
				'foreign_title_fields' => 'name,gender',
				'show_in_list' => 0 
			),
			'updated_by' => array (
				'type' => 'foreign_key',
				'foreign_collection' => 'person',
				'foreign_search_fields' => 'name.first,name.middle,name.last',
				'foreign_title_fields' => 'name,gender',
				'show_in_list' => 0 
			),
			'for_org' => array (
				'type' => 'foreign_key',
				'foreign_collection' => 'organization',
				'foreign_search_fields' => 'name,abbreviation',
				'foreign_title_fields' => 'name,abbreviation',
				'show_in_list' => 0 
			) 
	);
	public $help = '';
	public $fields = array ();
	public $fieldDefault = array (
		'title' => '',
		'type' => 'string',
		'input_mode' => 'typeing', /* clicking selecting */
		'placeholder' => 'Text',
		'help' => '',
		'name' => '',
		'value' => '',
		'required' => '',
		'minlength' => -1,
		'maxlength' => -1,
		'input_tag_length' => 40,
		'select_tag_hight' => 1,
		'list_class' => '',
		'multiple' => 0,
		'unique' => 0,
		'show_in_list' => 0,
		'default' => '',
		'sub_tasks' => array (
			'All' 
		),
		'is_search_field_level1_embaded' => 0,
		'foreign_collection' => '',
		'foreign_search_fields' => '',
		'foreign_title_fields' => '',
	);
	
	/* data storage for class is server code then store values in $data */
	public $table = array ();
	public $record = array ();

	public function getFieldValue($record, $fieldName, $prefix = '', $suffix = '', $content = '') {
		$rStr = '';
		if (is_array($record) && array_key_exists($fieldName, $record) && isset($record[$fieldName])) {
			if ($content == '') {
				$rStr .= $prefix . $record[$fieldName] . $suffix;
			} else {
				$rStr .= $prefix . $content . $suffix;
			}
		}
		return $rStr;
	}	
	/* convert string with underscores as Title */
	public function getTitle($t) {
		$ta = split ( '_', $t );
		$rS = '';
		foreach ( $ta as $w ) {
			$rS .= ' ' . ucfirst ( strtolower ( $w ) );
		}
		return $rS;
	}
	/* get the list of records for the class */
	public function getTable() {
		if ($this->dataLocation == DATA_LOCATION_SERVER_CODE) {
			/* The data for the class is located in the $data variable */
			return $this->table;
		}
	}

	private function showReadOnlyValue($value, $field) {
		$rStr = '';
		if ($field ['type'] == 'foreign_key' && $value != '') {
			$rStr .= showSelectedReadOnlyFieldsFromDocOfCollection ( 
				$value, 
				$field ['foreign_collection'], $field ['foreign_title_fields'] 
			);
		} elseif ($field ['type'] == 'list' && $value != '') {
			$listInstance = new $field ['list_class'] ();
			if ($listInstance->titleValueConversionRequired) {
				foreach ( $listInstance->getTable () as $r ) {
					if ($r ['value'] == $value) {
						$rStr = $r ['title'];
					}
				}
				if ($rStr == '') {
					$rStr = $value;
				}
			} else {
				$rStr = $value;
			}
		} else {
				
			$rStr = $value;
		}
		return $rStr;
	}

	private function getCreateLink($st = '') {
		if ($st != '') {
			$st = '/' .$st;
		} elseif ($_SESSION ['url_sub_task'] != '') {
			$st = '/' . $_SESSION ['url_sub_task'];
		}
		if ($this->collectionName == 'contact') {
			return 'Create : '
					. '<a href="/' . $this->collectionName . '/create/phone">Phone</a>'
					. '<a href="/' . $this->collectionName . '/create/email_address">Email</a>'
					. '<a href="/' . $this->collectionName . '/create/postal_address">Postal</a>'
					. '<a href="/' . $this->collectionName . '/create/web">Web</a>'
					. '<a href="/' . $this->collectionName . '/create/fax">Fax</a>'
					. '<a href="/' . $this->collectionName . '/create/voip">VoIP</a>'
					. '<a href="/' . $this->collectionName . '/create/pager">Pager</a>'
			;
		}		
		return '<a href="/' . $this->collectionName . '/create' . $st . '">Create</a>';
	}
	private function getRemoveLink($st = '') {
		if ($st != '') {
			$st = '/' .$st;
		} elseif ($_SESSION ['url_sub_task'] != '') {
			$st = '/' . $_SESSION ['url_sub_task'];
		}
		
		if (isset ( $this->record ['_id'] )) {
			return '<a href="/' . $this->collectionName . '/remove' . $st . '?id=' . $this->record ['_id'] . '">Remove</a>';
		} else {
			return '';
		}
	}
	private function getUpdateLink($st = '') {	
		if ($st != '') {
			$st = '/' .$st;
		} elseif ($_SESSION ['url_sub_task'] != '') {
			$st = '/' . $_SESSION ['url_sub_task'];
		}
		if (isset ( $this->record ['_id'] )) {
			return '<a href="/' . $this->collectionName . '/update' . $st . '?id=' . $this->record ['_id'] . '">Update</a>';
		} else {
			return '';
		}
	}
	private function getCopyLink($st = '') {
		if ($st != '') {
			$st = '/' .$st;
		} elseif ($_SESSION ['url_sub_task'] != '') {
			$st = '/' . $_SESSION ['url_sub_task'];
		}
		
		if (isset ( $this->record ['_id'] )) {
			return '<a href="/' . $this->collectionName . '/copy' . $st . '?id=' . $this->record ['_id'] . '">Copy</a>';
		} else {
			return '';
		}
	}	
	private function getListLink($st = '') {
		if ($st != '') {
			$st = '/' .$st;
		} elseif ($_SESSION ['url_sub_task'] != '') {
			$st = '/' . $_SESSION ['url_sub_task'];
		}
		
		return '<a href="/' . $this->collectionName . '/read' . $st . '">List</a>';
	}
	private function getShowLink($st = '') {
		if ($st != '') {
			$st = '/' .$st;
		} elseif ($_SESSION ['url_sub_task'] != '') {
			$st = '/' . $_SESSION ['url_sub_task'];
		}
		
		if (isset ( $this->record ['_id'] )) {
			return '<a href="/' . $this->collectionName . '/show' . $st . '?id=' . $this->record ['_id'] . '">Show</a>';
		} else {
			return '';
		}
	}
	private function getPresentLink($st = '') {
		if ($st != '') {
			$st = '/' .$st;
		} elseif ($_SESSION ['url_sub_task'] != '') {
			$st = '/' . $_SESSION ['url_sub_task'];
		}
		
		if (isset ( $this->record ['_id'] )) {
			return '<a href="/' . $this->collectionName . '/present' . $st . '?id=' . $this->record ['_id'] . '">Present</a>';
		} else {
			return '';
		}
	}
	private function showLinks($st = '') {
		if ( $_SESSION ['allowed_as'] == 'PUBLIC') {
			/* Do not show action buttons to public */
			return '';
		}
		$rStr = '<hr />| ';
		if ($this->curlsMode != 'Create') {
			$x = $this->getCreateLink ($st);
			if ($x != '') {
				$rStr .= $x . ' | ';
			}
		}
		if ($this->curlsMode != 'Remove') {
			$x = $this->getRemoveLink ($st);
			if ($x != '') {
				$rStr .= $x . ' | ';
			}
		}
		if ($this->curlsMode != 'Copy') {
			$x = $this->getCopyLink ($st);
			if ($x != '') {
				$rStr .= $x . ' | ';
			}
		}		
		if ($this->curlsMode != 'Update') {
			$x = $this->getUpdateLink ($st);
			if ($x != '') {
				$rStr .= $x . ' | ';
			}
		}
		if ($this->curlsMode != 'List') {
			$x = $this->getListLink ($st);
			if ($x != '') {
				$rStr .= $x . ' | ';
			}
		}
		if ($this->curlsMode != 'Show') {
			$x = $this->getShowLink ($st);
			if ($x != '') {
				$rStr .= $x . ' | ';
			}
		}
		if ($this->curlsMode != 'Present') {
			$x = $this->getPresentLink ($st);
			if ($x != '') {
				$rStr .= $x . ' | ';
			}
		}
		return $rStr;
	}
	private function initializeTask() {
		$this->fields = array_merge ( $this->defaultFields, $this->fields );
		$this->record = array ();
		$this->errorMessage = array ();
	}
	private function convertField($direction, $name, $attributes, $value) {
		$nv = $value; 
		/* if no conversion return same value so new value = value received */
		
		/* echo "<br />Converting Field " . $name; */
		
		/* if type is password and length is not 32 the convert using the md5 */
		/* keep password at first level only, not in containers */
		if ($attributes ['type'] == 'password' && strlen ( $value ) != 32) {
			if ($direction == 'before_save') {
				$nv = md5 ( $value );
			}
		}
		
		/* save time and date as objects */
		if ($attributes ['type'] == 'time') {
			if ($direction == 'before_save') {
				$nv = new MongoDate ( strtotime ( $value ) );
			}
			if ($direction == 'after_read') {
				if (is_object ( $value )) {
					$nv = date ( 'H:i', $value->sec );
				} else {
					if ($value == '') {
						$nv = date ( 'H:i', (new MongoDate ())->sec );
					} else {
						$nv = date ( 'H:i', strtotime ( $value ) );
					}
				}
			}
		}
		
		if ($attributes ['type'] == 'number') {
			if ($direction == 'before_save') {
				$nv = floatval ( $value );
			}
			if ($direction == 'after_read') {
				$nv = floatval ( $value );
			}
		}
		if ($attributes ['type'] == 'date') {
			if ($direction == 'before_save') {
				$nv = new MongoDate ( strtotime ( $value ) );
			}
			if ($direction == 'after_read') {
				if (is_object ( $value )) {
					$nv = date ( 'Y-M-d', $value->sec );
				} else {
					if ($value == '') {
						$nv = date ( 'Y-M-d', (new MongoDate ())->sec );
					} else {
						$nv = date ( 'Y-M-d', strtotime ( $value ) );
					}
				}
			}
		}
		
		if ($attributes ['type'] == 'datetime') {
			if ($direction == 'before_save') {
				$nv = new MongoDate ( strtotime ( $value ) );
			}
			if ($direction == 'after_read') {
				
				if (is_object ( $value )) {
					$nv = date ( 'Y-M-d H:i e', $value->sec );
				} else {
					if ($value == '') {
						$nv = date ( 'Y-M-d H:i e', (new MongoDate ())->sec );
					} else {
						$nv = date ( 'Y-M-d H:i e', strtotime ( $value ) );
					}
				}
			}
		}
		
		/* convert static list values in title */
		if ($attributes ['type'] == 'list') {
			if ($direction == 'after_read') {
				$listInstance = new $attributes ['list_class'] ();
				foreach ( $listInstance->getTable () as $r ) {
					if ($r ['value'] == $value) {
						$nv = $r ['title'];
						break;
					}
				}
			}
		}
				
		/* update is always the latest date time */
		if ($name == 'updated_on' || $name == 'created_on') {
			if ($direction == 'before_save') {
				$nv = new MongoDate (); /* current date and time */
			}
			if ($direction == 'after_read') {
				$nv = date ( 'Y-M-d H:i e', $value->sec );
			}
		}
		
		if ($name == 'updated_by' || $name == 'created_by') {
			if ($value == '') {
				$value = $_SESSION ['login_person_id'];
			}
			if ($direction == 'before_save') {
				if ($_SESSION ['login_person_id'] != '') {
					$nv = new MongoId ( ( string ) $_SESSION ['login_person_id'] );
				} else {
					$nv = ''; /* user is joining and not logged in as user */
				}
				/* current logged in user */
			}
			if ($direction == 'after_read') {
				$nv = ( string ) $value;
			}
		}
					
		/* convert foreign keys in the mongo id object before saving */
		if ($name == 'for_org') {
			if ($_SESSION ['url_domain_org'] ['_id'] != '') {
				/* update to the current domain */
				/* this will help in transfer of domains to actual domain accounts */
				$value = ( string ) $_SESSION ['url_domain_org'] ['_id'];
			} else {
				/* owebp.com */
				$value = '54c27c437f8b9a7a0d074be6';
			}
		}
		if ($attributes ['type'] == 'foreign_key') {
			/* already took care for for org above */
			if ($direction == 'before_save' && isValidMongoObjectID($value)) {
				$nv = new MongoId ( ( string ) $value );
			}
			if ($direction == 'after_read'&& isValidMongoObjectID($value)) {
				/* get the title fields values */
				$nv = ( string ) $value;
			}
		}
		if ($attributes ['type'] == 'file') {
			if ($direction == 'before_save') {
				/* for file upload */		
				$target_folder = '/hybr/websites/ajpm/public/common/file/' . ( string ) $_SESSION ['url_domain_org'] ['_id'];
				if (! is_dir ( $target_folder )) {
					mkdir ( $target_folder );
				}
				if (! is_writeable ( $target_folder )) {
					throw new RuntimeException ( "Cannot write to destination folder " . $target_folder );
				}
				try {
					
					// Undefined | Multiple Files | $_FILES Corruption Attack
					// If this request falls under any of them, treat it invalid.
					if (! isset ( $_FILES [$name] ['error'] ) || is_array ( $_FILES [$name] ['error'] )) {
						throw new RuntimeException ( 'Invalid parameters.' );
					}
					
					// Check $_FILES['upfile']['error'] value.
					switch ($_FILES [$name] ['error']) {
						case UPLOAD_ERR_OK :
							break;
						case UPLOAD_ERR_NO_FILE :
							throw new RuntimeException ( 'No file sent.' );
						case UPLOAD_ERR_INI_SIZE :
						case UPLOAD_ERR_FORM_SIZE :
							throw new RuntimeException ( 'Exceeded filesize limit.' );
						default :
							throw new RuntimeException ( 'Unknown errors.' );
					}
					
					// You should also check filesize here.
					if ($_FILES [$name] ['size'] <= 0) {
						throw new RuntimeException ( 'Empty file.' );
					}
					/* not more than 10MB file */
					if ($_FILES [$name] ['size'] > 5000000) {
						throw new RuntimeException ( 'Exceeded filesize limit 5MB.' );
					}
					
					// DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
					// Check MIME Type by yourself.
					$finfo = new finfo ( FILEINFO_MIME_TYPE );
					$ext = array_search ( $finfo->file ( $_FILES [$name] ['tmp_name'] ), array (
							// images
							'png' => 'image/png',
							'jpg' => 'image/jpeg',
							'jpe' => 'image/jpeg',
							'jpeg' => 'image/jpeg',
							'gif' => 'image/gif',
							'bmp' => 'image/bmp',
							'ico' => 'image/vnd.microsoft.icon',
							'tiff' => 'image/tiff',
							'tif' => 'image/tiff',
							'svg' => 'image/svg+xml',
							'svgz' => 'image/svg+xml',
							
							// audio/video
							'mp3' => 'audio/mpeg',
							'qt' => 'video/quicktime',
							'mov' => 'video/quicktime',
							
							// adobe
							'pdf' => 'application/pdf',
							'psd' => 'image/vnd.adobe.photoshop',
							'ai' => 'application/postscript',
							'eps' => 'application/postscript',
							'ps' => 'application/postscript',
							
							// ms office
							'doc' => 'application/msword',
							'rtf' => 'application/rtf',
							'xlsx' => 'application/vnd.ms-excel',
							'ppt' => 'application/vnd.ms-powerpoint',
							
							// open office
							'odt' => 'application/vnd.oasis.opendocument.text',
							'ods' => 'application/vnd.oasis.opendocument.spreadsheet',

							// gedcom
							'ged' => 'text/plain',
					), true ); 
					if (false === $ext ) { 
						throw new RuntimeException ( 'Invalid file format. Allowed are images, audio, video, adobe, ms office, open office, gedcom '  . $ext);
					}
					
					// You should name it uniquely.
					// DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
					// On this example, obtain safe unique name from its binary data.
					$_FILES [$name] ['name'] = str_replace ( " ", "_", $_FILES [$name] ['name'] );
					$_FILES [$name] ['name'] = preg_replace ( '/\\.[^.\\s]{3,4}$/', '', $_FILES [$name] ['name'] );
					
					$saveAsFileName = sprintf ( $target_folder . '/%s.%s', basename ( $_FILES [$name] ['name'] ), $ext );
					if (isset($_POST['save_as_file_name']) && $_POST['save_as_file_name'] != '') {
						$saveAsFileName = sprintf ( $target_folder . '/%s.%s', basename ( $_POST['save_as_file_name'] ), $ext );
					}
					if (! move_uploaded_file ( $_FILES [$name] ['tmp_name'], $saveAsFileName)) {
						throw new RuntimeException ( 'Failed to move uploaded file.' );
					}
					
					/* echo 'File is uploaded successfully.'; */
					$nv = ( string ) basename($saveAsFileName);
				} catch ( RuntimeException $e ) {
					array_push ( $this->errorMessage, $e->getMessage () );
				}
			}
			if ($direction == 'after_read') {
				$nv = ( string ) $value;
			}
		}
		
		return $nv;
	}
	private function processFieldsForPresentationAndStorage($direction, $slfs, $slrec, $level) {
		if ($level == 1 && $direction == 'before_save') {
			/* created_on and created_by only at first level */
			if (isset ( $slrec ['_id'] )) {
				/* this record is for update */
				$slrec ['_id'] = new MongoId ( $slrec ['_id'] );
				
				/* read the current record before updating it. This is to maintain the created_on date. */
				$currentRecord = getOneDocument($this->collectionName, '_id', $slrec ['_id'] );
				
				if (isset ( $currentRecord ['created_on'] )) {
					/* current record is in object format */
					$slrec ['created_on'] = $currentRecord ['created_on'];
				} else {
					$slrec ['created_on'] = new MongoDate (); /* current time */
				}
				if (isset ( $currentRecord ['created_by'] )) {
					/* current record is in object format */
					$slrec ['created_by'] = $currentRecord ['created_by'];
				} else {
					if ($_SESSION ['login_person_id']  != '') {
						$slrec ['created_by'] = new MongoId ( $_SESSION ['login_person_id'] ); /* current user */
					}
				}
			} else {
				/* this is new record */
				$slrec ['created_on'] = new MongoDate (); /* current time */
				if ($_SESSION ['login_person_id']  != '') {
					$slrec ['created_by'] = new MongoId ( $_SESSION ['login_person_id'] );
				}
			}
		}
		
		
		
		/* level will increase for containers */
		$level ++;
		
		foreach ( $slfs as $key => $val ) {
			debugPrintArray (print_r($slfs[$key], true), 'First Value of ' . $key);
			
			$field = array_merge ( $this->fieldDefault, $val );
			
			
			if ($this->curlsMode == 'List' && $field ['show_in_list'] == 0) {
				/* this field is not required to show */
				continue;
			}
			
			if ($key == 'created_on' && $direction == 'before_save') {
				continue; /* took care about it above */
			}
			
			if ($key == 'created_by' && $direction == 'before_save') {
				continue; /* took care about it above */
			}
			
			if (! isset ( $slrec [$key] )) {
				if ($direction == 'before_save') {
					$slrec [$key] = '';
				}
				if ($direction == 'after_read') {
					$slrec [$key] = '';
				}
			}
			
			$slrec [$key] = $this->convertField ( $direction, $key, $field, $slrec [$key] );
			debugPrintArray (print_r($slrec[$key], true), 'After Convert Value of ' . $key);
			
			if ($field ['type'] == 'container') {
				/* the array push here helps the record in proper format for query. The objects in array without keys */
				
				if (! isset ( $slrec [$key] )) {
					echo '<hr /> ERROR: <b>' . $key . '</b> of record missing | ';
					print_r ( $slrec );
				}
				if (! is_array ( $slrec [$key] )) {
					/* echo '<hr /> ERROR: <b>'.$key.'</b> of record is not array | '; print_r($slrec); */
					/*
					 * this indicate we have a field $key but it does not
					 * exists in the current record value, so no need to process this field
					 */
					continue;
				}
				$arr = array ();
				foreach ( $slrec [$key] as $subValues ) {
					$field2 = array_merge ( $this->fieldDefault, $val );
					if (! isset ( $field2 ['fields'] )) {
						echo '<hr /> ERROR: field fields missing ' . $key . ' = ';
						print_r ( $field2 );
					}
					array_push ( $arr, $this->processFieldsForPresentationAndStorage ( $direction, $field2 ['fields'], $subValues, $level ) );
				}
				$slrec [$key] = $arr;
			}

			debugPrintArray (print_r($slfs[$key], true), 'Final Value of ' . $key);
		} /* foreach ( $slfs as $key => $val ) */
		return $slrec;
	}
	public function showError() {
		$rStr = '<ul class="ui-state-error ui-corner-all">';
		foreach ( $this->errorMessage as $msg ) {
			$rStr .= '<li>' . $msg . '</li>';
		}
		return $rStr . '</ul>' . $this->showLinks ();
	}
	public function save($urlArgsArray) {
		$this->initializeTask ();
		debugPrintArray ( $_POST, 'POST' );
		
		$this->record = $_POST;
		
		if ($this->record ['session_id'] != session_id ()) {
			return 'Fake request';
		}
		if (isset($this->record['session_id'])) {
			unset($this->record['session_id']); /* no need to save this */
		}

		$rStr = '';
 		debugPrintArray ( $this->record, 'Before Process'); 
		$this->record = $this->processFieldsForPresentationAndStorage ( 'before_save', $this->fields, $this->record, 1 );
 		debugPrintArray ( $this->record, 'After Process'); 

		if (count ( $this->errorMessage ) > 0) {
			return $this->showError ();
		}
		$savePersonRecord = true;
		if ($this->collectionName == 'person' 
			&& isset($this->record['check_duplicate']) 
			&& $this->record['check_duplicate'] == 'True'
		) {
			/* if person already exists then report it */
			$foundPerson = false;
			$rStr .= '<tr>';
			$rStr .= '<th>' . 'For first name ' . '</th>';
			$rStr .= '<th>' . 'Current Profile Owner' . '</th>';
			$rStr .= '<th>' . 'Request to transfer to you' . '</th>';
			$rStr .= '<th colspan=10>' . 'Names in profile' . '</th>';
			$rStr .= '</tr>';
			$personInstance = new public_Person();
			foreach ($this->record['name'] as $name) {
				$this->findCursor = $_SESSION ['mongo_database']->{$this->collectionName}->find (array(
					'name.first' => $name['first'] 
				));
				foreach($this->findCursor as $rec) {
					/* read login credential */
					$rStr .= '<tr>';
					$rStr .= '<td>' . $name['first'] . '</td>';
					$rStr .= '<td>';
					$personInstance->record = getOneDocument('person', '_id', $rec['created_by']); 
					foreach($personInstance->record['name'] as $createdByName) {
						$rStr .= $personInstance->getFullName('Official', true);
						break;
					}
					$rStr .= '</td>';
					$rStr .= '<td>';
					$email = $personInstance->getEmailAddress();
					$rStr .= '<a target="_blank" href="mailto:'
						. $email
						. '?Subject=Please%20transfer%20person%20profile%20'
						. (string) $rec['_id']
						. '%20to%20me">'.'Email To Transfer'.'</a>'; 
					$rStr .= '</td>';
					foreach($rec['name'] as $foundName) {
						$foundPerson = true;
						$rStr .= '<td>' 
								. $foundName['first'] . ' ' 
								. $foundName['middle'] . ' ' 
								. $foundName['last'] 
							. '</td>';
					}
					$rStr .= '</tr>';
				}
			}
			if ($foundPerson) {
				$rStr = 'Following persons already exists: <table class="showTable">' . $rStr 
					. '</table>To create this profile try again with setting "Check Duplicate" to False. <br />';	
				$savePersonRecord = false;
			} else {
				$rStr = '';
			}	
		}
 		debugPrintArray ( $this->record ); 
		if ($_SESSION ['mongo_database'] != NULL && $savePersonRecord) {
			$_SESSION ['mongo_database']->{$this->collectionName}->save ( $this->record );
			$rStr .= ' Saved successfully ';
		} else {
			array_push ( $this->errorMessage, $rStr . ' Not saved successfully' );
		}
		if (count ( $this->errorMessage ) > 0) {
			return $this->showError ();
		}
		
		$rStr .= $this->showLinks ();
		return $rStr; 
		
	}
	public function delete($urlArgsArray) {
		$this->initializeTask ();
		$this->record = $_POST;
		
		if ($_POST ['session_id'] != session_id ()) {
			return 'Fake request';
		}
		
		$rStr = '';
		if (isset ( $this->record ['_id'] )) {
			$this->record ['_id'] = new MongoId ( $this->record ['_id'] );
		} else {
			$rStr = 'No record selected for removal.';
		}
		
		/* remove the uploded file */
		if ($this->collectionName == 'file_upload') {
			$target_folder = '/hybr/websites/ajpm/public/common/file/' . ( string ) $_SESSION ['url_domain_org'] ['_id'];
			if (is_dir ( $target_folder ) && is_writeable ( $target_folder )) {
				echo "Removing : " . $target_folder . '/' . $this->record ['file_name'];
				print_r ( $this->record );
				unlink ( $target_folder . '/' . $this->record ['file_name'] );
			}
		}
		
		if ($_SESSION ['mongo_database'] != NULL) {
			$_SESSION ['mongo_database']->{$this->collectionName}->remove ( array (
					'_id' => new MongoId ( $this->record ['_id'] ) 
			) );
			$rStr .= ' Removed successfully';
		} else {
			array_push ( $this->errorMessage, 'Not removed successfully' );
		}
		if (count ( $this->errorMessage ) > 0) {
			return $this->showError ();
		}
		$rStr .= $this->showLinks ();
		return $rStr;
	}
	private function getFormTitle() {
		$st = '';
		if ($_SESSION ['url_sub_task'] != 'All') {
			$st = $_SESSION ['url_sub_task'];
		}
		if ($this->curlsMode == "Present All") {
			return $this->getTitle ( $this->collectionName );
		}
		return $this->curlsMode . ' ' . $st . ' ' . $this->getTitle ( $this->collectionName );
	}
	private function getCollectionQueryConditions() {
				
		/* read the record */
		$conditions = array ();
		
		$hasCurretDomain = array (
				'web_domain.name' => $_SESSION ['url_domain'] 
		);
		$isOwnedByCurrentUrlDomain = array ();
		if (isset($_SESSION ['url_domain_org']) && isset ( $_SESSION ['url_domain_org'] ['_id'] )) {
			$isOwnedByCurrentUrlDomain = array (
					'for_org' => new MongoId ( $_SESSION ['url_domain_org'] ['_id'] ) 
			);
		}

		$requestForSingleRecord = in_array ( $this->curlsMode, array (
				'Create',
				'Update',
				'Copy',
				'Remove',
				'Show',
				'Present' 
		) );
		$requestForMultipleRecord = in_array ( $this->curlsMode, array (
				'List',
				'Present All' 
		) );
		
		$validItem = array ();
		if ( in_array($_SESSION['allowed_as'], array('PUBLIC')) && $this->collectionName == "item") {
			$validItem = array (
					'$or' => array (
							array (
									'price.for' => 'Purchase and Sale' 
							),
							array (
									'price.for' => 'Make and Sale' 
							) 
					) 
			);
		}
		
		$validItemCatalog = array ();
		if ( in_array($_SESSION['allowed_as'], array('PUBLIC')) && $this->collectionName == "item_catalog") {
			$validItemCatalog = array (
					'$or' => array (
							array (
									'use' => 'Purchase and Sale' 
							),
							array (
									'use' => 'Make and Sale' 
							) 
					) 
			);
		}
		
		$validRecord = array ();
		if ($requestForSingleRecord && isset ( $this->record ['_id'] ) && $this->record ['_id'] != '') {
			$validRecord = array (
					'_id' => new MongoId ( $this->record ['_id'] ) 
			);
		}
		
		$validOrganization = $isOwnedByCurrentUrlDomain;
		if ($this->collectionName == "organization") {
			$validOrganization = array (
					'$or' => array (
							$isOwnedByCurrentUrlDomain,
							$hasCurretDomain 
					) 
			);
		}
		
		/* person can be created in other domains and transferd here */
		if ($requestForSingleRecord & in_array($this->collectionName,array('user', 'person','item'))) {
			$validOrganization = array();
		}
		
		$validUser = array ();
		if (! empty ( $_SESSION ['user'] ) && 
			isset ( $_SESSION ['login_person_id'] ) && 
			$_SESSION ['login_person_id'] != '' &&
			! $_SESSION['public_task']
		) {
			$validUser = array('$or' => array( 
				array (
					'created_by' => new MongoId ( $_SESSION ['login_person_id'] )
				),
				array (
					'updated_by' => new MongoId ( $_SESSION ['login_person_id'] )
				)
			));
		}
		
		if ($this->collectionName == "user") {
		}

		$conditions = array ();
		if (! empty ( $validUser )) {
			array_push ( $conditions, $validUser );
		}
		if (! empty ( $validOrganization )) {
			array_push ( $conditions, $validOrganization );
		}
		if (! empty ( $validRecord )) {
			array_push ( $conditions, $validRecord );
		}
		if (! empty ( $validItem )) {
			array_push ( $conditions, $validItem );
		}
		if (! empty ( $validItemCatalog )) {
			array_push ( $conditions, $validItemCatalog );
		}
		
		$retCond = array();
		if (!empty($conditions)) {
			$retCond = array (
					'$and' => $conditions 
			);
		}
		
 		debugPrintArray ( $retCond ); 
		debugPrintArray ( $this->record ); 
		debugPrintArray ( $_SESSION ); 
		return $retCond;
	}

	private function makeSurePersonprofileExists() {
		if ($_SESSION['allowed_as'] == 'PUBLIC') {
			return;
		}
		if (empty ( $_SESSION ['user'] ) ) {
			array_push ( $this->errorMessage, 'Please login first.' );
		}
		if (!in_array($_SESSION['url_action'], array('public_Person', 'public_User'))
			&& (!isset ($_SESSION ['login_person_id'])
				|| $_SESSION ['login_person_id'] == ''
			)
		) {
			array_push ( $this->errorMessage, '# Please create <a href="/person">person</a> profile. Assign it to your credentials and re-login.' );
		}
	}

	private function getReports($doc) {
		$rStr = '';
		if ($this->collectionName == 'item') {
			$rStr .= '<a target="_blank" href="http://'
				.$_SESSION['url_domain'].'/common/didl.php?i='
				.$doc['_id']
				.'&p='.md5($doc['distribution_report_password'])
				.'">Item Distribution</a>';
		}
		if ($rStr != '') {
			$rStr = '<hr />Reports: ' . $rStr;
		}
		return $rStr;
	}

	private function edit($urlArgsArray) {
		$this->makeSurePersonprofileExists();
		
		if (!empty($this->errorMessage)) {
			return $this->showError ();
		}
		
		$this->initializeTask ();
		
		/* make sure id exists */
		$this->record ['_id'] = '';
		if (isset ( $urlArgsArray ['id'] )) {
			$this->record ['_id'] = $urlArgsArray ['id'];
		}
		
		
		if ($this->record ['_id'] == '') {
			array_push ( $this->errorMessage, 'Record id not specified' );
			return $this->showError ();
		}
		
		$this->findCursor = $_SESSION ['mongo_database']->{$this->collectionName}->find (getQueryConditions($this->record));
		
		if ($this->findCursor->count () < 1) {
			 /* array_push ( $this->errorMessage, 'Not a single (' . $this->findCursor->count () . ') record with id "' . $this->record ['_id'] . '" exists in ' . $this->collectionName . '. Or you do not have access to record.' . implode(" ",getQueryConditions($this->record)) . '<pre>' . var_dump($_SESSION) . '</pre>' );  */
			array_push ( $this->errorMessage, 'Not a single (' . $this->findCursor->count () . ') record with id "' . $this->record ['_id'] . '" exists in ' . $this->collectionName . '. Or you do not have access to record.');
			return $this->showError ();
		}
		
		/* get the form for only first record */
		foreach ( $this->findCursor as $doc ) {
			$rStr = '';

			$doc = $this->processFieldsForPresentationAndStorage ( 'after_read', $this->fields, $doc, 1 );
			
			if ($this->curlsMode == 'Present') {
				$rStr .= $this->presentDocument ( $this->subTaskKeyToSave, $this->fields, $doc );
			} elseif ($this->curlsMode == 'Present Json') {
					return $doc;				
			} else {
				$rStr .= $this->getReports($doc);
				/* initialize form */
				$f = new InputForm ();
				$f->curlsMode = $this->curlsMode;
				$f->form ['title'] = $this->getFormTitle ();
				$f->subTaskKeyToSave = $this->subTaskKeyToSave;
				$f->collectionName = $this->collectionName;
				$method = 'save';
				if ($this->curlsMode == 'Remove') {
					$method = 'delete';
				}
				if ($this->curlsMode == 'Copy') {
					unset($doc['_id']);
				}
				$rStr .= $f->showForm ( $urlArgsArray, '/' . $this->collectionName . '/' . $method, $doc, $this->fields );
				$rStr .= $this->getReports($doc);
			}
			$rStr .= $this->showLinks ();
			

			return $rStr;
		}
		array_push ( $this->errorMessage, 'Record does not exists' );
		return $this->showError ();
	}
	public function create($urlArgsArray) {
		$this->makeSurePersonprofileExists();
		if (!empty($this->errorMessage)) {
			return $this->showError ();
		}
		$this->curlsMode = 'Create';
		
		$this->initializeTask ();
		
		/* initialize form */
		$f = new InputForm ();
		$f->form ['title'] = $this->getFormTitle ();
		
		$f->curlsMode = $this->curlsMode;
		$f->subTaskKeyToSave = $this->subTaskKeyToSave;
		$f->collectionName = $this->collectionName;
		
		$rStr = $f->showForm ( $urlArgsArray, '/' . $this->collectionName . '/save', array (), $this->fields );
		$rStr .= $this->showLinks ();
		return $rStr;
	}
	
	private function showInList($fieldDefination, $record, $fieldName) {
		$rStr = '';
		$fieldDefination = array_merge ( $this->fieldDefault, $fieldDefination );
		
		if ($fieldDefination ['show_in_list'] == 0) {
			return $rStr;
		}
		
		if ($fieldDefination['type'] == 'container') {
			foreach ($fieldDefination['fields'] as $subFieldName => $subFieldDefination) {
				if (is_array($record[$fieldName])) {
					foreach ( $record[$fieldName] as $index => $subField ) {
						$rStr .= $this->showInList(
								$subFieldDefination,
								$subField,
								$subFieldName) . ", ";
					}
				} else {
					$rStr .= ' ' . $record[$fieldName];
				}
				$rStr = rtrim ( $rStr, ", " );
			}
			

		} else if ($fieldDefination['type'] == 'foreign_key') {
			$rStr .= $this->showReadOnlyValue (
				$record [$fieldName], /* record id */ 
				$fieldDefination
			);
		} else {
			$rStr .= ' ' . $record[$fieldName];
		}
		
		if ($rStr == '') $rStr = 'No Value';
		return $rStr;
	}
	
	public function readAll($urlArgsArray) {
		$this->makeSurePersonprofileExists();
		if (!empty($this->errorMessage)) {
			return $this->showError ();
		}
		$this->initializeTask ();
		
		if (empty ( $_SESSION ['url_domain_org'] ) || ( string ) $_SESSION ['url_domain_org'] ['_id'] == '') {
			return '<a href="/organization/create">Create</a> a organization profile first and relogin';
		}

		$this->findCursor = $_SESSION ['mongo_database']->{$this->collectionName}->find (getQueryConditions(array()));

		debugPrintArray ($this->findCursor->count(), 'findCursor count'); 
		
		/* get the form */
		
		
		$rStr = '<div class="ui-widget">'; /* main */
		if ($this->collectionName == 'animal_event') {
			$rStr .= '<hr />Reports: <a target="_blank" href="http://admin.'.$_SESSION['url_domain'].'/common/ar.php'.'">Animal Events Report</a>';
		}
 		$rStr .= '<div class="ui-widget-header ui-corner-top jpmHeaderPadding">'
			. $this->getFormTitle () .'</div>';
		$rStr .= '<div class="ui-widget-content ui-corner-bottom jpmContentPadding">'; /* widget content */

		if ($this->curlsMode == 'Present Json All') {
			$records = array();
			foreach ( $this->findCursor as $doc ) {
				array_push($records, $doc);
			}
			return $records;
		} else if ($this->curlsMode == 'Present All') {
			$rStr .= $this->presentAllDocument ( $this->subTaskKeyToSave, $this->fields, $this->findCursor );
		} else { /* Present All */
			
			$rStr .= 'Use Ctrl + F to search a record. <table class="showTable">';
			$rStr .= '<thead><tr>';
			foreach ( $this->fields as $key => $val ) {
				$field2 = array_merge ( $this->fieldDefault, $val );
				if ($field2 ['show_in_list'] == 0) {
					continue;
				}
				$rStr .= '<th>';
				$rStr .= $this->getTitle ( $key );
				$rStr .= '</th>';
			}
			$rStr .= '<th>Show</th><th>Remove</th><th>Copy</th><th>Update</th><th>Present</th>';
			$rStr .= '</tr></thead>';
			$rStr .= '<tbody>';
			
			foreach ( $this->findCursor as $doc ) {
				$this->record = $this->processFieldsForPresentationAndStorage ( 'after_read', $this->fields, $doc, 1 );
				
				$rStr .= '<tr>';
				foreach ( $this->fields as $fieldName => $fieldDefination ) {
					$showStr = $this->showInList(
						$fieldDefination, 
						$this->record, 
						$fieldName
					);
					if ($showStr != '') {
						$rStr .= '<td>' . $showStr . '</td>';
					}
				}
				$subTaskForList = $_SESSION['url_sub_task'];
				if ( $this->collectionName == 'contact') {
					$subTaskForList = $this->record ['medium'];
				}
				$rStr .= '<td>' . $this->getShowLink ($subTaskForList). '</td>';
				$rStr .= '<td>' . $this->getRemoveLink ($subTaskForList). '</td>';
				$rStr .= '<td>' . $this->getCopyLink ($subTaskForList). '</td>';
				$rStr .= '<td>' . $this->getUpdateLink ($subTaskForList). '</td>';
				$rStr .= '<td>' . $this->getPresentLink ($subTaskForList). '</td>';
				$rStr .= '</tr>';
			}
			$rStr .= '</tbody>';
			$rStr .= '</table>';
		} /* Present All */
 		$rStr .= '</div>'; /* widget content */
		$this->record = array ();
		$rStr .= $this->showLinks ();
		if ($this->collectionName == 'animal_event') {
			$rStr .= '<hr />Reports: <a target="_blank" href="http://admin.'.$_SESSION['url_domain'].'/common/ar.php'.'">Animal Events Report</a>';
		}
		$rStr .= '</div>'; /* main */
		return $rStr;
	}
	public function read($urlArgsArray) {
		$this->curlsMode = 'List';
		return $this->readAll ( $urlArgsArray );
	}
	public function remove($urlArgsArray) {
		$this->curlsMode = 'Remove';
		return $this->edit ( $urlArgsArray );
	}
	public function show($urlArgsArray) {
		$this->curlsMode = 'Show';
		return $this->edit ( $urlArgsArray );
	}
	public function update($urlArgsArray) {
		$this->curlsMode = 'Update';
		return $this->edit ( $urlArgsArray );
	}
	public function copy($urlArgsArray) {
		$this->curlsMode = 'Copy';
		return $this->edit ( $urlArgsArray );
	}	
	public function present($urlArgsArray) {
		$this->curlsMode = 'Present';
		return $this->edit ( $urlArgsArray );
	}
	public function presentDocument($subTaskKeyToSave, $fields, $doc) {
		return $doc;
	}
	/* get the document/record */
	public function d($urlArgsArray) {
		return $doc;
	}
	
	public function presentJson($urlArgsArray) {
		$this->curlsMode = 'Present Json';
		return json_encode($this->edit( $urlArgsArray ));
	}
	
	public function presentJsonAll($urlArgsArray) {
		$this->curlsMode = 'Present Json All';
		return json_encode($this->readAll( $urlArgsArray ));
	}
	
	public function presentAll($urlArgsArray) {
		$this->curlsMode = 'Present All';
		return $this->readAll ( $urlArgsArray );
	}
	public function presentAllDocument($subTaskKeyToSave, $fields, $docCursor) {
		return 'Not Implemented';
	}
	public function myModuleName() {
		$modules = new OwebpModule();
		foreach ($modules->table as $record) {
			if (in_array($this->collectionName,$record['collections'])) {
				return $record['value'];
			}
		}
		return '';
	}
}
?>
