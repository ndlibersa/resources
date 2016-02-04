<?php

$newDowntime = new Downtime();

if ($_POST['sourceOrganizationID']) {
	$newDowntime->entityID = $_POST['sourceOrganizationID'];
	$newDowntime->entityTypeID = 1;
} else {
	$newDowntime->entityID = $_POST['sourceResourceID'];
	$newDowntime->entityTypeID = 2;
}

$newDowntime->creatorID = $user->loginID;
$newDowntime->downtimeTypeID = $_POST['downtimeType'];
$newDowntime->issueID = $_POST['issueID'];
$newDowntime->startDate = date('Y-m-d H:i:s', strtotime($_POST['startDate']));
$newDowntime->endDate = date('Y-m-d H:i:s', strtotime($_POST['endDate']));

$newDowntime->dateCreated = date( 'Y-m-d H:i:s');
$newDowntime->note = ($_POST['note']) ? $_POST['note']:null;

$newDowntime->save();

?>