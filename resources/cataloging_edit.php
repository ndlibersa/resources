<?php
include_once '../directory.php';
include_once '../user.php';

$resourceID = $_GET['resourceID'];
$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

//get all authentication types for output in drop down
$authenticationTypeArray = array();
$authenticationTypeObj = new AuthenticationType();
$authenticationTypeArray = $authenticationTypeObj->allAsArray();

//get all access methods for output in drop down
$accessMethodArray = array();
$accessMethodObj = new AccessMethod();
$accessMethodArray = $accessMethodObj->allAsArray();

//get all user limits for output in drop down
//overridden for better sort
$userLimitArray = array();
$userLimitObj = new UserLimit();
$userLimitArray = $userLimitObj->allAsArray();

//get all storage locations for output in drop down
$storageLocationArray = array();
$storageLocationObj = new StorageLocation();
$storageLocationArray = $storageLocationObj->allAsArray();

//get all administering sites for output in checkboxes
$administeringSiteArray = array();
$administeringSiteObj = new AdministeringSite();
$administeringSiteArray = $administeringSiteObj->allAsArray();


//get administering sites for this resource
$sanitizedInstance = array();
$instance = new AdministeringSite();
$resourceAdministeringSiteArray = array();
foreach ($resource->getResourceAdministeringSites() as $instance) {
$resourceAdministeringSiteArray[] = $instance->administeringSiteID;
}


//get all authorized sites for output in checkboxes
$authorizedSiteArray = array();
$authorizedSiteObj = new AuthorizedSite();
$authorizedSiteArray = $authorizedSiteObj->allAsArray();


//get authorized sites for this resource
$sanitizedInstance = array();
$instance = new AuthorizedSite();
$resourceAuthorizedSiteArray = array();
foreach ($resource->getResourceAuthorizedSites() as $instance) {
$resourceAuthorizedSiteArray[] = $instance->authorizedSiteID;
}





?>
<div id='div_accessForm'>
<form id='accessForm'>
<input type='hidden' name='editResourceID' id='editResourceID' value='<?php echo $resourceID; ?>'>

<div class='formTitle' style='width:617px; margin-bottom:5px;'><span class='headerText'>Edit Cataloging</span></div>

<span class='smallDarkRedText' id='span_errors'></span>

<table class='noBorder' style='width:610px;'>
<tr style='vertical-align:top;'>
<td style='vertical-align:top;' colspan='2'>


<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='accessHead'><b>Record Set</b></label>&nbsp;&nbsp;</span>

<table class='surroundBox' style='width:610px;'>
<tr>
<td>
	<table class='noBorder' style='width:570px; margin:15px 20px 10px 20px;'>
	<tr>
	<td>
		<table>
		<tr>
		<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='authenticationTypeID'>Identifier:</label></td>
		<td>
		  <input type="text" style='width:95px;' class='changeInput'>
		</td>
		</tr>

		<tr>
		<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='accessMethodID'>URL:</label></td>
		<td>
		  <input type="text" style='width:95px;' class='changeInput'>
		</td>
		</tr>

		<tr>
		<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='storageLocationID'>Records Loaded:</label></td>
		<td>
		  <input type="text" style='width:95px;' class='changeInput'>
		</td>
		</tr>


		</table>

	</td>
	<td>
		<table>
		<tr>
		<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='authenticationUserName'>OCLC Holdings:</label></td>
		<td><input type='checkbox' id='authenticationUserName' name='authenticationUserName' /></td>
		</tr>

		<tr>
		<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='authenticationPassword'>Rejected:</label></td>
		<td><input type='checkbox' id='authenticationUserName' name='authenticationUserName' /></td>
		</tr>

		<tr>
		<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='userLimitID'>Rejected Reason</label></td>
		<td>
		<select name='userLimitID' id='userLimitID' style='width:100px;' class='changeSelect' >
		<option value=''></option>
		</select>

		</td>
		</tr>
		</table>
	</td>
	</tr>
	</table>
</td>
</tr>
</table>

</td>
</tr>
</table>


<hr style='width:620px;margin:15px 0px 10px 0px;' />

<table class='noBorderTable' style='width:125px;'>
<tr>
	<td style='text-align:left'><input type='button' value='submit' name='submitAccessChanges' id ='submitAccessChanges'></td>
	<td style='text-align:right'><input type='button' value='cancel' onclick="kill(); tb_remove();"></td>
</tr>
</table>


<script type="text/javascript" src="js/forms/accessForm.js?random=<?php echo rand(); ?>"></script>