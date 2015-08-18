<?php

class Downtime extends DatabaseObject {

	protected function defineRelationships() {}

	protected function overridePrimaryKeyName() {}

	public function load() {

		//This is a custom load method that joins the downtime type name into the attributes

		//if exists in the database
		if (isset($this->primaryKey)) {	
			$query = "SELECT d.*, dt.name, i.subjectText
				  FROM Downtime d
				  LEFT JOIN DowntimeType dt ON dt.downtimeTypeID=d.downtimeTypeID
				  LEFT JOIN Issue i ON i.issueID=d.issueID
				  WHERE d.downtimeID={$this->primaryKey}";
			
			$result = $this->db->processQuery($query, 'assoc');

			foreach (array_keys($result) as $attributeName) {
				$this->addAttribute($attributeName);
				$this->attributes[$attributeName] = $result[$attributeName];
			}

		}else{
			// Figure out attributes from existing database
			$query = "SELECT COLUMN_NAME FROM information_schema.`COLUMNS` WHERE table_schema = '";
			$query .= $this->db->config->database->name . "' AND table_name = '$this->tableName'";// MySQL-specific
			foreach ($this->db->processQuery($query) as $result) {
				$attributeName = $result[0];
				$this->addAttribute($attributeName);
			}
		}
	}

	public function save() {

		//We have added the name attribute after the fact, and here, we are cleaning it up
		unset($this->attributes["name"]); 
		unset($this->attributesNames["name"]);

		unset($this->attributes["subjectText"]); 
		unset($this->attributesNames["subjectText"]);

		parent::save();
	}

	public function getDowntimeTypesArray() {
		$query = "SELECT dt.*
				  FROM DowntimeType dt";

		$result = $this->db->processQuery($query, "assoc");
		$names = array();

		foreach ($result as $name) {
			array_push($names, $name);
		}

		return $names;
	}

}

?>