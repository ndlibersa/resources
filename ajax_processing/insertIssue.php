<?php

	$formDataArray = $_POST["issue"];
	$resourceIDArray = $_POST["resourceIDs"];
	$newIssue = new Issue();

	$newIssue->creatorID = $user->loginID;
	$newIssue->dateCreated = date( 'Y-m-d H:i:s');

	if(!is_numeric($formDataArray["reminderInterval"])) {
		$formDataArray["reminderInterval"] = 0;
	}

	foreach($formDataArray as $key => $value) {
		$newIssue->$key = $value;
	}

	$newIssue->save();

	foreach($resourceIDArray as $resourceID) {
		$newIssueRelationship = new IssueRelationship();
		$newIssueRelationship->issueID = $newIssue->primaryKey;
		$newIssueRelationship->entityID = $resourceID;
		$newIssueRelationship->entityTypeID = 2;
		$newIssueRelationship->save();
		unset($newIssueRelationship);
	}

?>