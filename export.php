<?php

/*
**************************************************************************************************************************
** CORAL Resources Module v. 1.0
**
** Copyright (c) 2010 University of Notre Dame
**
** This file is part of CORAL.
**
** CORAL is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
**
** CORAL is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License along with CORAL.  If not, see <http://www.gnu.org/licenses/>.
**
**************************************************************************************************************************
*/


session_start();

include_once 'directory.php';

function export_value($value) {
  echo "<td>" . nl2br(htmlspecialchars($value)) . "</td>";
}

$queryDetails = Resource::getSearchDetails();
$whereAdd = $queryDetails["where"];
$searchDisplay = $queryDetails["display"];
$orderBy = $queryDetails["order"];

//get the results of the query into an array
$resourceObj = new Resource();
$resourceArray = array();
$resourceArray = $resourceObj->export($whereAdd, $orderBy);



$replace = array("/", "-");
$excelfile = "resources_export_" . str_replace( $replace, "_", format_date( date( 'Y-m-d' ) ) );

header("Pragma: public");
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename='" . $excelfile . "'");

?>

<html>
<head>
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="public">
</head>
<body>

<h2>Resource Record Export</h2><?php echo format_date( date( 'Y-m-d' ) ); ?><br />
<?php echo "<table><tr><td>" . implode("</td></tr><tr><td>", $searchDisplay) . "</td></tr></table>"; ?>
<table border='1'>
<tr>
<th>Record ID</th>
<th>Name</th>
<th>Type</th>
<th>Format</th>
<th>Date Created</th>
<th>User Created</th>
<th>Date Updated</th>
<th>User Updated</th>
<th>Status</th>
<th>ISSN/ISBN</th>
<th>Resource URL</th>
<th>Organizations</th>
<th>Description</th>
<th>Aliases</th>
<th>Parent Record</th>
<th>Child Record</th>
<th>Acquisition Type</th>
<th>Initial Cost</th>
<th>Order Number</th>
<th>System Number</th>
<th>Purchasing Sites</th>
<th>Subscription Start</th>
<th>Subscription End</th>
<th>Subscription Alert Enabled</th>
<th>License Names</th>
<th>License Status</th>
<th>Authorized Sites</th>
<th>Administering Sites</th>
<th>Authentication Type</th>
<th>Access Method</th>
<th>Storage Location</th>
<th>Simultaneous User Limit</th>
<th>Username</th>
<th>Password</th>
<th>Cataloging Type</th>
<th>Cataloging Status</th>
<th>Catalog Record Set Identifier</th>
<th>Catalog Record Source URL</th>
<th>Catalog Records Available</th>
<th>Catalog Records Loaded</th>
<th>OCLC Holdings Updated</th>
<th>Notes</th>
</tr>

<?php




foreach($resourceArray as $resource) {

	if ($resource['updateDate'] == "0000-00-00"){
		$updateDateFormatted="";
	}else{
		$updateDateFormatted=format_date($resource['updateDate']);
	}

	echo "<tr style='vertical-align:top;'>";

	export_value($resource['resourceID']);
	export_value($resource['titleText']);
	export_value($resource['resourceType']);
	export_value($resource['resourceFormat']);
	export_value(format_date($resource['createDate']));
	export_value($resource['createName']);
	export_value($updateDateFormatted);
	export_value($resource['updateName']);
	export_value($resource['status']);
	export_value($resource['isbnOrISSN']);
	export_value($resource['resourceURL']);
	export_value($resource['organizationNames']);
	export_value($resource['descriptionText']);
	export_value($resource['aliases']);
	export_value($resource['parentResources']);
	export_value($resource['childResources']);
	export_value($resource['acquisitionType']);
	export_value($resource['payments']);
	export_value($resource['orderNumber']);
	export_value($resource['systemNumber']);
	export_value($resource['purchasingSites']);
	export_value($resource['subscriptionStartDate']);
	export_value($resource['subscriptionEndDate']);
	export_value(($resource['subscriptionAlertEnabledInd'] ? 'Y' : 'N'));
	export_value($resource['licenseNames']);
	export_value($resource['licenseStatuses']);
	export_value($resource['authorizedSites']);
	export_value($resource['administeringSites']);
	export_value($resource['authenticationType']);
	export_value($resource['accessMethod']);
	export_value($resource['storageLocation']);
	export_value($resource['userLimit']);
	export_value($resource['authenticationUserName']);
	export_value($resource['authenticationPassword']);
	export_value($resource['catalogingType']);
	export_value($resource['catalogingStatus']);
	export_value($resource['recordSetIdentifier']);
	export_value($resource['bibSourceURL']);
	export_value($resource['numberRecordsAvailable']);
	export_value($resource['numberRecordsLoaded']);
	export_value(($resource['hasOclcHoldings'] ? 'Y' : 'N'));
	$notes_array = explode('CORAL_SPLIT', $resource['notes']);
	foreach ($notes_array as $note) {
	  export_value($note);
	}


	echo "</tr>";
}



?>

</table>

</body>
</html>
