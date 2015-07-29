<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/common/Configuration.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/Resource.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/ResourceRelationship.php";

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

	function __construct() {
		$this->config = new Configuration();
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
	 * @param 	$datas 	array		all datas about the resource
	 * @param 	$identifiers 	array		list of identifiers (type =>identifier)
	 */
	public function addResource($datas, $identifiers) {
		$res_tmp = new Resource();
		$org = null;
		$parentName = null;

		$htmlContent = ''; //TODO _ DEBUG _ Displaying after select

		/*******************************************
		 * *     Has the resource to be inserted ?    **
		 * ******************************************/
		$hasToBeInserted = $this->hasResourceToBeInserted($datas, $identifiers);

		/****************************************
		 * *		Datas insertion		**
		 * ***************************************/
		if ($hasToBeInserted) {
			$res = $res_tmp->getNewInitializedResource();
			echo "DEBUG _ Resource insertion !!</br>";
			
			//Resource treatment
			foreach ($datas as $key => $value) {
				if ($key == "organization") {
					$org = $value;
				} elseif ($key == "parentResource") {
					$parentName = $value;
				} else {
					$res->$key = (string) $value;
				}
			}
			$res->save();

			//Resource identifiers treatment
			$res->setIdentifiers($identifiers);

			//Parent treatment
			if ($parentName != null) {
				$parentID = $this->parentTreatment($parentName);
				$this->setResourcesRelationship($res->resourceID, $parentID);
			}

			if ($org != null) { // Do we have to create an organization or attach the resource to an existing one?
				$this->organizationTreatment($org, $res->resourceID);
			}
		} else {
			echo "DEBUG _ Resource not inserted ! </br>";
		}
	}

// -------------------------------------------------------------------------

	private function setResourceOrganizationLink($roleID, $resourceID, $organizationID) {
		$organizationLink = new ResourceOrganizationLink();
		$organizationLink->organizationRoleID = $roleID;
		$organizationLink->resourceID = $resourceID;
		$organizationLink->organizationID = $organizationID;
		$organizationLink->save();
	}

// -------------------------------------------------------------------------

	/**
	 *  Check if this resource already exist and if we have to add it in DB
	 * @param type $datas		array, all resource datas
	 * @param type $identifiers	array, all resource's identifiers
	 * @return boolean		true if the resource has to be inserterted, false else
	 */
	private function hasResourceToBeInserted($datas, $identifiers) {
		$res_tmp = new Resource();
		$hasToBeInserted = false;

		$resource = $res_tmp->getResourceByIdentifiers($identifiers);

		if (count($resource) == 0) { //resource doesn't exist, we have to create it
			$hasToBeInserted = true;
		} elseif ($datas['parentResource']) { //resource exists and got a parent, test title + parent
			$res = $resource[0];
			//$currentResourceID = $res->resourceID;
			$parents = $res->getParentResources();
			$nbParents = count($parents);

			if ($nbParents == 0) { //existing resource doesn't have any parent
				$hasToBeInserted = true;
			} else {
				$hasToBeInserted = true;
				foreach ($parents as $parentResource) {
					if ($parentResource->titleText == $datas['parentResource']) {
						$hasToBeInserted = false;
					}
				}
			}
		}

		return $hasToBeInserted;
	}
	
// -------------------------------------------------------------------------	
	private function parentTreatment($parentName) {
		$resource = new Resource();
		$parentResource = $resource->getResourceByTitle($parentName);
		
		// Search if such parent exists
		$numberOfParents = count($parentResource);
		$parentID = null;

		if ($numberOfParents == 0) { // If not, create parent
			$parentResource = $resource->getNewInitializedResource();
			$parentID = $parentResource->resourceID;
			self::$parentInserted++;
		} elseif ($numberOfParents == 1) {// Else, attach the resource to its parent.
			$parentID = $parentResource[0]->resourceID;
			self::$parentAttached++;
		}
		
		return $parentID;
	}
// -------------------------------------------------------------------------	
	private function  setResourcesRelationship($resourceID, $parentID){
		if($parentID != NULL){
			$resourceRelationship = new ResourceRelationship();
			$resourceRelationship->resourceID = $resourceID;
			$resourceRelationship->relatedResourceID = $parentID;
			$resourceRelationship->relationshipTypeID = '1';  //hardcoded because we're only allowing parent relationships
			if (!$resourceRelationship->exists()) {
				$resourceRelationship->save();
			}
		}
	}
// -------------------------------------------------------------------------
	
	private function organizationTreatment($organizations, $resourceID) {
		$loginID = $_SESSION['loginID'];
		$htmlContent = '';
		//Organizations module is used
		if ($this->config->settings->organizationsModule == 'Y') { //TODO _ hierarchy platform/provider
			$dbName = $this->config->settings->organizationsDatabaseName;
			foreach ($organizations as $role => $orgName) {
				$organization = new Organization(); //TODO _ instanciation dans une boucle j'aime pas trop ça ;)
				$organizationRole = new OrganizationRole();
				$organizationID = false;
				
				// Does the organization already exists?
				$query = "SELECT count(*) AS count FROM $dbName.Organization WHERE UPPER(name) = '" . str_replace("'", "''", strtoupper($orgName)) . "'";
				$result = $organization->db->processQuery($query, 'assoc');

				if ($result['count'] == 0) { // If not, we try to create it
					$query = "INSERT INTO $dbName.Organization SET createDate=NOW(), createLoginID='$loginID', name='" . mysql_escape_string($orgName) . "'";
					try {
						$result = $organization->db->processQuery($query);
						$organizationID = $result;
						self::$organizationsInserted++;
						array_push(self::$arrayOrganizationsCreated, $orgName);
					} catch (Exception $e) {
						$htmlContent .= "<p>Organization $orgName could not be added.</p>";
					}
				}
				// If yes, we attach it to our resource
				elseif ($result['count'] == 1) {
					$query = "SELECT name, organizationID FROM $dbName.Organization WHERE UPPER(name) = '" . str_replace("'", "''", strtoupper($orgName)) . "'";
					$result = $organization->db->processQuery($query, 'assoc');
					$organizationID = $result['organizationID'];
					self::$organizationsAttached++;
				} else {
					$htmlContent .= "<p>Error: more than one organization is called $orgName. Please consider deduping.</p>";
				}

				if ($organizationID) {
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
			if ($organizations['platform']) {
				$platformName = $organizations['platform'];
				$providerName = $organizations['provider'];
			}
		}
		// If we do not use the Organizations module
		else {
			foreach ($organizations as $role => $orgName) {
				$organization = new Organization(); //TODO _ instanciation dans une boucle j'aime pas trop ça ;)
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
		echo $htmlContent;
	}

// -------------------------------------------------------------------------

	/********************************
	 *    		Accessors		*
	 ********************************/
	public static function getNbRow() {
		return self::$row;
	}

// -------------------------------------------------------------------------
	public static function getNbInserted() {
		return self::$inserted;
	}

// -------------------------------------------------------------------------
	public static function getNbParentInserted() {
		return self::$parentInserted;
	}

// -------------------------------------------------------------------------
	public static function incrementNbParentInserted() {
		self::$parentInserted++;
	}
	
// -------------------------------------------------------------------------
	public static function getNbParentAttached() {
		return self::$parentAttached;
	}
	
// -------------------------------------------------------------------------
	public static function incrementNbParentAttached() {
		self::$parentAttached++;
	}
	
// -------------------------------------------------------------------------
	public static function getNbOrganizationsInserted() {
		return self::$organizationsInserted;
	}

// -------------------------------------------------------------------------
	public static function getNbOrganizationsAttached() {
		return self::$organizationsAttached;
	}

// -------------------------------------------------------------------------
}

?>