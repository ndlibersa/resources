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


if (!isset($_SESSION['loginID'])){
	//the following code takes the remote auth variable name from the config settings and evaluates it to get the actual value from web server
	$config = new Configuration();

	$varName = $config->settings->remoteAuthVariableName;

	//if the first character is a $ it needs to be stripped off for the eval to work
	$theVarStem = ltrim($varName, "$");

	//evaluate the remote variable
	$remoteAuth=eval("return \$$theVarStem;");

	//use the split in case the remote login is supplied as an email address
	list ($loginID,$restofAddr) = explode("@", $remoteAuth);

	session_start();
	$_SESSION['loginID'] = $loginID;
}else{
	$loginID = $_SESSION['loginID'];
}



if ($loginID){
	include_once('setuser.php');
}else{
	$user = new User();
	$errorMessage = "Login is not working.  Check the .htaccess file and the remoteAuthVariableName specified in /admin/configuration.ini";
}



?>
