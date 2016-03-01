<?php
$util = new utility();

$organizationID = $_GET["organizationID"];

$resourceID = $_GET["resourceID"];
$issueID = $_GET['issueID'];

$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

$isOrgDowntime = false;
if ($organizationID) {
	$organization = new Organization(new NamedArguments(array('primaryKey' => $organizationID)));
	$issues = $organization->getIssues();
	$isOrgDowntime = true;
} else {
	$issues = $resource->getIssues();

	$organizationArray = $resource->getOrganizationArray();
	$organizationData = $organizationArray[0];

	if ($organizationData['organizationID']) {
		$organizationID = $organizationData['organizationID'];

		$organization = new Organization(new NamedArguments(array('primaryKey' => $organizationID)));

		$orgIssues = $organization->getIssues();

		foreach ($orgIssues as $issue) {
			array_push($issues, $issue);
		}
		$organizationResourcesArray = $resource->getSiblingResourcesArray($organizationID);
	}
}

//our $organizationID could have come from the $_GET or through the resource
if ($organizationID) {
	$downtimeObj = new Downtime();
	$downtimeTypeNames = $downtimeObj->getDowntimeTypesArray();

	$defaultStart = date("Y-m-d\TH:i");
	$defaultEnd = date("Y-m-d\TH:i", strtotime("+1 day"));

?>

<form id='newDowntimeForm'>
<?php
if ($isOrgDowntime) {
	echo '<input type="hidden" name="sourceOrganizationID" value="'.$organizationID.'" />';
} else {
	echo '<input type="hidden" name="sourceResourceID" value="'.$resourceID.'" />';
}
?>
	<table class="thickboxTable" style="width:98%;background-image:url('images/title.gif');background-repeat:no-repeat;">
		<tr>
			<td colspan="2">
				<h1> Resource Downtime Report</h1>
			</td>
		</tr>
		<tr>
			<td><label>Downtime Start:</label></td>
			<td>
				<input value="<?php echo $defaultStart; ?>" type="datetime-local" name="startDate" id="startDate" />
				<span id='span_error_startDate' class='smallDarkRedText addDowntimeError'>
			</td>
		</tr>
		<tr>
			<td><label>Downtime Resolution:</label></td>
			<td>
				<input value="<?php echo $defaultEnd; ?>"  type="datetime-local" name="endDate" id="endDate" />
				<span id='span_error_endDate' class='smallDarkRedText addDowntimeError'>
			</td>
		</tr>
		<tr>
			<td><label>Problem Type:</label></td>
			<td>
				<select class="downtimeType" name="downtimeType">
<?php
			foreach ($downtimeTypeNames as $downtimeType) {
				echo "<option value=".$downtimeType["downtimeTypeID"].">".$downtimeType["shortName"]."</option>";
			}
?>
				</select>
			</td>
		</tr>
		<tr>
<?php
if ($issues) {
?>
			<td><label>Link to open issue:</label></td>
			<td>
				<select class="issueID" name="issueID">
					<option value="">none</option>
<?php
			foreach ($issues as $issue) {
				echo "<option".(($issueID == $issue->issueID) ? ' selected':'')." value=".$issue->issueID.">".$issue->subjectText."</option>";
			}
?>
				</select>
			</td>
		</tr>
<?php
}
?>
		<tr>
			<td><label>Note:</label></td>
			<td>
				<textarea name="note"></textarea>
			</td>
		</tr>
	</table>

	<table class='noBorderTable' style='width:125px;'>
		<tr>
			<td style='text-align:left'><input type='button' value='submit' name='submitNewDowntime' id='submitNewDowntime'></td>
			<td style='text-align:right'><input type='button' value='cancel' onclick="tb_remove();"></td>
		</tr>
	</table>

</form>

<?php
} else {
	echo '
		<p>
			Creating downtime requires an organization or a resource to be associated with an organization.
		</p>
		<input type="button" value="cancel" onclick="tb_remove();">';
}
?>


