<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Root2.php";
class Html_Tag2 extends Root2 {
	function __construct($opts = array()) {
		$this->setOptionDefault ( 'tag', '' );
		$this->setOptionDefault ( 'content', '' );
		$this->setOptionDefault ( 'type', 'text' );
		/* update options with input */
		parent::__construct ( $opts );
	}
	private function getTagAttribute($attribute) {
		$attribute = trim(strtolower($attribute));
		if ($this->getOption ( $attribute ) != '') {
			if ($attribute == 'id') {
				return ' id="' . 'ajpm_' . str_replace ( ']', '', str_replace ( '[', '_', $this->getOption ( 'id' ) ) ) . '"';
			} else {
				return ' ' . $attribute . '="' . $this->getOption ( $attribute ) . '"';
			}
		} else {
			return '';
		}
	}
	private function getTagId() {
		if ($this->getOption ( 'id' ) != '') {
			
		} else {
			return '';
		}
	}
	private function getStartTag() {
		if ($this->getOption ( 'tag' ) != '') {
			$rStr = '<' . $this->getOption('tag') . $this->getTagId();
			foreach ( $this->getOptions () as $key => $value ) {
				if (in_array ( $key, array (
						'tag',
						'content',
						'id' 
				) )) {
					continue;
				}
				$rStr .= $this->getTagAttribute ( $key );
			} /* foreach($this->getOptions() as $key => $value) */
			$rStr .= ' >';
			return $rStr;
		} else {
			return '';
		}
	}
	private function getEndTag() {
		if ($this->getOption ( 'tag' ) != '') {
			return '</' . $this->getOption ( 'tag' ) . '>';
		} else {
			return '';
		}
	}
	public function get() {
		if (in_array ( $this->getOption ( 'tag' ), array (
				'input' 
		) )) {
			return $this->getStartTag () . $this->getEndTag ();
		}
		return $this->getStartTag () . $this->getOption ( 'content' ) . $this->getEndTag ();
	}
} /* class */
?>
