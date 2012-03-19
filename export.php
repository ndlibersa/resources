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


//array of where clauses
$whereAdd = array();
$searchDisplay = array();
$config = new Configuration();


//if name is passed in also search alias, organizations and organization aliases
if ($_SESSION['res_name']) {
	if ($config->settings->organizationsModule == 'Y'){
		$dbName = $config->settings->organizationsDatabaseName;

		$whereAdd[] = "((UPPER(R.titleText) LIKE UPPER('%" . str_replace("'","''",$_SESSION['res_name']) . "%')) OR (UPPER(A.shortName) LIKE UPPER('%" . str_replace("'","''",$_SESSION['res_name']) . "%')) OR (UPPER(O.name) LIKE UPPER('%" . str_replace("'","''",$_SESSION['res_name']) . "%')) OR (UPPER(OA.name) LIKE UPPER('%" . str_replace("'","''",$_SESSION['res_name']) . "%')))";

	}else{

		$whereAdd[] = "((UPPER(R.titleText) LIKE UPPER('%" . str_replace("'","''",$_SESSION['res_name']) . "%')) OR (UPPER(A.shortName) LIKE UPPER('%" . str_replace("'","''",$_SESSION['res_name']) . "%')) OR (UPPER(O.shortName) LIKE UPPER('%" . str_replace("'","''",$_SESSION['res_name']) . "%')))";

	}

	$searchDisplay[] = "Name contains: " . $_SESSION['res_name'];
}

//get where statements together (and escape single quotes)
if ($_SESSION['res_resourceID']){
	$whereAdd[] = "R.resourceID = '" . $_SESSION['res_resourceID'] . "'";
	$searchDisplay[] = "Resource ID: " . $_SESSION['res_resourceID'];
}

if ($_SESSION['res_resourceISBNOrISSN']){
	$whereAdd[] = "REPLACE(isbnOrISSN,'-','') = '" . str_replace("-","",$_SESSION['res_resourceISBNOrISSN']) . "'";
	$searchDisplay[] = "ISSN/ISBN: " . $_SESSION['res_resourceISBNOrISSN'];
}

if ($_SESSION['res_fund']){
	$whereAdd[] = "REPLACE(fundName,'-','') = '" . str_replace("-","",$_SESSION['res_fund']) . "'";
	$searchDisplay[] = "Fund: " . $_SESSION['res_fund'];
}

if ($_SESSION['res_statusID']){
	$whereAdd[] = "R.statusID = '" . $_SESSION['res_statusID'] . "'";
	$status = new Status(new NamedArguments(array('primaryKey' => $_SESSION['res_statusID'])));
	$searchDisplay[] = "Status: " . $status->shortName;
}

if ($_SESSION['res_resourceFormatID']){
	$whereAdd[] = "R.resourceFormatID = '" . $_SESSION['res_resourceFormatID'] . "'";
	$resourceFormat = new ResourceFormat(new NamedArguments(array('primaryKey' => $_SESSION['res_resourceFormatID'])));
	$searchDisplay[] = "Resource Format: " . $resourceFormat->shortName;
}

if ($_SESSION['res_resourceTypeID']){
	$whereAdd[] = "R.resourceTypeID = '" . $_SESSION['res_resourceTypeID'] . "'";
	$resourceType = new ResourceType(new NamedArguments(array('primaryKey' => $_SESSION['res_resourceTypeID'])));
	$searchDisplay[] = "Resource Type: " . $resourceType->shortName;
}

if ($_SESSION['res_acquisitionTypeID']){
	$whereAdd[] = "R.acquisitionTypeID = '" . $_SESSION['res_acquisitionTypeID'] . "'";
	$acquisitionType = new AcquisitionType(new NamedArguments(array('primaryKey' => $_SESSION['res_acquisitionTypeID'])));
	$searchDisplay[] = "Acquisition Type: </td><td>" . $acquisitionType->shortName;
}


