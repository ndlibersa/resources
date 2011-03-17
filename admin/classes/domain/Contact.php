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

class Contact extends DatabaseObject {

	protected function defineRelationships() {}

	protected function overridePrimaryKeyName() {}

	//returns array of role objects
	public function getContactRoles(){

		$query = "SELECT CR.* FROM ContactRole CR, ContactRoleProfile CRP where CRP.contactRoleID = CR.contactRoleID AND contactID = '" . $this->contactID . "'";

		$result = $this->db->processQuery($query, 'assoc');

		$objects = array();

		//need to do this since it could be that there's only one request and this is how the dbservice returns result
		if (isset($result['contactRoleID'])){
			$object = new ContactRole(new NamedArguments(array('primaryKey' => $result['contactRoleID'])));
			array_push($objects, $object);
		}else{
			foreach ($result as $row) {
				$object = new ContactRole(new NamedArguments(array('primaryKey' => $row['contactRoleID'])));
				array_push($objects, $object);
			}
		}

		return $objects;
	}


	//deletes all contact roles associated with this org
	public function removeContactRoles(){

		$query = "DELETE FROM ContactRoleProfile WHERE contactID = '" . $this->contactID . "'";

		return $this->db->processQuery($query);
	}


}

?>