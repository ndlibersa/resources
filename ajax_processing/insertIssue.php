<?php

	$formDataArray = $_POST["issue"];
	$newIssue = new Issue();

	$newIssue->creatorID = $user->loginID;
	$newIssue->dateCreated = date( 'Y-m-d H:i:s');

	if(!is_numeric($formDataArray["reminderInterval"])) {
		$formDataArray["reminderInterval"] = 0;
	}

	foreach($formDataArray as $key => $value) {
		$newIssue->$key = $value;
	}

	print_r($formDataArray);

	$newIssue->save();

?>