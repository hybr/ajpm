<?php

/**
 * Function to create the menu HTML code
 * @param string $parent first menu document
 * @return string HTML ul list of the menu
 */

/* find id of All domain */


function getMenu($parent = '571f91cba934995b1b9af90d') {

	$rStr = '';
	if ($parent == '571f91cba934995b1b9af90d') {
		$rStr .= '<ul>';
	}
	foreach ($_SESSION['mongo_database']->database_domain->find() as $databaseDomain ) {
		if (!validDatabaseDomain($databaseDomain)) {
			 continue;
		}
		if ( (string) $databaseDomain ['parent_domain'] == $parent) {
			$rStr .= '<li>' . $databaseDomain ['name'] ;
			$rStr .= '<ul>';
			foreach ($_SESSION['mongo_database']->database_collection->find() as $collection ) {
				if (array_key_exists('domain', $collection) && !empty($collection['domain'])) {
				foreach( $collection['domain'] as $assignedDatabaseDomain) {
					if ( (string) $databaseDomain['_id'] == (string) $assignedDatabaseDomain['name']) {
						$rStr .= '<li><a href="/' . $collection['name'] 
						. '">' . ucwords ( join ( ' ', split ( '_', $collection['name'] ) ) ) . '</a></li>';
					}
				}
				}
			}
			$rStr .= getMenu ( (string)$databaseDomain ['_id'] );
			$rStr .= '</ul>';
			$rStr .= '</li>';
		}
	}
	if ($parent == '571f91cba934995b1b9af90d') {
		$rStr .= '</ul>';
	}
	return $rStr;
}

?>
