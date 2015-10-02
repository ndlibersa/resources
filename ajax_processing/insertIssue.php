<?php
$formDataArray = $_POST["issue"];
$resourceIDArray = $_POST["resourceIDs"];
$contactIDs = $_POST['contactIDs'];
$organizationID = $_POST['organizationID'];

$sourceResourceID = $_POST['sourceResourceID'];
$sourceOrganizationID = $_POST['sourceOrganizationID'];

$sourceResource = new Resource(new NamedArguments(array('primaryKey' => $sourceResourceID))); 


$issueEmails = array();

if (!empty($_POST["ccEmails"])) {
	$issueEmails = explode(',',$_POST["ccEmails"]);
}

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

//start building the email body
$emailMessage = "{$newIssue->bodyText}\r\n\r\n";

if ($organizationID) {
	$newIssueRelationship = new IssueRelationship();
	$newIssueRelationship->issueID = $newIssue->primaryKey;
	$newIssueRelationship->entityID = $organizationID;
	$newIssueRelationship->entityTypeID = 1;
	$newIssueRelationship->save();

	$organizationArray = $sourceResource->getOrganizationArray();

	$issuedOrgs = array();
	foreach ($organizationArray as $orgData) {
		if (!in_array($orgData['organizationID'],$issuedOrgs)) {
			$issuedOrgs[] = $orgData['organizationID'];
		}
	}
} else {
	foreach($resourceIDArray as $resourceID) {
		$newIssueRelationship = new IssueRelationship();
		$newIssueRelationship->issueID = $newIssue->primaryKey;
		$newIssueRelationship->entityID = $resourceID;
		$newIssueRelationship->entityTypeID = 2;
		$newIssueRelationship->save();
		unset($newIssueRelationship);
	}
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

	$organizationContactsArray = $sourceResource->organizationContactsArray($sourceOrganizationID);

	//send emails to contacts
	foreach ($organizationContactsArray as $contactData) {
		if (in_array($contactData['contactID'],$contactIDs)) {
			mail($email, "{$newIssue->subjectText}",$emailMessage);
		}
	}
}

if (count($issueEmails) > 0) {
	//send emails to CCs
	foreach ($issueEmails as $email) {
		mail($email, "{$newIssue->subjectText}",$emailMessage);
	}
}
echo $emailMessage;
?>