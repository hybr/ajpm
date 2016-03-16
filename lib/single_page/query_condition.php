<?php
/**
 * Based of authorization and request create the record featching condition
 * @param array $record
 * @return array Array of conditions for featching records
 */
function getQueryConditions($record = array()) {
	$conds = array ();
	
	$requestForSingleRecord = in_array ( strtolower ( $_SESSION ['url_task'] ), array (
			'create',
			'update',
			'copy',
			'remove',
			'show',
			'present',
			'presentjson'
	) );
	$requestForMultipleRecord = in_array ( strtolower ( $_SESSION ['url_task'] ), array (
			'read',
			'presentall' 
	) );
	
	$globalCollections = array (
			'user',
			'person',
			'item' 
	);
	
	$recordId = '';
	if ($requestForSingleRecord && ! empty ( $record ) && isset ( $record ['_id'] )) {
		$recordId = new MongoId ( ( string ) $record ['_id'] );
	}
	$recordsOwnedByOrg = array (
			'for_org' => new MongoId ( $_SESSION ['url_domain_org'] ['_id'] ) 
	);
	if (strtolower ( $_SESSION ['url_action'] ) == 'public_organization') {
		$recordsOwnedByOrg = array (
				'$or' => array (
						array (
								'_id' => new MongoId ( $_SESSION ['url_domain_org'] ['_id'] ) 
						),
						array (
								'for_org' => new MongoId ( $_SESSION ['url_domain_org'] ['_id'] ) 
						) 
				) 
		);
	}
	
	/* common conditions */
	
	$userRecords = array (
			'ERROR' => 'No Person Defined' 
	);
	if (isset ( $_SESSION ['person'] ) && isset ( $_SESSION ['person'] ['_id'] )) {
		/* show only records which are created and updated by user's person profile */
		$userRecords = array (
				'$or' => array (
						array (
								'created_by' => new MongoId ( ( string ) $_SESSION ['person'] ['_id'] ) 
						),
						array (
								'updated_by' => new MongoId ( ( string ) $_SESSION ['person'] ['_id'] ) 
						) 
				) 
		);
		/* if we are looking at person collection then show only person's profile */
		if (strtolower ( $_SESSION ['url_action'] ) == 'public_person') {
			$userRecords = array (
					'$or' => array (
							array (
									'_id' => new MongoId ( ( string ) $_SESSION ['person'] ['_id'] ) 
							),
							array (
									'created_by' => new MongoId ( ( string ) $_SESSION ['person'] ['_id'] ) 
							),
							array (
									'updated_by' => new MongoId ( ( string ) $_SESSION ['person'] ['_id'] ) 
							) 
					) 
			);
		}
	}
	
	$orgRecords = $recordsOwnedByOrg;
	
	if (strtolower ( $_SESSION ['url_action'] ) == 'public_item') {
		$itemForSaleCond = array (
				"_id" => array (
						'$exists' => true 
				) 
		); /* an always true condition */
		/* for sale item for public/user only for others we have all items */
		if (in_array ( $_SESSION ['allowed_as'], array (
				'PUBLIC',
				'USER' 
		) )) {
			$itemForSaleCond = array (
					'$or' => array (
							array (
									'price.for' => 'Purchase and Sale' 
							),
							array (
									'price.for' => 'Make and Sale' 
							) 
					) 
			);
		}
		$commonItemsForSale = array (
				'$and' => array (
						array (
								'manufacturar' => 'COMMON_ITEM' 
						),
						$itemForSaleCond 
				) 
		);
		$commonItemForSale = array (
				'$and' => array (
						array (
								'_id' => $recordId 
						),
						$commonItemsForSale 
				) 
		);
		$orgItemsForSale = array (
				'$and' => array (
						$recordsOwnedByOrg,
						$itemForSaleCond 
				) 
		);
		$orgItemForSale = array (
				'$and' => array (
						array (
								'_id' => $recordId 
						),
						$orgItemsForSale 
				) 
		);
		
		$orgRecords = array (
				'$or' => array (
						$orgItemsForSale,
						$commonItemsForSale 
				) 
		);
	}
	
	$orgRecord = array (
			'$and' => array (
					array (
							'_id' => $recordId 
					),
					$orgRecords 
			) 
	);
	
	$userRecord = array (
			'$and' => array (
					array (
							'_id' => $recordId 
					),
					$userRecords 
			) 
	);
	/* all actions/collections */
	
	if (in_array ( $_SESSION ['allowed_as'], array (
			'USER' 
	) )) {
		if (! is_null ( $recordId ) && $recordId != '') {
			$conds = $userRecord;
		} else {
			$conds = $userRecords;
		}
	}
	
	if (in_array ( $_SESSION ['allowed_as'], array (
			'OWNER',
			'AUTHORATIVE' 
	) )) {
		if (! is_null ( $recordId ) && $recordId != '') {
			$conds = $orgRecord;
		} else {
			$conds = $orgRecords;
		}
	}
	
	if (in_array ( $_SESSION ['allowed_as'], array (
			'PUBLIC' 
	) )) {
		if (! is_null ( $recordId ) && $recordId != '') {
			if (in_array ( strtolower ( $_SESSION ['url_task'] ), array (
					'present',
					'presentjson'
			) )) {
				$conds = $orgRecord;
			}
		} else {
			if (in_array ( strtolower ( $_SESSION ['url_task'] ), array (
					'presentall' 
			) )) {
				$conds = $orgRecords;
			}
		}
	}
	
	/* specific actions or collections */
	
	if (strtolower ( $_SESSION ['url_action'] ) == 'public_user') {
		if (in_array ( $_SESSION ['allowed_as'], array (
				'USER',
				'OWNER',
				'AUTHORATIVE' 
		) )) {
			$conds = array (
					'_id' => new MongoId ( ( string ) $_SESSION ['user'] ['_id'] ) 
			);
		}
		if (in_array ( $_SESSION ['allowed_as'], array (
				'NULL',
				'PUBLIC' 
		) )) {
			$conds = array (
					'_id' => 'NO ACCESS' 
			);
		}
	}
	if (strtolower ( $_SESSION ['url_action'] ) == 'public_person') {
		if (in_array ( $_SESSION ['allowed_as'], array (
				'USER',
				'OWNER',
				'AUTHORATIVE' 
		) )) {
			if (! is_null ( $recordId ) && $recordId != '') {
				$conds = $userRecord;
			} else {
				$conds = $userRecords;
			}
		}
		if (in_array ( $_SESSION ['allowed_as'], array (
				'NULL',
				'PUBLIC' 
		) )) {
			$conds = array (
					'_id' => 'NO ACCESS' 
			);
		}
	}
	
	/* all other collections */
	
	if (empty ( $conds )) {
		$conds = array (
				'_id' => 'NO ACCESS' 
		);
	}
	
	if (strtolower ( $_SESSION ['url_action'] ) == 'public_user') {
		if (in_array ( $_SESSION ['user'] ['email_address'], array (
				'sharma.yogesh.1234@gmail.com' 
		) )) {
			$conds = array (); /* show all users so that I can fix the issues */
		}
	}
	if ($_SESSION ['debug']) {
		echo '<pre>getQueryCriteria $conds = ';
		print_r ( $conds );
		echo '</pre>';
		echo '<pre>$_SESSION = ';
		print_r ( $_SESSION );
		echo '</pre>';
		echo '<pre>$requestForMultipleRecord= ';
		print_r ( $requestForMultipleRecord );
		echo '</pre>';
		echo '<pre>$recordId= ';
		print_r ( $recordId );
		echo '</pre>';
	}
	return ($conds);
}