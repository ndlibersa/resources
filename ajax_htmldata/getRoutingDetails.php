<?php
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
