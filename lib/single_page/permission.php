<?php
/**
 * This framework will implement authorizations alsong with authentication.
 * Authorization will decides who can do what based on following information
 *
 * organization role = organization, team, level, location
 * person position = person, organization role
 * rbac permission = all, create, remove, update, list, show, present, present all
 * rbac rules =  organization role, rbac permission
 *
 * @param string $moduleNames
 * @param string $subTask
 * @return boolean
 */

function getPersonRbacRules() {
	$personRbacRules = array();
	
	if (isset( $_SESSION ['person']) && isset($_SESSION ['person'] ['_id'])) {
		$organizationWorkerCursor = $_SESSION ['mongo_database']->organization_worker->find ( array (
			'person' => new MongoId((string)(trim($_SESSION ['person'] ['_id'])))
		));
		foreach ($organizationWorkerCursor as $organizationWorkerDoc) {
			array_push($personRbacRules, $organizationWorkerDoc);
		}
	} else {
		array_push($personRbacRules, 'person id is missing');
	}
	
	return $personRbacRules;
}

function isAllowed($collectionName, $subTask) {
	
	$_SESSION ['allowed_as'] = "NULL";

	/* allow yogesh as super admin */
	if ( isset($_SESSION ['user']) 
		&& isset($_SESSION ['user']['email_address'])
		&& $_SESSION ['user']['email_address'] == 'sharma.yogesh.1234@gmail.com'
	) {
		$_SESSION ['allowed_as'] = "OWNER";
		return true;
	}
	
	/* once person join the website he/she must be allowed to create the person record */
	if (isset ( $_SESSION ['user'] ) && ! empty ( $_SESSION ['user'] ) && in_array ( strtolower ( $_SESSION ['url_action'] ), array (
			'public_query.php',
			'public_search.php',
			'public_person',
			'public_user',
			'public_contact',
	) )) {
		$_SESSION ['allowed_as'] = "USER";
		return true;
	}

	/* allow public tasks */
	$task = strtolower ( $_SESSION ['url_action'] )
	. '-' . strtolower ( $_SESSION ['url_task'] )
	. '-' . strtolower ( $_SESSION ['url_sub_task'] );
	
	if (in_array ( $task, array (
			'public_database_domain-present-all',
			'public_user-login-all',
				
			'public_checkuser-exsistance-all',
			'public_checkuser-e-all',
			'public_checkuser-password-all',
			'public_checkuser-p-all',
				
			'public_user-authenticate-all',
			'public_user-logout-all',
			'public_user-join-all',
			'public_user-register-all',
			'public_user-forgetpassword-all',
			'public_user-va-all',
			'public_user-sendactivationemail-all',
			'public_shoppingcart-present-all',
			'public_shoppingcart-presentall-all',

			'public_contact-present-all',
			'public_contact-present_document-all',
			'public_contact-presentjson-all',
			'public_contact-presentjsonall-all',

			'public_webpage-present-all',
			'public_webpage-present_document-all',
			'public_webpage-presentjson-all',
			'public_webpage-presentjsonall-all',
				
			'public_person-present-all',
			'public_person-present_document-all',
			'public_person-presentjson-all',
			'public_person-presentjsonall-all',

			'public_itemcatalog-present-all',
			'public_itemcatalog-present_document-all',
			'public_itemcatalog-presentjson-all',
			'public_itemcatalog-presentjsonall-all',

			'public_item-present-all',
			'public_item-present_document-all',
			'public_item-presentjson-all',
			'public_item-presentjsonall-all',

			'public_realestateasset-present-all',
			'public_realestateasset-present_document-all',
			'public_realestateasset-presentjson-all',
			'public_realestateasset-presentjsonall-all',
				
			'public_user-login-all',
			'public_organization-clients-all',
			'public_familytree-presentall-all',
			'public_familytree-present-all',
				
			'public_search.php-presentall-all'
	) )) {
		$_SESSION ['allowed_as'] = "PUBLIC";
		return true;
	}	

	/* check if the domain associated with this collection is allowed */
	if (!validDatabaseCollection($collectionName)) {
		return false;
	}
	
	/* creater is admin */
	/* echo "<pre>"; print_r($_SESSION['person']); echo '</pre>'; */
	$orgOwnerId = '';
	if (isset ( $_SESSION ['url_domain_org'] ['org_owner'] )) {
		$orgOwnerId = ( string ) $_SESSION ['url_domain_org'] ['org_owner'];
	}
	$orgCreatedId = '';
	if (isset ( $_SESSION ['url_domain_org'] ['created_by'] )) {
		$orgCreatedId = ( string ) $_SESSION ['url_domain_org'] ['created_by'];
	}
	if (isset ( $_SESSION ['person'] ) && isset ( $_SESSION ['person'] ['_id'] ) && ($orgOwnerId == ( string ) $_SESSION ['person'] ['_id'] || $orgCreatedId == ( string ) $_SESSION ['person'] ['_id'])) {
		$_SESSION ['allowed_as'] = "OWNER";
		return true;
	}	
	
	/* get list of roles for the person */
	$personRbacRules = getPersonRbacRules();
	debugPrintArray($personRbacRules, '$personRbacRules');
	
	if (!empty($personRbacRules)) {
		foreach($personRbacRules as $personRbacRule) {
			/* $personRbacRule['position'] is rbac_rule id */
			/* $_SESSION['collection']['domain'] has modules associated with collection */
		
			/* get the rbac_rule record/doc */
			$rbacRule = array();
			if (isset($personRbacRule['position'])) {
				$rbacRule = $_SESSION ['mongo_database']->rbac_rule->findOne ( array (
					'_id' => new MongoId((string)(trim($personRbacRule['position'])))
				));
			}
			debugPrintArray($rbacRule, '$rbacRule');
			
			/* $rbacRule['module'] = database_domain id */
			foreach($_SESSION['collection']['domain'] as $databaseDomainOfCollection) {
				debugPrintArray($databaseDomainOfCollection, '$databaseDomainOfCollection');
				if (isset($rbacRule['module']) && $databaseDomainOfCollection['name'] ==  $rbacRule['module']) {
					if ($subTask == $rbacRule ['permission'] || $rbacRule ['permission'] == 'All') {
						$_SESSION ['allowed_as'] = "AUTHORATIVE";
						return true;
					}
				}
			}
		}
	}
	$_SESSION['authorization_message'] = 'Your position in organization does not allow this';
	/* we give only message but allow user to continue so that he can create person profile */	
	return false;
} /* function isAllowed */

