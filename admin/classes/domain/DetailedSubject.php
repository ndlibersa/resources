<?php

/*
**************************************************************************************************************************
** CORAL Resources Module v. 1.0
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

class DetailedSubject extends DatabaseObject {

	protected function defineRelationships() {}

	protected function overridePrimaryKeyName() {}
	
	//returns array of Detail Subject objects associated with the General Subject
	public function getDetailedSubjects(){

		$query = "SELECT DS.* FROM DetailedSubject DS, 
				GeneralDetailSubjectlink GDSL 
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

	//returns number of times this Detailed subject is in use. 
	public function getNumberOfChildren(){

		$query = "SELECT count(*) childCount FROM GeneralDetailSubjectLink WHERE detailedSubjectID = '" . $this->detailedSubjectID . "';";

		$result = $this->db->processQuery($query, 'assoc');

		return $result['childCount'];

	}	

	//returns number of detail subjects that match what is passed. 	
	public function duplicateCheck($shortName){
		$query = "SELECT count(*) duplicateCount FROM DetailedSubject WHERE `shortName` = '" . $shortName . "'";
		$result = $this->db->processQuery($query, 'assoc');

		return $result['duplicateCount'];
	}	
	
}

?>