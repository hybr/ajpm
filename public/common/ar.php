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
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'common.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'url_domain.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'layout_and_theme.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'mongod_setup.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'action_and_task.php';
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

	$currentDate = new DateTime('today');
	$hours_in_day   = 24;
	$minutes_in_hour= 60;
	$seconds_in_mins= 60;

?>

<hr /><b>Report Alerts</b>
This report generates following alerts
<ol>
	<li>If cow/bull/ox age is more than 8 years</li>
	<li>If cow will deliver baby in less than two months</li>
	<li>If cow does not got re-crossed between 3 to 10 months of last delivery</li>
</ol>

<hr /><b>Farm Animals Report</b>
This is the report of all the registered animals on the farm on <?php echo $currentDate->format('Y-M-d'); ?>

<table border=1 >
	<thead>
		<tr>
			<th>Tag</th>
			<th>Type</th>
			<th>Name</th>
			<th>Mother</th>
			<th>Father</th>
			<th>Events</th>
			<th>Age</th>
		</tr>
	</thead>
	<tbody>
<?php

	foreach ( $animalRecordsCursor as $animalDoc ) {
		$birthDate = null;
		$deathDate = null;
		$animalAtFarm = 1; /* true */
		$gotCrossed = 0; /* false */
		$deliveredBaby = 0; /* false */
		$warning = array();
		$thisAnimalRowStr = '';

		/* get events for this animal */
		$animalEventRecordsCursor = $_SESSION ['mongo_database']->animal_event->find (array(
			'$or' => array(
    			array('animal' => $animalDoc ['_id'] instanceof MongoId ? $animalDoc ['_id'] : new MongoId($animalDoc ['_id'])),
    			array('delivered_animal' => $animalDoc ['_id'] instanceof MongoId ? $animalDoc ['_id'] : new MongoId($animalDoc ['_id'])),
    			array('crossed_by_animal' => $animalDoc ['_id'] instanceof MongoId ? $animalDoc ['_id'] : new MongoId($animalDoc ['_id']))
			)
		));
		$animalEventRecordsCursor->sort(array('date' => 1, 'time' => 1));
		$thisAnimalRowStr .= getAsCell(
			getUpdateRecordLink(
				'animal',
				$animalDoc['_id'],
				$animalDoc['tag_number']
		        )
		);
		$thisAnimalRowStr .= getAsCell(
			getCreateRecordLink(
				'animal',
				$animalDoc['type'],
				'?type=' . $animalDoc['type']
		        )
		);
		$thisAnimalRowStr .= '<td>';
		if (array_key_exists('name',$animalDoc) && isset($animalDoc['name']) && $animalDoc['name'] != '') { 
			$thisAnimalRowStr .= '<i>Name</i>: ' . $animalDoc['name']; 
		} else { $thisAnimalRowStr .= ''; /* no name */}
		if (array_key_exists('color',$animalDoc) && isset($animalDoc['color']) && $animalDoc['color'] != '') { 
			$thisAnimalRowStr .= '<br /><i>Color</i>: ' . $animalDoc['color']; 
		} else { $thisAnimalRowStr .= ''; /* no color */}
		if (array_key_exists('breed',$animalDoc) && isset($animalDoc['breed']) && $animalDoc['breed'] != '') { 
			$thisAnimalRowStr .= '<br /><i>Breed</i>: ' . $animalDoc['breed']; 
		} else { $thisAnimalRowStr .= ''; /* no color */}

		$thisAnimalRowStr .= '</td><td>';
		if (array_key_exists('mother',$animalDoc) 
			&& isset($animalDoc['mother']) 
			&& $animalDoc['mother'] != ''
		) { 
			$thisAnimalRowStr .= getAnimalNameUsingId ($animalDoc['mother']);
		} else { $thisAnimalRowStr .= ''; }

		$thisAnimalRowStr .= '</td><td>';
		if (array_key_exists('father',$animalDoc) 
			&& isset($animalDoc['father']) 
			&& $animalDoc['father'] != ''
		) { 
			$thisAnimalRowStr .= getAnimalNameUsingId ($animalDoc['father']);
		} else { $thisAnimalRowStr .= ''; }

		$thisAnimalRowStr .= '</td><td>';
		$thisAnimalRowStr .= getCreateRecordLink(
			'animal_event',
			'Create',
			'?animal=' . (string)($animalDoc['_id'])
		);
		$thisAnimalRowStr .= '<table border=1>';

		foreach ( $animalEventRecordsCursor as $animalEventDoc ) {

			$eventEpoch = $animalEventDoc['date']->sec;
			$eventDate  = new DateTime("@$eventEpoch");
			$diff = $eventDate->diff($currentDate);


			$thisAnimalRowStr .= '<tr><td>';

			if (array_key_exists('date',$animalEventDoc) 
				&& isset($animalEventDoc['date']) 
				&& $animalEventDoc['date'] != ''
			) { 
				$thisAnimalRowStr .= date('D Y-M-d',$animalEventDoc['date']->sec);
				$thisAnimalRowStr .= ' ' . date('h:i a',$animalEventDoc['time']->sec);
			} else { $thisAnimalRowStr .= ''; }
			$thisAnimalRowStr .= '</td><td>';

			/* setting up various flags start */
			$animalDefaults = getAnimalDefaults($animalDoc['type']);

			$otherAnimalId = '';
			if ($animalEventDoc['type'] == 'Delivered Baby' 
				&& array_key_exists('delivered_animal',$animalEventDoc)
			) {
				if ($animalDoc['_id'] == $animalEventDoc['delivered_animal']) {
					$otherAnimalId = $animalEventDoc['animal'];
					$animalEventDoc['type'] = 'Birth';
				} else {
					$otherAnimalId = $animalEventDoc['delivered_animal'];
					$deliveredBaby = 1; /* true */
					$daysDeliverdBaby = $diff->days;
				}
			}

			if ($animalEventDoc['type'] == 'Got Crossed'
				&& array_key_exists('crossed_by_animal',$animalEventDoc)
			) {
				if ($animalDoc['_id'] == $animalEventDoc['crossed_by_animal']) {
					/* animalDoc is male */
					$otherAnimalId = $animalEventDoc['animal'];
				} else { 
					/* animalDoc is female */
					$otherAnimalId = $animalEventDoc['crossed_by_animal'];
					$gotCrossed = 1;
					$daysLeftToDelivery = ( $animalDefaults['pregnancy_period_in_days'] - $diff->days);
					if ($daysLeftToDelivery >= 0) {
						$warning['got_crossed'] = getAlertText(
							'<li>' . $daysLeftToDelivery . " days (" . date('Y-M-d', (time() + ($daysLeftToDelivery*24*60*60))). ") left to delivery out of " 
								. $animalDefaults['pregnancy_period_in_days'] . ' days</li>',
							($daysLeftToDelivery < $animalDefaults['pregnancy_taking_care_before_days'])
						);
					}
				}
			}


			if ($animalEventDoc['type'] == 'Birth') { $birthDate = $eventDate; }
			if ($animalEventDoc['type'] == 'Death') { $deathDate = $eventDate; }

			if ($animalEventDoc['type'] == 'Returned'
				|| $animalEventDoc['type'] == 'Death'
				|| $animalEventDoc['type'] == 'Released'
			) {
				$animalAtFarm = 0;
			}
			/* setting up various flags end */

			$thisAnimalRowStr .= getCreateRecordLink(
				'animal_event',
				$animalEventDoc['type'],
				'?animal=' . (string)($animalDoc['_id']). '&type=' . $animalEventDoc['type']
			);
			$thisAnimalRowStr .= '</td><td>';

			if ($otherAnimalId != '') {
				$thisAnimalRowStr .= getAnimalNameUsingId ($otherAnimalId);
			}
			$thisAnimalRowStr .= '</td><td>';



			if (array_key_exists('detail',$animalEventDoc) 
				&& isset($animalEventDoc['detail']) 
				&& $animalEventDoc['detail'] != ''
			) { 
				$thisAnimalRowStr .= ' |  ' . $animalEventDoc['detail'];
			} else { $thisAnimalRowStr .= ''; }

			if (array_key_exists('medicin',$animalEventDoc) 
				&& isset($animalEventDoc['medicin']) 
				&& $animalEventDoc['medicin'] != ''
			) { 
				$thisAnimalRowStr .= ' |  medicin used ' . $animalEventDoc['medicin'];
			} else { $thisAnimalRowStr .= ''; }

			if (array_key_exists('cost',$animalEventDoc) 
				&& isset($animalEventDoc['cost']) 
				&& $animalEventDoc['cost'] != '') { 
				$thisAnimalRowStr .= ' |  it costs ' . $animalEventDoc['cost'];
				if (array_key_exists('currency',$animalEventDoc) && isset($animalEventDoc['currency']) && $animalEventDoc['currency'] != '') { 
					$thisAnimalRowStr .= ' ' . $animalEventDoc['currency'];
				} else { $thisAnimalRowStr .= ''; }
			} else { $thisAnimalRowStr .= ''; }

			$names = getNamesOfPersons($animalEventDoc, 'providers');
			if ($names != '') { $thisAnimalRowStr .= ' | help by ' . $names; } 
			$thisAnimalRowStr .= '</td><td>';

			$thisAnimalRowStr .= getCopyRecordLink(
				'animal_event',
				$animalEventDoc['_id'],
				'Copy'
			);
			$thisAnimalRowStr .= '</td><td>';
			$thisAnimalRowStr .= getUpdateRecordLink(
				'animal_event',
				$animalEventDoc['_id'],
				'Edit'
			);
			$thisAnimalRowStr .= '</td><tr>';
		}

		$thisAnimalRowStr .= '<tr><td colspan=6>';
		if ($animalAtFarm) {
			if ($deliveredBaby && (!$gotCrossed)
				&& $animalDoc['_id'] == $animalEventDoc['animal']
			) {
				$warning['not_crossed_again'] = getAlertText(
					'<li>' . $daysDeliverdBaby . " days since delivered baby and not crossed again</li>",
					!($daysDeliverdBaby > $animalDefaults['re_crossed_days_start'] 
						&& $daysDeliverdBaby < $animalDefaults['re_crossed_days_end'])
				);
			}
		} else {
			$warning['not_at_farm'] = '<li>No more at our farm</li>';
		}

		$thisAnimalRowStr .= '<ul>' . join(' ', $warning) . '</ul>';
		$thisAnimalRowStr .= '</td></tr>';
		$thisAnimalRowStr .= '</table></td><td>';

		if ($birthDate != null) {
			$lastDate = $currentDate;
			$diedText = '';
			if ($deathDate != null) {
				$lastDate = $deathDate;
				$diedText = 'Died in ';
			}
			$ageDiff = $birthDate->diff($lastDate);
			$thisAnimalRowStr .= getAlertText(
				$diedText . $ageDiff->y . " years " . $ageDiff->m . " months " . $ageDiff->d . " day(s)",
				!($ageDiff->days < $animalDefaults['good_age_in_days']) /* true = red */
			);
		}
		$thisAnimalRowStr .= '</td>';

		if ($animalAtFarm) {
			echo '<tr>' . $thisAnimalRowStr . '</tr>';
		} else {
			echo '<tr style="background-color: lightgray;">' . $thisAnimalRowStr . '</tr>';
		}
	}
?>
	</tbody>
</table>
