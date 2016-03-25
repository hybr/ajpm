<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_public_RealEstateAsset extends owebp_Base {
		
	function __construct() {
		$this->collectionName = 'real_estate_asset';
	} /* __construct */
	public $fields = array (
		'type' => array (
			'type' => 'string',
			'show_in_list' => 1,
		),
		'type' => array (
			'help' => 'Selet the type of real estate',
			'type' => 'list',
			'list_class' => 'RealEstateAssetType',
			'input_mode' => 'selecting',
			'default' => 'Home',
		)
	); /* fields */
	
	
	public function presentDocument($subTaskKeyToSave, $fields, $doc) {
		$rStr = '';
	
		$rStr .= '<table class="ui-widget">';
		$rStr .= '<tr><td class="ui-widget-header" colspan="2"><h2>' . $doc ['name'] . '</h2></td></tr>';
		
		$rStr .= '<tr class="ui-widget-content"><td class="jpmContentPadding" colspan="2">' . $doc ['statement'] . '</td></tr>';
	
		$rStr .= '<tr class="ui-widget-content">';
		$rStr .= '<td class="jpmContentPadding">';
		$rStr .=  "Abbreviation";
		$rStr .= '</td>';
		$rStr .= '<td class="jpmContentPadding">';
		$rStr .= $doc['abbreviation'];
		$rStr .= '</td>';
		$rStr .= '</tr>';
		if ($doc ['web_site_owner'] != "") {
			$owner = $_SESSION ['mongo_database']->person->findOne ( array (
				'_id' => new MongoId ( ( string ) ($doc ['web_site_owner']) )
			) );
			$rStr .= '<tr class="ui-widget-content">';
			$rStr .= '<td class="jpmContentPadding">';
			$rStr .=  "Owner";
			$rStr .= '</td>';
			$rStr .= '<td class="jpmContentPadding">';
			$personClass =  new public_Person();
			$personClass->record = $owner; 
			$rStr .= $personClass->getOfficialFullName();
			$rStr .= '</td>';
			$rStr .= '</tr>';
		}				
		
		if ($doc ['parent_organization'] != "") {
			$parentOrg = $_SESSION ['mongo_database']->organization->findOne ( array (
				'_id' => new MongoId ( ( string ) ($doc ['parent_organization']) )
			) );
			$rStr .= '<tr class="ui-widget-content">';
			$rStr .= '<td class="jpmContentPadding">';
			$rStr .=  "Parent organization"; 
			$rStr .= '</td>';
			$rStr .= '<td class="jpmContentPadding">';
			$rStr .=  $parentOrg['name'];
			$rStr .= '</td>';			
			$rStr .= '</tr>';
		}
		foreach ($doc ['web_domain'] as $domain) {
			$rStr .= '<tr class="ui-widget-content">';
			$rStr .= '<td class="jpmContentPadding">';
			$rStr .=  "Domain";
			$rStr .= '</td>';
			$rStr .= '<td class="jpmContentPadding">';
			$rStr .= $domain['name'];
			$rStr .= '</td>';
			$rStr .= '</tr>';
		}
		$rStr .= '<tr class="ui-widget-content">';
		$rStr .= '<td class="jpmContentPadding">';
		$rStr .=  "Theme";
		$rStr .= '</td>';
		$rStr .= '<td class="jpmContentPadding">';
		$rStr .= $doc['web_site_theme'];
		$rStr .= '</td>';
		$rStr .= '</tr>';	
		
		if ($doc ['web_site_home_page'] != "") {
			$webPage = $_SESSION ['mongo_database']->web_page->findOne ( array (
				'_id' => new MongoId ( ( string ) ($doc ['web_site_home_page']) )
			) );
			$rStr .= '<tr class="ui-widget-content">';
			$rStr .= '<td class="jpmContentPadding">';
			$rStr .=  "Home Page";
			$rStr .= '</td>';
			$rStr .= '<td class="jpmContentPadding">';
			$rStr .=  $webPage['title'];
			$rStr .= '</td>';
			$rStr .= '</tr>';
		}
		
		$rStr .= '</table>';
		return $rStr;
	}
	
	public function presentAllDocument($subTaskKeyToSave, $fields, $docCursor) {
		$rStr = '<ul>';
		foreach ( $docCursor as $doc ) {
			$rStr .= '<li><a href="/organization/present?id=' . (string) ($doc['_id']) . '" >'
					. $doc['name'] . '</a>: ' . $doc['statement'];
			$parentOrganization = $this->getDocumentById('organization', $doc['parent_organization']);
			if ((string) ($parentOrganization ['_id']) != ( string ) ($parentOrganization ['_id'])) {
			$rStr .= ' | Patent Organization: <a href="/organization/present?id=' 
					. ( string ) ($parentOrganization ['_id']) . '" >' 
					. $parentOrganization ['name'] . '</a>';
			}
			$rStr .=  '</li>';
		}
		return $rStr . '</ul>';
	}	

	public function clients() {
		$rStr = '<ol>';
		$docCursor = $_SESSION ['mongo_database']->{$this->collectionName}->find ();	
		foreach ( $docCursor as $doc ) {
			$rStr .= '<li>' . $doc['name'] . ' : ' . $doc['statement'];
			if (!empty($doc['web_domain'])) {
				$rStr .= '<ul>';
			}
			foreach ($doc['web_domain'] as $domain) {
				$rStr .= '<li><a target="_blank" href="http://'.$domain['name'].'">' . $domain['name'] . '</a></li>';
			}
			if (!empty($doc['web_domain'])) {
				$rStr .= '</ul>';
			}
			$parentOrganization = $this->getDocumentById('organization', (string) $doc['parent_organization']);
			if (!empty($parentOrganization)
				&& (string) ($parentOrganization ['_id']) != ( string ) ($doc ['_id'])) {
				$rStr .= ' | Patent Organization: ' . $parentOrganization ['name'] ;
			}
			$rStr .=  '</li>';
		}
		return $rStr . '</ol>';
	}
	
	/* public function getHomePageId () { */
	public function a($urlArgsArray) {
		$response = array();
		
		/**
		 * get the organization ID
		 */
		$organizationId = getParamValue('a', $urlArgsArray);
			
		$orgRecord = $_SESSION ['mongo_database']->{$this->collectionName}->findOne ( array (
			'_id' => new MongoId((string)(trim($organizationId)))
		) );
			
		$response['home_page_id'] = $orgRecord['web_site_home_page'];
			
		return json_encode($response);

	}
} /* class */
?>
