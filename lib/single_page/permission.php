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
	
	if (!isset( $_SESSION ['person'] )) {
		return array();
		/* rbac rule does not exists unterl person profile exists */
	}
	
	$organizationWorkerCursor = $_SESSION ['mongo_database']->organization_worker->find ( array (
			'organization_worker' => new MongoId((string)(trim($_SESSION ['person'] ['_id'])))
	));
	foreach ($organizationWorkerCursor as $organizationWorkerDoc) {
		array_push($organizationWorkerDoc, $personRbacRules);
	}
	
	return $personRbacRules;
}

function isAllowed($collectionName, $subTask) {
	$_SESSION ['allowed_as'] = "NULL";	
	
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
	
	if (!empty($personRbacRules)) {
		foreach($personRbacRules as $personRbacRule) {
			/* $personRbacRule['position'] is rbac_rule id */
			/* $_SESSION['collection']['domain'] has modules associated with collection */
		
			/* get the rbac_rule record/doc */
			$rbacRule = $_SESSION ['mongo_database']->rbac_rule->findOne ( array (
					'_id' => new MongoId((string)(trim($personRbacRule['position'])))
			));
		
			/* $rbacRule['module'] = database_domain id */
			foreach($_SESSION['collection']['domain'] as $databaseDomainOfCollection) {
				if ($databaseDomainOfCollection['name'] ==  $rbacRule['module']) {
					if ($subTask == $rbacRule ['permission'] || $rbacRule ['permission'] == 'All') {
						$_SESSION ['allowed_as'] = "AUTHORATIVE";
						return true;
					}
				}
			}
		}
	} else {
		$_SESSION['authorization_message'] = 'Person does not have position (RBAC) rules assigned';
		/* we give only message but allow user to continue so that he can create person profile */
	}
	
	/* once person join the website he/she must be allowed to create the person record */
	if (isset ( $_SESSION ['user'] ) && ! empty ( $_SESSION ['user'] ) && in_array ( strtolower ( $_SESSION ['url_action'] ), array (
			'public_query.php',
			'public_person',
			'public_user',
			'public_contact',
			'public_webpage',
			'public_organization',
			'public_itemcatalog',
			'public_item'
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
			'public_itemcatalog-presentall-all',
			'public_item-present-all',
			'public_shoppingcart-present-all',
			'public_shoppingcart-presentall-all',
			'public_webpage-present-all',
			'public_webpage-present_document-all',
				
			'public_itemcatalog-presentjsonall-all',
			'public_item-presentjsonall-all',
				
			'public_item-presentjson-all',
			'public_webpage-presentjson-all',
			'public_realestateasset-presentjson-all',
				
			'public_user-login-all',
			'public_contact-presentall-all',
			'public_organization-clients-all',
			'public_familytree-presentall-all',
			'public_familytree-present-all',
				
			'public_search.php-presentall-all'
	) )) {
		$_SESSION ['allowed_as'] = "PUBLIC";
		return true;
	}	
	
	return false;
} /* function isAllowed */

