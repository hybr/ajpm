<?php

require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Root.php";
class Form_Field_Checkboxes extends Root {

        function __construct($opts = array()) {
                /* define options  and their defaults */
                $this->setOptionDefault('name', '');
                $this->setOptionDefault('size', '');
                $this->setOptionDefault('value', '');
                $this->setOptionDefault('required', '');
                $this->setOptionDefault('position', 'bottom'); /* possible values bottom, right */
                $this->setOptionDefault('dataClassName', '');

                /* update options with input */
                parent::__construct($opts);
        }

	public function show() {
		$dataClassName = $this->getOption('dataClassName');
		if ($dataClassName == '') { return 'data source mising'; }
		$dataClassInstance = new $dataClassName();

		/* option are content  of select tag */
		$selectOptionsList = '';
		$currentValue = $this->getOption('value');
		$i = 0;
                foreach ( $dataClassInstance->getTable () as $r ) {
			$checked = '';
			if (in_array($r['value'], $currentValue)) {
                                $checked = 'checked';
                        }
			$oneBox = '<label>';
			$input = new Html_Input(array(
				'type' => 'checkbox',
				'name' => $this->getOption('name') . '['.$i.']',
				'content' => $r['value'],
				'checked' => $checked,
			));
			$input->setOption('type','checkbox');
			$oneBox .= $input->get();
			$oneBox .= $r['title'];
			$oneBox .= '</label>';
			$selectOptionsList .= $oneBox;
			$i = $i + 1;
                } /* foreach ( $dataClassInstance->getTable () as $r ) */

		/* return with cover */
		return (new Html_FormFieldComponentCover(array(
			'position' => $this->getOption('position'),
			'content' => $selectOptionsList,
		)))->get();

	}

} /* class */
