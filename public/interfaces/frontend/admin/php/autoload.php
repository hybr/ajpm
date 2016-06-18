<?php
/**
 * function to load the class based on first word in URL path
 *
 * From PHP Manual: __autoload — Attempt to load undefined class. You can
 * define this function to enable classes autoloading.
 *
 * If class does not exists then it throws exception that class file does not
 * exists
 *
 * first function checks about PhpGedcom requirement
 *
 * @param string $class_name Name of the class to load
 * @throws Exception It throws if file is not found
 */
function __autoload($class_name) {
	if (strpos ( $class_name, 'PhpGedcom\\' ) !== false) {
		$pathToPhpGedcom = SERVER_SIDE_LIB_DIR . '/mrkrstphr/php-gedcom/library';
		$file = $pathToPhpGedcom . DIRECTORY_SEPARATOR . str_replace ( '\\', DIRECTORY_SEPARATOR, $class_name ) . '.php';
		if (file_exists ( $file )) {
			require_once ($file);
		} else {
			throw new Exception ( 'Gedcom File ' . $file . ' not found' );
		}
		return;
	}
	
	/* replace the _ in class name to directory seperator */
	$owebpClassName = str_replace ( '_', DIRECTORY_SEPARATOR, $class_name );
	$file = SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . $owebpClassName . '.php';
	if (file_exists ( $file )) {
		require_once $file;
		if (! class_exists ( $class_name, false )) {
			throw new Exception ( "Unable to load class: $class_name", E_USER_WARNING );
		}
	} else {
		throw new Exception ( 'File ' . $file . ' not found' );
	}
}

?>
