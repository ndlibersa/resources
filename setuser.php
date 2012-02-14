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


//Get user privilege
$user = new User(new NamedArguments(array('primaryKey' => $loginID)));
$testUser = $user->privilegeID;

//if the user doesn't exist in database set them up with shell user account (we assume that since they were authorized to come in they can create new requests and view existing)
if (!($testUser)){
	$user = new User();
	$user->loginID = $loginID;

	//default user to read only privilege
	$user->privilegeID='3';

	//default to no account tab privilege
	$user->accountTabIndicator='0';

	try{
		$ldap = new LdapPerson($loginID);

		if ($ldap->lname){
			$user->lastName = $ldap->lname;
			$user->firstName = $ldap->fname;
		}
	}catch(Exception $e) {
		$errorMessage = "LDAP Connection is not working or has timed out.  Please check configuration.ini to verify settings.";
	}
	$user->save();
}


$privilege = new Privilege(new NamedArguments(array('primaryKey' => $user->privilegeID)));

?>