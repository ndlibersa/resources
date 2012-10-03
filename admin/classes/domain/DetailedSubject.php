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

class DetailedSubject extends DatabaseObject {

	protected function defineRelationships() {}

	protected function overridePrimaryKeyName() {}
	
	//returns number of times this Detailed subject is in use. 
	public function getNumberOfChildren(){

		$query = "SELECT count(*) childCount FROM GeneralDetailSubjectLink WHERE detailedSubjectID = '" . $this->detailedSubjectID . "';";
		$result = $this->db->processQuery($query, 'assoc');

		return $result['childCount'];

	}	

	//returns number of detail subjects that match what is passed. 	
	public function duplicateCheck($shortName){
		$query = "SELECT count(*) duplicateCount FROM DetailedSubject WHERE `shortName` = '" . str_replace( "'", "''", $shortName ) . "'";
		$result = $this->db->processQuery($query, 'assoc');

		return $result['duplicateCount'];
	}	

	//returns number of detail subjects that are in use.  These are associated with a resource. 	
	public function inUse($detailedSubjectID, $generalSubjectID){

		if ($generalSubjectID != -1) {	
			$query = "SELECT count(*) inUse FROM `ResourceSubject`
					  INNER JOIN `GeneralDetailSubjectLink` ON (`ResourceSubject`.`generalDetailSubjectLinkID` = `GeneralDetailSubjectLink`.`generalDetailSubjectLinkID`)
					WHERE
					  `GeneralDetailSubjectLink`.`detailedSubjectID` = " . $detailedSubjectID .
					" AND `GeneralDetailSubjectLink`.`generalSubjectID` = " . $generalSubjectID;
		} else {
			$query = "SELECT count(*) inUse FROM `GeneralDetailSubjectLink` WHERE `GeneralDetailSubjectLink`.`detailedSubjectID` = " . $detailedSubjectID;
		}
		
		$result = $this->db->processQuery($query, 'assoc');

		return $result['inUse'];
	}	
	
}

?>