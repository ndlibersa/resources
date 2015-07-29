<?php

class Issue extends DatabaseObject {

	protected function defineRelationships() {}

	protected function overridePrimaryKeyName() {}

	public function getContacts(){
		$query = "SELECT c.contactID 
				FROM IssueContact ic
				LEFT JOIN Contact c ON c.contactID=ic.contactID 
				WHERE ic.issueID=".$this->issueID;
		$result = $this->db->processQuery($query, 'assoc');

		$objects = array();

		//need to do this since it could be that there's only one request and this is how the dbservice returns result
		if (isset($result['contactID'])){
			$object = new Contact(new NamedArguments(array('primaryKey' => $result['contactID'])));
			array_push($objects, $object);
		}else{
			foreach ($result as $row) {
				$object = new Contact(new NamedArguments(array('primaryKey' => $row['contactID'])));
				array_push($objects, $object);
			}
		}
		return $objects;
	}

	public function getAssociatedOrganization() {
		$orgDB = $this->db->config->settings->organizationsDatabaseName;
		$query = "SELECT o.organizationID 
				  FROM IssueRelationship ir
				  LEFT JOIN `{$orgDB}`.Organization o ON (o.organizationID=ir.entityID AND ir.entityTypeID=1)
				  WHERE ir.issueID={$this->issueID}";
		$result = $this->db->processQuery($query, 'assoc');
		$objects = array();

		if (isset($result['organizationID'])){
			$object = new Organization(new NamedArguments(array('primaryKey' => $result['organizationID'])));
			array_push($objects, $object);
		}
		return $objects;
	}


	public function getAssociatedResources() {
		$query = "SELECT r.resourceID 
				  FROM IssueRelationship ir
				  LEFT JOIN Resource r ON (r.resourceID=ir.entityID AND ir.entityTypeID=2)
				  WHERE ir.issueID={$this->issueID}";
		$result = $this->db->processQuery($query, 'assoc');
		$objects = array();

		//need to do this since it could be that there's only one request and this is how the dbservice returns result
		if (isset($result['resourceID'])){
			$object = new Resource(new NamedArguments(array('primaryKey' => $result['resourceID'])));
			array_push($objects, $object);
		} else{
			foreach ($result as $row) {
				$object = new Resource(new NamedArguments(array('primaryKey' => $row['resourceID'])));
				array_push($objects, $object);
			}
		}
		return $objects;
	}

}

?>