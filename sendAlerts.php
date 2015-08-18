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

session_start();
include_once 'directory.php';

$util = new Utility();
$config = new Configuration();

if ($config->settings->enableAlerts == 'Y'){
	$alertDaysInAdvance = new AlertDaysInAdvance();
	//returns array of all days in advance objects
	$alertDaysArray = $alertDaysInAdvance->all();

	$resourceIDArray = array();

	//loop through each of the days, e.g. 30, 60, 90
	foreach ($alertDaysArray as $alertDays){
		//get resources that fit this criteria
		if (is_numeric($alertDays->daysInAdvanceNumber)){
			foreach($alertDays->getResourcesToAlert() as $resource){
				$resourceIDArray[] = $resource->resourceID;
			}
		}

	}


	if (count($resourceIDArray) > 0){
		//now get unique resource IDs out
		$resourceIDArray = array_unique($resourceIDArray);

		//now loop through each resource and send the email alert
		foreach ($resourceIDArray as $resourceID){

			$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

			$sendToArray = array();

			//determine who to send the email to
			$alertEmailAddress = new AlertEmailAddress();

			foreach($alertEmailAddress->allAsArray() as $alertEmail){
				$sendToArray[] = $alertEmail['emailAddress'];
			}


			//formulate email to be sent
			$email = new Email();
			$email->to = implode(", ", $sendToArray);
			$email->message = $util->createMessageFromTemplate('Alert', $resourceID, $resource->titleText, '', '', '');
			$email->subject		= "CORAL Alert: " . $resource->titleText;

			$email->send();

		}
	}else{
		echo "No Resources found fitting alert day criteria";		
	}

	//Get all Issues that should be alerted today
	$Issue = new Issue();
	$alertableIssuesArray = $Issue->getAllAlertable();

	//If we have alertable issues then loop over them
	if (count($alertableIssuesArray) > 0){
		foreach($alertableIssuesArray as $alertableIssue) {
			//start building the email body
			$emailMessage = "Subject: {$alertableIssue['subjectText']}\r\n\r\n
			Body: {$alertableIssue['bodyText']}\r\n\r\n
			Applies To: {$alertableIssue['appliesto']}\r\n
			\r\n\r\nContacts: \r\n\r\n
			{$alertableIssue['contacts']}\r\n";

			foreach(explode(",", $alertableIssue['CCs']) as $emailAddr) {
				mail($emailAddr, "New Issue: {$alertableIssue['subjectText']}",$emailMessage);
			}

		}
	} else {
	 	echo "No Issues found fitting alert criteria";			
	}



}else{
	echo "Alerts not enabled in configuration.ini file";
}



?>
