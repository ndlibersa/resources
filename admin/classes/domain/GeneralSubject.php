<?php

/*
**************************************************************************************************************************
** CORAL Resources Module v. 1.2
**
** Copyright (c) 2010 University of Notre Dame
**
** This file is part of CORAL.
**
** CORAL is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
**
** CORAL is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License along with CORAL.  If not, see <http://www.gnu.org/licenses/>.
**
**************************************************************************************************************************
*/

class GeneralSubject extends DatabaseObject {

	protected function defineRelationships() {}

	protected function overridePrimaryKeyName() {}

	// Return all the Detailed Subjects associated with this General Subject
	public function getDetailedSubjects(){

		$query = "SELECT DS.* FROM DetailedSubject DS, 
				GeneralDetailSubjectLink GDSL 
				WHERE GDSL.generalSubjectID = '" . $this->generalSubjectID . "' AND 
				DS.detailedSubjectID = GDSL.detailedSubjectID 
				ORDER BY DS.shortName";

		$result = $this->db->processQuery($query, 'assoc');

		$objects = array();

		//need to do this since it could be that there's only one request and this is how the dbservice returns result
		if (isset($result['detailedSubjectID'])){
			$object = new DetailedSubject(new NamedArguments(array('primaryKey' => $result['detailedSubjectID'])));
			array_push($objects, $object);
		}else{
			foreach ($result as $row) {
				$object = new DetailedSubject(new NamedArguments(array('primaryKey' => $row['detailedSubjectID'])));
				array_push($objects, $object);
			}
		}

		return $objects;
	}
	
	//deletes the General Subject and the Linking data
	public function deleteGeneralSubject(){

		$query = "DELETE FROM GeneralDetailSubjectLink WHERE generalSubjectID = '" . $this->generalSubjectID . "'";
		$this->db->processQuery($query);

		$query = "DELETE FROM GeneralSubject WHERE generalSubjectID = '" . $this->generalSubjectID . "'";
		
		return $this->db->processQuery($query);
		
	}
	
	//returns number of times this subject is in use. 
	public function getNumberOfChildren(){

		$query = "SELECT count(*) childCount FROM GeneralDetailSubjectLink WHERE generalSubjectID = '" . $this->generalSubjectID . "' AND detailedSubjectID <> -1;";
		$result = $this->db->processQuery($query, 'assoc');
		
			// Check resource association also
			if ($result['childCount'] == 0) {
					$query = "SELECT count(*) childCount FROM ResourceSubject 
						INNER JOIN GeneralDetailSubjectLink GDSL ON (ResourceSubject.generalDetailSubjectLinkID = GDSL.generalDetailSubjectLinkID)
						WHERE GDSL.generalSubjectID = " . $this->generalSubjectID;
						$result = $this->db->processQuery($query, 'assoc');
			}

		return $result['childCount'];

	}	
	
	// Save the General subject.  Use this function since a record needs to be added to the linking table also.
	public function save(){
	
		if (isset($this->primaryKey)) {
			// Update object
			echo $query;
			$query = "UPDATE GeneralSubject SET shortName='" . str_replace( "'", "''", $this->shortName ) . "' WHERE generalSubjectID=". $this->primaryKey;
			$this->db->processQuery($query);
		} else {
			// Insert object
			
			$query = "INSERT INTO GeneralSubject (`shortName`) VALUES ('" . str_replace( "'", "''", $this->shortName ) . "')";
			$this->primaryKey = $this->db->processQuery($query);

			$query = "INSERT INTO GeneralDetailSubjectLink (`generalSubjectID`,`detailedSubjectID` ) VALUES ('" . $this->primaryKey . "', -1)";
			$this->db->processQuery($query);
			
		}	
		
		return;
		
	}

	//returns number of General subjects that match what is passed. 		
	public function duplicateCheck($shortName){
		$query = "SELECT count(*) duplicateCount FROM GeneralSubject WHERE `shortName` = '" . str_replace( "'", "''", $shortName ) . "'";
		$result = $this->db->processQuery($query, 'assoc');

		return $result['duplicateCount'];
	}	

	//Checking to see if this id is in use with resources or detailed subjects. 	
	public function inUse($id){
		// Check Detailed subjects
		$query = "SELECT count(*) inUse FROM `GeneralDetailSubjectLink` WHERE `GeneralDetailSubjectLink`.`generalSubjectID` = " . $id . " AND detailedSubjectID <> -1;";
		$result = $this->db->processQuery($query, 'assoc');

		// Check resources to see if in use
		if ($result['inUse'] == 0) {
			$query = "SELECT count(*) inUse FROM `ResourceSubject` 
				INNER JOIN `GeneralDetailSubjectLink` 
				ON (`ResourceSubject`.`generalDetailSubjectLinkID` = `GeneralDetailSubjectLink`.`generalDetailSubjectLinkID`)
				WHERE `GeneralDetailSubjectLink`.`generalSubjectID` = " . $id ;
				
			$result = $this->db->processQuery($query, 'assoc');
		}

		return $result['inUse'];
	}		
	
}



?>