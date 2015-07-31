<?php
	$resourceID = $_GET['resourceID'];

	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

	$util = new Utility();
	$getIssuesFormData = "action=getIssuesList&resourceID=".$resourceID;


?>

	<table class='linedFormTable issueTable'>
		<tr>
			<th>Issues/Problems</th>
		</tr>
		<tr>
			<td><a class="thickbox" href="ajax_forms.php?action=getNewIssueForm&resourceID=<?php echo $resourceID; ?>&modal=true">report new issue</a></td>
		</tr>
		<tr>
			<td>
				<a href="<?php echo $getIssuesFormData; ?>" class="issuesBtn" id="openIssuesBtn">view open issue</a> 
				<a target="_blank" href="export_issues.php?resourceID=<?php echo $resourceID;?>"><img src="images/xls.gif" /></a>
				<div class="issueList" id="openIssues" style="display:none;"></div>
			</td>
		</tr>
		<tr>
			<td>
				<a href="<?php echo $getIssuesFormData."&archived=1"; ?>" class="issuesBtn" id="archivedIssuesBtn">view archived issues</a> 
				<a href=""><img src="images/xls.gif" /></a>
				<div class="issueList" id="archivedIssues"></div>
			</td>
		</tr>
	</table>

<?php
	


/*
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

				<table class='linedFormTable'>
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
*/
?>

