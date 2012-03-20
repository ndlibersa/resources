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
<th>Username / Password</th>
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

	echo "<td>" . $resource['resourceID'] . "</td>";
	echo "<td>" . $resource['titleText'] . "</td>";
	echo "<td>" . $resource['resourceType'] . "</td>";
	echo "<td>" . $resource['resourceFormat'] . "</td>";
	echo "<td>" . format_date($resource['createDate']) . "</td>";
	echo "<td>" . $resource['createName'] . "</td>";
	echo "<td>" . $updateDateFormatted . "</td>";
	echo "<td>" . $resource['updateName'] . "</td>";
	echo "<td>" . $resource['status'] . "</td>";
	echo "<td>" . $resource['isbnOrISSN'] . "</td>";
	echo "<td>" . $resource['resourceURL'] . "</td>";
	echo "<td>" . $resource['organizationNames'] . "</td>";
	echo "<td>" . $resource['descriptionText'] . "</td>";
	echo "<td>" . $resource['aliases'] . "</td>";
	echo "<td>" . $resource['parentResources'] . "</td>";
	echo "<td>" . $resource['childResources'] . "</td>";
	echo "<td>" . $resource['acquisitionType'] . "</td>";
	echo "<td>" . $resource['payments'] . "</td>";
	echo "<td>" . $resource['orderNumber'] . "</td>";
	echo "<td>" . $resource['systemNumber'] . "</td>";
	echo "<td>" . $resource['purchasingSites'] . "</td>";
	echo "<td>" . $resource[''] . "</td>";
	echo "<td>" . $resource[''] . "</td>";
	echo "<td>" . $resource[''] . "</td>";
	echo "<td>" . $resource['licenseNames'] . "</td>";
	echo "<td>" . $resource[''] . "</td>";
	echo "<td>" . $resource['authorizedSites'] . "</td>";
	echo "<td>" . $resource['administeringSites'] . "</td>";
	echo "<td>" . $resource[''] . "</td>";
	echo "<td>" . $resource[''] . "</td>";
	echo "<td>" . $resource[''] . "</td>";
	echo "<td>" . $resource[''] . "</td>";
	echo "<td>" . $resource[''] . "</td>";
	echo "<td>" . $resource[''] . "</td>";
	echo "<td>" . $resource[''] . "</td>";
	echo "<td>" . $resource[''] . "</td>";
	echo "<td>" . $resource[''] . "</td>";
	echo "<td>" . $resource[''] . "</td>";
	echo "<td>" . $resource[''] . "</td>";
	echo "<td>" . $resource[''] . "</td>";
	echo "<td>" . $resource[''] . "</td>";


	echo "</tr>";
}



?>

</table>

</body>
</html>
