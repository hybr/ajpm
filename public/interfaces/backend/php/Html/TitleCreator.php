<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Root.php";
class TitleCreator extends Root { 

	function __construct($opts = array()) {
		/* define options  and their defaults */
		$this->setOptionDefault('string', '');

		/* update options with input */
		parent::__construct($opts);
	}

	/* convert string with underscores as Title */
	public function get() {
		$ta = split( '_', $this->getOption('string'));
		$rS = '';
		foreach ( $ta as $w ) {
			$rS .= ' ' . ucfirst ( strtolower ( $w ) );
		}
		return $rS;
	}


} /* class */
?>
