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

class ResourceType extends DatabaseObject {

	protected function defineRelationships() {}

	protected function overridePrimaryKeyName() {}


	//returns number of children for this particular contact role
	public function getNumberOfChildren(){

		$query = "SELECT count(*) childCount FROM Resource WHERE resourceTypeID = '" . $this->resourceTypeID . "';";

		$result = $this->db->processQuery($query, 'assoc');

		return $result['childCount'];

	}
 
 public static function getResourceTypeID($type) {
       $id = null;
       $query = "SELECT  resourceTypeID FROM ResourceType WHERE upper(shortName) = '" . str_replace("'", "''", strtoupper($type)) . "'";
       $result =  $this->db->processQuery($query);
       if (count($result) == 0){ //this type doesn't exist, we create it
             $resType = new ResourceType();
             $resType->shortName = $type;
             $resType->save();
             $id = $resType->resourceTypeID;
       } else {
             $id = $result[0];
       }
       return $id;
 }


}

?>