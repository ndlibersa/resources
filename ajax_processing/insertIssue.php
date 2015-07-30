<?php

$formDataArray = $_POST["issue"];
$resourceIDArray = $_POST["resourceIDs"];
$contactIDs = $_POST['contactIDs'];

$issueEmails = array();
$issueEmails = explode(',',$_POST["ccEmails"]);

$newIssue = new Issue();

$newIssue->creatorID = $user->loginID;
$newIssue->dateCreated = date( 'Y-m-d H:i:s');

if($_POST["ccCreator"]) {
	$issueEmails[] = $user->emailAddress;
}

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

if (count($issueEmails) > 0) {
	foreach ($issueEmails as $email) {
		$newIssueEmail = new IssueEmail();
		$newIssueEmail->issueID = $newIssue->primaryKey;
		$newIssueEmail->email = $email;
		$newIssueEmail->save();
		unset($newIssueEmail);
	}
}

if (count($contactIDs)) {
	foreach ($contactIDs as $contactID) {
		$newIssueContact = new IssueContact();
		$newIssueContact->issueID = $newIssue->primaryKey;
		$newIssueContact->contactID = $contactID;
		$newIssueContact->save();
		unset($newIssueContact);
	}
}

?>