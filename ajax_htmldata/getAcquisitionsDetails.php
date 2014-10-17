<?php
    	$config = new Configuration();
    	$resourceID = $_GET['resourceID'];
    	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

    	$orderType = new OrderType(new NamedArguments(array('primaryKey' => $resource->orderTypeID)));
		$acquisitionType = new AcquisitionType(new NamedArguments(array('primaryKey' => $resource->acquisitionTypeID)));
    	$costDetails = new CostDetails(new NamedArguments(array('primaryKey' => $resource->costDetailsID)));

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

			<?php if (($resource->currentStartDate) && ($resource->currentStartDate != '0000-00-00')) { ?>
			<tr>
			<td style='vertical-align:top;width:110px;'>Sub Start:</td>
			<td style='width:350px;'><?php echo format_date($resource->currentStartDate); ?></td>
			</tr>
			<?php } ?>

			<?php if (($resource->currentEndDate) && ($resource->currentEndDate != '0000-00-00')) { ?>
			<tr>
			<td style='vertical-align:top;width:110px;'>Current Sub End:</td>
			<td style='width:350px;'><?php echo format_date($resource->currentEndDate); ?>&nbsp;&nbsp;
			<?php if ($resource->subscriptionAlertEnabledInd == "1") { echo "<i>Expiration Alert Enabled</i>"; } ?>
			</td>
			</tr>
			<?php } ?>

			</table>
			<?php if ($user->canEdit()){ ?>
				<a href='ajax_forms.php?action=getOrderForm&height=462&width=783&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox' id='newAlias'>edit acquisitions information</a>
			<?php } ?>
			<br />
			<br />
			<br />

			<table class='linedFormTable' style='width:460px;'>
			<?php $enhancedCostFlag = ($config->settings->enhancedCostHistory == 'Y') ? 1 : 0 ?>
			<tr>
			<th colspan=<?php echo ($enhancedCostFlag ? 10 : 5) ?> style='vertical-align:bottom;'>
			<span style='float:left;vertical-align:bottom;'>Cost</span>
			<?php if ($user->canEdit()){ ?>
				<span style='float:right;vertical-align:bottom;'><a href='ajax_forms.php?action=getCostForm&height=462&width=783&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox' id='editCost'><img src='images/edit.gif' alt='edit' title='edit cost information'></a></span>
			<?php } ?>

			</th>
			</tr>
            <tr>
            <th style="background-color: #ffc">Year</th>
            <?php if ($enhancedCostFlag){ ?>
                <th style="background-color: #ffc">Sub Start</th>
                <th style="background-color: #ffc">Sub End</th>
            <?php } ?>
            <th style="background-color: #ffc">Fund</th>
            <th style="background-color: #ffc">Payment</th>
            <?php if ($enhancedCostFlag){ ?>
                <th style="background-color: #ffc">%</th>
            <?php } ?>
            <th style="background-color: #ffc">Type</th>
            <?php if ($enhancedCostFlag){ ?>
                <th style="background-color: #ffc">Details</th>
            <?php } ?>
            <th style="background-color: #ffc">Notes</th>
            <?php if ($enhancedCostFlag){ ?>
                <th style="background-color: #ffc">Invoice</th>
            <?php } ?>
            </tr>

			<?php
			if (count($paymentArray) > 0){
				foreach ($paymentArray as $payment){
				$year = $payment['year'] ? $payment['year'] : "&nbsp;";
				$fundName = $payment['fundName'] ? $payment['fundName'] : "&nbsp;";
				$costNote = $payment['costNote'] ? $payment['costNote'] : "&nbsp;";
$pctChange = 50;
				$costDetails = $payment['costDetails'] ? $payment['costDetails'] : "&nbsp;";
				$invoice = $payment['year'] ? $payment['year'] : "&nbsp;";

				if (integer_to_cost($payment['paymentAmount'])){
					$cost = $payment['currencyCode'] . " " . integer_to_cost($payment['paymentAmount']);
				}else{
					$cost = "&nbsp;";
				}

				?>
				<tr>
				<td><?php echo $year; ?></td>
                <?php if ($enhancedCostFlag){ ?>
                    <td><?php echo $subStart; ?></td>
                    <td><?php echo $subEnd; ?></td>
                <?php } ?>
                <td><?php echo $fund; ?></td>
                <td><?php echo $cost; ?></td>
                <?php if ($enhancedCostFlag){ ?>
                    <td><?php echo $pctChange; ?></td>
                <?php } ?>
                <td><?php echo $payment['orderType']; ?></td>
                <?php if ($enhancedCostFlag){ ?>
                    <td><?php echo $costDetails; ?></td>
                <?php } ?>
                <td><?php echo $costNote; ?></td>
                <?php if ($enhancedCostFlag){ ?>
                    <td><?php echo $invoice; ?></td>
                <?php } ?>
				</tr>

				<?php
				}
			}else{
                $n = ($enhancedCostFlag ? 8 : 3);
				echo "<tr><td colspan=" . $n . "><i>No payment information available.</i></td></tr>";
			}
			?>

			</table>
			<?php if ($user->canEdit()){ ?>
				<a href='ajax_forms.php?action=getCostForm&height=462&width=783&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox' id='newAlias'>edit cost information</a>
			<?php } ?>
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

?>

