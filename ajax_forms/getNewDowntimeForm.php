<?php
$util = new utility();

$resourceID = $_GET["resourceID"];

$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));
$issues = $resource->getIssues();

$organizationArray = $resource->getOrganizationArray();
$organizationData = $organizationArray[0];

if ($organizationData['organizationID']) {

	$organization = new Organization(new NamedArguments(array('primaryKey' => $organizationData['organizationID'])));

	$orgIssues = $organization->getIssues();

	foreach ($orgIssues as $issue) {
		array_push($issues, $issue);
	}

	$downtimeObj = new Downtime();
	$downtimeTypeNames = $downtimeObj->getDowntimeTypesArray();

	$organizationContactsArray = $resource->organizationContactsArray($organizationData['organizationID']);
	$organizationResourcesArray = $resource->getSiblingResourcesArray($organizationData['organizationID']);

	$defaultStart = date("Y-m-d\TH:i");
	$defaultEnd = date("Y-m-d\TH:i", strtotime("+1 day"));

?>

<form id='newDowntimeForm'>
	<input type="hidden" name="sourceOrganizationID" value="<?php echo $organizationData['organizationID'];?>" />
	<input type="hidden" name="sourceResourceID" value="<?php echo $resourceID;?>" />
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
				echo "<option value=".$downtimeType["downtimeTypeID"].">".$downtimeType["name"]."</option>";
			}
?>
				</select>
			</td>
		</tr>
		<tr>
			<td><label>Link to open issue:</label></td>
			<td>
				<select class="issueID" name="issueID">
					<option value="">none</option>
<?php
			foreach ($issues as $issue) {
				echo "<option value=".$issue->issueID.">".$issue->subjectText."</option>";
			}
?>
				</select>
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
			Opening an issue requires a resource to be associated with an organization.
		</p>
		<input type="button" value="cancel" onclick="tb_remove();">';
}
?>


