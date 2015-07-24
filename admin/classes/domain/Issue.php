<?php

class Issue extends DatabaseObject {

	protected function defineRelationships() {}

	protected function overridePrimaryKeyName() {}

	public function getContacts(){
		$query = "SELECT c.* 
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

}

?>