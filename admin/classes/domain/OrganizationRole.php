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

class OrganizationRole extends DatabaseObject {

	protected function defineRelationships() {}

	protected function overridePrimaryKeyName() {}


	//gets an array of organization roles
	public function getArray(){

		$orgRoleArray = array();

		$config = new Configuration;

		//if the org module is installed get the org name from org database
		if ($config->settings->organizationsModule == 'Y'){
			$dbName = $config->settings->organizationsDatabaseName;

			//get roles
			$query = "SELECT * FROM " . $dbName . ".OrganizationRole ORDER BY shortName;";


			if ($orgResult = mysql_query($query)){
				while ($orgRoleRow = mysql_fetch_assoc($orgResult)){
					$orgRoleArray[$orgRoleRow['organizationRoleID']] = $orgRoleRow['shortName'];
				}
			}


		//otherwise if the org module is not installed get the org roles from this database
		}else{

			//get roles
			$query = "SELECT * FROM OrganizationRole ORDER BY shortName;";


			if ($orgResult = mysql_query($query)){
				while ($orgRoleRow = mysql_fetch_assoc($orgResult)){
					$orgRoleArray[$orgRoleRow['organizationRoleID']] = $orgRoleRow['shortName'];
				}
			}

		}


		return $orgRoleArray;


	}



	//Try to determine provider ID for submit new resource when provider is entered
	public function getProviderID(){

		$config = new Configuration;

		//if the org module is installed get the org name from org database
		if ($config->settings->organizationsModule == 'Y'){
			$dbName = $config->settings->organizationsDatabaseName;

			//get roles
			$query = "SELECT * FROM " . $dbName . ".OrganizationRole WHERE upper(shortName) LIKE '%PROVIDER%';";

			$result = $this->db->processQuery($query, 'assoc');

			if (isset($result['organizationRoleID'])){
				return $result['organizationRoleID'];
			}else{
				return '';
			}

		//otherwise if the org module is not installed get the org roles from this database
		}else{

			//get roles
			$query = "SELECT * FROM OrganizationRole WHERE upper(shortName) LIKE '%PROVIDER%';";
			$result = $this->db->processQuery($query, 'assoc');

			if (isset($result['organizationRoleID'])){
				return $result['organizationRoleID'];
			}else{
				return '';
			}

		}



	}





	//returns number of children for this particular contact role
	public function getNumberOfChildren(){

		$query = "SELECT count(*) childCount FROM ResourceOrganizationLink WHERE organizationRoleID = '" . $this->organizationRoleID . "';";

		$result = $this->db->processQuery($query, 'assoc');

		return $result['childCount'];

	}





}

?>