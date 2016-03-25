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

class OrgNameMapping extends DatabaseObject {

	protected function defineRelationships() {}

	public function getOrgNameMappingByImportConfigID($importConfigID) {
		$query = "SELECT * FROM OrgNameMapping WHERE importConfigID = '" . $importConfigID . "'";
		$result = $this->db->processQuery($query, 'assoc');

		$objects = array();

		if (isset($result['orgNameMappingID'])){
			$object = new OrgNameMapping(new NamedArguments(array('primaryKey' => $result['orgNameMappingID'])));
			array_push($objects, $object);
		}else{
			foreach($result as $row) {
				$object = new OrgNameMapping(new NamedArguments(array('primaryKey' => $row['orgNameMappingID'])));
				array_push($objects, $object);
			}
		}
		return $objects;
	}
}

?>
