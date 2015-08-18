<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/common/Configuration.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/Resource.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/ResourceRelationship.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/ResourceType.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/AliasType.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/Alias.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "organizations/admin/classes/domain/Organization.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "organizations/admin/classes/domain/OrganizationRole.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/ResourceOrganizationLink.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "organizations/admin/classes/domain/OrganizationHierarchy.php";

//include_once $_SERVER['DOCUMENT_ROOT'] . "resources/directory.php";


class ImportTool {

      /**
       * @var Configuration 
       *    used to know which module is activated and their DB names
       */
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
            $resourceType = null;
            $aliases = null;

            /*             * *****************************************
             * *     Has the resource to be inserted ?    **
             * ***************************************** */
            $hasToBeInserted = $this->hasResourceToBeInserted($datas, $identifiers);

            /*             * **************************************
             * *                     Datas insertion                **
             * ************************************** */
            if ($hasToBeInserted) {
                  $res = $res_tmp->getNewInitializedResource();

//Resource treatment
                  foreach ($datas as $key => $value) {
                        switch ($key) {
                              case "organization":
                                    $org = $value;
                                    break;
                              case "parentResource":
                                    $parentName = $value;
                                    break;
                              case "resourceType":
                                    $resourceType = $value;
                                    break;
                              case "alias":
                                    $aliases = $value;
                                    break;
                              default:
                                    $res->$key = (string) $value;
                                    break;
                        }
                  }

//ResourceType treatment
                  if ($resourceType != NULL) {
                        $res->resourceTypeID = ResourceType::getResourceTypeID((string) $resourceType);
                  }

                  $res->save();

//Resource identifiers treatment
                  $res->setIdentifiers($identifiers);

//Aliases treatment (history name change/ variant name) 
                  if ($aliases != null) {
                        $this->aliasesTreatment($aliases, $res->resourceID);
                  }

//Parent treatment
                  if ($parentName != null) {
                        $parentID = $this->parentTreatment($parentName);
                        $this->setResourcesRelationship($res->resourceID, $parentID);
                  }

                  if ($org != null) { // Do we have to create an organization or attach the resource to an existing one?
                        $this->organizationTreatment($org, $res->resourceID);
                  }
                  self::$inserted++;
            }
            self::$row++;
      }

// -------------------------------------------------------------------------

      /**
       *  Link a resource with an organization
       * @param int     $roleID   ID of the organization's role (DB primaryKey)
       * @param int     $resourceID     ID of the resource (DB primaryKey)
       * @param int     $organizationID   ID of the organization (DB primaryKey)
       */
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
            $hasToBeInserted = true;

            $resource = $res_tmp->getResourceByIdentifiers($identifiers);
            $nbRes = count($resource);
            
            if ($nbRes == 0) { //resource doesn't exist, we have to create it
                  $hasToBeInserted = true;
            } else {
                  $ii = 0;
                  while ($hasToBeInserted && $ii<$nbRes){
                        $res = $resource[$ii];
                        $parents = $res->getParentResources(); // getParentResources return an array of relationship
                        $nbParents = count($parents);

                        if (($datas['parentResource']) && $nbParents == 0) {
                              $hasToBeInserted = true;
                        } elseif ($datas['parentResource']) { //both resources have a parent
                              $hasToBeInserted = true;
                              foreach ($parents as $relationship) {
                                    $parentResource = new Resource(new NamedArguments(array('primaryKey' => "$relationship->relatedResourceID")));
                                    if ($parentResource->titleText == $datas['parentResource']) {
                                          $hasToBeInserted = false;
                                    }
                              }
                        } elseif ($nbParents != 0) { //existing resource got a parent but not the new one
                              $hasToBeInserted = true;
                        } else {
                              $hasToBeInserted = false;
                        }
                        $ii++;
                  }
            }
            return $hasToBeInserted;
      }

// -------------------------------------------------------------------------	
      
      /**
       *  Create resource's parent or attach it
       * @param string $parentName
       * @return int    ID of the parent resource
       */
      private function parentTreatment($parentName) {
            $resource = new Resource();
            $parentResource = $resource->getResourceByTitle($parentName);

// Search if such parent exists
            $numberOfParents = count($parentResource);
            $parentID = null;

            if ($numberOfParents == 0) { // If not, create parent
                  $parentResource = $resource->getNewInitializedResource();
                  $parentResource->titleText = $parentName; //TODO _ appel à addResource avec $datas complètes
                  $parentResource->save();
                  $parentID = $parentResource->resourceID;
                  self::$parentInserted++;
            } elseif ($numberOfParents == 1) {// Else, attach the resource to its parent.
                  $parentID = $parentResource[0]->resourceID;
                  self::$parentAttached++;
            }

            return $parentID;
      }

