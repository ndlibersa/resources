<?php

/*
 * *************************************************************************************************************************
 * * CORAL Resources Module v. 1.0
 * *
 * * Copyright (c) 2010 University of Notre Dame
 * *
 * * This file is part of CORAL.
 * *
 * * CORAL is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * *
 * * CORAL is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * *
 * * You should have received a copy of the GNU General Public License along with CORAL.  If not, see <http://www.gnu.org/licenses/>.
 * *
 * *************************************************************************************************************************
 */

/* Put in admin/classes/domain */

class Identifier extends DatabaseObject {

	//protected function overridePrimaryKeyName() {}; //TODO_A quoi ça sert ?? idem pour defineRelationShip etc ...


	public function getIdentifierTypeID($type) {
		$config = new Configuration();
		$dbName = $config->settings->resourcesDatabaseName;
		$query = "SELECT identifierTypeID FROM IdentifierType WHERE UPPER(identifierName) = '" . str_replace("'", "''", strtoupper($type)) . "'";
		$result = $this->db->processQuery($query);

		if (count($result) == 0) {//this type doesn't exist
			//Have we to create this type ?
			if (is_numeric($type)) { //this identifier type will be considered as "isxn" (come from csv import)
				$id = 1;
			} else { //we need to create this type
				$query = "INSERT INTO $dbName.IdentifierType SET identifierName='" . mysql_escape_string($type) . "'"; //TODO_mysql_escape_string() OBSOLETE --> mysqli_escape_string
				$result = $this->db->processQuery($query);
				$id = $result[0];
			}
		} else {
			$id = $result[0];
		}

		return $id;
	}

}

?>