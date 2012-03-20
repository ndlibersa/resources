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
<th>Related Product</th>
<th>ISSN/ISBN</th>
<th>Organizations</th>
<th>Purchasing Sites</th>
<th>Authorized Sites</th>
<th>Acquisition Type</th>
<th>Order Number</th>
<th>System Number</th>
<th>License Names</th>
<th>Administering Sites</th>
<th>Resource URL</th>
</tr>

<?php




foreach($resourceArray as $resource) {

	if ($resource['updateDate'] == "0000-00-00"){
		$updateDateFormatted="";
	}else{
		$updateDateFormatted=format_date($resource['updateDate']);
	}

	echo "<tr>";

	echo "<td style='vertical-align:top;'>" . $resource['resourceID'] . "</td>";
	echo "<td style='vertical-align:top;'>" . $resource['titleText'] . "</td>";
	echo "<td style='vertical-align:top;'>" . $resource['resourceType'] . "</td>";
	echo "<td style='vertical-align:top;'>" . $resource['resourceFormat'] . "</td>";
	echo "<td style='vertical-align:top;'>" . format_date($resource['createDate']) . "</td>";
	echo "<td style='vertical-align:top;'>" . $resource['createName'] . "</td>";
	echo "<td style='vertical-align:top;'>" . $updateDateFormatted . "</td>";
	echo "<td style='vertical-align:top;'>" . $resource['updateName'] . "</td>";
	echo "<td style='vertical-align:top;'>" . $resource['status'] . "</td>";
	echo "<td style='vertical-align:top;'>" . $resource['childResources'] . "<br />" . $resource['parentResources'] . "</td>";
	echo "<td style='vertical-align:top;'>" . $resource['isbnOrISSN'] . "</td>";
	echo "<td style='vertical-align:top;'>" . $resource['organizationNames'] . "</td>";
	echo "<td style='vertical-align:top;'>" . $resource['purchasingSites'] . "</td>";
	echo "<td style='vertical-align:top;'>" . $resource['authorizedSites'] . "</td>";
	echo "<td style='vertical-align:top;'>" . $resource['acquisitionType'] . "</td>";
	echo "<td style='vertical-align:top;'>" . $resource['orderNumber'] . "</td>";
	echo "<td style='vertical-align:top;'>" . $resource['systemNumber'] . "</td>";
	echo "<td style='vertical-align:top;'>" . $resource['licenseNames'] . "</td>";
	echo "<td style='vertical-align:top;'>" . $resource['administeringSites'] . "</td>";
	echo "<td style='vertical-align:top;'>" . $resource['resourceURL'] . "</td>";


	echo "</tr>";
}



?>

</table>

</body>
</html>
