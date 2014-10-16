<?php
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

?>


