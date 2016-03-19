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

class ImportConfig extends DatabaseObject {

	protected function defineRelationships() {}

	public function allAsArray() {
		$query = "SELECT * FROM ImportConfig ORDER BY 2";
		$result = $this->db->processQuery($query, 'assoc');

		$resultArray = array();
		$rowArray = array();

		if (isset($result['shortName'])){

			foreach (array_keys($result) as $attributeName) {
				$rowArray[$attributeName] = $result[$attributeName];
			}
			array_push($resultArray, $rowArray);
		}else{

			foreach ($result as $row) {
				foreach (array_keys($this->attributeNames) as $attributeName) {
					$rowArray[$attributeName] = $row[$attributeName];
				}
				array_push($resultArray, $rowArray);
			}
		}
		return $resultArray;
	}

	public function removeOrgNameMappings() {
		$query = "DELETE
			FROM OrgNameMapping
			WHERE importConfigID = '" . $this->importConfigID . "'";
		$result = $this->db->processQuery($query);
	}

	public function removeImportConfig() {
		$this->removeOrgNameMappings();
		$this->delete;
	}
}

?>
