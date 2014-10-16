<?php
		$name = $_GET['name'];
		if (isset($organizationID)) $organizationID = $_GET['organizationID']; else $organizationID = '';


		$organization = new Organization();
		$orgArray = array();

		$exists = 0;

		foreach ($organization->allAsArray() as $orgArray) {
			if ((strtoupper($orgArray['name']) == strtoupper($name)) && ($orgArray['organizationID'] != $organizationID)) {
				$exists++;
			}
		}

		echo $exists;

		break;



	//used to verify resource name/title isn't already being used as it's added
