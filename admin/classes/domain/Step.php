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

class Step extends DatabaseObject {

	protected function defineRelationships() {}

	protected function overridePrimaryKeyName() {}





	//returns array of step objects that are children
	public function getChildSteps(){

		$query = "SELECT stepID FROM Step S
					WHERE S.priorStepID = '" . $this->stepID . "'
					AND S.workflowID='1'
					ORDER BY stepName";

		$result = $this->db->processQuery($query, 'assoc');

		$objects = array();

		//need to do this since it could be that there's only one request and this is how the dbservice returns result
		if (isset($result['stepID'])){
			$object = new Step(new NamedArguments(array('primaryKey' => $result['stepID'])));
			array_push($objects, $object);
		}else{
			foreach ($result as $row) {
				$object = new Step(new NamedArguments(array('primaryKey' => $row['stepID'])));
				array_push($objects, $object);
			}
		}

		return $objects;
	}


	//returns all steps except this one (for child step selector on workflow admin)
	public function getSteps(){
		$query = "SELECT stepID FROM Step S
					WHERE S.stepID != '" . $this->stepID . "'
					ORDER BY stepName";

		$result = $this->db->processQuery($query, 'assoc');

		$objects = array();

		//need to do this since it could be that there's only one request and this is how the dbservice returns result
		if (isset($result['stepID'])){
			$object = new Step(new NamedArguments(array('primaryKey' => $result['stepID'])));
			array_push($objects, $object);
		}else{
			foreach ($result as $row) {
				$object = new Step(new NamedArguments(array('primaryKey' => $row['stepID'])));
				array_push($objects, $object);
			}
		}

		return $objects;
	}
	
	//returns all distinct step names
	public function allStepNames() {
	  $query = "SELECT DISTINCT(s.stepName) FROM Step s JOIN Workflow w ON w.workflowID = s.workflowID  ORDER BY LOWER(s.stepName)";
	  
	  $result = $this->db->processQuery($query, 'assoc');

		$names = array();
		
		//need to do this since it could be that there's only one request and this is how the dbservice returns result
		if (isset($result['stepName'])){
			$names[]= $result['stepName'];
		}else{
			foreach ($result as $row) {
			  $names[]= $row['stepName'];
			}
		}
		
		return $names;
	}


}

?>