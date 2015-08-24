<?php
$util = new utility();

$resourceID = $_GET["resourceID"];

$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID))); 

$organizationArray = $resource->getOrganizationArray();
$organizationData = $organizationArray[0];

//the issues feature currently support org or resource contacts, but not both
$moduleFilter = ($config->settings->organizationsModule == 'Y') ? 'organizations':'resources';
$contactsArray = $resource->getUnarchivedContacts($moduleFilter);
if ($organizationData['organizationID']) {
	$organizationResourcesArray = $resource->getSiblingResourcesArray($organizationData['organizationID']);
?>

<form id='newIssueForm'>
	<input type="hidden" id="sourceOrganizationID" name="sourceOrganizationID" value="<?php echo $organizationData['organizationID'];?>" />
	<input type="hidden" name="sourceResourceID" value="<?php echo $resourceID;?>" />
	<table class="thickboxTable" style="width:98%;background-image:url('images/title.gif');background-repeat:no-repeat;">
		<tr>
			<td colspan="2">
				<h1> Report New Problem</h1>
				<span class='smallDarkRedText'>* required fields</span>
			</td>
		</tr>
		<tr>
			<td><label>Organization:&nbsp;&nbsp;<span class='bigDarkRedText'>*</span></label></td>
			<td>
				<p><?php echo $organizationData['organization']; ?></p>
				<span id='span_error_organizationId' class='error smallDarkRedText'></span>
			</td>
		</tr>
		<tr>
			<td><label>Contact:&nbsp;&nbsp;<span class='bigDarkRedText'>*</span></label></td>
			<td>
				<select multiple style="min-height: 60px;" type='text' id='contactIDs' name='contactIDs[]'>
<?php 

	foreach ($contactsArray as $contact) {
		echo "		<option value=\"{$contact['contactID']}\">{$contact['name']}</option>";
	}

?>
				</select>
				<span id='span_error_contactName' class='error smallDarkRedText'></span>
			</td>
		</tr>
<?php
if ($config->settings->organizationsModule == 'Y') {
?>
		<tr>
			<td></td>
			<td>
				<input type="hidden" name="orgModuleUrl" id="orgModuleUrl" value="<?php echo $util->getCoralUrl();?>organizations/" />
				<a id="getCreateContactForm" href="#">add contact</a>
				<div id="inlineContact"></div>
			</td>
		</tr>
<?php
}
?>
		<tr>
			<td><label>CC myself:</label></td>
			<td>
				<input type='checkbox' id='ccCreator' name='ccCreator' class='changeInput' />
				<span id='span_error_ccCreator' class='error smallDarkRedText'></span>
			</td>
		</tr>
		<tr>
			<td><label>CC:</label></td>
			<td>
				<input type="text" id="inputEmail" name="inputEmail" />
				<input type="button" id="addEmail" name="addEmail" value="Add" />
				<p>
					Current CCs: <span id="currentEmails"></span>
				</p>
				<input type="hidden" id='ccEmails' name='ccEmails' value='' class='changeInput' />
				<span id='span_error_contactIDs' class='error smallDarkRedText'></span>
			</td>
		</tr>
		<tr>
			<td><label>Subject:&nbsp;&nbsp;<span class='bigDarkRedText'>*</span></label></td>
			<td>
				<input type='text' id='subjectText' name='issue[subjectText]' value='' class='changeInput' />
				<span id='span_error_subjectText' class='error smallDarkRedText'></span>
			</td>
		</tr>
		<tr>
			<td><label>Body:&nbsp;&nbsp;<span class='bigDarkRedText'>*</span></label></td>
			<td>
				<textarea id='bodyText' name='issue[bodyText]' value='' />
				<span id='span_error_bodyText' class='error smallDarkRedText'></span>
			</td>
		</tr>
		<tr>
			<td><label>Applies to:&nbsp;&nbsp;<span class='bigDarkRedText'>*</span></label></td>
			<td>

				<div>
					<input type="checkbox" class="issueResources entityArray" name="resourceIDs[]" value="<?php echo $resourceID;?>" checked /> <label for="thisResources">Applies only to <?php echo $resource->titleText ?></label>
				</div>
				<div>
					<input type="checkbox" class="issueResources entityArray" name="organizationID" id="organizationID" value="<?php echo $organizationData['organizationID'];?>" /> <label for="allResources">Applies to all resources of <?php echo $organizationData['organization']; ?></label>
				</div>
				<div>
					<input type="checkbox" class="issueResources" id="otherResources" /><label for="otherResources"> Applies to other Resources of <?php echo $resource->titleText ?></label>
				</div>
				<select multiple id="resourceIDs" name="resourceIDs[]">
<?php
	if (!empty($organizationResourcesArray)) {
		foreach ($organizationResourcesArray as $resource) {
			echo "		<option class=\"entityArray\" value=\"{$resource['resourceID']}\">{$resource['titleText']}</option>";
		}
	}
?>
				</select>
				<span id='span_error_entities' class='error smallDarkRedText'></span>
			</td>
		</tr>
	</table>

	<p> Send me a reminder every 
		<select name="issue[reminderInterval]">
			<?php for ($i = 1; $i <= 31; $i++) echo "<option>{$i}</option>"; ?>
		</select> day(s) 
	</p>

	<table class='noBorderTable' style='width:125px;'>
		<tr>
			<td style='text-align:left'><input type='button' value='submit' name='submitNewIssue' id='submitNewIssue'></td>
			<td style='text-align:right'><input type='button' value='cancel' onclick="tb_remove();"></td>
		</tr>
	</table>

</form>

<?php
} else {
	echo '
		<p>
			Opening an issue requires a resource to be associated with an organization.
		</p>
		<input type="button" value="cancel" onclick="tb_remove();">';
}
?>


