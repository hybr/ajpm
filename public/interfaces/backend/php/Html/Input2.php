<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Html" . DIRECTORY_SEPARATOR . "Tag2.php";
class Html_Input2 extends Html_Tag2 { 
	function __construct($opts = array()) {
		parent::__construct($opts);
		$this->setOptionDefault('tag', 'input');
		$this->setOptionDefault('value', $this->getOption('content'));
	}

} /* class */
?>