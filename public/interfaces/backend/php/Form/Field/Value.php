<?php

require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Root.php";
class Form_Field_Value extends Root {

        function __construct($opts = array()) {
                /* define options  and their defaults */
                $this->setOptionDefault('value', '');
                $this->setOptionDefault('jpm_foreign_collection', '');
                $this->setOptionDefault('jpm_foreign_title_fields', '');
                $this->setOptionDefault('dataClassName', '');
                /* update options with input */
                parent::__construct($opts);
        }

	public function get() {

		$value = $this->getOption('value');

		if ($this->getOption('dataClassName') != '') {
                	$dataClassName = $this->getOption('dataClassName');
	                $dataClassInstance = new $dataClassName();
                        if ($dataClassInstance->titleValueConversionRequired) {
				$titleValue = '';
                                foreach ( $dataClassInstance->getTable () as $r ) {
                                        if ($r ['value'] == $value) {
                                                $titleValue = $r ['title'];
						break;
                                        }
                                }
                                if ($titleValue == '') {
                                        $titleValue = $value;
                                }
				$value = $titleValue;
                        }
		}
		

		if ($this->getOption('jpm_foreign_collection') != '' && $value != '') {
                        $value = showSelectedReadOnlyFieldsFromDocOfCollection ( 
				$this->getOption('value'), 
				$this->getOption('jpm_foreign_collection'), 
				$this->getOption('jpm_foreign_title_fields')
			);
		}
		/* return with cover */

		return $value;
	}

} /* class */
