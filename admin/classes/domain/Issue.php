<?php

class Issue extends DatabaseObject {

	protected function defineRelationships() {}

	protected function overridePrimaryKeyName() {}

	public function getContacts() {
		$orgDB = $this->db->config->settings->organizationsDatabaseName;
		$query = "SELECT ic.contactID,c.name,c.emailAddress
				FROM IssueContact ic
				LEFT JOIN `{$orgDB}`.Contact c ON c.contactID=ic.contactID 
				WHERE ic.issueID=".$this->issueID;
		$result = $this->db->processQuery($query, 'assoc');
		$objects = array();

		if (isset($result['contactID'])){
			return array($result);
		} else {
			return $result;
		}
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

	public function getAllAlertable() {
		$orgDB = $this->db->config->settings->organizationsDatabaseName;

		$query = "SELECT i.*,(SELECT GROUP_CONCAT(CONCAT(sc.name,' - ',sc.emailAddress) SEPARATOR ', ')
								FROM IssueContact sic 
								LEFT JOIN `{$orgDB}`.Contact sc ON sc.contactID=sic.contactID
								WHERE sic.issueID=i.issueID) AS `contacts`,
							 (SELECT GROUP_CONCAT(se.titleText SEPARATOR ', ')
								FROM IssueRelationship sir 
								LEFT JOIN Resource se ON (se.resourceID=sir.entityID AND sir.entityTypeID=2)
								WHERE sir.issueID=i.issueID) AS `appliesto`,
							 (SELECT GROUP_CONCAT(sie.email SEPARATOR ', ')
								FROM IssueEmail sie 
								WHERE sie.issueID=i.issueID) AS `CCs`
				  FROM Issue i
				  WHERE TIMESTAMPDIFF(DAY,i.dateCreated,CURDATE())%i.reminderInterval=0
				  AND i.dateClosed IS NULL";
		
		$result = $this->db->processQuery($query, 'assoc');

		$objects = array();
		//need to do this since it could be that there's only one request and this is how the dbservice returns result
		if (isset($result['issueID'])){
			return array($result);
		}else{
			return $result;
		}

		return $objects;
	}

}

?>