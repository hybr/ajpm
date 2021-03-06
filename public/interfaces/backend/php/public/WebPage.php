<?php
require_once SERVER_SIDE_LIB_DIR . DIRECTORY_SEPARATOR . "Base.php";
class public_WebPage extends Base {
	function __construct() {
		$this->collectionName = 'web_page';
	} /* __construct */
	public $fields = array (
		'web_page_type' => array(
			'show_in_list' => 1,
			'type' => 'container',
			'fields' => array (
				'name' => array (
					'type' => 'list',
					'list_class' => 'WebPageType',
					'input_mode' => 'clicking',
					'show_in_list' => 1,
					'default' => 'None' 
				),
			),
		),
		'web_page_keywords' => array(
				'type' => 'container',
				'fields' => array (
						'word' => array (
								'searchable' => 1,
						)
				),
				'searchable' => 1,
		),			
		'title' => array(
			'searchable' => 1,
			'show_in_list' => 1,
			'searchResultTitle' => 1,
		),
 		'component' => array (
			'type' => 'container',
			'required' => 1,
 			'show_in_list' => 1,
 			'help' => 'Selection and Sequence of Blocks',
			'fields' => array (
				'type' => array (
					'type' => 'list',
					'list_class' => 'WebPageComponent',
					'input_mode' => 'selecting',
					'default' => 'Paragraph',
					'show_in_list' => 1,
				)
			) 
		),
		'slider_image' => array (
			'type' => 'container',
			'show_in_list' => 0,
			'fields' => array (
				'caption' => array (),
				'statement' => array (),
				'file_name' => array (
					'type' => 'file_list', 
					'required' => 1,
					'searchable' => 1
				),
				'click_link_url' => array (
					'type' => 'url',
					'searchable' => 1
				)
			)
		),
		'media_box' => array (
				'type' => 'container',
				'show_in_list' => 0,
				'fields' => array (
						'title' => array ('required' => 1, 'searchable' => 1),
						'image_file_name' => array ('type' => 'file_list','required' => 1),
						'content' => array('required' => 1, 'searchable' => 1),
						'link_name' => array('required' => 1, 'searchable' => 1),
						'link_url' => array('type' => 'url', 'required' => 1, 'searchable' => 1)
				)
		),
		'paragraph' => array (
				'type' => 'container',
				'fields' => array (
						'title' => array ('required' => 1, 'searchable' => 1),
						'content' => array ('type' => 'textarea', 'required' => 1, 'searchable' => 1, 'searchResultDetail' => 1,)
				)
		),
		'contacts' => array(
			'type' => 'container',
			'fields' => array (
				'contact' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'contact',
					'foreign_search_fields' => 'location,medium,phone_number,fax_number,pager_number,voip_number,email_address,city,pin_or_zip,area,street,home_or_building',
					'foreign_title_fields' => 'location,medium,phone_number,fax_number,pager_number,voip_number,email_address,city,pin_or_zip,area,street,home_or_building'
				)                                                    
			),
		),
		'what_is_next' => array (
				'type' => 'container',
				'show_in_list' => 1,
				'fields' => array (
						'title' => array ('show_in_list' => 1, 'required' => 1, 'searchable' => 1),
						'url' => array ('type' => 'url', 'required' => 1, 'searchable' => 1,)
				)
		),
			
		); /* fields */
	
	public function presentDocument($subTaskKeyToSave, $fields, $doc) {
		// $rStr = '<div class="ui-widget ui-widget-content ui-corner-all" >';
		$rStr = '<div class="ui-widget" >';
		foreach($doc['component'] as $component) {
			if ($component['type'] == 'Paragraph') {
				foreach ($doc['paragraph'] as $paragraph) {
					$rStr .= '<br /><div class="ui-widget-header ui-corner-top jpmHeaderPadding">' 
						. $paragraph['title'] . '</div>';
					$rStr .= '<div class="ui-widget-content ui-corner-bottom jpmContentPadding">' 
						. $paragraph['content'] . '</div>';
				}
				
			}

			if ($component['type'] == 'Learn More') {
				$rStr .= '<br /><div class="ui-widget-content ui-corner-all jpmContentPadding"><br />'
					. (string)$_SESSION ['url_domain_org'] ['statement'] 
					. '<br /><br /><a href="/">Learn More</a><br />&nbsp;</div>';
			}
			
			if ($component['type'] == 'Media Box') {
				$rStr .= '<br /><table class="showTable"><tr>';
				foreach ($doc['media_box'] as $box) {
					$rStr .= '<td>';
					$rStr .= '<div class="ui-widget-header ui-corner-top jpmHeaderPadding">'	
						. $box['title'] . '</div>';
					$rStr .= '<img src="' . $box['image_file_name'] . '" />';
					$rStr .= '<p>' . $box['content'] . '</p>';
					$rStr .= '<a href="'.$box['link_url'].'">'.$box['link_name'].'</a>';
 					$rStr .= '</td>';
				}				
				$rStr .= '</tr></table>';
			}
			if ($component['type'] == 'Contacts') {
				$rStr .= '<br /><div class="ui-widget-header ui-corner-top jpmHeaderPadding">Contact Information</div>';
				$rStr .= '<div class="ui-widget-content ui-corner-bottom jpmContentPadding">';
				$rStr .= '<table class="showTable">';
				$contactInstance = new public_Contact();
				foreach ($doc['contacts'] as $rec) {
					$contactDoc = getOneDocument('contact', '_id', $rec['contact']);
					$rStr .= $contactInstance->showContactAsTableRow($contactDoc);
				}				
				$rStr .= '</table></div>';
			}
			if ($component['type'] == 'Image Slider') {
				$rStr .= '<br /><div class="tabsImageSlider">';

				$i = 1;
				$rStr .= '<ul>';
				foreach ($doc['slider_image'] as $img) {
					$rStr .= '<li>';
					// $rStr .= '<a href="#'.(string)$img['_id'].$i.'">'.$img['caption'].'</a>';
					$rStr .= '<a href="#i'.$i.'">'.$img['caption'].'</a>';
 					$rStr .= '</li>';
					$i++;
				}				
				$rStr .= '</ul>';


				$i = 1;
				foreach ($doc['slider_image'] as $img) {
					// $rStr .= '<div id="'.(string)$img['_id'].$i.'">';
					$rStr .= '<div id="i'.$i.'">';
					$rStr .= '<img src="'.$img['file_name'].'" />';
 					$rStr .= '</div>';
					$i++;
				}				

				$rStr .= '</div>'; /* tabsImageSlider */
			}
			
		}
		$rStr .= '<script>$(document).ready(function() { document.title = "'
				.$_SESSION ['url_domain_org']['abbreviation']
				.' '.$doc['title']
				.'"; });</script>';
		return $rStr . '</div>';
	}	
} /* class */
?>
