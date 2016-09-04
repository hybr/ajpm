<?php

/* Animal Event Report */

/**
 * session_start() creates a session or resumes the current one based on a 
 * session identifier passed via a GET or POST request, or passed via a cookie.
 */
session_start ();

/**
 * A constant to hold the absolute path of ajpm lib folder on server
 *
 * @constant string DIR
 */
define ( 'SERVER_SIDE_PUBLIC_DIR', __DIR__ );

define ( 'SERVER_SIDE_LIB_DIR', SERVER_SIDE_PUBLIC_DIR
 	. DIRECTORY_SEPARATOR . '..'
 	. DIRECTORY_SEPARATOR . 'interfaces'
 	. DIRECTORY_SEPARATOR . 'backend'
 	. DIRECTORY_SEPARATOR . 'php'
);

define ( 'SERVER_SIDE_SP_DIR', SERVER_SIDE_PUBLIC_DIR
	. DIRECTORY_SEPARATOR . '..'
 	. DIRECTORY_SEPARATOR . 'interfaces'
 	. DIRECTORY_SEPARATOR . 'frontend'
	. DIRECTORY_SEPARATOR . 'admin'
	. DIRECTORY_SEPARATOR . 'php'
);

/**
 * Include the common files
 */
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'debug.php';

include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'url_domain.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'layout_and_theme.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'action_and_task.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'mongod_setup.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'common.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'autoload.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'get_menu.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'permission.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'query_condition.php';


// Prevent caching.
header ( 'Cache-Control: no-cache, must-revalidate' );
$offset = 60; # 60 seconds
$expire = "expires: " . gmdate ("D, d M Y H:i:s", time() + $offset) . " GMT";
header ( 'Expires: ' . $expire ); 

// The JSON standard MIME header.
header ( 'Content-type: text/html' );

$errorMessage = '';

/* find arguments */
$urlPartsArray = parse_url ( $_SERVER ['REQUEST_URI'] );

$urlArgsArray = array ();
if (array_key_exists ( 'query', $urlPartsArray )) {
	parse_str ( $urlPartsArray ['query'], $urlArgsArray );
}

/*
if (!isset($urlArgsArray ['c']) || $urlArgsArray ['c'] == '') {
	$errorMessage = "Code is missing in the URL request";
}

if (!isValidMongoObjectID($urlArgsArray ['c'])) {
	$errorMessage = "Wrong code";
}
*/

debugPrintArray($urlArgsArray,'urlArgsArray');
debugPrintArray($_SESSION,'_SESSION');

if (!(array_key_exists('allowed_as', $_SESSION) && $_SESSION['allowed_as'] == 'OWNER')) {
	$errorMessage = 'Not Auhtorised';
}
?>

<?php echo $_SESSION['url_domain_org']['name'] ?>
<br /><?php echo $_SESSION['url_domain_org']['statement'] ?>

<?php if ($errorMessage != '') { ?>
	<?php echo $errorMessage; exit; ?>
<?php } /* if ($errorMessage == '') */ ?>

<?php
	$animalRecordsCursor = $_SESSION ['mongo_database']->animal->find (array(
		'for_org' => $_SESSION ['url_domain_org'] ['_id']
	))->sort(array(
		'tag_number' => 1
	));
	debugPrintArray($animalRecordsCursor,'animalRecordsCursor');

	if (! isset($animalRecordsCursor)) {
		echo "Invalid code."; exit;
	}


?>

<hr /><b>Report Alerts</b>
This report generates following alerts
<ol>
	<li>If cow/bull/ox age is more than 8 years</li>
	<li>If cow will deliver baby in less than two months</li>
	<li>If cow does not got re-crossed between 3 to 10 months of last delivery</li>
</ol>

<hr /><b>Farm Animals Report</b>
This is the report of all the registered animals on the farm