// -------------------------------------------------------------------------	
      
      /**
       *  Link a resource with its parent
       * @param int      $resourceID      ID of the resource
       * @param int      $parentID          ID of the parent resouce
       */
      private function setResourcesRelationship($resourceID, $parentID) {
            if ($parentID != NULL) {
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

      /**
       *  create reosurce's organization or attach it
       * @param array    $organizations   array(role => organization)
       * @param int      $resourceID      ID of the resource
       */
      private function organizationTreatment($organizations, $resourceID) {
            $loginID = $_SESSION['loginID'];
            $htmlContent = '';

//Organizations module is used
            if ($this->config->settings->organizationsModule == 'Y') {
                  $dbName = $this->config->settings->organizationsDatabaseName;
                  foreach ($organizations as $role => $orgName) {
                        $organization = new Organization(); 
                        $organizationRole = new OrganizationRole();
                        $organizationID = false;

// Does the organization already exists?
                        $query = "SELECT count(*) AS count FROM $dbName.Organization WHERE UPPER(name) = '" . str_replace("'", "''", strtoupper($orgName)) . "'";
                        $result = $organization->db->processQuery($query, 'assoc');

                        if ($result['count'] == 0) { // If not, we try to create it
                              $organizationID = $this->createOrgWithOrganizationModule($orgName);
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

//Hierarchy platform/provider ( gokb packages)
                  if ($organizations['platform']) {
                        $platformName = $organizations['platform'];
                        $providerName = $organizations['provider'];
                        $this->setOrganizationsHierarchy($platformName, $providerName);
                  }
            }
// If we do not use the Organizations module
            else {
                  foreach ($organizations as $role => $orgName) {
                        $organization = new Organization(); 
                        $organizationRole = new OrganizationRole();
                        $organizationID = false;
// Search if such organization already exists
                        $organizationExists = $organization->alreadyExists($orgName);

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
      
      /**
       * create a new organization when Organization module is activated
       * @param string $orgName
       * @return int    ID of the created organization
       */
      private function createOrgWithOrganizationModule($orgName) {
            $dbName = $this->config->settings->organizationsDatabaseName;
            $loginID = $_SESSION['loginID'];

            $organization = new Organization();
            $query = "INSERT INTO $dbName.Organization SET createDate=NOW(), createLoginID='$loginID', name='" . mysql_escape_string($orgName) . "'";
            try {
                  $result = $organization->db->processQuery($query);
                  $organizationID = $result;
                  self::$organizationsInserted++;
                  array_push(self::$arrayOrganizationsCreated, $orgName);
            } catch (Exception $e) {
//  $htmlContent .= "<p>Organization $orgName could not be added.</p>";
            }
            return $organizationID;
      }

// -------------------------------------------------------------------------
      
      /**
       * child/parent  organizations treatment
       * @param string   $orgName
       * @param string   $parentOrgName
       */
      private function setOrganizationsHierarchy($orgName, $parentOrgName) {
            $orgID = null;
            $parentID = null;
            $relation = new OrganizationHierarchy();
            $dbName = $this->config->settings->organizationsDatabaseName;

            $query = "SELECT organizationID "
                    . "FROM $dbName.Organization "
                    . "WHERE upper(name) = '" . str_replace("'", "''", strtoupper($orgName)) . "'";
            $result = $relation->db->processQuery($query);

            if (count($result) > 0)
                  $orgID = $result[0];


            $query = "SELECT organizationID "
                    . "FROM $dbName.Organization "
                    . "WHERE upper(name) = '" . str_replace("'", "''", strtoupper($parentOrgName)) . "'";
            $result = $relation->db->processQuery($query);
            if (count($result) > 0)
                  $parentID = $result[0];


            if (($orgID != null) &&
                    ($parentID != null) &&
                    (!($relation->relationExists($orgID, $parentID)))) {
//                  $relation->organizationID = $orgID;
//                  $relation->parentOrganizationID = $parentID;
//                  $relation->save();
//$query = "INSERT INTO $dbName.OrganizationHierarchy SET `organizationID`=$orgID, `parentOrganizationID`=$parentID ;";
                  $query = "INSERT INTO $dbName.OrganizationHierarchy VALUES ($orgID, $parentID);";
                  $relation->db->processQuery($query);
            }
      }

// -------------------------------------------------------------------------

      /**
       *  Add resource aliases to resource object in DB
       * @param array    $aliases         array (alias type => array (alias name))
       * @param int      $resourceID      ID of the resource
       */
      private function aliasesTreatment($aliases, $resourceID) {
            foreach ($aliases as $aliasType => $aliasArray) {
                  $typeID = AliasType::getAliasTypeID((string) $aliasType);
                  foreach ($aliasArray as $alias) {
                        $al = new Alias();
                        $al->resourceID = $resourceID;
                        $al->aliasTypeID = $typeID;
                        $al->shortName = (string) $alias;
                        $al->save();
                  }
            }
      }

// -------------------------------------------------------------------------
      /*       * ******************************
       *                Accessors               *
       * ****************************** */
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
      public static function getNbParentAttached() {
            return self::$parentAttached;
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
      public static function getArrayOrganizationsCreated() {
            return self::$arrayOrganizationsCreated;
      }

}

?>