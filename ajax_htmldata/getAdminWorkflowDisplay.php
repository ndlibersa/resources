<?php

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

?>

