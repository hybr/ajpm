<?php
class Root2 {

	private $options = array();
	
	function __construct($opts = array()) {
		/* define options  and their defaults */
		$this->setOptionDefault('name', '');
		$this->setOptionDefault('size', '');
		$this->setOptionDefault('value', '');
		$this->setOptionDefault('required', '');
		
		/* possible values bottom, right */
		$this->setOptionDefault('position', 'bottom');
		
		$this->setOptionDefault('jpm_foreign_collection', '');
		$this->setOptionDefault('jpm_foreign_search_fields', '');
		$this->setOptionDefault('jpm_foreign_title_fields', '');
		
		$this->setOptionDefault('id', '');
		$this->setOptionDefault('class', '');
		$this->setOptionDefault('style', '');
				
				
		foreach($opts as $key => $value) {
			$this->setOption($key, $value);
		}
	}

	public function setOption($key = '', $value = '') {
		$key = trim(strtolower($key));
		$this->options[$key] = $value;
	}

	public function setExistsOption($key = '', $value = '') {
		$key = trim(strtolower($key));
		if (array_key_exists($key, $this->options)) {
			$this->options[$key] = $value;
		} else {
			$this->options[$key] = 'Invalid Option';
		}
	}
		
	public function getOption($key = '') {
		$key = trim(strtolower($key));
		if (array_key_exists($key, $this->options) && isset($this->options[$key])) {
			$rStr = '';
			if ($key == 'id' || $key == 'class') {
				$rStr .= 'ajpm-'; 
				/* all css classess and id starts with ajpm. This is to create namespace */	
			}
			return $rStr . trim($this->options[$key]);
		}
		return '';
	}
	
	public function getAllOptions() {
		return $this->options;
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