<table border=1 >
	<thead>
		<tr>
			<th>Tag</th>
			<th>Type</th>
			<th>Name</th>
			<th>Mother</th>
			<th>Father</th>
			<th>Events</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach ( $animalRecordsCursor as $animalDoc ) {

		/* get events for this animal */
		$animalEventRecordsCursor = $_SESSION ['mongo_database']->animal_event->find (array(
			'$or' => array(
    			array('animal' => $animalDoc ['_id'] instanceof MongoId ? $animalDoc ['_id'] : new MongoId($animalDoc ['_id'])),
    			array('delivered_animal' => $animalDoc ['_id'] instanceof MongoId ? $animalDoc ['_id'] : new MongoId($animalDoc ['_id'])),
    			array('crossed_by_animal' => $animalDoc ['_id'] instanceof MongoId ? $animalDoc ['_id'] : new MongoId($animalDoc ['_id']))
			)
		));
		$animalEventRecordsCursor->sort(array('date' => 1, 'time' => 1));
		echo '<tr><td>' . $animalDoc['tag_number'];
		echo '</td><td>' . $animalDoc['type'];
		echo '</td><td>';
		if (array_key_exists('name',$animalDoc) && isset($animalDoc['name']) && $animalDoc['name'] != '') { 
			echo $animalDoc['name']; 
		} else { echo ''; /* no name */}

		echo '</td><td>';
		if (array_key_exists('mother',$animalDoc) 
			&& isset($animalDoc['mother']) 
			&& $animalDoc['mother'] != ''
		) { 
			echo getAnimalNameUsingId ($animalDoc['mother']);
		} else { echo ''; }

		echo '</td><td>';
		if (array_key_exists('father',$animalDoc) 
			&& isset($animalDoc['father']) 
			&& $animalDoc['father'] != ''
		) { 
			echo getAnimalNameUsingId ($animalDoc['father']);
		} else { echo ''; }

		echo '</td><td><ul>';
		foreach ( $animalEventRecordsCursor as $animalEventDoc ) {
			$gotCrossed = 0; /* false */
			$deliveredBaby = 0; /* false */
			$providers = null;
			if (array_key_exists('providers',$animalEventDoc) 
				&& isset($animalEventDoc['providers'])
				&& !(empty($animalEventDoc['providers']))
			) { 
				$_ids = array();
				foreach($animalEventDoc['providers'] as $provider){
				    $_ids[] = $provider['name'] instanceof MongoId ? $provider['name'] : new MongoId($provider['name']);
				}
				$providers = $_SESSION ['mongo_database']->person->find(array(
    					'_id' => array( '$in' => $_ids)
				));
			}

			$hours_in_day   = 24;
			$minutes_in_hour= 60;
			$seconds_in_mins= 60;
			$currentDate = new DateTime('today');
			$eventEpoch = $animalEventDoc['date']->sec;
			$eventDate  = new DateTime("@$eventEpoch");
			$diff = $eventDate->diff($currentDate);

			echo '<li>';
			if ($animalEventDoc['type'] == 'Delivered Baby') {
				$daysDeliverdBaby = $diff->days;
				if ($animalDoc['_id'] == $animalEventDoc['delivered_animal']) {
					$animalEventDoc['type'] = 'Birth';
					echo getAnimalNameUsingId ($animalEventDoc['delivered_animal']) . ' ' ;
				} else {
					$deliveredBaby = 1; /* true */
					echo getAnimalNameUsingId ($animalEventDoc['animal']) . ' ' ;
				}
			} else {
				echo getAnimalNameUsingId ($animalEventDoc['animal']) . ' ' ;
			}
			if ($animalEventDoc['type'] == 'Birth') {
				$ageAlertInDays = 0;
				if ($animalDoc['type'] == 'Cow'
					|| $animalDoc['type'] == 'Ox (Breed)'
					|| $animalDoc['type'] == 'Bull (Cart)'
				) {
					$ageAlertInDays = 8 * 365; /* 8 years */
				}
				if ($diff->days > $ageAlertInDays) { echo '<span style="color: red;">'; }
				echo 'Age: ' . $diff->y . " years " . $diff->m . " months " . $diff->d . " day(s)"; 
				if ($diff->days > $ageAlertInDays) { echo '</span>'; }
				echo "<br/>";
				/*
				echo $months    = ($diff->y * 12) + $diff->m . " months " . $diff->d . " day(s)"; echo "<br/>";
				echo $weeks     = floor($diff->days/7) . " weeks " . $diff->d%7 . " day(s)"; echo "<br/>";
				echo $days      = $diff->days . " days"; echo "<br/>";
				echo $hours     = $diff->h + ($diff->days * $hours_in_day) . " hours"; echo "<br/>";
				echo $mins      = $diff->h + ($diff->days * $hours_in_day * $minutes_in_hour) . " minutest"; echo "<br/>";
				echo $seconds   = $diff->h + ($diff->days * $hours_in_day * $minutes_in_hour * $seconds_in_mins) . " seconds"; echo "<br/>";
				*/
			}
			if ($animalEventDoc['type'] == 'Got Crossed'
				&& $animalDoc['_id'] == $animalEventDoc['animal']
			) {
				$gotCrossed = 1; /* true */
				$pregnancyPeriodInDays = 0;
				$takingCareAlertInDays = 0;
				if ($animalDoc['type'] == 'Cow') {
					$pregnancyPeriodInDays = 9 * 30; /* 9 months */
					$takingCareAlertInDays = 2 * 30; /* 2 months before delivery */
				}
				$daysLeftToDelivery = ( $pregnancyPeriodInDays - $diff->days);
				if ($daysLeftToDelivery >= 0) {
					if ($daysLeftToDelivery < $takingCareAlertInDays) { echo '<span style="color: red;">'; }
					echo $daysLeftToDelivery . " days left to delivery out of " . $pregnancyPeriodInDays . ' days';
					if ($daysLeftToDelivery < $takingCareAlertInDays) { echo '</span>'; }
					echo "<br/>";
				}
			}

			echo $animalEventDoc['type'];
			if ($deliveredBaby && $animalDoc['_id'] == $animalEventDoc['animal']) {
				echo ' ' . getAnimalNameUsingId ($animalEventDoc['delivered_animal']);
			}
			if (array_key_exists('date',$animalEventDoc) 
				&& isset($animalEventDoc['date']) 
				&& $animalEventDoc['date'] != ''
			) { 
				echo ' on ' . date('D Y-M-d',$animalEventDoc['date']->sec);
			} else { echo ''; }

			if ($animalEventDoc['type'] == 'Got Crossed' 
				&& isset($animalEventDoc['crossed_by_animal'])
			) {
				echo ' with ' . getAnimalNameUsingId ($animalEventDoc['crossed_by_animal']);
			}

			if (array_key_exists('detail',$animalEventDoc) && isset($animalEventDoc['detail']) && $animalEventDoc['detail'] != '') { 
				echo ' |  ' . $animalEventDoc['detail'];
			} else { echo ''; }
			if (array_key_exists('medicin',$animalEventDoc) && isset($animalEventDoc['medicin']) && $animalEventDoc['medicin'] != '') { 
				echo ' |  medicin used ' . $animalEventDoc['medicin'];
			} else { echo ''; }
			if (array_key_exists('cost',$animalEventDoc) && isset($animalEventDoc['cost']) && $animalEventDoc['cost'] != '') { 
				echo ' |  it costs ' . $animalEventDoc['cost'];
				if (array_key_exists('currency',$animalEventDoc) && isset($animalEventDoc['currency']) && $animalEventDoc['currency'] != '') { 
					echo ' ' . $animalEventDoc['currency'];
				} else { echo ''; }
			} else { echo ''; }
			if (isset($providers)) { 
				$names = '';
				foreach($providers as $provider) {
					$names .= ' ' . $provider['name'][0]['first'] . ' ' . $provider['name'][0]['last'] . ', ';
				}
				if ($names != '') {
					echo ' | help by ' . $names;
				}
			} else { echo ''; }
			echo '</li>';
		}
		if ($deliveredBaby && (!$gotCrossed)
			&& $animalDoc['_id'] == $animalEventDoc['animal']
		) {
			echo '<li>';
			$reCrossedDaysStart = 0;
			$reCrossedDaysEnd = 0;
			if ($animalDoc['type'] == 'Cow') {
				$reCrossedDaysStart = 2 * 30; /* 2 months */
				$reCrossedDaysEnd = 10 * 30; /* 10 months */
			}
			if ($daysDeliverdBaby > $reCrossedDaysStart
				&& $daysDeliverdBaby < $reCrossedDaysEnd
			) {
				echo '<span style="color: red;">'; 
			}
			echo $daysDeliverdBaby . " days since delivered baby " . getAnimalNameUsingId ($animalEventDoc['delivered_animal']). " and not crossed again ";
			if ($daysDeliverdBaby > $reCrossedDaysStart
				&& $daysDeliverdBaby < $reCrossedDaysEnd
			) {
				echo '</span>'; 
			}
			echo '</li>';
		}
		echo '</ul></td></tr>';
	}
?>
	</tbody>
</table>