if ($_SESSION['res_creatorLoginID']){
	$whereAdd[] = "R.createLoginID = '" . $_SESSION['res_creatorLoginID'] . "'";
	$createUser = new User(new NamedArguments(array('primaryKey' => $_SESSION['res_creatorLoginID'])));

	if ($createUser->firstName){
		$name = $createUser->lastName . ", " . $createUser->firstName;
	}else{
		$name = $createUser->loginID;
	}

	$searchDisplay[] = "Creator: </td><td>" . $name;
}

if ($_POST['creatorLoginID']) $whereAdd[] = "R.createLoginID = '" . $_POST['creatorLoginID'] . "'";


if ($_SESSION['res_noteTypeID']){
	$whereAdd[] = "RN.noteTypeID = '" . $_SESSION['res_noteTypeID'] . "'";
	$noteType = new NoteType(new NamedArguments(array('primaryKey' => $_SESSION['res_noteTypeID'])));
	$searchDisplay[] = "Note Type: " . $noteType->shortName;
}

if ($_SESSION['res_resourceNote']){
	$whereAdd[] = "UPPER(RN.noteText) LIKE UPPER('%" . str_replace("'","''",$_SESSION['res_resourceNote']) . "%')";
	$searchDisplay[] = "Note contains: " . $_SESSION['res_resourceNote'];
}

if ($_SESSION['res_startWith']){
	$whereAdd[] = "TRIM(LEADING 'THE ' FROM UPPER(R.titleText)) LIKE UPPER('" . $_SESSION['res_startWith'] . "%')";
	$searchDisplay[] = "Starts with: " . $_SESSION['res_startWith'];
}

if ($_SESSION['res_purchaseSiteID']){
	$whereAdd[] = "RPSL.purchaseSiteID = '" . $_SESSION['res_purchaseSiteID'] . "'";
	$purchaseSite = new PurchaseSite(new NamedArguments(array('primaryKey' => $_SESSION['res_purchaseSiteID'])));
	$searchDisplay[] = "Purchase Site: " . $purchaseSite->shortName;
}

if ($_SESSION['res_authorizedSiteID']){
	$whereAdd[] = "RAUSL.authorizedSiteID = '" . $_SESSION['res_authorizedSiteID'] . "'";
	$authorizedSite = new AuthorizedSite(new NamedArguments(array('primaryKey' => $_SESSION['res_authorizedSiteID'])));
	$searchDisplay[] = "Authorized Site: " . $authorizedSite->shortName;
}

if ($_SESSION['res_administeringSiteID']){
	$whereAdd[] = "RADSL.administeringSiteID = '" . $_SESSION['res_administeringSiteID'] . "'";
	$administeringSite = new AdministeringSite(new NamedArguments(array('primaryKey' => $_SESSION['res_administeringSiteID'])));
	$searchDisplay[] = "Administering Site: " . $administeringSite->shortName;
}

if ($_SESSION['res_catalogingStatus'] == 'none') {
  $whereAdd[] = "(R.catalogingStatus = '' OR R.catalogingStatus IS NULL)";
  $searchDisplay[] = "Cataloging Status: none";
} else if ($_SESSION['res_catalogingStatus']) {
  $whereAdd[] = "R.catalogingStatus = '" . mysql_real_escape_string($_SESSION['res_catalogingStatus']) . "'";
  $searchDisplay[] = "Cataloging Status: " . $_SESSION['res_catalogingStatus'];
}

if ($_SESSION['res_stepName']) {
  $status = new Status();
  $completedStatusID = $status->getIDFromName('complete');
  $whereAdd[] = "(R.statusID != $completedStatusID AND RS.stepName = '" . mysql_real_escape_string($_SESSION['res_stepName']) . "' AND RS.stepStartDate IS NOT NULL AND RS.stepEndDate IS NULL)";
  $searchDisplay[] = "Routing Step: " . $_SESSION['res_stepName'];
}



$orderBy = $_SESSION['res_orderBy'];



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
