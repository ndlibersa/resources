<?php

include_once $_SERVER['DOCUMENT_ROOT']."resources/admin/classes/common/Configuration.php";


class ImportTool {

	private $config;
	
	/**
	* @var int
	* 		Number of resources treated
	*/
	private static $row;

	/**
	* @var int
	* 		Number of resources inserted
	*/
    private static $inserted;

    /**
	* @var int
	* 		Number of parents inserted
	*/
    private static $parentInserted;

    /**
	* @var int
	* 		Number of parents which resources were attached
	*/
    private static $parentAttached;

    /**
	* @var int
	* 		Number of organizations created
	*/
    private static $organizationsInserted;

    /**
	* @var int
	* 		Number of organizations which resources were attached
	*/
    private static $organizationsAttached;

    /**
	* @var array
	* 		Organizations created
	*/
    private static $arrayOrganizationsCreated;

// -------------------------------------------------------------------------

	function __construct(){
		$config = new Configuration();
		self::$row = 0;
    	self::$inserted = 0;
    	self::$parentInserted = 0;
    	self::$parentAttached = 0;
    	self::$organizationsInserted = 0;
    	self::$organizationsAttached = 0;
    	self::$arrayOrganizationsCreated = array();
	}

// -------------------------------------------------------------------------

	/**
	* add a resource to the database
	* @param 	$datas 			array 	all datas about the resource
	* @param 	$identifiers 	array 	list of identifiers
	*/
	public function addResource($datas, $identifiers) {
		$res = new Resource();
		$res_tmp = new Resource(); //useless ?

		$org = null;
		$parentName = null;
		$hasToBeInserted = false;

		$htmlContent = '';

		/**********************************************
		 **     Has the resource to be inserted ?    **
		 **********************************************/
		//Identifiers test
		$resource = $res_tmp->getResourceByIsbnOrISSN($identifiers);
		$idExist = count($resource);

		if ($idExist == 0) { //resource doesn't exist, we have to create it
			$hasToBeInserted = true;
		}			
		elseif ($datas['parentResource']){ //resource exists and got a parent, test title + parent
			$res = $resource[0];
			$currentResourceID = $res->resourceID;
			$parents = $res->getParentResources();
			$nbParents = count($parents);

			if ($nbParents == 0 ){ //existing resource doesn't have any parent
				$hasToBeInserted = true;
			} else {
				$hasToBeInserted = true;
				foreach ($parents as $parentResource) {
					if($parentResource->titleText == $datas['parentResource']){
						$hasToBeInserted = false;
					}
				}
			}
		}
		else{ //Resource exists but doesn't have any parent in $datas (package or orphan resource)
			$hasToBeInserted = false;
		}
			

		/*********************************************
		 **				Datas insertion				**
		 *********************************************/
		if ($hasToBeInserted){
			//Resource treatment
			foreach ($datas as $key => $value) {
				if ($key == "organization") { $org = $value; }
				elseif ($key == "parentResource") { $parentName = $value; }
				else { $res->$key = $value; }
			}

			$res->createLoginID		= $loginID;
			$res->createDate 		= date( 'Y-m-d');
			$res->updateLoginID    	= '';
          	$res->updateDate      	= '';
          	$res->statusID         	= 1; //in progress, don't know why ...
			$res->save();

			//Resource identifiers
			$res->setIdentifiers($identifiers); 

			//Parent treatment _ see line 219 (import.php)
			if ($parentName != null){
				// Search if such parent exists
            	$numberOfParents = count($resourceObj->getResourceByTitle($parentName));
            	$parentID = null;

            	if ($numberOfParents == 0) { // If not, create parent
		            $parentResource = new Resource();
					$parentResource->createLoginID = $loginID;
					$parentResource->createDate    = date( 'Y-m-d' );
					$parentResource->titleText     = $parentName;
					$parentResource->statusID      = 1;
					$parentResource->save();

					$parentID = $parentResource->resourceID;
					self::$parentInserted++;

				} elseif ($numberOfParents == 1) {
					// Else, attach the resource to its parent.
					$parentResource = $resourceObj->getResourceByTitle($parentName);
					$parentID = $parentResource[0]->resourceID;

					self::$parentAttached++; 
				}
			}

			//Save relationship
			$resourceRelationship = new ResourceRelationship();
          	$resourceRelationship->resourceID = $res->resourceID;
          	$resourceRelationship->relatedResourceID = $parentID;
          	$resourceRelationship->relationshipTypeID = '1';  //hardcoded because we're only allowing parent relationships
			if (!$resourceRelationship->exists()) {
				$resourceRelationship->save();
			}

			if ($org != null) { // Do we have to create an organization or attach the resource to an existing one?
	            // If we use the Organizations module
				if ($config->settings->organizationsModule == 'Y') { //TODO _ hierarchy platform/provider
    	        	$dbName = $config->settings->organizationsDatabaseName;
    	        	foreach ($org as $role => $orgName) {
    	        		$organization = new Organization();//TODO _ instanciation dans une boucle j'aime pas trop ça ;)
	            		$organizationRole = new OrganizationRole();
	            		$organizationID = false;
    	        		// Does the organization already exists?
              			$query = "SELECT count(*) AS count FROM $dbName.Organization WHERE UPPER(name) = '" . str_replace("'", "''", strtoupper($orgName)) . "'";
              			$result = $organization->db->processQuery($query, 'assoc');

              			if ($result['count'] == 0){ // If not, we try to create it

              				$query = "INSERT INTO $dbName.Organization SET createDate=NOW(), createLoginID='$loginID', name='" . mysql_escape_string($orgName) . "'";
			                try {
			                  $result = $organization->db->processQuery($query);
			                  $organizationID = $result;
			                  self::$organizationsInserted++;
			                  array_push(self::$arrayOrganizationsCreated, $orgName);
			                } catch (Exception $e) {
			                  $htmlContent .=  "<p>Organization $orgName could not be added.</p>";
			                }
              			} 
              			// If yes, we attach it to our resource
              			elseif ($result['count'] == 1) {
              				$query = "SELECT name, organizationID FROM $dbName.Organization WHERE UPPER(name) = '" . str_replace("'", "''", strtoupper($orgName)) . "'";
			                $result = $organization->db->processQuery($query, 'assoc');
			                $organizationID = $result['organizationID'];
			                self::$organizationsAttached++;
              			}
              			else {
			                $htmlContent .= "<p>Error: more than one organization is called $organizationName. Please consider deduping.</p>";
			            }

			            if ($organizationID){
			            	// Get role
			                $query = "SELECT organizationRoleID from OrganizationRole WHERE shortName='" . mysql_escape_string($role) . "'";
			                $result = $organization->db->processQuery($query);
			                $roleID = ($result[0]) ? $result[0] : 1;
			                // Does the organizationRole already exists?
			                $query = "SELECT count(*) AS count FROM $dbName.OrganizationRoleProfile WHERE organizationID=$organizationID AND organizationRoleID=$roleID";
			                $result = $organization->db->processQuery($query, 'assoc');
			                // If not, we try to create it
			                if ($result['count'] == 0) {
			                 	$query = "INSERT INTO $dbName.OrganizationRoleProfile SET organizationID=$organizationID, organizationRoleID=$roleID";
			                  	try {
				                    $result = $organization->db->processQuery($query);
				                    if (!in_array($orgName, self::$arrayOrganizationsCreated)) {
				                      self::$organizationsInserted++;
				                      array_push(self::$arrayOrganizationsCreated, $orgName);
				                    }
			                  	} catch (Exception $e) {
			                    	$htmlContent .= "<p>Unable to associate organization $orgName with its role.</p>";
			                  	}
			                }
			            }

			            // Let's link the resource and the organization.
			            // (this has to be done whether the module Organization is in use or not)
			            if ($organizationID && $roleID) {
							$this->setResourceOrganizationLink($roleID, $resourceID, $organizationID);
			            }
    	        	}

    	        	//TODO _ hierarchy platform/provider (packages)
    	        	if($org['platform']){
    	        		$platformName = $org['platform'];
    	        		$providerName = $org['provider'];
    	        	}
				}
				// If we do not use the Organizations module
				else {
					foreach ($org as $role => $orgName) {
				$organization = new Organization();//TODO _ instanciation dans une boucle j'aime pas trop ça ;)
	            		$organizationRole = new OrganizationRole();
	            		$organizationID = false;
    	        		// Search if such organization already exists
		             	$organizationExists = $organization->alreadyExists($orgName);
		              	$parentID = null;

						if (!$organizationExists) { // If not, create it
                			$organization->shortName = $orgName;
                			$organization->save();
                			$organizationID = $organization->organizationID();
                			self::$organizationsInserted++;
                			array_push(self::$arrayOrganizationsCreated, $orgName);
              			} elseif ($organizationExists == 1) { // Else, 
                			$organizationID = $organization->getOrganizationIDByName($orgName);
                			self::$organizationsAttached++;
              			} else {
                			$htmlContent .= "<p>Error: more than one organization is called $orgName. Please consider deduping.</p>";
             			}

             			$organizationRoles = $organizationRole->getArray();
            			if (($roleID = array_search($role, $organizationRoles)) == 0) {
                			// If role is not found, fallback to the first one.
                			$roleID = '1';
              			} 
    	        	
              			// Let's link the resource and the organization.
			            // (this has to be done whether the module Organization is in use or not)
			            if ($organizationID && $roleID) {
							$this->setResourceOrganizationLink($roleID, $resourceID, $organizationID);
			            }
    	        	}
				}
			}
		}
	}

// -------------------------------------------------------------------------

	private function setResourceOrganizationLink($roleID, $resourceID, $organizationID) {
		$organizationLink = new ResourceOrganizationLink();
		$organizationLink->organizationRoleID = $roleID;
		$organizationLink->resourceID = $resource->resourceID;
		$organizationLink->organizationID = $organizationID;
		$organizationLink->save();
	}


// -------------------------------------------------------------------------

		/****************************
		 *    		Accessors		*
		 ****************************/
	public static function getNbRow(){
		return self::$row;
	}
// -------------------------------------------------------------------------
	public static function getNbInserted(){
		return self::$inserted;
	}
// -------------------------------------------------------------------------
	public static function getNbParentInserted(){
		return self::$parentInserted;
	}
// -------------------------------------------------------------------------
	public static function getNbParentAttached(){
		return self::$parentAttached;
	}
// -------------------------------------------------------------------------
	public static function getNbOrganizationsInserted(){
		return self::$organizationsInserted;
	}
// -------------------------------------------------------------------------
	public static function getNbOrganizationsAttached(){
		return self::$organizationsAttached;
	}
// -------------------------------------------------------------------------
	
}


?>