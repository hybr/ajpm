<?php
class Root {

	function __construct($opts = array()) {
		/* echo '<pre>'; print_r($opts); echo '</pre>'; */
		foreach($opts as $key => $value) {
			$this->setOption($key, $value);
		}
	/*
		$this->callbacks[$onAction][] = $callbackMethod;
		echo get_class($this) . '<pre>|'; print_r($this->getOptions()); echo '|</pre>'; 
	*/

	}

	private $options = array();

	public function getOptions() {
		return $this->options;
	}

	public function setOptionDefault($key = '', $value = '') {
		$this->options[$key] = $value;
	}

	public function setOption($key = '', $value = '') {
		if (array_key_exists($key, $this->options)) {
			$this->options[$key] = $value;
		} else {
			$this->options[$key] = 'Invalid Option';
		}
	}

	public function getOption($key = '') {
		if (array_key_exists($key, $this->options) && isset($this->options[$key])) {
			return $this->options[$key];
		}
		return '';
	}

	public function createTreeView($array, $currentParent, $currLevel = 0) {
	
		$retStr = '';
	
		foreach ($array as $key => $record) {
	
			if ($currentParent == $record['parent']) {
					
				$retStr .=  '<li>';
	
				$id = 'id'.$currLevel.$key.$this->getOption( 'name' );
				 
				$checked = '';
				if ($record['id'] == $this->getOption('value')) {
					$checked = 'checked=checked';
				}
				$inputType = 'radio';
				if ($this->getOption( 'multiple' ) != '') {
					$inputType = 'checkbox';
				}
				$retStr .= '<input type="' . $inputType . '"'
					.' id="'.$id.'"'
					.' name="'.$this->getOption( 'name' ).'"'
			    		.' value="'. $record['id'] .'" '
	    				. $checked
	    				.' />';
				 
				$retStr .= '<label for="'.$id.'">'.$record['text'].'</label>';
					
				$childsText = $this->createTreeView ($array, $record['id'], $currLevel+1);
				 
				if ($childsText != '') {
					$retStr .= '<ol>' . $childsText . '</ol>';
				}
					
				$retStr .= '</li>';
			}
	
		}
	
		return $retStr;
	
	}
} /* class */
?>
