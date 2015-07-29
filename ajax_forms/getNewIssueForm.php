<?php

$resourceID = $_GET["resourceID"];

$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID))); 

$organizationArray = $resource->getOrganizationArray();
$organizationID = $organizationArray[0]["organizationID"];

if($organizationID) {
	$organizationContactsArray = $resource->organizationContactsArray($organizationID);
}
	

/*
if (organizationID) {
	$organization = new Organization
	get organization contacts
	get organization resources
}

if (resourceID) {
	use to highlight in list of applies to
}

-on organization change - 

*/
?>

<form id='newIssueForm'>

	<table class="thickboxTable" style="width:98%;background-image:url('images/title.gif');background-repeat:no-repeat;">
		<tr>
			<td colspan="2">
				<h1> Report New Problem</h1>
			</td>
		</tr>
		<tr>
			<td><label>Organization:</label></td>
			<td>
				<p><?php echo $organization->shortName; ?></p>
				<span id='span_error_organizationId' class='smallDarkRedText'>
			</td>
		</tr>
		<tr>
			<td><label>Contact:</label></td>
			<td>
				<select type='text' id='contactName' name='contactName'>
<?php 

	foreach ($organizationContactsArray as $contact) {
		echo "<option>{$contact['name']}</option>";
	}

?>
				</select>
				<span id='span_error_contactName' class='smallDarkRedText'>
				<a href="">add additional contact:</a>
			</td>
		</tr>
		<tr>
			<td><label>CC myself:</label></td>
			<td>
				<input type='checkbox' id='primaryContact' name='primaryContact' class='changeInput' />
				<span id='span_error_primaryContact' class='smallDarkRedText'>
			</td>
		</tr>
		<tr>
			<td><label>CC:</label></td>
			<td>
				<select id='contactIDs' name='contactIDs' value='' class='changeInput' />
				<span id='span_error_contactIDs' class='smallDarkRedText'>
				<a href="">add additional cc:</a>
			</td>
		</tr>
		<tr>
			<td><label>Subject:</label></td>
			<td>
				<input type='text' id='subjectText' name='subjectText' value='' class='changeInput' />
				<span id='span_error_subjectText' class='smallDarkRedText'>
			</td>
		</tr>
		<tr>
			<td><label>Body:</label></td>
			<td>
				<textarea id='bodyText' name='bodyText' value='' />
				<span id='span_error_bodyText' class='smallDarkRedText'>
			</td>
		</tr>
		<tr>
			<td><label>Applies to:</label></td>
			<td>
				<select multiple id="resourceIDs" name="resourceIDs">
				</select>
				<span id='span_error_resourceIDs' class='smallDarkRedText'>
			</td>
		</tr>
	</table>

	<p> Send me a reminder in 
		<select></select> days 
	</p>

	<table class='noBorderTable' style='width:125px;'>
		<tr>
			<td style='text-align:left'><input type='button' value='submit' name='submitNewIssue' id='submitNewIssue'></td>
			<td style='text-align:right'><input type='button' value='cancel' onclick="tb_remove();"></td>
		</tr>
	</table>

</form>


