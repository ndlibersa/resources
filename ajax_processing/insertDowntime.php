<?php

$newDowntime = new Downtime();
$newDowntime->entityID = $_POST['sourceResourceID'];
$newDowntime->creatorID = $user->loginID;
$newDowntime->downtimeTypeID = $_POST['downtimeType'];
$newDowntime->issueID = $_POST['issueID'];
$newDowntime->startDate = date('Y-m-d H:i:s', strtotime($_POST['startDate']));
$newDowntime->endDate = date('Y-m-d H:i:s', strtotime($_POST['endDate']));

$newDowntime->dateCreated = date( 'Y-m-d H:i:s');
$newDowntime->entityTypeID = 2;
$newDowntime->note = ($_POST['note']) ? $_POST['note']:null;

$newDowntime->save();

?>