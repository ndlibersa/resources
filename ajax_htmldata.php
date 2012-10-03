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
include_once 'user.php';

$config = new Configuration();
$util = new Utility();

switch ($_GET['action']) {


    case 'getProductDetails':
			$resourceID = $_GET['resourceID'];
			$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));
			$resourceFormat = new ResourceFormat(new NamedArguments(array('primaryKey' => $resource->resourceFormatID)));
			$resourceType = new ResourceType(new NamedArguments(array('primaryKey' => $resource->resourceTypeID)));
			$acquisitionType = new AcquisitionType(new NamedArguments(array('primaryKey' => $resource->acquisitionTypeID)));
			$status = new Status(new NamedArguments(array('primaryKey' => $resource->statusID)));

			$createUser = new User(new NamedArguments(array('primaryKey' => $resource->createLoginID)));
			$updateUser = new User(new NamedArguments(array('primaryKey' => $resource->updateLoginID)));
			$archiveUser = new User(new NamedArguments(array('primaryKey' => $resource->archiveLoginID)));

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


		?>
		<table class='linedFormTable' style='width:460px;'>
			<tr>
			<th colspan='2' style='margin-top: 7px; margin-bottom: 5px;'>
			<span style='float:left; vertical-align:top; max-width:400px; margin-left:3px;'><span style='font-weight:bold;font-size:120%;margin-right:8px;'><?php echo $resource->titleText; ?></span><span style='font-weight:normal;font-size:100%;'><?php echo $acquisitionType->shortName . " " . $resourceFormat->shortName . " " . $resourceType->shortName; ?></span></span>

			<span style='float:right; vertical-align:top;'><?php if ($user->canEdit()){ ?><a href='ajax_forms.php?action=getUpdateProductForm&height=498&width=730&resourceID=<?php echo $resource->resourceID; ?>&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit resource'></a><?php } ?>  <?php if ($user->isAdmin){ ?><a href='javascript:void(0);' class='removeResource' id='<?php echo $resourceID; ?>'><img src='images/cross.gif' alt='remove resource' title='remove resource'></a><?php } ?></span>
			</th>
			</tr>

			<tr>
			<td style='vertical-align:top;width:115px;'>Record ID:</td>
			<td style='width:345px;'><?php echo $resource->resourceID; ?></td>
			</tr>

			<tr>
			<td style='vertical-align:top;width:115px;'>Status:</td>
			<td style='width:345px;'><?php echo $status->shortName; ?></td>
			</tr>

			<?php
			if (($resource->archiveDate) && ($resource->archiveDate != '0000-00-00')){
			?>

				<tr class='lightGrayBackground'>
				<td>
				Archived:
				</td>
				<td>
				<i>

				<?php
					echo format_date($resource->archiveDate);

					if ($archiveUser->getDisplayName){
						echo " by " . $archiveUser->getDisplayName;
					}else if ($resource->archiveLoginID){
						echo " by " . $resource->archiveLoginID;
					}
				?>

				</i>
				</td>
				</tr>

			<?php
			}
			?>

			<tr>
			<td>
			Created:
			</td>
			<td>
			<i>

				<?php
					echo format_date($resource->createDate);

					if ($createUser->getDisplayName){
						echo " by " . $createUser->getDisplayName;
					}else if ($resource->createLoginID){
						echo " by " . $resource->createLoginID;
					}
				?>

			</i>
			</td>
			</tr>

			<?php
			if (($resource->updateDate) && ($resource->updateDate != '0000-00-00')){
			?>

				<tr>
				<td>
				Last Update:
				</td>
				<td>
				<i>
				<?php
					echo format_date($resource->updateDate);

					if ($updateUser->getDisplayName){
						echo " by " . $updateUser->getDisplayName;
					}else if ($resource->updateLoginID){
						echo " by " . $resource->updateLoginID;
					}
				?>
				</i>
				</td>
				</tr>

			<?php
			}




			if (($parentResource->titleText) || (count($childResourceArray) > 0)){ ?>
				<tr>
				<td style='vertical-align:top;width:115px;'>Related Products:
					<?php if (count($childResourceArray) > 0) { ?>				
						<br><span style='float: right;'>Child:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br></span>				
					<?php } ?>				
				</td>
				<td style='width:345px;'>
				<?php

					if ($parentResource->titleText){
						echo "<span style='float: left;'>" . $parentResource->titleText . "&nbsp;&nbsp;(Parent)&nbsp;&nbsp;<a href='resource.php?resourceID=" . $parentResource->resourceID . "' target='_BLANK'><img src='images/arrow-up-right.gif' alt='view resource' title='View " . $parentResource->titleText . "' style='vertical-align:top;'></a></span><br />";
					} else { 
						echo "<br />"; 
					}

				?>

				<?php 
			
				if (count($childResourceArray) > 0) { ?>				
					<?php
					foreach ($childResourceArray as $childResource){
						$childResourceObj = new Resource(new NamedArguments(array('primaryKey' => $childResource['resourceID'])));
						echo "<span style='float: left;'>" . $childResourceObj->titleText . "<a href='resource.php?resourceID=" . $childResourceObj->resourceID . "' target='_BLANK'><img src='images/arrow-up-right.gif' alt='view resource' title='View " . $childResourceObj->titleText . "' style='vertical-align:top;'></a></span><br />";
					}


					?>
					</td>
				</tr>

			<?php 
				}
			}

			if ($resource->isbnOrISSN){
			?>
			<tr>
			<td style='vertical-align:top;width:115px;'>ISSN / ISBN:</td>
			<td style='width:345px;'><?php echo $resource->isbnOrISSN; ?></td>
			</tr>
			<?php
			}

			if (count($aliasArray) > 0){
			?>
			<tr>
			<td style='vertical-align:top;width:115px;'>Aliases:</td>
			<td style='width:345px;'>
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
			<td style='vertical-align:top;width:115px;'>Organizations:</td>
			<td style='width:345px;'>

				<?php
				foreach ($orgArray as $organization){
					//if organizations is installed provide a link
					if ($config->settings->organizationsModule == 'Y'){
						echo "<span style='float:left; width:75px;'>" . $organization['organizationRole'] . ":</span><span style='width:270px;'>" . $organization['organization'] . "&nbsp;&nbsp;<a href='" . $util->getOrganizationURL() . $organization['organizationID'] . "' target='_blank'><img src='images/arrow-up-right.gif' alt='View " . $organization['organization'] . "' title='View " . $organization['organization'] . "' style='vertical-align:top;'></a></span><br />";
					}else{
						echo "<span style='float:left; width:75px;'>" . $organization['organizationRole'] . ":</span><span style='width:270px;'>" . $organization['organization'] . "</span><br />";
					}
				}
				?>
			</td>
			</tr>

			<?php
			}

			if ($resource->resourceURL) { ?>
				<tr>
				<td style='vertical-align:top;width:115px;'>Resource URL:</td>
				<td style='width:345px;'><?php echo $resource->resourceURL; ?>&nbsp;&nbsp;<a href='<?php echo $resource->resourceURL; ?>' target='_blank'><img src='images/arrow-up-right.gif' alt='Visit Resource URL' title='Visit Resource URL' style='vertical-align:top;'></a></td>
				</tr>
			<?php
			}
			
			if ($resource->resourceAltURL) { ?>
				<tr>
				<td style='vertical-align:top;width:115px;'>Alt URL:</td>
				<td style='width:345px;'><?php echo $resource->resourceAltURL; ?>&nbsp;&nbsp;<a href='<?php echo $resource->resourceAltURL; ?>' target='_blank'><img src='images/arrow-up-right.gif' alt='Visit Secondary Resource URL' title='Visit Secondary Resource URL' style='vertical-align:top;'></a></td>
				</tr>
			<?php
			}

			if ($resource->descriptionText){ ?>
				<tr>
				<td style='vertical-align:top;width:115px;'>Description:</td>
				<td style='width:345px;'><?php echo nl2br($resource->descriptionText); ?></td>
				</tr>
			<?php } ?>


		</table>
		<?php if ($user->canEdit()){ ?>
		<a href='ajax_forms.php?action=getUpdateProductForm&height=498&width=730&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox' id='editResource'>edit product details</a><br />
		<?php } ?>

		<br />
		<br />
		
		<?php

		//get subjects for this tab
 		$sanitizedInstance = array();
 		$generalDetailSubjectIDArray = array();


 		foreach ($resource->getGeneralDetailSubjectLinkID() as $instance) {
 			foreach (array_keys($instance->attributeNames) as $attributeName) {
 				$sanitizedInstance[$attributeName] = $instance->$attributeName;
 			}
		
			$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;
 			array_push($generalDetailSubjectIDArray, $sanitizedInstance);

		}

		if (count($generalDetailSubjectIDArray) > 0){

		?>
			<table class='linedFormTable' style='width:460px;max-width:460px;'>
				<tr>
				<th>Subjects</th>
				<th>
				</th>
				<th>
				</th>				
				</tr>
				<?php 
					$generalSubjectID = 0;
					foreach ($generalDetailSubjectIDArray as $generalDetailSubjectID){ 
						$generalSubject = new GeneralSubject(new NamedArguments(array('primaryKey' => $generalDetailSubjectID[generalSubjectID])));
						$detailedSubject = new DetailedSubject(new NamedArguments(array('primaryKey' => $generalDetailSubjectID[detailedSubjectID])));					
						
				?>
						<tr>
							<td>
								<?php if ($generalDetailSubjectID['generalSubjectID'] != $generalSubjectID) { 
										echo $generalSubject->shortName; 
											// Allow deleting of the General Subject if no Detail Subjects exist
											if (in_array($generalDetailSubjectID['generalSubjectID'], $generalDetailSubjectIDArray[0], true) > 1) {
												$canDelete = false;
											} else {
												$canDelete = true;
											}
										
									} else {
										echo "&nbsp;"; 
										$canDelete = true;	
									}
								?>
							</td>
							
							<td>
								<?php echo $detailedSubject->shortName; ?>
							</td>		
							
							<td style='width:50px;'>
								<?php if ($user->canEdit() && $canDelete){ ?>


									<a href='javascript:void(0);' tab='Product' class='removeResourceSubjectRelationship' generalDetailSubjectID='<?php echo $generalDetailSubjectID[generalDetailSubjectLinkID]; ?>' resourceID='<?php echo $resourceID; ?>'><img src='images/cross.gif' alt='remove subject' title='remove subject'></a>
								<?php } ?>
							</td>							
							
							

						</tr>
						
				<?php
						$generalSubjectID = $generalDetailSubjectID['generalSubjectID'];
					}
				?>

	<?php } ?>
			</table>
		<?php

		

		if ($user->canEdit()){
		?>
			<a href='ajax_forms.php?action=getResourceSubjectForm&height=233&width=425&tab=Product&resourceID=<?php echo $resourceID; ?>&modal=true' class='thickbox'>add new subject</a>
		<?php
		}
						

		
		?>
		<br />
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
			<table class='linedFormTable' style='width:460px;max-width:460px;'>
				<tr>
				<th>Additional Notes</th>
				<th>
				<?php if ($user->canEdit()){ ?>
					<a href='ajax_forms.php?action=getNoteForm&height=233&width=410&tab=Product&resourceID=<?php echo $resourceID; ?>&resourceNoteID=&modal=true' class='thickbox'>add new note</a>
				<?php } ?>
				</th>
				</tr>
				<?php foreach ($noteArray as $resourceNote){ ?>
					<tr>
					<td style='width:115px;'><?php echo $resourceNote['noteTypeName']; ?><br />
					<?php if ($user->canEdit()){ ?>
					<a href='ajax_forms.php?action=getNoteForm&height=233&width=410&tab=Product&resourceID=<?php echo $resourceID; ?>&resourceNoteID=<?php echo $resourceNote['resourceNoteID']; ?>&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit note'></a>  <a href='javascript:void(0);' class='removeNote' id='<?php echo $resourceNote['resourceNoteID']; ?>' tab='Product'><img src='images/cross.gif' alt='remove note' title='remove note'></a>
					<?php } ?>
					</td>
					<td><?php echo nl2br($resourceNote['noteText']); ?><br /><i><?php echo format_date($resourceNote['updateDate']) . " by " . $resourceNote['updateUser']; ?></i></td>
					</tr>
				<?php } ?>
			</table>
		<?php
		}else{
			if ($user->canEdit()){
			?>
				<a href='ajax_forms.php?action=getNoteForm&height=233&width=410&tab=Product&resourceID=<?php echo $resourceID; ?>&resourceNoteID=&modal=true' class='thickbox'>add new note</a>
			<?php
			}
		}

        break;


    case 'getResourceTitle':
    	$resourceID = $_GET['resourceID'];
    	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		echo $resource->titleText;
        break;



    case 'getAcquisitionsDetails':
    	$config = new Configuration();
    	$resourceID = $_GET['resourceID'];
    	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

    	$orderType = new OrderType(new NamedArguments(array('primaryKey' => $resource->orderTypeID)));
		$acquisitionType = new AcquisitionType(new NamedArguments(array('primaryKey' => $resource->acquisitionTypeID)));

		//get purchase sites
		$sanitizedInstance = array();
		$instance = new PurchaseSite();
		$purchaseSiteArray = array();
		foreach ($resource->getResourcePurchaseSites() as $instance) {
			$purchaseSiteArray[]=$instance->shortName;
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

		?>
			<table class='linedFormTable' style='width:460px;'>
			<tr>
			<th colspan='2' style='vertical-align:bottom;'>
			<span style='float:left;vertical-align:bottom;'>Order</span>

			<?php if ($user->canEdit()){ ?>
				<span style='float:right;vertical-align:bottom;'><a href='ajax_forms.php?action=getOrderForm&height=462&width=783&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox' id='editOrder'><img src='images/edit.gif' alt='edit' title='edit order information'></a></span>
			<?php } ?>

			</th>
			</tr>

			<?php if ($resource->acquisitionTypeID) { ?>
				<tr>
				<td style='vertical-align:top;width:110px;'>Acquisition Type:</td>
				<td style='width:350px;'><?php echo $acquisitionType->shortName; ?></td>
				</tr>
			<?php } ?>

			<?php if ($resource->orderNumber) { ?>
				<tr>
				<td style='vertical-align:top;width:110px;'>Order Number:</td>
				<td style='width:350px;'><?php echo $resource->orderNumber; ?></td>
				</tr>
			<?php } ?>

			<?php if ($resource->systemNumber) { ?>
				<tr>
				<td style='vertical-align:top;width:110px;'>System Number:</td>
				<td style='width:350px;'>
				<?php
					echo $resource->systemNumber;
					if ($config->settings->catalogURL != ''){
						echo "&nbsp;&nbsp;<a href='" . $config->settings->catalogURL . $resource->systemNumber . "' target='_blank'>catalog view</a>";
					}
				?>
				</td>
				</tr>
			<?php } ?>

			<?php if (count($purchaseSiteArray) > 0) { ?>
				<tr>
				<td style='vertical-align:top;width:110px;'>Purchasing Sites:</td>
				<td style='width:350px;'><?php echo implode(", ", $purchaseSiteArray); ?></td>
				</tr>
			<?php } ?>

			<?php if (($resource->subscriptionStartDate) && ($resource->subscriptionStartDate != '0000-00-00')) { ?>
			<tr>
			<td style='vertical-align:top;width:110px;'>Subscription Start:</td>
			<td style='width:350px;'><?php echo format_date($resource->subscriptionStartDate); ?></td>
			</tr>
			<?php } ?>

			<?php if (($resource->subscriptionEndDate) && ($resource->subscriptionEndDate != '0000-00-00')) { ?>
			<tr>
			<td style='vertical-align:top;width:110px;'>Subscription End:</td>
			<td style='width:350px;'><?php echo format_date($resource->subscriptionEndDate); ?>&nbsp;&nbsp;
			<?php if ($resource->subscriptionAlertEnabledInd == "1") { echo "<i>Expiration Alert Enabled</i>"; } ?>
			</td>
			</tr>
			<?php } ?>

			</table>
			<br />

			<table class='linedFormTable' style='width:460px;'>
			<tr>
			<th colspan='3'>Initial Cost</th>
			</th>
			</tr>

			<?php
			if (count($paymentArray) > 0){
				foreach ($paymentArray as $payment){
				if ($payment['fundName']){
					$fund = $payment['fundName'];
				}else{
					$fund = "&nbsp;";
				}

				if (integer_to_cost($payment['paymentAmount'])){
					$cost = $payment['currencyCode'] . " " . integer_to_cost($payment['paymentAmount']);
				}else{
					$cost = "&nbsp;";
				}


				?>
				<tr>
				<td><?php echo $fund; ?></td>
				<td><?php echo $cost; ?></td>
				<td><?php echo $payment['orderType']; ?></td>
				</tr>

				<?php
				}
			}else{
				echo "<tr><td colspan='3'><i>No payment information available.</i></td></tr>";
			}
			?>

			</table>
			<?php if ($user->canEdit()){ ?>
				<a href='ajax_forms.php?action=getOrderForm&height=462&width=783&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox' id='newAlias'>edit acquisitions information</a>
			<?php } ?>
			<br />
			<br />
			<br />
			<br />

			<table class='linedFormTable' style='width:460px;'>
			<tr>
			<th colspan='2'>
			<span style='float:left;vertical-align:bottom;'>License</span>
			<?php if ($user->canEdit()){ ?>
				<span style='float:right;vertical-align:bottom;'><a href='ajax_forms.php?action=getLicenseForm&height=420&width=385&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox' id='editLicense'><img src='images/edit.gif' alt='edit' title='edit resource'></a></span>
			<?php } ?>
			</th>
			</tr>

			<tr>
			<td style='vertical-align:top;width:110px;'>Status:</td>
			<td style='width:350px;'>

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

			<?php if ($config->settings->licensingModule == "Y"){ ?>

			<tr>
			<td style='vertical-align:top;width:110px;'>Licenses:</td>
			<td style='width:350px;'>
			<?php

			if (count($licenseArray) > 0){
				foreach ($licenseArray as $license){
					echo $license['license'] . "&nbsp;&nbsp;<a href='" . $util->getLicensingURL() . $license['licenseID'] . "' target='_blank'><img src='images/arrow-up-right.gif' alt='View License' title='View License' style='vertical-align:top;'></a><br />";
				}
			}else{
				echo "<i>No associated licenses available.</i>";
			}

			?>


			</td>
			</tr>

			<?php } ?>

			</table>
			<?php if ($user->canEdit()){ ?>
				<?php if ($config->settings->licensingModule == "Y"){ ?>
					<a href='ajax_forms.php?action=getLicenseForm&height=420&width=378&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox'>edit license and status</a>
				<?php }else{ ?>
					<a href='ajax_forms.php?action=getLicenseForm&height=300&width=378&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox'>edit license status</a>
				<?php } ?>
			<?php } ?>
			<br /><br /><br /><br />



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
			<table class='linedFormTable' style='width:460px;max-width:460px;'>
				<tr>
				<th>Additional Notes</th>
				<th>
				<?php if ($user->canEdit()){?>
					<a href='ajax_forms.php?action=getNoteForm&height=233&width=410&tab=Acquisitions&resourceID=<?php echo $resourceID; ?>&resourceNoteID=&modal=true' class='thickbox'>add new note</a>
				<?php } ?>
				</th>
				</tr>
				<?php foreach ($noteArray as $resourceNote){ ?>
					<tr>
					<td style='width:110px;'><?php echo $resourceNote['noteTypeName']; ?><br />
					<?php if ($user->canEdit()){?>
					<a href='ajax_forms.php?action=getNoteForm&height=233&width=410&tab=Acquisitions&resourceID=<?php echo $resourceID; ?>&resourceNoteID=<?php echo $resourceNote['resourceNoteID']; ?>&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit note'></a>  <a href='javascript:void(0);' class='removeNote' id='<?php echo $resourceNote['resourceNoteID']; ?>' tab='Acquisitions'><img src='images/cross.gif' alt='remove note' title='remove note'></a>
					<?php } ?>
					</td>
					<td><?php echo nl2br($resourceNote['noteText']); ?><br /><i><?php echo format_date($resourceNote['updateDate']) . " by " . $resourceNote['updateUser']; ?></i></td>
					</tr>
				<?php } ?>
			</table>
		<?php
		}else{
			if ($user->canEdit()){
			?>
				<a href='ajax_forms.php?action=getNoteForm&height=233&width=410&tab=Acquisitions&resourceID=<?php echo $resourceID; ?>&resourceNoteID=&modal=true' class='thickbox'>add new note</a>
			<?php
			}
		}

        break;




    case 'getAccessDetails':
    	$resourceID = $_GET['resourceID'];
    	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

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



		//get authorized sites
		$sanitizedInstance = array();
		$instance = new PurchaseSite();
		$authorizedSiteArray = array();
		foreach ($resource->getResourceAuthorizedSites() as $instance) {
			$authorizedSiteArray[]=$instance->shortName;
		}



		?>
			<table class='linedFormTable' style='width:460px;'>
			<tr>
			<th colspan='2'>
			<span style='float:left;vertical-align:bottom;'>Access Information</span>


			<?php if ($user->canEdit()){ ?>
				<span style='float:right;vertical-align:bottom;'><a href='ajax_forms.php?action=getAccessForm&height=394&width=640&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox' id='editAccess'><img src='images/edit.gif' alt='edit' title='edit resource'></a></span>
			<?php } ?>

			</th>
			</tr>

			<?php if (count($administeringSiteArray) > 0) { ?>
				<tr>
				<td style='vertical-align:top;width:150px;'>Administering Sites:</td>
				<td style='width:310px;'><?php echo implode(", ", $administeringSiteArray); ?></td>
				</tr>
			<?php } ?>

			<?php if (count($authorizedSiteArray) > 0) { ?>
				<tr>
				<td style='vertical-align:top;width:150px;'>Authorized Sites:</td>
				<td style='width:310px;'><?php echo implode(", ", $authorizedSiteArray); ?></td>
				</tr>
			<?php } ?>

			<?php if ($authenticationType->shortName) { ?>
				<tr>
				<td style='vertical-align:top;width:150px;'>Authentication Type:</td>
				<td style='width:310px;'><?php echo $authenticationType->shortName; ?></td>
				</tr>
			<?php } ?>


			<?php if (($resource->authenticationUserName) || ($resource->authenticationPassword)) { ?>
				<tr>
				<td style='vertical-align:top;width:150px;'>Username / Password:</td>
				<td style='width:310px;'><?php echo $resource->authenticationUserName . " / " . $resource->authenticationPassword; ?></td>
				</tr>
			<?php } ?>

			<?php if ($userLimit->shortName) { ?>
				<tr>
				<td style='vertical-align:top;width:150px;'>Simultaneous User Limit:</td>
				<td style='width:310px;'><?php echo $userLimit->shortName; ?></td>
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
				<td style='width:310px;'><?php echo $storageLocation->shortName; ?></td>
				</tr>
			<?php } ?>

			<?php if ($resource->coverageText) { ?>
				<tr>
				<td style='vertical-align:top;width:150px;'>Coverage:</td>
				<td style='width:310px;'><?php echo $resource->coverageText; ?></td>
				</tr>
			<?php } ?>

			<?php if ($accessMethod->shortName) { ?>
				<tr>
				<td style='vertical-align:top;width:150px;'>Access Method:</td>
				<td style='width:310px;'><?php echo $accessMethod->shortName; ?></td>
				</tr>
			<?php
			}

			if ((count($administeringSiteArray) == 0) && (!$authenticationType->shortName) && (!$resource->coverageText) && (!$resource->authenticationUserName) && (!$resource->authenticationPassword) && (!$userLimit->shortName) && (!$resource->registeredIPAddressException) && (!$storageLocation->shortName) && (!$accessMethod->shortName)){
				echo "<tr><td colspan='2'><i>No access information available.</i></td></tr>";
			}

			?>
			</table>

			<?php if ($user->canEdit()){ ?>
				<a href='ajax_forms.php?action=getAccessForm&height=394&width=640&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox' id='editAccess'>edit access information</a>
			<?php } ?>

			<br /><br /><br />



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
			<table class='linedFormTable' style='width:460px;max-width:460px;'>
				<tr>
				<th>Additional Notes</th>
				<th>
				<?php if ($user->canEdit()){?>
					<a href='ajax_forms.php?action=getNoteForm&height=233&width=410&tab=Access&resourceID=<?php echo $resourceID; ?>&resourceNoteID=&modal=true' class='thickbox'>add new note</a>
				<?php } ?>
				</th>
				</tr>
				<?php foreach ($noteArray as $resourceNote){ ?>
					<tr>
					<td style='width:150px;'><?php echo $resourceNote['noteTypeName']; ?><br />
					<?php if ($user->canEdit()){?>
					<a href='ajax_forms.php?action=getNoteForm&height=233&width=410&tab=Access&resourceID=<?php echo $resourceID; ?>&resourceNoteID=<?php echo $resourceNote['resourceNoteID']; ?>&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit note'></a>  <a href='javascript:void(0);' class='removeNote' id='<?php echo $resourceNote['resourceNoteID']; ?>' tab='Access'><img src='images/cross.gif' alt='remove note' title='remove note'></a>
					<?php } ?>
					</td>
					<td><?php echo nl2br($resourceNote['noteText']); ?><br /><i><?php echo format_date($resourceNote['updateDate']) . " by " . $resourceNote['updateUser']; ?></i></td>
					</tr>
				<?php } ?>
			</table>
		<?php
		}else{
			if ($user->canEdit()){
			?>
				<a href='ajax_forms.php?action=getNoteForm&height=233&width=410&tab=Access&resourceID=<?php echo $resourceID; ?>&resourceNoteID=&modal=true' class='thickbox'>add new note</a>
			<?php
			}
		}

        break;



    case 'getContactDetails':
    	$resourceID = $_GET['resourceID'];
    	if (isset($_GET['archiveInd'])) $archiveInd = $_GET['archiveInd']; else $archiveInd='';
    	if (isset($_GET['showArchivesInd'])) $showArchivesInd = $_GET['showArchivesInd']; else $showArchivesInd='';

    	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		$util = new Utility();

		//these are used to display the header since the arrays have resource and organization level contacts combined
		$resContactFlag = 0;
		$orgContactFlag = 0;

 		//get contacts
 		$sanitizedInstance = array();
 		$contactArray = array();
 		$contactObjArray = array();

 		if ((isset($archiveInd)) && ($archiveInd == "1")){
 			//if we want archives to be displayed
 			if ($showArchivesInd == "1"){
 				if (count($resource->getArchivedContacts()) > 0){
 					echo "<i><b>The following are archived contacts:</b></i>";
 				}
 				$contactArray = $resource->getArchivedContacts();
 			}
 		}else{
 			$contactArray = $resource->getUnarchivedContacts();
 		}


		if (count($contactArray) > 0){
			foreach ($contactArray as $contact){
				if (($resContactFlag == 0) && (!isset($contact['organizationName']))){
					echo "<div class='formTitle' style='padding:4px; font-weight:bold; margin-bottom:8px;'>Resource Specific:</div>";
					$resContactFlag = 1;
				}else if (($orgContactFlag == 0) && (isset($contact['organizationName']))){
					if ($resContactFlag == 0){
						echo "<i>No Resource Specific Contacts</i><br /><br />";
					}

					if ($user->canEdit() && ($archiveInd != 1) && ($showArchivesInd != 1)){ ?>
						<a href='ajax_forms.php?action=getContactForm&height=389&width=620&modal=true&type=named&resourceID=<?php echo $resourceID; ?>' class='thickbox' id='newNamedContact'>add contact</a>
						<br /><br /><br />
					<?php
					}

					echo "<div class='formTitle' style='padding:4px; font-weight:bold; margin-bottom:8px;'>Inherited:</div>";
					$orgContactFlag = 1;
				}else{
					echo "<br />";
				}

				?>

				<table class='linedFormTable' style='width:460px;'>
				<tr>
				<th style='background:#f5f8fa;'>
					<?php echo $contact['contactRoles']; ?>
				</th>
				<th style='background:#f5f8fa;'>
				<span style='float:left;vertical-align:bottom;'>
					<?php if ($contact['name']) { echo $contact['name']; }else{ echo "&nbsp;"; } ?>
				</span>
				<span style='float:right;vertical-align:top;'>
				<?php
					if (($user->canEdit()) && (!isset($contact['organizationName']))){
						echo "<a href='ajax_forms.php?action=getContactForm&height=389&width=620&modal=true&type=named&resourceID=" . $resourceID . "&contactID=" . $contact['contactID'] . "' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit contact'></a>";
						echo "&nbsp;&nbsp;<a href='javascript:void(0)' class='removeContact' id='" . $contact['contactID'] . "'><img src='images/cross.gif' alt='remove note' title='remove contact'></a>";
					}else{
						echo "&nbsp;";
					}

				?>
				</span>
				</th>
				</tr>

				<?php
				if (isset($contact['organizationName'])){ ?>

				<tr>
				<td style='vertical-align:top;width:110px;'>Organization:</td>
				<td><?php echo $contact['organizationName'] . "&nbsp;&nbsp;<a href='" . $util->getCORALURL() . "organizations/orgDetail.php?showTab=contacts&organizationID=" . $contact['organizationID'] . "' target='_blank'><img src='images/arrow-up-right.gif' alt='Visit Contact in Organizations Module' title='Visit Contact in Organizations Module' style='vertical-align:top;'></a>"; ?></td>
				</tr>

				<?php
				}

				if (($contact['archiveDate'] != '0000-00-00') && ($contact['archiveDate'])) { ?>
				<tr>
				<td style='vertical-align:top;background-color:#ebebeb; width:110px;'>No longer valid:</td>
				<td style='background-color:#ebebeb'><i><?php echo format_date($contact['archiveDate']); ?></i></td>
				</tr>
				<?php
				}

				if ($contact['title']) { ?>
				<tr>
				<td style='vertical-align:top; width:110px;'>Title:</td>
				<td><?php echo $contact['title']; ?></td>
				</tr>
				<?php
				}

				if ((isset($contact['addressText'])) && ($contact['addressText'] != '')){ ?>
					<tr>
					<td style='vertical-align:top; width:110px;'>Address:</td>
					<td><?php echo nl2br($contact['addressText']); ?></td>
					</tr>
				<?php
				}

				if ((isset($contact['state']) || (isset($contact['country']))) && (($contact['state'] != '') || ($contact['country'] != ''))){ ?>
					<tr>
					<td style='vertical-align:top; width:110px;'>Location:</td>
					<td><?php
						if (!($contact['state'])) {
							echo $contact['country'];
						}else if (!($contact['country'])) {
							echo $contact['state'];
						}else{
							echo $contact['state'] . ", " . $contact['country'];
						}
						?>
					</td>
					</tr>
				<?php
				}

				if ($contact['phoneNumber']) { ?>
				<tr>
				<td style='vertical-align:top; width:110px;'>Phone:</td>
				<td><?php echo $contact['phoneNumber']; ?></td>
				</tr>
				<?php
				}

				if ($contact['altPhoneNumber']) { ?>
				<tr>
				<td style='vertical-align:top; width:110px;'>Alt Phone:</td>
				<td><?php echo $contact['altPhoneNumber']; ?></td>
				</tr>
				<?php
				}

				if ($contact['faxNumber']) { ?>
				<tr>
				<td style='vertical-align:top; width:110px;'>Fax:</td>
				<td><?php echo $contact['faxNumber']; ?></td>
				</tr>
				<?php
				}

				if ($contact['emailAddress']) { ?>
				<tr>
				<td style='vertical-align:top; width:110px;'>Email:</td>
				<td><a href='mailto:<?php echo $contact['emailAddress']; ?>'><?php echo $contact['emailAddress']; ?></a></td>
				</tr>
				<?php
				}

				if ($contact['noteText']) { ?>
				<tr>
				<td style='vertical-align:top; width:110px;'>Notes:</td>
				<td><?php echo nl2br($contact['noteText']); ?></td>
				</tr>
				<?php
				}

				if ($contact['lastUpdateDate']) { ?>
				<tr>
				<td style='vertical-align:top; width:110px;'>Last Updated:</td>
				<td><i><?php echo format_date($contact['lastUpdateDate']); ?></i></td>
				</tr>
				<?php
				}

				?>

				</table>
			<?php
			}


			if ($user->canEdit() && ($orgContactFlag == 0) && ($showArchivesInd != 1)){ ?>
				<a href='ajax_forms.php?action=getContactForm&height=389&width=620&modal=true&type=named&resourceID=<?php echo $resourceID; ?>' class='thickbox' id='newNamedContact'>add contact</a>
				<br /><br /><br />
			<?php
			}


		} else {
			if (($archiveInd != 1) && ($showArchivesInd != 1)){
				echo "<i>No contacts available</i><br /><br />";
				if (($user->canEdit())){ ?>
					<a href='ajax_forms.php?action=getContactForm&height=389&width=620&modal=true&type=named&resourceID=<?php echo $resourceID; ?>' class='thickbox' id='newNamedContact'>add contact</a>
					<br /><br /><br />
				<?php
				}
			}
		}

		if (($showArchivesInd == "0") && ($archiveInd == "1") && (count($resource->getArchivedContacts()) > 0)){
			echo "<i>" . count($resource->getArchivedContacts()) . " archived contact(s) available.  <a href='javascript:updateArchivedContacts(1);'>show archived contacts</a></i><br />";
		}

		if (($showArchivesInd == "1") && ($archiveInd == "1") && (count($resource->getArchivedContacts()) > 0)){
			echo "<i><a href='javascript:updateArchivedContacts(0);'>hide archived contacts</a></i><br />";
		}

		echo "<br /><br />";

        break;




    case 'getAccountDetails':
    	$resourceID = $_GET['resourceID'];
    	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		$externalLoginArray = $resource->getExternalLoginArray();

		$resELFlag = 0;
		$orgELFlag = 0;

		if (count($externalLoginArray) > 0){
			foreach ($externalLoginArray as $externalLogin){

				if (($resELFlag == 0) && ($externalLogin['organizationName'] == '')){
					echo "<div class='formTitle' style='padding:4px; font-weight:bold; margin-bottom:8px;'>Resource Specific:</div>";
					$resELFlag = 1;
				}else if (($orgELFlag == 0) && ($externalLogin['organizationName'] != '')){
					if ($resELFlag == 0){
						echo "<i>No Resource Specific Accounts</i><br /><br />";
					}

					if ($user->canEdit()){ ?>
						<a href='ajax_forms.php?action=getAccountForm&height=314&width=403&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox' id='newAccount'>add new account</a>
						<br /><br /><br />
					<?php
					}

					echo "<div class='formTitle' style='padding:4px; font-weight:bold; margin-bottom:8px;'>Inherited:</div>";
					$orgELFlag = 1;
				}else{
					echo "<br />";
				}

			?>
				<table class='linedFormTable' style='width:460px;max-width:460px;'>
				<tr>
				<th colspan='2' style='background:#f5f8fa;'>
				<span style='float:left; vertical-align:bottom;'>
					<?php echo $externalLogin['externalLoginType']; ?>
				</span>

				<span style='float:right;'>
				<?php
					if (($user->canEdit()) && ($externalLogin['organizationName'] == '')){ ?>
						<a href='ajax_forms.php?action=getAccountForm&height=314&width=403&modal=true&resourceID=<?php echo $resourceID; ?>&externalLoginID=<?php echo $externalLogin['externalLoginID']; ?>' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit account'></a>  <a href='javascript:void(0);' class='removeAccount' id='<?php echo $externalLogin['externalLoginID']; ?>'><img src='images/cross.gif' alt='remove account' title='remove account'></a>
						<?php
					}else{
						echo "&nbsp;";
					}
				?>
				</span>
				</th>
				</tr>

				<?php if (isset($externalLogin['organizationName'])) { ?>
				<tr>
				<td style='vertical-align:top; width:130px;'>Organization:</td>
				<td><?php echo $externalLogin['organizationName'] . "&nbsp;&nbsp;<a href='" . $util->getCORALURL() . "organizations/orgDetail.php?showTab=accounts&organizationID=" . $externalLogin['organizationID'] . "' target='_blank'><img src='images/arrow-up-right.gif' alt='Visit Account in Organizations Module' title='Visit Account in Organizations Module' style='vertical-align:top;'></a>"; ?></td>
				</tr>
				<?php
				}

				if ($externalLogin['loginURL']) { ?>
				<tr>
				<td style='vertical-align:top; width:130px;'>Login URL:</td>
				<td><?php echo $externalLogin['loginURL']; ?>&nbsp;&nbsp;<a href='<?php echo $externalLogin['loginURL']; ?>' target='_blank'><img src='images/arrow-up-right.gif' alt='Visit Login URL' title='Visit Login URL' style='vertical-align:top;'></a></td>
				</tr>
				<?php
				}

				if ($externalLogin['username']) { ?>
				<tr>
				<td style='vertical-align:top; width:130px;'>User Name:</td>
				<td><?php echo $externalLogin['username']; ?></td>
				</tr>
				<?php
				}

				if ($externalLogin['password']) { ?>
				<tr>
				<td style='vertical-align:top; width:130px;'>Password:</td>
				<td><?php echo $externalLogin['password']; ?></td>
				</tr>
				<?php
				}

				if ($externalLogin['updateDate']) { ?>
				<tr>
				<td style='vertical-align:top; width:130px;'>Last Updated:</td>
				<td><i><?php echo format_date($externalLogin['updateDate']); ?></i></td>
				</tr>
				<?php
				}

				if ($externalLogin['emailAddress']) { ?>
				<tr>
				<td style='vertical-align:top; width:130px;'>Registered Email:</td>
				<td><?php echo $externalLogin['emailAddress']; ?></td>
				</tr>
				<?php
				}

				if ($externalLogin['noteText']) { ?>
				<tr>
				<td style='vertical-align:top; width:130px;'>Notes:</td>
				<td><?php echo nl2br($externalLogin['noteText']); ?></td>
				</tr>
				<?php
				}
				?>
				</table>
			<?php
			}
		} else {
			echo "<i>No accounts available</i><br /><br />";

		}

		if ($user->canEdit() && ($orgELFlag == 0)){ ?>
			<a href='ajax_forms.php?action=getAccountForm&height=314&width=403&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox' id='newAccount'>add new account</a>
			<br /><br /><br />
		<?php
		}

        break;



    case 'getAttachmentDetails':
    	$resourceID = $_GET['resourceID'];
    	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));


 		//get attachments
 		$sanitizedInstance = array();
 		$attachmentArray = array();
 		foreach ($resource->getAttachments() as $instance) {
 			foreach (array_keys($instance->attributeNames) as $attributeName) {
 				$sanitizedInstance[$attributeName] = $instance->$attributeName;
 			}

 			$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;

 			$attachmentType = new AttachmentType(new NamedArguments(array('primaryKey' => $instance->attachmentTypeID)));
 			$sanitizedInstance['attachmentTypeShortName'] = $attachmentType->shortName;

 			array_push($attachmentArray, $sanitizedInstance);
		}

		if (count($attachmentArray) > 0){
			foreach ($attachmentArray as $attachment){
			?>
				<table class='linedFormTable' style='width:460px;max-width:460px;'>
				<tr>
				<th colspan='2'>
					<span style='float:left; vertical-align:bottom;'>
						<?php echo $attachment['shortName']; ?>&nbsp;&nbsp;
						<a href='attachments/<?php echo $attachment['attachmentURL']; ?>' style='font-weight:normal;' target='_blank'><img src='images/arrow-up-right-blue.gif' alt='view attachment' title='view attachment' style='vertical-align:top;'></a></a>
					</span>
					<span style='float:right;'>
					<?php
						if ($user->canEdit()){ ?>
							<a href='ajax_forms.php?action=getAttachmentForm&height=305&width=360&attachmentID=<?php echo $attachment['attachmentID']; ?>&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit attachment'></a>  <a href='javascript:void(0);' class='removeAttachment' id='<?php echo $attachment['attachmentID']; ?>'><img src='images/cross.gif' alt='remove this attachment' title='remove this attachment'></a>
							<?php
						}else{
							echo "&nbsp;";
						}
					?>
					</span>
				</th>
				</tr>

				<?php if ($attachment['attachmentTypeShortName']) { ?>
				<tr>
				<td style='vertical-align:top; width:110px;'>Type:</td>
				<td style='vertical-align:top; width:350px;'><?php echo $attachment['attachmentTypeShortName']; ?></td>
				</tr>
				<?php
				}

				if ($attachment['descriptionText']) { ?>
				<tr>
				<td style='vertical-align:top; width:110px;'>Details:</td>
				<td style='vertical-align:top; width:350px;'><?php echo $attachment['descriptionText']; ?></td>
				</tr>
				<?php
				}
				?>

				</table>
				<br /><br />
			<?php
			}
		} else {
			echo "<i>No attachments available</i><br /><br />";
		}

		if ($user->canEdit()){
		?>
		<a href='ajax_forms.php?action=getAttachmentForm&height=305&width=360&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox' id='newAttachment'>add new attachment</a><br /><br />
		<?php
		}


        break;






	case 'getRoutingDetails':
		$resourceID = $_GET['resourceID'];
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));
		$status = new Status();

		$completeStatusID = $status->getIDFromName('complete');
		$archiveStatusID = $status->getIDFromName('archive');

		$resourceSteps = $resource->getResourceSteps();

		if (count($resourceSteps) == "0"){
			if (($resource->statusID != $completeStatusID) && ($resource->statusID != $archiveStatusID)){
				echo "<i>No workflow steps have been set up for this resource's combination of Acquisition Type and Resource Format.<br />If you think this is in error, please contact your workflow administrator.</i>";
			}else{
				echo "<i>Not entered into workflow.</i>";
			}
		}else{
			?>
			<table class='linedDataTable' style='width:100%;margin-bottom:5px;'>
				<tr>
				<th style='background-color:#dad8d8;width:350px;'>Step</th>
				<th style='background-color:#dad8d8;width:150px;'>Group</th>
				<th style='background-color:#dad8d8;width:120px;'>Start Date</th>
				<th style='background-color:#dad8d8;width:250px;'>Complete</th>
				</tr>
			<?php
			$openStep=0;
			foreach($resourceSteps as $resourceStep){
				$userGroup = new UserGroup(new NamedArguments(array('primaryKey' => $resourceStep->userGroupID)));
				$eUser = new User(new NamedArguments(array('primaryKey' => $resourceStep->endLoginID)));

				$classAdd = "style='background-color: white;'";
				//make the row gray if it is complete or not started
				if ((($resourceStep->stepEndDate) && ($resourceStep->stepEndDate != "0000-00-00")) || (!$resourceStep->stepStartDate) || ($resource->statusID == $archiveStatusID) || ($resource->statusID == $completeStatusID)){
					 $classAdd = "class='complete'";
				}


				?>
				<tr>
				<td <?php echo $classAdd; ?>><?php echo $resourceStep->stepName; ?></td>
				<td <?php echo $classAdd; ?>><?php echo $userGroup->groupName; ?></td>
				<td <?php echo $classAdd; ?>><?php if ($resourceStep->stepStartDate) { echo format_date($resourceStep->stepStartDate); } ?></td>
				<td <?php echo $classAdd; ?>>
				<?php
					if ($resourceStep->stepEndDate) {
						if (($eUser->firstName) || ($eUser->lastName)){
							echo format_date($resourceStep->stepEndDate) . " by " . $eUser->firstName . " " . $eUser->lastName;
						}else{
							echo format_date($resourceStep->stepEndDate) . " by " . $resourceStep->endLoginID;
						}
					}else{
						//add if user is in group or an admin and resource is not completed or archived
						if ((($user->isAdmin) || ($user->isInGroup($resourceStep->userGroupID))) && ($resourceStep->stepStartDate) &&  ($resource->statusID != $archiveStatusID) && ($resource->statusID != $completeStatusID)){
							echo "<a href='javascript:void(0);' class='markComplete' id='" . $resourceStep->resourceStepID . "'>mark complete</a>";
						}
						//track how many open steps there are
						$openStep++;
					}?>
				</td>
				</tr>
				<?php


			}
			echo "</table>";
		}


		if ($resource->workflowRestartLoginID){
			$rUser = new User(new NamedArguments(array('primaryKey' => $resource->workflowRestartLoginID)));

			//workflow restart is being used for both completion and restart - until the next database upgrade
			//this was marked complete...
			if (($openStep > 0) && ($resource->statusID == $completeStatusID)){
				if ($rUser->firstName){
					echo "<i>Workflow completed on " . format_date($resource->workflowRestartDate) . " by " . $rUser->firstName . " " . $rUser->lastName . "</i><br />";
				}else{
					echo "<i>Workflow completed on " . format_date($resource->workflowRestartDate) . " by " . $resource->workflowRestartLoginID . "</i><br />";
				}
			}else{
				if ($rUser->firstName){
					echo "<i>Workflow restarted on " . format_date($resource->workflowRestartDate) . " by " . $rUser->firstName . " " . $rUser->lastName . "</i><br />";
				}else{
					echo "<i>Workflow restarted on " . format_date($resource->workflowRestartDate) . " by " . $resource->workflowRestartLoginID . "</i><br />";
				}
			}
		}


		echo "<br /><br />";

		if ($user->canEdit()){
			if (($resource->statusID != $completeStatusID) && ($resource->statusID != $archiveStatusID)){
				echo "<img src='images/pencil.gif' />&nbsp;&nbsp;<a href='javascript:void(0);' class='restartWorkflow' id='" . $resourceID . "'>restart workflow</a><br />";
				echo "<img src='images/pencil.gif' />&nbsp;&nbsp;<a href='javascript:void(0);' class='markResourceComplete' id='" . $resourceID . "'>mark entire workflow complete</a><br />";
			}
		}

		break;








	//number of attachments, used to display on the tab so user knows whether to look on tab
	case 'getAttachmentsNumber':
		$resourceID = $_GET['resourceID'];
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		echo count($resource->getAttachments());

		break;



	//update main header title
	case 'getTitle':
		$resourceID = $_GET['resourceID'];
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		echo $resource->titleText;

		break;




	//determine if resource is valid
	case 'getIsValidResourceID':
		$resourceID = $_GET['resourceID'];
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		if($resource->titleText != ''){
			echo "1";
		}else{
			echo "0";
		}


		break;




    case 'getRightPanel':
    	$resourceID = $_GET['resourceID'];
    	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		$config=new Configuration();

		//get parent resource
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


		//get organizations (already returned in an array)
		$orgArray = $resource->getDistinctOrganizationArray();

		//get licenses (already returned in array)
		$licenseArray = $resource->getLicenseArray();

		echo "<div style='background-color:white; width:219px; padding:7px;'>";
		echo "<div class='rightPanelLink'><a href='summary.php?resourceID=" . $resource->resourceID . "' target='_blank' class='helpfulLink'>Print View</a></div>";
		if (($resource->systemNumber) && ($config->settings->catalogURL != '')) {
			echo "<div class='rightPanelLink'><a href='" . $config->settings->catalogURL . $resource->systemNumber . "' target='_blank'>Catalog View</a></div>";
		}
		echo "</div>";

		if (($parentResource->titleText) || (count($childResourceArray) > 0)){

		?>
			<div style='background-color:white; width:219px; padding:7px;'>
				<?php

				if ($parentResource->titleText){
					echo "<div class='rightPanelHeader'>Parent Record</div>";
					echo "<div class='rightPanelLink'><a href='resource.php?resourceID=" . $parentResource->resourceID . "' target='_BLANK' class='helpfulLink'>" . $parentResource->titleText . "</a></div>";
					echo "</br>";					
				}

				if ((count($childResourceArray) > 0)){ 				
					echo "<div class='rightPanelHeader'>Child Record(s)</div>";
				
					foreach ($childResourceArray as $childResource){
						$childResourceObj = new Resource(new NamedArguments(array('primaryKey' => $childResource['resourceID'])));
						echo "<div class='rightPanelLink'><a href='resource.php?resourceID=" . $childResourceObj->resourceID . "' target='_BLANK' class='helpfulLink'>" . $childResourceObj->titleText . "</a></div>";
					}
				}
				
				?>
			</div>

		<?php
		}

		if ((count($orgArray) > 0) && ($config->settings->organizationsModule == 'Y')){

		?>

			<div style='background-color:white; width:219px; padding:7px;'>
				<div class='rightPanelHeader'>Organizations Module</div>

				<?php
				foreach ($orgArray as $organization){
					echo "<div class='rightPanelLink'><a href='" . $util->getOrganizationURL() . $organization['organizationID'] . "' target='_blank' class='helpfulLink'>" . $organization['organization'] . "</a></div>";
				}

				?>
			</div>
		<?php
		}

		if ((count($licenseArray) > 0) && ($config->settings->licensingModule == 'Y')){

		?>
			<div style='background-color:white; width:219px; padding:7px;'>
				<div class='rightPanelHeader'>Licensing Module</div>

				<?php
				foreach ($licenseArray as $license){
					echo "<div class='rightPanelLink'><a href='" . $util->getLicensingURL() . $license['licenseID'] . "' target='_blank' class='helpfulLink'>" . $license['license'] . "</a></div>";
				}

				?>

			</div>

		<?php
		}

        break;






    case 'getSavedQueue':

		$resourceArray = array();
		$resourceArray = $user->getResourcesInQueue('saved');

		echo "<div class='adminRightHeader'>Saved Requests</div>";



		if (count($resourceArray) == "0"){
			echo "<i>No saved requests</i>";
		}else{
		?>

			<table class='dataTable' style='width:570px;margin-bottom:5px;'>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Date Created</th>
				<th>Acquisition Type</th>
				<th>Status</th>
				<th>&nbsp;</th>
			</tr>

		<?php
			$i=0;
			foreach ($resourceArray as $resource){

				$i++;
				if ($i % 2 == 0){
					$classAdd="";
				}else{
					$classAdd="class='alt'";
				}

				$acquisitionType = new AcquisitionType(new NamedArguments(array('primaryKey' => $resource['acquisitionTypeID'])));
				$status = new Status(new NamedArguments(array('primaryKey' => $resource['statusID'])));



		?>
				<tr id='tr_<?php echo $resource['resourceID']; ?>'>
					<td <?php echo $classAdd; ?>><a href='ajax_forms.php?action=getNewResourceForm&height=483&width=775&resourceID=<?php echo $resource['resourceID']; ?>&modal=true' class='thickbox'><?php echo $resource['resourceID']; ?></a></td>
					<td <?php echo $classAdd; ?>><a href='ajax_forms.php?action=getNewResourceForm&height=483&width=775&resourceID=<?php echo $resource['resourceID']; ?>&modal=true' class='thickbox'><?php echo $resource['titleText']; ?></a></td>
					<td <?php echo $classAdd; ?>><?php echo $resource['createDate']; ?></td>
					<td <?php echo $classAdd; ?>><?php echo $acquisitionType->shortName; ?></td>
					<td <?php echo $classAdd; ?>><?php echo $status->shortName; ?></td>
					<td <?php echo $classAdd; ?> style='text-align:right; width:40px;'>
					<a href='ajax_forms.php?action=getNewResourceForm&height=483&width=775&resourceID=<?php echo $resource['resourceID']; ?>&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit request'></a>&nbsp;
					<a href='javascript:void(0);' class='deleteRequest' id='<?php echo $resource['resourceID']; ?>'><img src='images/cross.gif' alt='remove request' title='remove request'></a>
					</td>
				</tr>



			<?php
			}

			echo "</table>";

		}


        break;





    case 'getSubmittedQueue':

		$resourceArray = array();
		$resourceArray = $user->getResourcesInQueue('progress');

		echo "<div class='adminRightHeader'>Submitted Requests</div>";



		if (count($resourceArray) == "0"){
			echo "<i>No submitted requests</i>";
		}else{
		?>

			<table class='dataTable' style='width:570px;margin-bottom:5px;'>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Date Created</th>
				<th>Acquisition Type</th>
				<th>Status</th>
			</tr>

		<?php
			$i=0;
			foreach ($resourceArray as $resource){

				$i++;
				if ($i % 2 == 0){
					$classAdd="";
				}else{
					$classAdd="class='alt'";
				}

				$acquisitionType = new AcquisitionType(new NamedArguments(array('primaryKey' => $resource['acquisitionTypeID'])));
				$status = new Status(new NamedArguments(array('primaryKey' => $resource['statusID'])));

		?>
				<tr id='tr_<?php echo $resource['resourceID']; ?>'>
					<td <?php echo $classAdd; ?>><a href='resource.php?resourceID=<?php echo $resource['resourceID']; ?>'><?php echo $resource['resourceID']; ?></a></td>
					<td <?php echo $classAdd; ?>><a href='resource.php?resourceID=<?php echo $resource['resourceID']; ?>'><?php echo $resource['titleText']; ?></a></td>
					<td <?php echo $classAdd; ?>><?php echo $resource['createDate']; ?></td>
					<td <?php echo $classAdd; ?>><?php echo $acquisitionType->shortName; ?></td>
					<td <?php echo $classAdd; ?>><?php echo $status->shortName; ?></td>
					</td>
				</tr>



			<?php
			}

			echo "</table>";

		}


        break;



    case 'getOutstandingQueue':


		$resourceArray = array();
		$resourceArray = $user->getOutstandingTasks();

		echo "<div class='adminRightHeader'>Outstanding Tasks</div>";



		if (count($resourceArray) == "0"){
			echo "<i>No outstanding requests</i>";
		}else{
		?>


			<table class='dataTable' style='width:646px;padding:0x;margin:0px;height:100%;'>
			<tr>
				<th style='width:45px;'>ID</th>
				<th style='width:300px;'>Name</th>
				<th style='width:95px;'>Acquisition Type</th>
				<th style='width:125px;'>Routing Step</th>
				<th style='width:75px;'>Start Date</th>
			</tr>

		<?php
			$i=0;
			foreach ($resourceArray as $resource){
				$taskArray = $user->getOutstandingTasksByResource($resource['resourceID']);
				$countTasks = count($taskArray);

				//for shading every other row
				$i++;
				if ($i % 2 == 0){
					$classAdd="";
				}else{
					$classAdd="class='alt'";
				}



				$acquisitionType = new AcquisitionType(new NamedArguments(array('primaryKey' => $resource['acquisitionTypeID'])));
				$status = new Status(new NamedArguments(array('primaryKey' => $resource['statusID'])));

		?>
				<tr id='tr_<?php echo $resource['resourceID']; ?>' style='padding:0x;margin:0px;height:100%;'>
					<td <?php echo $classAdd; ?>><a href='resource.php?resourceID=<?php echo $resource['resourceID']; ?>'><?php echo $resource['resourceID']; ?></a></td>
					<td <?php echo $classAdd; ?>><a href='resource.php?resourceID=<?php echo $resource['resourceID']; ?>'><?php echo $resource['titleText']; ?></a></td>
					<td <?php echo $classAdd; ?>><?php echo $acquisitionType->shortName; ?></td>

					<?php
						$j=0;


						if (count($taskArray) > 0){
							foreach ($taskArray as $task){
								if ($j > 0){
								?>
								<tr>
								<td <?php echo $classAdd; ?> style='border-top-style:none;'>&nbsp;</td>
								<td <?php echo $classAdd; ?> style='border-top-style:none;'>&nbsp;</td>
								<td <?php echo $classAdd; ?> style='border-top-style:none;'>&nbsp;</td>

								<?php
									$styleAdd=" style='border-top-style:none;'";
								}else{
									$styleAdd="";
								}


								echo "<td " . $classAdd . " " . $styleAdd . ">" . $task['stepName'] . "</td>";
								echo "<td " . $classAdd . " " . $styleAdd . ">" . format_date($task['startDate']) . "</td>";
								echo "</tr>";

								$j++;
							}

						}else{
							echo "<td " . $classAdd . ">&nbsp;</td><td " . $classAdd . ">&nbsp;</td></tr>";
						}


			}

			echo "</table>";


		}

		break;




	//used to populate the tabs in the queue
	case 'getSavedRequestsNumber':

		echo count($user->getResourcesInQueue('saved'));

		break;



	//used to populate the tabs in the queue
	case 'getOutstandingTasksNumber':

		echo count($user->getOutstandingTasks());

		break;


	//used to populate the tabs in the queue
	case 'getSubmittedRequestsNumber':

		echo count($user->getResourcesInQueue('progress'));

		break;



	case 'getSearchResources':
    Resource::setSearch($_POST['search']);
    
    $queryDetails = Resource::getSearchDetails();
    $whereAdd = $queryDetails["where"];
    $page = $queryDetails["page"];
    $orderBy = $queryDetails["order"];
    $recordsPerPage = $queryDetails["perPage"];
    
    //numbers to be displayed in records per page dropdown
		$recordsPerPageDD = array(10,25,50,100);

		//determine starting rec - keeping this based on 0 to make the math easier, we'll add 1 to the display only
		//page will remain based at 1
		if ($page == '1'){
			$startingRecNumber = 0;
		}else{
			$startingRecNumber = ($page * $recordsPerPage) - $recordsPerPage;
		}


		//get total number of records to print out and calculate page selectors
		$resourceObj = new Resource();
		$totalRecords = $resourceObj->searchCount($whereAdd);

		//reset pagestart to 1 - happens when a new search is run but it kept the old page start
		if ($totalRecords < $startingRecNumber){
			$page = 1;
			$startingRecNumber = 1;
		}

		$limit = $startingRecNumber . ", " . $recordsPerPage;

		$resourceArray = array();
		$resourceArray = $resourceObj->search($whereAdd, $orderBy, $limit);

		if (count($resourceArray) == 0){
			echo "<br /><br /><i>Sorry, no requests fit your query</i>";
			$i=0;
		}else{
			//maximum number of pages to display on screen at one time
			$maxDisplay = 25;

			$displayStartingRecNumber = $startingRecNumber + 1;
			$displayEndingRecNumber = $startingRecNumber + $recordsPerPage;

			if ($displayEndingRecNumber > $totalRecords){
				$displayEndingRecNumber = $totalRecords;
			}

			//div for displaying record count
			echo "<span style='float:left; font-weight:bold; width:650px;'>Displaying " . $displayStartingRecNumber . " to " . $displayEndingRecNumber . " of " . $totalRecords . " Resource Records</span><span style='float:right;width:20px;'><a href='javascript:void(0);'><img src='images/xls.gif' id='export'></a></span>";


			//print out page selectors as long as there are more records than the number that should be displayed
			if ($totalRecords > $recordsPerPage){
				echo "<div style='vertical-align:bottom;text-align:left;clear:both;'>";

				//print starting <<
				if ($page == 1){
					echo "<span class='smallerText'><<</span>&nbsp;";
				}else{
					$prevPage = $page - 1;
					echo "<a href='javascript:void(0);' id='" . $prevPage . "' class='setPage smallLink' alt='previous page' title='previous page'><<</a>&nbsp;";
				}


				//now determine the starting page - we will display 3 prior to the currently selected page
				if ($page > 3){
					$startDisplayPage = $page - 3;
				}else{
					$startDisplayPage = 1;
				}

				$maxPages = ($totalRecords / $recordsPerPage) + 1;

				//now determine last page we will go to - can't be more than maxDisplay
				$lastDisplayPage = $startDisplayPage + $maxDisplay;
				if ($lastDisplayPage > $maxPages){
					$lastDisplayPage = ceil($maxPages);
				}

				for ($i=$startDisplayPage; $i<$lastDisplayPage;$i++){

					if ($i == $page){
						echo "<span class='smallerText'>" . $i . "</span>&nbsp;";
					}else{
						echo "<a href='javascript:void(0);' id='" . $i . "' class='setPage smallLink'>" . $i . "</a>&nbsp;";
					}

				}

				$nextPage = $page + 1;
				//print last >> arrows
				if ($nextPage >= $maxPages){
					echo "<span class='smallerText'>>></span>&nbsp;";
				}else{
					echo "<a href='javascript:void(0);' id='" . $nextPage . "' class='setPage smallLink' alt='next page' title='next page'>>></a>&nbsp;";
				}

				echo "</div>";


			}else{
				echo "<div style='vertical-align:bottom;text-align:left;clear:both;'>&nbsp;</div>";
			}


			?>
			<table class='dataTable' style='width:727px'>
			<tr>
			<th><table class='noBorderTable' style='width:100%'><tr><td>ID</td><td style='width:10px;'><a href='javascript:setOrder("R.resourceID + 0","asc");'><img src='images/arrowup.gif' border=0></a></td><td style='width:10px;'><a href='javascript:setOrder("R.resourceID + 0","desc");'><img src='images/arrowdown.gif' border=0></a></td></tr></table></th>
			<th><table class='noBorderTable' style='width:100%'><tr><td>Name</td><td style='width:10px;'><a href='javascript:setOrder("R.titleText","asc");'><img src='images/arrowup.gif' border=0></a></td><td style='width:10px;'><a href='javascript:setOrder("R.titleText","desc");'><img src='images/arrowdown.gif' border=0></a></td></tr></table></th>
			<th><table class='noBorderTable' style='width:100%'><tr><td>Creator</td><td style='width:10px;'><a href='javascript:setOrder("CU.loginID","asc");'><img src='images/arrowup.gif' border=0></a></td><td style='width:10px;'><a href='javascript:setOrder("CU.loginID","desc");'><img src='images/arrowdown.gif' border=0></a></td></tr></table></th>
			<th><table class='noBorderTable' style='width:100%'><tr><td>Date Created</td><td style='width:10px;vertical-align:top;'><a href='javascript:setOrder("R.createDate","asc");'><img src='images/arrowup.gif' border=0></a></td><td style='width:10px;vertical-align:top;'><a href='javascript:setOrder("R.createDate","desc");'><img src='images/arrowdown.gif' border=0></a></td></tr></table></th>
			<th><table class='noBorderTable' style='width:100%'><tr><td>Acquisition Type</td><td style='width:10px;vertical-align:top;'><a href='javascript:setOrder("acquisitionType","asc");'><img src='images/arrowup.gif' border=0></a></td><td style='width:10px;vertical-align:top;'><a href='javascript:setOrder("acquisitionType","desc");'><img src='images/arrowdown.gif' border=0></a></td></tr></table></th>
			<th><table class='noBorderTable' style='width:100%'><tr><td>Status</td><td style='width:10px;'><a href='javascript:setOrder("S.shortName","asc");'><img src='images/arrowup.gif' border=0></a></td><td style='width:10px;'><a href='javascript:setOrder("S.shortName","desc");'><img src='images/arrowdown.gif' border=0></a></td></tr></table></th>
			</tr>

			<?php

			$i=0;
			foreach ($resourceArray as $resource){
				$i++;
				if ($i % 2 == 0){
					$classAdd="";
				}else{
					$classAdd="class='alt'";
				}
				echo "<tr>";
				echo "<td $classAdd><a href='resource.php?resourceID=" . $resource['resourceID'] . "'>" . $resource['resourceID'] . "</a></td>";
				echo "<td $classAdd><a href='resource.php?resourceID=" . $resource['resourceID'] . "'>" . $resource['titleText'] . "</a></td>";

				if ($resource['firstName'] || $resource['lastName']){
					echo "<td $classAdd>" . $resource['firstName'] . " " . $resource['lastName'] ."</td>";
				}else{
					echo "<td $classAdd>" . $resource['createLoginID'] . "</td>";
				}
				echo "<td $classAdd>" . format_date($resource['createDate']) . "</td>";

				echo "<td $classAdd>" . $resource['acquisitionType'] . "</td>";
				echo "<td $classAdd>" . $resource['status'] . "</td>";
				echo "</tr>";
			}

			?>
			</table>

			<table style='width:100%;margin-top:4px'>
			<tr>
			<td style='text-align:left'>
			<?php
			//print out page selectors
			if ($totalRecords > $recordsPerPage){

				//print starting <<
				if ($page == 1){
					echo "<span class='smallerText'><<</span>&nbsp;";
				}else{
					$prevPage = $page - 1;
					echo "<a href='javascript:void(0);' id='" . $prevPage . "' class='setPage smallLink' alt='previous page' title='previous page'><<</a>&nbsp;";
				}


				//now determine the starting page - we will display 3 prior to the currently selected page
				if ($page > 3){
					$startDisplayPage = $page - 3;
				}else{
					$startDisplayPage = 1;
				}

				$maxPages = ($totalRecords / $recordsPerPage) + 1;

				//now determine last page we will go to - can't be more than maxDisplay
				$lastDisplayPage = $startDisplayPage + $maxDisplay;
				if ($lastDisplayPage > $maxPages){
					$lastDisplayPage = ceil($maxPages);
				}

				for ($i=$startDisplayPage; $i<$lastDisplayPage;$i++){

					if ($i == $page){
						echo "<span class='smallerText'>" . $i . "</span>&nbsp;";
					}else{
						echo "<a href='javascript:void(0);' id='" . $i . "' class='setPage smallLink'>" . $i . "</a>&nbsp;";
					}

				}

				$nextPage = $page + 1;
				//print last >> arrows
				if ($nextPage >= $maxPages){
					echo "<span class='smallerText'>>></span>&nbsp;";
				}else{
					echo "<a href='javascript:void(0);' id='" . $nextPage . "' class='setPage smallLink' alt='next page' title='next page'>>></a>&nbsp;";
				}
			}
			?>
			</td>
			<td style="text-align:right">
			<select id='numberRecordsPerPage' name='numberRecordsPerPage' style='width:50px;'>
				<?php
				foreach ($recordsPerPageDD as $i){
					if ($i == $recordsPerPage){
						echo "<option value='" . $i . "' selected>" . $i . "</option>";
					}else{
						echo "<option value='" . $i . "'>" . $i . "</option>";
					}
				}
				?>
			</select>
			<span class='smallText'>records per page</span>
			</td>
			</tr>
			</table>

			<?php
		}

		break;






	case 'getAdminUserDisplay':
		$instanceArray = array();
		$user = new User();
		$tempArray = array();

		foreach ($user->allAsArray() as $tempArray) {

			$privilege = new Privilege(new NamedArguments(array('primaryKey' => $tempArray['privilegeID'])));

			$tempArray['priv'] = $privilege->shortName;

			array_push($instanceArray, $tempArray);
		}



		if (count($instanceArray) > 0){
			?>
			<div class="adminRightHeader">Users</div>
			<table class='linedDataTable' style='width:570px;margin-bottom:5px;'>
				<tr>
				<th>Login ID</td>
				<th>First Name</td>
				<th>Last Name</td>
				<th>Privilege</td>
				<th>View Accounts</td>
				<th>Email Address</td>
				<th>&nbsp;</td>
				<th>&nbsp;</td>
				</tr>
				<?php

				foreach($instanceArray as $instance) {
					if ($instance['accountTabIndicator'] == '1') {
						$accountTab = 'Y';
					}else{
						$accountTab = 'N';
					}

					echo "<tr>";
					echo "<td>" . $instance['loginID'] . "</td>";
					echo "<td>" . $instance['firstName'] . "</td>";
					echo "<td>" . $instance['lastName'] . "</td>";
					echo "<td>" . $instance['priv'] . "</td>";
					echo "<td>" . $accountTab . "</td>";
					echo "<td>" . $instance['emailAddress'] . "</td>";
					echo "<td><a href='ajax_forms.php?action=getAdminUserUpdateForm&loginID=" . $instance['loginID'] . "&height=275&width=315&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit user'></a></td>";
					echo "<td><a href='javascript:deleteUser(\"" . $instance['loginID'] . "\")'><img src='images/cross.gif' alt='remove' title='remove'></a></td>";
					echo "</tr>";
				}

				?>
			</table>
			<a href='ajax_forms.php?action=getAdminUserUpdateForm&loginID=&height=275&width=315&modal=true' class='thickbox' id='addUser'>add new user</a>
			<?php

		}else{
			echo "(none found)<br /><a href='ajax_forms.php?action=getAdminUserUpdateForm&loginID=&height=275&width=315&modal=true' class='thickbox' id='addUser'>add new user</a>";
		}

		break;









	case 'getAdminCurrencyDisplay':

		$instanceArray = array();
		$obj = new Currency();

		$instanceArray = $obj->allAsArray();

		echo "<div class='adminRightHeader'>Currency</div>";

		if (count($instanceArray) > 0){
			?>
			<table class='linedDataTable'>
				<tr>
				<th style='width:25px;'>Code</th>
				<th style='width:100%;'>Name</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				</tr>
				<?php

				foreach($instanceArray as $instance) {
					echo "<tr>";
					echo "<td>" . $instance['currencyCode'] . "</td>";
					echo "<td>" . $instance['shortName'] . "</td>";
					echo "<td><a href='ajax_forms.php?action=getAdminCurrencyUpdateForm&updateID=" . $instance['currencyCode'] . "&height=178&width=260&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit'></a></td>";
					echo "<td><a href='javascript:deleteCurrency(\"Currency\", \"" . $instance['currencyCode'] . "\");'><img src='images/cross.gif' alt='remove' title='remove'></a></td>";
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo "(none found)<br />";
		}

		echo "<a href='ajax_forms.php?action=getAdminCurrencyUpdateForm&updateID=&height=178&width=260&modal=true' class='thickbox'>add new currency</a>";

		break;




	case 'getAdminDisplay':
		$className = $_GET['className'];


		$instanceArray = array();
		$obj = new $className();

		$instanceArray = $obj->allAsArray();

		echo "<div class='adminRightHeader'>" . preg_replace("/[A-Z]/", " \\0" , $className) . "</div>";

		if (count($instanceArray) > 0){
			?>
			<table class='linedDataTable'>
				<tr>
				<th style='width:100%;'>Value</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				</tr>
				<?php

				foreach($instanceArray as $instance) {
					echo "<tr>";
					echo "<td>" . $instance['shortName'] . "</td>";
					echo "<td><a href='ajax_forms.php?action=getAdminUpdateForm&className=" . $className . "&updateID=" . $instance[lcfirst($className) . 'ID'] . "&height=128&width=260&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit'></a></td>";
					echo "<td><a href='javascript:void(0);' class='removeData' cn='" . $className . "' id='" . $instance[lcfirst($className) . 'ID'] . "'><img src='images/cross.gif' alt='remove' title='remove'></a></td>";
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo "(none found)<br />";
		}

		echo "<a href='ajax_forms.php?action=getAdminUpdateForm&className=" . $className . "&updateID=&height=128&width=260&modal=true' class='thickbox'>add new " . strtolower(preg_replace("/[A-Z]/", " \\0" , lcfirst($className))) . "</a>";

		break;








	case 'getAdminAlertDisplay':

		$alertEmailAddress = new AlertEmailAddress();
		$alertDaysInAdvance = new AlertDaysInAdvance();


		$emailAddressArray = $alertEmailAddress->allAsArray();
		$daysInAdvanceArray = $alertDaysInAdvance->allAsArray();

		echo "<div class='adminRightHeader'>Alert Settings</div>";

		if (count($emailAddressArray) > 0){
			?>
			<table class='linedDataTable'>
				<tr>
				<th style='width:100%;'>Email Address</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				</tr>
				<?php

				foreach($emailAddressArray as $emailAddress) {
					echo "<tr>";
					echo "<td>" . $emailAddress['emailAddress'] . "</td>";
					echo "<td><a href='ajax_forms.php?action=getAdminAlertEmailForm&alertEmailAddressID=" . $emailAddress['alertEmailAddressID'] . "&height=128&width=260&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit'></a></td>";
					echo "<td><a href='javascript:deleteAlert(\"AlertEmailAddress\", " . $emailAddress['alertEmailAddressID'] . ");'><img src='images/cross.gif' alt='remove' title='remove'></a></td>";
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo "(none found)<br />";
		}

		echo "<a href='ajax_forms.php?action=getAdminAlertEmailForm&alertEmailAddressID=&height=128&width=260&modal=true' class='thickbox'>add email address</a>";
		echo "<br /><br /><br />";


		if (count($daysInAdvanceArray) > 0){
			?>
			<table class='linedDataTable'>
				<tr>
				<th style='width:100%;'>Days in advance of expiration</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				</tr>
				<?php

				foreach($daysInAdvanceArray as $daysInAdvance) {
					echo "<tr>";
					echo "<td>" . $daysInAdvance['daysInAdvanceNumber'] . "</td>";
					echo "<td><a href='ajax_forms.php?action=getAdminAlertDaysForm&alertDaysInAdvanceID=" . $daysInAdvance['alertDaysInAdvanceID'] . "&height=128&width=260&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit'></a></td>";
					echo "<td><a href='javascript:deleteAlert(\"AlertDaysInAdvance\", " . $daysInAdvance['alertDaysInAdvanceID'] . ");'><img src='images/cross.gif' alt='remove' title='remove'></a></td>";
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo "(none found)<br />";
		}




		echo "<a href='ajax_forms.php?action=getAdminAlertDaysForm&alertDaysInAdvanceID=&height=128&width=260&modal=true' class='thickbox'>add days</a>";

		break;



	case 'getAdminWorkflowDisplay':

		$workflow = new Workflow();
		$workflowArray = $workflow->allAsArray();

		$userGroup = new UserGroup();
		$userGroupArray = $userGroup->allAsArray();


		echo "<div class='adminRightHeader'>Workflow Setup</div>";

		if (count($workflowArray) > 0){
			?>
			<table class='linedDataTable' style='width:100%'>
				<tr>
				<th>Acquisition Type</th>
				<th>Resource Format</th>
				<th>Resource Type</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				</tr>
				<?php

				foreach($workflowArray as $wf) {

					$resourceFormat = new ResourceFormat(new NamedArguments(array('primaryKey' => $wf['resourceFormatIDValue'])));
					$acquisitionType = new AcquisitionType(new NamedArguments(array('primaryKey' => $wf['acquisitionTypeIDValue'])));
					if (($wf['resourceTypeIDValue'] != '') && ($wf['resourceTypeIDValue'] != '0')){
						$resourceType = new ResourceType(new NamedArguments(array('primaryKey' => $wf['resourceTypeIDValue'])));
						$rtName = $resourceType->shortName;
					}else{
						$rtName = 'any';
					}

					echo "<tr>";
					echo "<td>" . $acquisitionType->shortName . "</td>";
					echo "<td>" . $resourceFormat->shortName . "</td>";
					echo "<td>" . $rtName . "</td>";
					echo "<td><a href='ajax_forms.php?action=getAdminWorkflowForm&workflowID=" . $wf['workflowID'] . "&height=528&width=750&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit'></a></td>";
					echo "<td><a href='javascript:deleteWorkflow(\"Workflow\", " . $wf['workflowID'] . ");'><img src='images/cross.gif' alt='remove' title='remove'></a></td>";
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo "(none found)<br />";
		}

		//user groups are required to set workflows up so display this message if there arent any
		if (count($userGroupArray) >0){
			echo "<a href='ajax_forms.php?action=getAdminWorkflowForm&workflowID=&height=528&width=750&modal=true' class='thickbox'>add workflow</a>";
		}else{
			echo "<i>You must set up at least one user group before you can add workflows</i>";
		}

		?>


		<br /><br /><br /><br />

		<?php

		echo "<div class='adminRightHeader'>User Group Setup</div>";

		if (count($userGroupArray) > 0){
			?>
			<table class='linedDataTable' style='width:100%'>
				<tr>
				<th>Group Name</th>
				<th>Email Address</th>
				<th>Users</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				</tr>
				<?php

				foreach($userGroupArray as $ug) {
					$userGroup = new UserGroup(new NamedArguments(array('primaryKey' => $ug['userGroupID'])));
					echo "<tr>";
					echo "<td>" . $userGroup->groupName . "</td>";
					echo "<td>" . $userGroup->emailAddress . "</td>";
					echo "<td>";
					foreach ($userGroup->getUsers() as $groupUser){
						echo $groupUser->getDisplayName . "<br />";
					}
					echo "</td>";
					echo "<td><a href='ajax_forms.php?action=getAdminUserGroupForm&userGroupID=" . $userGroup->userGroupID . "&height=400&width=305&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit'></a></td>";
					echo "<td><a href='javascript:deleteWorkflow(\"UserGroup\", " . $userGroup->userGroupID . ");'><img src='images/cross.gif' alt='remove' title='remove'></a></td>";
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo "(none found)<br />";
		}


		echo "<a href='ajax_forms.php?action=getAdminUserGroupForm&userGroupID=&height=400&width=305&modal=true' class='thickbox'>add user group</a>";

		break;


	case 'getGeneralSubjectDisplay':
		$className = $_GET['className'];


		$instanceArray = array();
		$obj = new $className();

		$instanceArray = $obj->allAsArray();

		echo "<div class='adminRightHeader'>" . preg_replace("/[A-Z]/", " \\0" , $className) . "</div>";

		if (count($instanceArray) > 0){
			?>
			<table class='linedDataTable'>
				<tr>
				<th style='width:100%;'>Value</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				</tr>
				<?php
	
				foreach($instanceArray as $instance) {
					echo "<tr>";
					echo "<td>" . $instance['shortName'] . "</td>";
					echo "<td><a href='ajax_forms.php?action=getGeneralSubjectUpdateForm&className=" . $className . "&updateID=" . $instance[lcfirst($className) . 'ID'] . "&height=128&width=260&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit'></a></td>";
					echo "<td><a href='javascript:void(0);' class='removeData' cn='" . $className . "' id='" . $instance[lcfirst($className) . 'ID'] . "'><img src='images/cross.gif' alt='remove' title='remove'></a></td>";
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo "(none found)<br />";
		}

		echo "<a href='ajax_forms.php?action=getAdminUpdateForm&className=" . $className . "&updateID=&height=128&width=260&modal=true' class='thickbox'>add new " . strtolower(preg_replace("/[A-Z]/", " \\0" , lcfirst($className))) . "</a>";

		break;


	case 'getAdminSubjectDisplay':

		$generalSubject = new GeneralSubject();
		$generalSubjectArray = $generalSubject->allAsArray();

		$detailedSubject = new DetailedSubject();
		$detailedSubjectArray = $detailedSubject->allAsArray();

		echo "<div class='adminRightHeader'>General Subject</div>";

		if (count($generalSubjectArray) > 0){
			?>
			<table class='linedDataTable'>
				<tr>
				<th style='width:100%;'>Value</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				</tr>
				<?php
					
				foreach($generalSubjectArray as $instance) {
					echo "<tr>";
					echo "<td>" . $instance['shortName'] . "</td>";
					echo "<td><a href='ajax_forms.php?action=getGeneralSubjectUpdateForm&className=" . "GeneralSubject" . "&updateID=" . $instance[lcfirst("GeneralSubject") . 'ID'] . "&height=128&width=260&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit'></a></td>";
						
						$generalSubject = new GeneralSubject();
						if ($generalSubject->inUse($instance[lcfirst("GeneralSubject") . 'ID']) == 0) {
							echo "<td><a href='javascript:deleteGeneralSubject(\"GeneralSubject\", " . $instance[lcfirst("GeneralSubject") . 'ID'] . ");'><img src='images/cross.gif' alt='remove' title='remove'></a></td>";
						} else {
							echo "<td><img src='images/do_not_enter.png' alt='subject in use' title='subject in use' /></td>";
						}
						
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo "(none found)<br />";
		}

		echo "<a href='ajax_forms.php?action=getGeneralSubjectUpdateForm&className=" . "GeneralSubject" . "&updateID=&height=145&width=260&modal=true' class='thickbox'>add new " . strtolower(preg_replace("/[A-Z]/", " \\0" , lcfirst("GeneralSubject"))) . "</a>";
		
		?>
		
		<br /><br />
		
		<?php
		echo "<div class='adminRightHeader'>Detailed Subject</div>";		
		
		if (count($detailedSubjectArray) > 0){
			?>
			<table class='linedDataTable'>
				<tr>
				<th style='width:100%;'>Value</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				</tr>
				<?php
					
				foreach($detailedSubjectArray as $instance) {
					echo "<tr>";
					echo "<td>" . $instance['shortName'] . "</td>";
					echo "<td><a href='ajax_forms.php?action=getDetailSubjectUpdateForm&className=" . "DetailedSubject" . "&updateID=" . $instance[lcfirst("DetailedSubject") . 'ID'] . "&height=128&width=260&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit'></a></td>";
						$detailedSubject = new DetailedSubject();
						if ($detailedSubject->inUse($instance[lcfirst("DetailedSubject") . 'ID'], -1) == 0) {
									echo "<td><a href='javascript:deleteDetailedSubject(\"DetailedSubject\", " . $instance[lcfirst("DetailedSubject") . 'ID'] . ");'><img src='images/cross.gif' alt='remove' title='remove'></a></td>";
						} else {
							echo "<td><img src='images/do_not_enter.png' alt='subject in use' title='subject in use' /></td>";
						}					
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo "(none found)<br />";
		}

		echo "<a href='ajax_forms.php?action=getDetailSubjectUpdateForm&className=" . "DetailedSubject" . "&updateID=&height=145&width=260&modal=true' class='thickbox'>add new " . strtolower(preg_replace("/[A-Z]/", " \\0" , lcfirst("DetailedSubject"))) . "</a>";

		?>		
		
		<br /><br />

		<?php

		echo "<div class='adminRightHeader'>Subject Relationships</div>";

		if (count($generalSubjectArray) > 0){
			?>
			<table class='linedDataTable' style='width:100%'>
				<tr>
				<th>General Subject</th>
				<th>Detailed Subject</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				</tr>
				<?php

				foreach($generalSubjectArray as $ug) {
					$generalSubject = new GeneralSubject(new NamedArguments(array('primaryKey' => $ug['generalSubjectID'])));

					echo "<tr>";
					echo "<td>" . $generalSubject->shortName . "</td>";
					echo "<td>";
					foreach ($generalSubject->getDetailedSubjects() as $detailedSubjects){
						echo $detailedSubjects->shortName . "<br />";
					}
					echo "</td>";
					echo "<td><a href='ajax_forms.php?action=getGeneralDetailSubjectForm&generalSubjectID=" . $generalSubject->generalSubjectID . "&height=400&width=305&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit'></a></td>";
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo "(none found)<br />";
		}


		break;



	default:
       echo "Action " . $action . " not set up!";
       break;


}


?>

