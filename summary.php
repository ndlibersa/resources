<?php

/*
**************************************************************************************************************************
** CORAL Resources Module v. 1.2
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

$resourceID = $_GET['resourceID'];
$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));


//if this is a valid resource
if ($resource->titleText){


	//set this to turn off displaying the title header in header.php
	$pageTitle=$resource->titleText . " Summary";


	$resourceFormat = new ResourceFormat(new NamedArguments(array('primaryKey' => $resource->resourceFormatID)));
	$resourceType = new ResourceType(new NamedArguments(array('primaryKey' => $resource->resourceTypeID)));
	$status = new Status(new NamedArguments(array('primaryKey' => $resource->statusID)));

	$createUser = new User(new NamedArguments(array('primaryKey' => $resource->createLoginID)));
	$updateUser = new User(new NamedArguments(array('primaryKey' => $resource->updateLoginID)));

	//get parent resource
	//only returns one - ResourceRelationship object
	$resourceRelationship = new ResourceRelationship();
	$resourceRelationship = $resource->getParentResource();
	$parentResource = new Resource(new NamedArguments(array('primaryKey' => $resourceRelationship->relatedResourceID)));

	//get children resources
	$sanitizedInstance = array();
	$instance = new Resource();
	$childResourceArray = array();
	foreach ($resource->getChildResources() as $instance) {
		foreach (array_keys($instance->attributeNames) as $attributeName) {
			$sanitizedInstance[$attributeName] = $instance->$attributeName;
		}

		$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;

		array_push($childResourceArray, $sanitizedInstance);
	}


	//get aliases
	$sanitizedInstance = array();
	$instance = new Alias();
	$aliasArray = array();
	foreach ($resource->getAliases() as $instance) {
		foreach (array_keys($instance->attributeNames) as $attributeName) {
			$sanitizedInstance[$attributeName] = $instance->$attributeName;
		}

		$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;

		$aliasType = new AliasType(new NamedArguments(array('primaryKey' => $instance->aliasTypeID)));
		$sanitizedInstance['aliasTypeShortName'] = $aliasType->shortName;

		array_push($aliasArray, $sanitizedInstance);
	}

	//get organizations (already returned in an array)
	$orgArray = $resource->getOrganizationArray();

	$orderType = new OrderType(new NamedArguments(array('primaryKey' => $resource->orderTypeID)));
	$acquisitionType = new AcquisitionType(new NamedArguments(array('primaryKey' => $resource->acquisitionTypeID)));

	//get purchase sites
	$sanitizedInstance = array();
	$instance = new PurchaseSite();
	$purchaseSiteArray = array();
	foreach ($resource->getResourcePurchaseSites() as $instance) {
		$purchaseSiteArray[]=$instance->shortName;
	}

	//get authorized sites
	$sanitizedInstance = array();
	$instance = new PurchaseSite();
	$authorizedSiteArray = array();
	foreach ($resource->getResourceAuthorizedSites() as $instance) {
		$authorizedSiteArray[]=$instance->shortName;
	}


	//get payments
	$sanitizedInstance = array();
	$instance = new ResourcePayment();
	$paymentArray = array();
	foreach ($resource->getResourcePayments() as $instance) {
			foreach (array_keys($instance->attributeNames) as $attributeName) {
				$sanitizedInstance[$attributeName] = $instance->$attributeName;
			}

			$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;

			$selector = new User(new NamedArguments(array('primaryKey' => $instance->selectorLoginID)));
			$sanitizedInstance['selectorName'] = $selector->firstName . " " . $selector->lastName;

			$orderType = new OrderType(new NamedArguments(array('primaryKey' => $instance->orderTypeID)));
			$sanitizedInstance['orderType'] = $orderType->shortName;


			array_push($paymentArray, $sanitizedInstance);

	}


	//get license statuses
	$sanitizedInstance = array();
	$instance = new ResourceLicenseStatus();
	$licenseStatusArray = array();
	foreach ($resource->getResourceLicenseStatuses() as $instance) {
			foreach (array_keys($instance->attributeNames) as $attributeName) {
				$sanitizedInstance[$attributeName] = $instance->$attributeName;
			}

			$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;

			$changeUser = new User(new NamedArguments(array('primaryKey' => $instance->licenseStatusChangeLoginID)));
			if (($changeUser->firstName) || ($changeUser->lastName)) {
				$sanitizedInstance['changeName'] = $changeUser->firstName . " " . $changeUser->lastName;
			}else{
				$sanitizedInstance['changeName'] = $instance->licenseStatusChangeLoginID;
			}

			$licenseStatus = new LicenseStatus(new NamedArguments(array('primaryKey' => $instance->licenseStatusID)));
			$sanitizedInstance['licenseStatus'] = $licenseStatus->shortName;


			array_push($licenseStatusArray, $sanitizedInstance);

	}



	//get licenses (already returned in array)
	$licenseArray = $resource->getLicenseArray();

	$userLimit = new UserLimit(new NamedArguments(array('primaryKey' => $resource->userLimitID)));
	$storageLocation = new StorageLocation(new NamedArguments(array('primaryKey' => $resource->storageLocationID)));
	$accessMethod = new AccessMethod(new NamedArguments(array('primaryKey' => $resource->accessMethodID)));
	$authenticationType = new AuthenticationType(new NamedArguments(array('primaryKey' => $resource->authenticationTypeID)));

	//get administering sites
	$sanitizedInstance = array();
	$instance = new AdministeringSite();
	$administeringSiteArray = array();
	foreach ($resource->getResourceAdministeringSites() as $instance) {
		$administeringSiteArray[]=$instance->shortName;
	}

	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="public">

	<head>
	<title>Resources Module - <?php echo $pageTitle; ?></title>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="print" />
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
	<link rel="SHORTCUT ICON" href="images/butterflyfishfavicon.ico" />

	</head>
	<body>


	<div class='printContent'>
	<table class='linedFormTable' style='width:510px;'>
		<tr>
		<th colspan='2' style='margin-top: 7px; margin-bottom: 5px;'>
		<span style='float:left; vertical-align:top; margin-left:3px;'><span style='font-weight:bold;font-size:120%;margin-right:8px;'><?php echo $resource->titleText; ?></span><span style='font-weight:normal;font-size:100%;'><?php echo $resourceFormat->shortName . " " . $resourceType->shortName; ?></span></span>
		</th>
		</tr>

		<tr>
		<td style='vertical-align:top;width:150px;'>Record ID:</td>
		<td style='width:335px;'><?php echo $resource->resourceID; ?></td>
		</tr>


		<tr>
		<td style='vertical-align:top;width:150px;'>Status:</td>
		<td style='width:335px;'><?php echo $status->shortName; ?></td>
		</tr>

		<tr>
		<td style='vertical-align:top;width:150px;'>
		Created:
		</td>
		<td>
		<i>
		<?php
			echo format_date($resource->createDate);
			//since resources could be created by other modules the user may or may not be set and may or may not have a user entry in this db
			if ($createUser->primaryKey){
				echo " by ";
				if ($createUser->firstName){
					echo $createUser->firstName . " " . $createUser->lastName;
				}else{
					echo $createUser->primaryKey;
				}
			}
		?>
		</i>
		</td>
		</tr>

		<?php
		if (($resource->updateDate) && ($resource->updateDate != '0000-00-00')){
		?>

			<tr>
			<td style='vertical-align:top;width:150px;'>
			Last Update:
			</td>
			<td>
			<i>
			<?php
				echo format_date($resource->updateDate);
				//since resources could be updated by other modules the user may or may not be set and may or may not have a user entry in this db
				if ($updateUser->primaryKey){
					echo " by ";
					if ($updateUser->firstName){
						echo $updateUser->firstName . " " . $updateUser->lastName;
					}else{
						echo $updateUser->primaryKey;
					}
				}
			?>
			</i>
			</td>
			</tr>

		<?php
		}

		if (($parentResource->titleText) || (count($childResourceArray) > 0)){ ?>
			<tr>
			<td style='vertical-align:top;width:150px;'>Related Products:</td>
			<td>
			<?php

			if ($parentResource->titleText){
				echo "<span style='float: left;'>" . $parentResource->titleText . "&nbsp;&nbsp;(Parent)</span>";
			}

			foreach ($childResourceArray as $childResource){
				$childResourceObj = new Resource(new NamedArguments(array('primaryKey' => $childResource['resourceID'])));
				echo "<span style='float: left;'>" . $childResourceObj->titleText . "&nbsp;&nbsp;(child)</span>";
			}


			?>
			</td>
			</tr>
		<?php }

		if ($resource->isbnOrISSN){
		?>
		<tr>
		<td style='vertical-align:top;width:150px;'>ISSN / ISBN:</td>
		<td style='width:335px;'><?php echo $resource->isbnOrISSN; ?></td>
		</tr>
		<?php
		}

		if (count($aliasArray) > 0){
		?>
		<tr>
		<td style='vertical-align:top;width:150px;'>Aliases:</td>
		<td>
		<?php
			foreach ($aliasArray as $resourceAlias){
				echo "\n<span style='float: left; width:95px;'>" . $resourceAlias['aliasTypeShortName'] . ":</span><span style='width:270px;'>" . $resourceAlias['shortName'] . "</span><br />";
			}
		?>
		</td>
		</tr>
		<?php
		}


		if (count($orgArray) > 0){
		?>

		<tr>
		<td style='vertical-align:top;width:150px;'>Organizations:</td>
		<td>

			<?php
			foreach ($orgArray as $organization){
				//if organizations is installed provide a link
				if ($config->settings->organizationsModule == 'Y'){
					echo "<span style='float:left; width:70px;'>" . $organization['organizationRole'] . ":</span><span style='width:270px;'>" . $organization['organization'] . "</span><br />";
				}else{
					echo "<span style='float:left; width:70px;'>" . $organization['organizationRole'] . ":</span><span style='width:270px;'>" . $organization['organization'] . "</span><br />";
				}
			}
			?>
		</td>
		</tr>

		<?php
		}

		if ($resource->resourceURL) { ?>
			<tr>
			<td style='vertical-align:top;width:150px;'>Resource URL:</td>
			<td><?php echo $resource->resourceURL; ?></td>
			</tr>
		<?php
		}

		if ($resource->resourceAltURL) { ?>
			<tr>
			<td style='vertical-align:top;width:150px;'>Alt URL:</td>
			<td><?php echo $resource->resourceAltURL; ?></td>
			</tr>
		<?php
		}
		
		if ($resource->descriptionText){ ?>
			<tr>
			<td style='vertical-align:top;width:150px;'>Description:</td>
			<td><?php echo nl2br($resource->descriptionText); ?></td>
			</tr>
		<?php } ?>


	</table>

	<br />

	<?php

	//get notes for this tab
	$sanitizedInstance = array();
	$noteArray = array();

	foreach ($resource->getNotes('Product') as $instance) {
		foreach (array_keys($instance->attributeNames) as $attributeName) {
			$sanitizedInstance[$attributeName] = $instance->$attributeName;
		}

		$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;

		$updateUser = new User(new NamedArguments(array('primaryKey' => $instance->updateLoginID)));

		//in case this user doesn't have a first / last name set up
		if (($updateUser->firstName != '') || ($updateUser->lastName != '')){
			$sanitizedInstance['updateUser'] = $updateUser->firstName . " " . $updateUser->lastName;
		}else{
			$sanitizedInstance['updateUser'] = $instance->updateLoginID;
		}

		$noteType = new NoteType(new NamedArguments(array('primaryKey' => $instance->noteTypeID)));

		if (!$noteType->shortName){
			$sanitizedInstance['noteTypeName'] = 'General Note';
		}else{
			$sanitizedInstance['noteTypeName'] = $noteType->shortName;
		}

		array_push($noteArray, $sanitizedInstance);
	}

	if (count($noteArray) > 0){
	?>
		<table class='linedFormTable' style='width:510px;'>
			<tr>
			<th colspan='2'>Additional Product Notes</th>
			</tr>
			<?php foreach ($noteArray as $resourceNote){ ?>
				<tr>
				<td style='width:150px;'><?php echo $resourceNote['noteTypeName']; ?>:</td>
				<td><?php echo nl2br($resourceNote['noteText']); ?><br /><i><?php echo format_date($resourceNote['updateDate']) . " by " . $resourceNote['updateUser']; ?></i></td>
				</tr>
			<?php } ?>
		</table>
	<?php
	}
	?>


	<br />


	<table class='linedFormTable' style='width:510px;'>
	<tr>
	<th colspan='2' style='vertical-align:bottom;'>
	<span style='float:left;vertical-align:bottom;'>Order</span>

	</th>
	</tr>

	<?php if ($resource->acquisitionTypeID) { ?>
		<tr>
		<td style='vertical-align:top;width:150px;'>Acquisition Type:</td>
		<td><?php echo $acquisitionType->shortName; ?></td>
		</tr>
	<?php } ?>

	<?php if ($resource->orderNumber) { ?>
		<tr>
		<td style='vertical-align:top;width:150px;'>Order Number:</td>
		<td><?php echo $resource->orderNumber; ?></td>
		</tr>
	<?php } ?>

	<?php if ($resource->systemNumber) { ?>
		<tr>
		<td style='vertical-align:top;width:150px;'>System Number:</td>
		<td><?php echo $resource->systemNumber; ?></td>
		</tr>
	<?php } ?>


	<?php if (count($purchaseSiteArray) > 0) { ?>
		<tr>
		<td style='vertical-align:top;width:150px;'>Purchasing Site:</td>
		<td><?php echo implode(", ", $purchaseSiteArray); ?></td>
		</tr>
	<?php } ?>

	<?php if (count($authorizedSiteArray) > 0) { ?>
		<tr>
		<td style='vertical-align:top;width:150px;'>Authorized Sites:</td>
		<td><?php echo implode(", ", $authorizedSiteArray); ?></td>
		</tr>
	<?php } ?>


	<?php if (($resource->subscriptionStartDate) && ($resource->subscriptionStartDate != '0000-00-00')) { ?>
	<tr>
	<td style='vertical-align:top;width:150px;'>Subscription Start:</td>
	<td><?php echo format_date($resource->subscriptionStartDate); ?></td>
	</tr>
	<?php } ?>

	<?php if (($resource->subscriptionEndDate) && ($resource->subscriptionEndDate != '0000-00-00')) { ?>
	<tr>
	<td style='vertical-align:top;width:150px;'>Subscription End:</td>
	<td><?php echo format_date($resource->subscriptionEndDate); ?>&nbsp;&nbsp;
	<?php if ($resource->subscriptionAlertEnabledInd == "1") { echo "<i>Expiration Alert Enabled</i>"; } ?>
	</td>
	</tr>
	<?php } ?>

	</table>
	<br />

	<table class='linedFormTable' style='width:510px;'>
	<tr>
	<th colspan='3'>Initial Cost</th>
	</th>
	</tr>

	<?php
	if (count($paymentArray) > 0){
		foreach ($paymentArray as $payment){ ?>
		<tr>
		<td><?php echo $payment['fundName']; ?></td>
		<td><?php echo $payment['currencyCode'] . " " . integer_to_cost($payment['paymentAmount']); ?></td>
		<td><?php echo $payment['orderType']; ?></td>
		</tr>

		<?php
		}
	}else{
		echo "<tr><td colspan='3'><i>No payment information available.</i></td></tr>";
	}
	?>

	</table>
	<br />

	<table class='linedFormTable' style='width:510px;'>
	<tr>
	<th colspan='2'>
	<span style='float:left;vertical-align:bottom;'>License</span>
	</th>
	</tr>

	<tr>
	<td style='vertical-align:top;width:150px;'>Status:</td>
	<td>

	<?php
	if (count($licenseStatusArray) > 0){
		foreach ($licenseStatusArray as $licenseStatus){
			echo $licenseStatus['licenseStatus'] . " on <i>" . format_date($licenseStatus['licenseStatusChangeDate']) . " by " . $licenseStatus['changeName'] . "</i><br />";
		}
	}else{
		echo "<i>No license status information available.</i>";
	}

	?>
	</td>
	</tr>

	<tr>
	<td style='vertical-align:top;width:150px;'>Licenses:</td>
	<td>
	<?php

	if (count($licenseArray) > 0){
		foreach ($licenseArray as $license){
			echo $license['license'] . "<br />";
		}
	}else{
		echo "<i>No associated licenses available.</i>";
	}

	?>


	</td>
	</tr>

	</table>

	<br />

	<?php

	//get notes for this tab
	$sanitizedInstance = array();
	$noteArray = array();
	foreach ($resource->getNotes('Acquisitions') as $instance) {
		foreach (array_keys($instance->attributeNames) as $attributeName) {
			$sanitizedInstance[$attributeName] = $instance->$attributeName;
		}

		$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;

		$updateUser = new User(new NamedArguments(array('primaryKey' => $instance->updateLoginID)));

		//in case this user doesn't have a first / last name set up
		if (($updateUser->firstName != '') || ($updateUser->lastName != '')){
			$sanitizedInstance['updateUser'] = $updateUser->firstName . " " . $updateUser->lastName;
		}else{
			$sanitizedInstance['updateUser'] = $instance->updateLoginID;
		}

		$noteType = new NoteType(new NamedArguments(array('primaryKey' => $instance->noteTypeID)));
		if (!$noteType->shortName){
			$sanitizedInstance['noteTypeName'] = 'General Note';
		}else{
			$sanitizedInstance['noteTypeName'] = $noteType->shortName;
		}

		array_push($noteArray, $sanitizedInstance);
	}

	if (count($noteArray) > 0){
	?>
		<table class='linedFormTable' style='width:510px;'>
			<tr>
			<th colspan='2'>Additional Acquisitions Notes</th>
			</th>&nbsp;
			</tr>
			<?php foreach ($noteArray as $resourceNote){ ?>
				<tr>
				<td style='width:150px;'><?php echo $resourceNote['noteTypeName']; ?></td>

				</td>
				<td><?php echo nl2br($resourceNote['noteText']); ?><br /><i><?php echo format_date($resourceNote['updateDate']) . " by " . $resourceNote['updateUser']; ?></i></td>
				</tr>
			<?php } ?>
		</table>
	<?php
	}
	?>

	<br />


	<table class='linedFormTable' style='width:510px;'>
	<tr>
	<th colspan='2'>
	<span style='float:left;vertical-align:bottom;'>Access Information</span>
	</th>
	</tr>


	<?php
		//If no access information is available, display that information
		if ((count($administeringSiteArray) == 0) && (!$authenticationType->shortName) && (!$resource->authenticationUserName) && (!$resource->authenticationPassword) && (!$userLimit->shortName) && (!$resource->registeredIPAddressException) && (!$storageLocation->shortName) && (!$accessMethod->shortName)){
			echo "<tr><td colspan='2'><i>No access information available.</i></td></tr>";
		}
	?>

	<?php if (count($administeringSiteArray) > 0) { ?>
		<tr>
		<td style='vertical-align:top;width:150px;'>Administering Sites:</td>
		<td><?php echo implode(", ", $administeringSiteArray); ?></td>
		</tr>
	<?php } ?>

	<?php if ($authenticationType->shortName) { ?>
		<tr>
		<td style='vertical-align:top;width:150px;'>Authentication Type:</td>
		<td><?php echo $authenticationType->shortName; ?></td>
		</tr>
	<?php } ?>

	<?php if (($resource->authenticationUserName) || ($resource->authenticationPassword)) { ?>
		<tr>
		<td style='vertical-align:top;width:150px;'>Username / Password:</td>
		<td><?php echo $resource->authenticationUserName . " / " . $resource->authenticationPassword; ?></td>
		</tr>
	<?php } ?>

	<?php if ($userLimit->shortName) { ?>
		<tr>
		<td style='vertical-align:top;width:150px;'>Simultaneous User Limit:</td>
		<td><?php echo $userLimit->shortName; ?></td>
		</tr>
	<?php } ?>


	<?php if ($resource->registeredIPAddressException){ ?>
		<tr>
		<td style='vertical-align:top;width:150px;'>Registered IP Address:</td>
		<td style='width:310px;'><?php echo $resource->registeredIPAddressException; ?></td>
		</tr>
	<?php } ?>


	<?php if ($storageLocation->shortName) { ?>
		<tr>
		<td style='vertical-align:top;width:150px;'>Storage Location:</td>
		<td><?php echo $storageLocation->shortName; ?></td>
		</tr>
	<?php } ?>

	<?php if ($accessMethod->shortName) { ?>
		<tr>
		<td style='vertical-align:top;width:150px;'>Access Method:</td>
		<td><?php echo $accessMethod->shortName; ?></td>
		</tr>
	<?php } ?>

	</table>

	<br />

	<?php


	//get notes for this tab
	$sanitizedInstance = array();
	$noteArray = array();
	foreach ($resource->getNotes('Access') as $instance) {
		foreach (array_keys($instance->attributeNames) as $attributeName) {
			$sanitizedInstance[$attributeName] = $instance->$attributeName;
		}

		$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;

		$updateUser = new User(new NamedArguments(array('primaryKey' => $instance->updateLoginID)));

		//in case this user doesn't have a first / last name set up
		if (($updateUser->firstName != '') || ($updateUser->lastName != '')){
			$sanitizedInstance['updateUser'] = $updateUser->firstName . " " . $updateUser->lastName;
		}else{
			$sanitizedInstance['updateUser'] = $instance->updateLoginID;
		}

		$noteType = new NoteType(new NamedArguments(array('primaryKey' => $instance->noteTypeID)));
		if (!$noteType->shortName){
			$sanitizedInstance['noteTypeName'] = 'General Note';
		}else{
			$sanitizedInstance['noteTypeName'] = $noteType->shortName;
		}

		array_push($noteArray, $sanitizedInstance);
	}

	if (count($noteArray) > 0){
	?>
		<table class='linedFormTable' style='width:510px;'>
			<tr>
			<th colspan='2'>Additional Access Notes</th>
			</tr>
			<?php foreach ($noteArray as $resourceNote){ ?>
				<tr>
				<td style='width:150px;'><?php echo $resourceNote['noteTypeName']; ?>:</td>
				<td><?php echo nl2br($resourceNote['noteText']); ?><br /><i><?php echo format_date($resourceNote['updateDate']) . " by " . $resourceNote['updateUser']; ?></i></td>
				</tr>
			<?php } ?>
		</table>
	<?php
	}
	?>

  <table class='linedFormTable' style='width:510px;'>
    <tr>
      <th colspan='2' style='vertical-align:bottom;'>
        <span style='float:left;vertical-align:bottom;'>Cataloging</span>
      </th>
    </tr>
    <?php if ($resource->hasCatalogingInformation()) { ?>
      <?php if ($resource->recordSetIdentifier) { ?>
    		<tr>
      		<td style='vertical-align:top;width:150px;'>Identifier:</td>
      		<td><?php echo $resource->recordSetIdentifier; ?></td>
    		</tr>
    	<?php } ?>
    	<?php if ($resource->bibSourceURL) { ?>
    		<tr>
      		<td style='vertical-align:top;width:150px;'>URL:</td>
      		<td><?php echo $resource->bibSourceURL; ?></td>
    		</tr>
    	<?php } ?>
    	<?php if ($resource->catalogingTypeID) { 
    		$catalogingType = new CatalogingType(new NamedArguments(array('primaryKey' => $resource->catalogingTypeID)));
    		?>
    		<tr>
      		<td style='vertical-align:top;width:150px;'>Cataloging Type:</td>
      		<td><?php echo $catalogingType->shortName; ?></td>
    		</tr>
    	<?php } ?>
    	<?php if ($resource->catalogingStatusID) { 
    		$catalogingStatus = new CatalogingStatus(new NamedArguments(array('primaryKey' => $resource->catalogingStatusID)));
    		?>
    		<tr>
      		<td style='vertical-align:top;width:150px;'>Cataloging Status:</td>
      		<td><?php echo $catalogingStatus->shortName; ?></td>
    		</tr>
    	<?php } ?>
    	<?php if ($resource->numberRecordsAvailable) { ?>
    		<tr>
      		<td style='vertical-align:top;width:150px;'># Records Available:</td>
      		<td><?php echo $resource->numberRecordsAvailable; ?></td>
    		</tr>
    	<?php } ?>
    	<?php if ($resource->numberRecordsLoaded) { ?>
    		<tr>
      		<td style='vertical-align:top;width:150px;'># Records Loaded:</td>
      		<td><?php echo $resource->numberRecordsLoaded; ?></td>
    		</tr>
    	<?php } ?>
    	<?php if ($resource->hasOclcHoldings) { ?>
    		<tr>
      		<td style='vertical-align:top;width:150px;'>OCLC Holdings:</td>
      		<td><?php echo $resource->hasOclcHoldings ? 'Yes' : 'No'; ?></td>
    		</tr>
    	<?php } ?>
    <?php } else { ?>
      <tr>
        <td colspan="2">
          <em>No cataloging information available.</em>
        </td>
      </tr>
    <?php } ?>
  </table>
  
  <br />

	<?php


	//get notes for this tab
	$sanitizedInstance = array();
	$noteArray = array();
	foreach ($resource->getNotes('Cataloging') as $instance) {
		foreach (array_keys($instance->attributeNames) as $attributeName) {
			$sanitizedInstance[$attributeName] = $instance->$attributeName;
		}

		$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;

		$updateUser = new User(new NamedArguments(array('primaryKey' => $instance->updateLoginID)));

		//in case this user doesn't have a first / last name set up
		if (($updateUser->firstName != '') || ($updateUser->lastName != '')){
			$sanitizedInstance['updateUser'] = $updateUser->firstName . " " . $updateUser->lastName;
		}else{
			$sanitizedInstance['updateUser'] = $instance->updateLoginID;
		}

		$noteType = new NoteType(new NamedArguments(array('primaryKey' => $instance->noteTypeID)));
		if (!$noteType->shortName){
			$sanitizedInstance['noteTypeName'] = 'General Note';
		}else{
			$sanitizedInstance['noteTypeName'] = $noteType->shortName;
		}

		array_push($noteArray, $sanitizedInstance);
	}

	if (count($noteArray) > 0){
	?>
		<table class='linedFormTable' style='width:510px;'>
			<tr>
			<th colspan='2'>Additional Cataloging Notes</th>
			</tr>
			<?php foreach ($noteArray as $resourceNote){ ?>
				<tr>
				<td style='width:150px;'><?php echo $resourceNote['noteTypeName']; ?>:</td>
				<td><?php echo nl2br($resourceNote['noteText']); ?><br /><i><?php echo format_date($resourceNote['updateDate']) . " by " . $resourceNote['updateUser']; ?></i></td>
				</tr>
			<?php } ?>
		</table>
	<?php
	}
	?>



	</div>
	</body>
	</html>

<?php
}
?>