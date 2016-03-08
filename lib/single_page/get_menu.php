<?php

/**
 * Function to create the menu HTML code
 * @param string $parent first menu record
 * @return string HTML ul list of the menu
 */
function getMenu($parent = 'All') {
	$module = new OwebpModule ();
	$rStr = '';
	if ($parent == 'All') {
		$rStr .= '<ul>';
	}
	foreach ( $module->table as $record ) {
		if ($record ['parent'] == $parent) {
			$rStr .= '<li>' . $record ['value'];
			$rStr .= '<ul>';
			foreach ( $record ['collections'] as $collection ) {
				$rStr .= '<li><a href="/' . $collection . '">' . ucwords ( join ( ' ', split ( '_', $collection ) ) ) . '</a></li>';
			}
			$rStr .= getMenu ( $record ['value'] );
			$rStr .= '</ul>';
			$rStr .= '</li>';
		}
	}
	if ($parent == 'All') {
		$rStr .= '</ul>';
	}
	return $rStr;
}

?>