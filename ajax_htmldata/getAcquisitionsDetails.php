<?php
	$config = new Configuration();
	$enhancedCostFlag = ((isset($config->settings->enhancedCostHistory)) && (strtoupper($config->settings->enhancedCostHistory) == 'Y')) ? 1 : 0;
	$enhancedCostFlag = (strtoupper($config->settings->enhancedCostHistory) == 'Y') ? 1 : 0;
	if ($enhancedCostFlag){
		$numCols = 10;
		$tableWidth = 760;
	}else{
		$numCols = 4;
		$tableWidth = 646;
	}

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

				$costDetails = new CostDetails(new NamedArguments(array('primaryKey' => $instance->costDetailsID)));
				$sanitizedInstance['costDetails'] = $costDetails->shortName;
				if ($enhancedCostFlag){
					$sanitizedInstance['amountChange'] = $instance->getPaymentAmountChangeFromPreviousYear();
				}

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
			<table class='linedFormTable' style='width:<?php echo $tableWidth; ?>px;padding:0x;margin:0px;height:100%;'>
			<tr>
			<th colspan='2' style='vertical-align:bottom;'>
			<span style='float:left;vertical-align:bottom;'>Order</span>
			<?php if ($user->canEdit()){ ?>
				<span style='float:right;vertical-align:bottom;'><a href='ajax_forms.php?action=getOrderForm&height=400&width=480&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox' id='editOrder'><img src='images/edit.gif' alt='edit' title='edit order information'></a></span>
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
				<a href='ajax_forms.php?action=getOrderForm&height=400&width=440&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox'>edit order information</a>
			<?php } ?>
			<br />
			<br />
			<br />

			<table class='linedFormTable' style='width:<?php echo $tableWidth; ?>px;margin-bottom:5px;'>
			<tr>
			<th colspan='<?php echo $numCols; ?>' style='vertical-align:bottom;'>
			<span style='float:left;vertical-align:bottom;'>Cost</span>
			<?php if ($user->canEdit()){ ?>
				<span style='float:right;vertical-align:bottom;'><a href='ajax_forms.php?action=getCostForm&height=400&width=440&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox' id='editCost'><img src='images/edit.gif' alt='edit' title='edit cost information'></a></span>
			<?php } ?>

			</th>
			</tr>
			<tr>
		<?php if ($enhancedCostFlag){ ?>
			<th>Year</th>
			<th>Sub Start</th>
			<th>Sub End</th>
		<?php } ?>
			<th>Fund</th>
			<th>Payment</th>
		<?php if ($enhancedCostFlag){ ?>
			<th style='text-align: right'>%</th>
		<?php } ?>
			<th>Type</th>
		<?php if ($enhancedCostFlag){ ?>
			<th>Details</th>
		<?php } ?>
			<th>Notes</th>
		<?php if ($enhancedCostFlag){ ?>
			<th>Invoice</th>
		<?php } ?>
			</tr>

			<?php
			if (count($paymentArray) > 0){
				foreach ($paymentArray as $payment){
				$year = $payment['year'] ? $payment['year'] : "&nbsp;";
				$subStart = $payment['subscriptionStartDate'] ? normalize_date($payment['subscriptionStartDate']) : "&nbsp;";
				$subEnd = $payment['subscriptionEndDate'] ? normalize_date($payment['subscriptionEndDate']) : "&nbsp;";
				$fundName = $payment['fundName'] ? $payment['fundName'] : "&nbsp;";
				if (integer_to_cost($payment['paymentAmount'])){
					$cost = $payment['currencyCode'] . " " . integer_to_cost($payment['paymentAmount']);
				}else{
					$cost = "&nbsp;";
				}
				$costDetails = $payment['costDetails'] ? $payment['costDetails'] : "&nbsp;";
				$costNote = $payment['costNote'] ? $payment['costNote'] : "&nbsp;";
				$invoiceNum = $payment['invoiceNum'] ? $payment['invoiceNum'] : "&nbsp;";

				?>
				<tr>
			<?php if ($enhancedCostFlag){ ?>
				<td><?php echo $year; ?></td>
				<td><?php echo $subStart; ?></td>
				<td><?php echo $subEnd; ?></td>
			<?php } ?>
				<td><?php echo $fundName; ?></td>
				<td><?php echo $cost; ?></td>
			<?php if ($enhancedCostFlag){ ?>
				<td style='text-align: right'><?php echo $payment['amountChange']; ?></td>
			<?php } ?>
				<td><?php echo $payment['orderType']; ?></td>
			<?php if ($enhancedCostFlag){ ?>
				<td><?php echo $costDetails; ?></td>
			<?php } ?>
				<td><?php echo $costNote; ?></td>
			<?php if ($enhancedCostFlag){ ?>
				<td><?php echo $invoiceNum; ?></td>
			<?php } ?>
				</tr>

				<?php
				}
			}else{
				echo "<tr><td colspan='" . $numCols . "'><i>No payment information available.</i></td></tr>";
			}
			?>
			</table>
			<?php if ($user->canEdit()){ ?>
				<a href='ajax_forms.php?action=getCostForm&height=400&width=784&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox'>edit cost information</a>
			<?php } ?>
			<br />
			<br />
			<br />

			<table class='linedFormTable' style='width:<?php echo $tableWidth; ?>px;padding:0x;margin:0px;height:100%;'>
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
			<table class='linedFormTable' style='width:<?php echo $tableWidth; ?>px;padding:0x;margin:0px;height:100%;'>
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

