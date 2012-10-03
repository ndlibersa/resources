<?php


/*
**************************************************************************************************************************
** CORAL Resources Module v. 1.2
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


include_once 'directory.php';
include_once 'user.php';


switch ($_GET['action']) {



    case 'submitResourceNote':
		//if this is an existing resourceNote
		$resourceNoteID=$_POST['resourceNoteID'];

		if ($resourceNoteID){
			$resourceNote = new ResourceNote(new NamedArguments(array('primaryKey' => $resourceNoteID)));
		}else{
			//set up new resourceNote
			$resourceNote = new ResourceNote();
			$resourceNote->resourceNoteID = '';
		}

		$resourceNote->updateLoginID 		= $loginID;
		$resourceNote->updateDate			= date( 'Y-m-d H:i:s' );
		$resourceNote->noteTypeID 			= $_POST['noteTypeID'];
		$resourceNote->tabName 				= $_POST['tabName'];
		$resourceNote->resourceID 			= $_POST['resourceID'];
		$resourceNote->noteText 			= $_POST['noteText'];

		try {
			$resourceNote->save();
		} catch (Exception $e) {
			echo $e->getMessage();
		}

        break;


	case 'deleteResourceNote':
		$resourceNoteID = $_GET['resourceNoteID'];
		$resourceNote = new ResourceNote(new NamedArguments(array('primaryKey' => $resourceNoteID)));

		try {
			$resourceNote->delete();
			echo "Note successfully deleted.";
		} catch (Exception $e) {
			echo $e->getMessage();
		}

		break;

    case 'submitNewResource':

		$resourceID = $_POST['resourceID'];

		if ($resourceID){
			//get this resource
			$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));
		}else{
			//set up new resource
			$resource = new Resource();
			$resource->createLoginID 		= $loginID;
			$resource->createDate			= date( 'Y-m-d' );
			$resource->updateLoginID 		= '';
			$resource->updateDate			= '';

		}


		//determine status id
		$status = new Status();
		$statusID = $status->getIDFromName($_POST['resourceStatus']);



		$resource->resourceTypeID 		= $_POST['resourceTypeID'];
		$resource->resourceFormatID 	= $_POST['resourceFormatID'];
		$resource->acquisitionTypeID 	= $_POST['acquisitionTypeID'];

		$resource->titleText 			= $_POST['titleText'];
		$resource->descriptionText 		= $_POST['descriptionText'];
		$resource->isbnOrISSN	 		= '';
		$resource->statusID		 		= $statusID;
		$resource->orderNumber	 		= '';
		$resource->systemNumber 		= '';
		$resource->userLimitID	 		= '';
		$resource->authenticationUserName 	= '';
		$resource->authenticationPassword 	= '';
		$resource->storageLocationID		= '';
		$resource->registeredIPAddresses 	= '';
		$resource->providerText			 	= $_POST['providerText'];
		$resource->coverageText 			= '';	
		
		if ($_POST['resourceURL'] != 'http://'){
			$resource->resourceURL = $_POST['resourceURL'];
		}else{
			$resource->resourceURL = '';
		}

		if ($_POST['resourceAltURL'] != 'http://'){
			$resource->resourceAltURL = $_POST['resourceAltURL'];
		}else{
			$resource->resourceAltURL = '';
		}
		
		try {
			$resource->save();
			echo $resource->primaryKey;
			$resourceID=$resource->primaryKey;

			//get the provider ID in case we insert what was entered in the provider text box as an organization link
			$organizationRole = new OrganizationRole();
			$organizationRoleID = $organizationRole->getProviderID();

			//add notes
			if (($_POST['noteText']) || (($_POST['providerText']) && (!$_POST['organizationID']))){
				//first, remove existing notes in case this was saved before
				$resource->removeResourceNotes();

				//this is just to figure out what the creator entered note type ID is
				$noteType = new NoteType();

				$resourceNote = new ResourceNote();
				$resourceNote->resourceNoteID 	= '';
				$resourceNote->updateLoginID 	= $loginID;
				$resourceNote->updateDate		= date( 'Y-m-d' );
				$resourceNote->noteTypeID 		= $noteType->getInitialNoteTypeID();
				$resourceNote->tabName 			= 'Product';
				$resourceNote->resourceID 		= $resourceID;

				//only insert provider as note if it's been submitted
				if (($_POST['providerText']) && (!$_POST['organizationID']) && ($_POST['resourceStatus'] == 'progress')){
					$resourceNote->noteText 	= "Provider:  " . $_POST['providerText'] . "\n\n" . $_POST['noteText'];
				}else{
					$resourceNote->noteText 	= $_POST['noteText'];
				}

				$resourceNote->save();
			}


			//first remove the organizations if this is a saved request
			$resource->removeResourceOrganizations();
			if (($_POST['organizationID']) && ($organizationRoleID)){

				$resourceOrganizationLink = new ResourceOrganizationLink();
				$resourceOrganizationLink->resourceID = $resourceID;
				$resourceOrganizationLink->organizationID = $_POST['organizationID'];
				$resourceOrganizationLink->organizationRoleID = $organizationRoleID;

				$resourceOrganizationLink->save();
			}

			$orderTypeArray = array();
			$orderTypeArray = explode(':::',$_POST['orderTypes']);
			$fundNameArray = array();
			$fundNameArray = explode(':::',$_POST['fundNames']);
			$paymentAmountArray = array();
			$paymentAmountArray = explode(':::',$_POST['paymentAmounts']);
			$currencyCodeArray = array();
			$currencyCodeArray = explode(':::',$_POST['currencyCodes']);

			//first remove all payment records, then we'll add them back
			$resource->removeResourcePayments();

			foreach ($orderTypeArray as $key => $value){
				if (($value) && (($paymentAmountArray[$key]) || ($fundNameArray[$key]))){
					$resourcePayment = new ResourcePayment();
					$resourcePayment->resourceID = $resourceID;
					$resourcePayment->orderTypeID = $value;
					$resourcePayment->fundName = $fundNameArray[$key];
					$resourcePayment->currencyCode = $currencyCodeArray[$key];
					$resourcePayment->paymentAmount = cost_to_integer($paymentAmountArray[$key]);

					try {
						$resourcePayment->save();
					} catch (Exception $e) {
						echo $e->getMessage();
					}
				}
			}



			//next if the resource was submitted, enter into workflow
			if ($statusID == $status->getIDFromName('progress')){
				$resource->enterNewWorkflow();
			}



		} catch (Exception $e) {
			echo $e->getMessage();
		}

        break;



    case 'submitProductUpdate':
		$resourceID = $_POST['resourceID'];

		//get this resource
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		$resource->updateLoginID 		= $loginID;
		$resource->updateDate			= date( 'Y-m-d H:i:s' );

		$resource->titleText 			= $_POST['titleText'];
		$resource->descriptionText 		= $_POST['descriptionText'];
		$resource->isbnOrISSN 			= $_POST['isbnOrISSN'];
		$resource->resourceFormatID 	= $_POST['resourceFormatID'];
		$resource->resourceTypeID 		= $_POST['resourceTypeID'];
		$resource->resourceURL 			= $_POST['resourceURL'];
		$resource->resourceAltURL 		= $_POST['resourceAltURL'];

		//to determine status id
		$status = new Status();

		if (((!$resource->archiveDate) || ($resource->archiveDate == '0000-00-00')) && ($_POST['archiveInd'] == "1")){
			$resource->archiveDate = date( 'Y-m-d' );
			$resource->archiveLoginID = $loginID;
			$resource->statusID = $status->getIDFromName('archive');
		}else if ($_POST['archiveInd'] == "0"){
			//if archive date is currently set and being removed, mark status as complete
			if (($resource->archiveDate != '') && ($resource->archiveDate != '0000-00-00')){
				$resource->statusID = $status->getIDFromName('complete');
			}
			$resource->archiveDate = '';
			$resource->archiveLoginID = '';
		}



		try {
			$resource->save();

		} catch (Exception $e) {
			echo $e->getMessage();
		}


		//update resource relationship (currently code only allows parent)
		//first remove the existing relationship then add it back
		$resource->removeParentResources();

		if (($_POST['parentResourceName']) && ($_POST['parentResourceID']) && ($_POST['parentResourceID'] != $resourceID)){
			$resourceRelationship = new ResourceRelationship();
			$resourceRelationship->resourceID = $resourceID;
			$resourceRelationship->relatedResourceID = $_POST['parentResourceID'];
			$resourceRelationship->relationshipTypeID = '1';  //hardcoded because we're only allowing parent relationships

			try {
				$resourceRelationship->save();

			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}

		//next, delete and then re-insert the aliases
		$alias = new Alias();
		foreach ($resource->getAliases() as $alias) {
			$alias->delete();
		}

		$aliasTypeArray = array();
		$aliasTypeArray = explode(':::', $_POST['aliasTypes']);
		$aliasNameArray = array();
		$aliasNameArray = explode(':::', $_POST['aliasNames']);


		foreach ($aliasTypeArray as $key => $value){
			if (($value) && ($aliasNameArray[$key])){
				$alias = new Alias();
				$alias->resourceID = $resourceID;
				$alias->aliasTypeID = $value;
				$alias->shortName = $aliasNameArray[$key];

				$alias->save();


			}
		}



		//now delete and then re-insert the organizations
		$resource->removeResourceOrganizations();

		$organizationRoleArray = array();
		$organizationRoleArray = explode(':::', $_POST['organizationRoles']);
		$organizationArray = array();
		$organizationArray = explode(':::', $_POST['organizations']);


		foreach ($organizationRoleArray as $key => $value){
			if (($value) && ($organizationArray[$key])){
				$resourceOrganizationLink = new ResourceOrganizationLink();
				$resourceOrganizationLink->resourceID = $resourceID;
				$resourceOrganizationLink->organizationRoleID = $value;
				$resourceOrganizationLink->organizationID = $organizationArray[$key];

				$resourceOrganizationLink->save();
			}
		}

        break;



	case 'deleteResource':
		$resourceID = $_GET['resourceID'];
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		try {
			$resource->removeResource();
			echo "Resource successfully deleted.";
		} catch (Exception $e) {
			echo $e->getMessage();
		}

		break;




	case 'submitAcquisitions':
		$resourceID = $_POST['resourceID'];
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		//first set subscription start Date for proper saving
		if ((isset($_POST['subscriptionStartDate'])) && ($_POST['subscriptionStartDate'] != '')){
			$resource->subscriptionStartDate = date("Y-m-d", strtotime($_POST['subscriptionStartDate']));
		}else{
			$resource->subscriptionStartDate= 'null';
		}

		//first set subscription start Date for proper saving
		if ((isset($_POST['subscriptionEndDate'])) && ($_POST['subscriptionEndDate'] != '')){
			$resource->subscriptionEndDate = date("Y-m-d", strtotime($_POST['subscriptionEndDate']));
		}else{
			$resource->subscriptionEndDate= 'null';
		}

		$resource->acquisitionTypeID 				= $_POST['acquisitionTypeID'];
		$resource->orderNumber 						= $_POST['orderNumber'];
		$resource->systemNumber 					= $_POST['systemNumber'];
		$resource->subscriptionAlertEnabledInd 		= $_POST['subscriptionAlertEnabledInd'];



		try {
			$resource->save();

			//first remove all administering sites, then we'll add them back
			$resource->removePurchaseSites();

			foreach (explode(':::',$_POST['purchaseSites']) as $key => $value){
				if ($value){
					$resourcePurchaseSiteLink = new ResourcePurchaseSiteLink();
					$resourcePurchaseSiteLink->resourceID = $resourceID;
					$resourcePurchaseSiteLink->purchaseSiteID = $value;
					try {
						$resourcePurchaseSiteLink->save();
					} catch (Exception $e) {
						echo $e->getMessage();
					}
				}
			}

			//first remove all payment records, then we'll add them back
			$resource->removeResourcePayments();

			$orderTypeArray = array();
			$orderTypeArray = explode(':::',$_POST['orderTypes']);
			$fundNameArray = array();
			$fundNameArray = explode(':::',$_POST['fundNames']);
			$paymentAmountArray = array();
			$paymentAmountArray = explode(':::',$_POST['paymentAmounts']);
			$currencyCodeArray = array();
			$currencyCodeArray = explode(':::',$_POST['currencyCodes']);

			foreach ($orderTypeArray as $key => $value){
				if (($value) && (($paymentAmountArray[$key]) || ($fundNameArray[$key]))){
					$resourcePayment = new ResourcePayment();
					$resourcePayment->resourceID = $resourceID;
					$resourcePayment->orderTypeID = $value;
					$resourcePayment->fundName = $fundNameArray[$key];
					$resourcePayment->currencyCode = $currencyCodeArray[$key];
					$resourcePayment->paymentAmount = cost_to_integer($paymentAmountArray[$key]);

					try {
						$resourcePayment->save();
					} catch (Exception $e) {
						echo $e->getMessage();
					}
				}
			}




		} catch (Exception $e) {
			echo $e->getMessage();
		}


		break;




	case 'submitLicenseUpdate':
		$resourceID = $_POST['resourceID'];
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		if (($_POST['licenseStatusID']) && ($_POST['licenseStatusID'] != $resource->getCurrentResourceLicenseStatus())){
			$resourceLicenseStatus = new ResourceLicenseStatus();
			$resourceLicenseStatus->resourceID 					= $resourceID;
			$resourceLicenseStatus->licenseStatusID 			= $_POST['licenseStatusID'];
			$resourceLicenseStatus->licenseStatusChangeLoginID 	= $loginID;
			$resourceLicenseStatus->licenseStatusChangeDate 	= date( 'Y-m-d H:i:s' );
			$resourceLicenseStatus->save();

		}

		try {


			//first remove all license links, then we'll add them back
			$resource->removeResourceLicenses();

			foreach (explode(':::',$_POST['licenseList']) as $key => $value){
				if ($value){
					$resourceLicenseLink = new ResourceLicenseLink();
					$resourceLicenseLink->resourceID = $resourceID;
					$resourceLicenseLink->licenseID = $value;
					try {
						$resourceLicenseLink->save();
					} catch (Exception $e) {
						echo $e->getMessage();
					}
				}
			}



		} catch (Exception $e) {
			echo $e->getMessage();
		}


		break;


	case 'submitAccess':
		$resourceID = $_POST['resourceID'];
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		$resource->authenticationTypeID 	= $_POST['authenticationTypeID'];
		$resource->accessMethodID 			= $_POST['accessMethodID'];
		$resource->coverageText 			= $_POST['coverageText'];			
		$resource->authenticationUserName 	= $_POST['authenticationUserName'];
		$resource->authenticationPassword	= $_POST['authenticationPassword'];
		$resource->storageLocationID		= $_POST['storageLocationID'];
		$resource->userLimitID				= $_POST['userLimitID'];



		try {
			$resource->save();

			//first remove all administering sites, then we'll add them back
			$resource->removeAdministeringSites();

			foreach (explode(':::',$_POST['administeringSites']) as $key => $value){
				if ($value){
					$resourceAdministeringSiteLink = new ResourceAdministeringSiteLink();
					$resourceAdministeringSiteLink->resourceID = $resourceID;
					$resourceAdministeringSiteLink->administeringSiteID = $value;
					try {
						$resourceAdministeringSiteLink->save();
					} catch (Exception $e) {
						echo $e->getMessage();
					}
				}
			}



			//first remove all authorized sites, then we'll add them back
			$resource->removeAuthorizedSites();

			foreach (explode(':::',$_POST['authorizedSites']) as $key => $value){
				if ($value){
					$resourceAuthorizedSiteLink = new ResourceAuthorizedSiteLink();
					$resourceAuthorizedSiteLink->resourceID = $resourceID;
					$resourceAuthorizedSiteLink->authorizedSiteID = $value;
					try {
						$resourceAuthorizedSiteLink->save();
					} catch (Exception $e) {
						echo $e->getMessage();
					}
				}
			}





		} catch (Exception $e) {
			echo $e->getMessage();
		}


		break;



    case 'submitContact':
		//if this is an existing contact
		if (isset($_POST['contactID'])) $contactID=$_POST['contactID']; else $contactID='';

		if ($contactID){
			$contact = new Contact(new NamedArguments(array('primaryKey' => $contactID)));
		}else{
			//set up new contact
			$contact = new Contact();
			$contact->contactID	= '';
		}

		$contact->lastUpdateDate		= date( 'Y-m-d H:i:s' );
		$contact->resourceID	 		= $_POST['resourceID'];
		$contact->name 					= $_POST['name'];
		$contact->title 				= $_POST['title'];
		$contact->addressText			= $_POST['addressText'];
		$contact->phoneNumber			= $_POST['phoneNumber'];
		$contact->altPhoneNumber		= $_POST['altPhoneNumber'];
		$contact->faxNumber				= $_POST['faxNumber'];
		$contact->emailAddress			= $_POST['emailAddress'];
		$contact->noteText				= $_POST['noteText'];

		if (((!$contact->archiveDate) || ($contact->archiveDate == '0000-00-00')) && ($_POST['archiveInd'] == "1")){
			$contact->archiveDate = date( 'Y-m-d H:i:s' );
		}else if ($_POST['archiveInd'] == "0"){
			$contact->archiveDate = '';
		}

		try {
			$contact->save();

			if (!$contactID){
				$contactID=$contact->primaryKey;
			}

			//first remove all orgs, then we'll add them back
			$contact->removeContactRoles();

			foreach (explode(',', $_POST['contactRoles']) as $id){
				if ($id){
					$contactRoleProfile = new ContactRoleProfile();
					$contactRoleProfile->contactID = $contactID;
					$contactRoleProfile->contactRoleID = $id;
					$contactRoleProfile->save();
				}
			}


		} catch (Exception $e) {
			echo $e->getMessage();
		}

        break;





    case 'submitExternalLogin':
		//if this is an existing contact
		$externalLoginID=$_POST['externalLoginID'];

		if ($externalLoginID){
			$externalLogin = new ExternalLogin(new NamedArguments(array('primaryKey' => $externalLoginID)));
		}else{
			//set up new external login
			$externalLogin = new ExternalLogin();
			$externalLogin->externalLoginID =  '';
		}

		$externalLogin->updateDate				= date( 'Y-m-d H:i:s' );
		$externalLogin->externalLoginTypeID 	= $_POST['externalLoginTypeID'];
		$externalLogin->resourceID 				= $_POST['resourceID'];
		$externalLogin->loginURL				= $_POST['loginURL'];
		$externalLogin->username				= $_POST['username'];
		$externalLogin->emailAddress			= $_POST['emailAddress'];
		$externalLogin->password				= $_POST['password'];
		$externalLogin->noteText				= $_POST['noteText'];

		try {
			$externalLogin->save();
		} catch (Exception $e) {
			echo $e->getMessage();
		}

        break;



    case 'submitAttachment':
		//if this is an existing attachment
		$attachmentID=$_POST['attachmentID'];

		if ($attachmentID){
			$attachment = new Attachment(new NamedArguments(array('primaryKey' => $attachmentID)));
		}else{
			//set up new attachment
			$attachment = new Attachment();
			$attachment->attachmentID =  '';
		}

		$attachment->resourceID 			= $_POST['resourceID'];
		$attachment->attachmentTypeID 		= $_POST['attachmentTypeID'];
		$attachment->shortName				= $_POST['shortName'];
		$attachment->descriptionText		= $_POST['descriptionText'];
		$attachment->attachmentURL			= $_POST['uploadDocument'];
		$attachment->shortName				= $_POST['shortName'];
		$attachment->descriptionText		= $_POST['descriptionText'];

		try {
			$attachment->save();
		} catch (Exception $e) {
			echo $e->getMessage();
		}

        break;





	//verify that the new attachment name doesn't have bad characters and the name isn't already being used
    case 'checkUploadAttachment':
		$uploadAttachment = $_GET['uploadAttachment'];
		$attachment = new Attachment();

		$exists = 0;
    
    if (!is_writable("attachments")) {
      echo 3;
      break;
    }
    
    
		//first check that it doesn't have any offending characters
		if ((strpos($uploadAttachment,"'") > 0) || (strpos($uploadAttachment,'"') > 0) || (strpos($uploadAttachment,"&") > 0) || (strpos($uploadAttachment,"<") > 0) || (strpos($uploadAttachment,">") > 0)){
			echo "2";
		}else{
			//loop through each existing attachment to verify this name isn't already being used
			foreach ($attachment->allAsArray() as $attachmentTestArray) {
				if (strtoupper($attachmentTestArray['attachmentURL']) == strtoupper($uploadAttachment)) {
					$exists = 1;
				}
			}

			echo $exists;
		}

		break;



	//performs attachment upload
    case 'uploadAttachment':
		$attachmentName = basename($_FILES['myfile']['name']);

		$attachment = new Attachment();

		$exists = 0;

		//verify the name isn't already being used
		foreach ($attachment->allAsArray() as $attachmentTestArray) {
			if (strtoupper($attachmentTestArray['attachmentURL']) == strtoupper($attachmentName)) {
				$exists++;
			}
		}

		//if match was found
		if ($exists == 0){

			$target_path = "attachments/" . basename($_FILES['myfile']['name']);

			//note, echos are meant for debugging only - only file name gets sent back
			if(move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) {
				//set to web rwx, everyone else rw
				//this way we can edit the attachment directly on the server
				chmod ($target_path, 0766);
				echo "success uploading!";
			} else {
			  header('HTTP/1.1 500 Internal Server Error');
			  echo "<div id=\"error\">There was a problem saving your file to $target_path.  Please ensure your attachments directory is writable.</div>";
			}

		}

		break;


	case 'deleteInstance':

 		$className = $_GET['class'];
 		$deleteID = $_GET['id'];

		//since we're using MyISAM which doesn't support FKs, must verify that there are no records of children or they could disappear
		$instance = new $className(new NamedArguments(array('primaryKey' => $deleteID)));
		$numberOfChildren = $instance->getNumberOfChildren();

		if ($numberOfChildren > 0){
			//print out a friendly message...
			echo "Unable to delete  - this " . strtolower(preg_replace("/[A-Z]/", " \\0" , lcfirst($className))) . " is in use.  Please make sure no resources are set up with this information.";
		}else{
			try {
				$instance->delete();
			} catch (Exception $e) {
				//print out a friendly message...
				echo "Unable to delete.  Please make sure no resources are set up with this information.";
			}
		}


		break;



     case 'updateData':
 		$className = $_POST['className'];
 		$updateID = $_POST['updateID'];
 		$shortName = $_POST['shortName'];

		if ($updateID != ''){
			$instance = new $className(new NamedArguments(array('primaryKey' => $updateID)));
		}else{
			$instance = new $className();
		}

		$instance->shortName = $shortName;

		try {
			$instance->save();
		} catch (Exception $e) {
			echo $e->getMessage();
		}

 		break;


	
		
     case 'updateCurrency':
 		$editCurrencyCode = $_POST['editCurrencyCode'];
 		$currencyCode = $_POST['currencyCode'];
 		$shortName = $_POST['shortName'];

		if ($editCurrencyCode != ''){
			$instance = new Currency(new NamedArguments(array('primaryKey' => $editCurrencyCode)));
		}else{
			$instance = new Currency();
		}


		$instance->currencyCode = $currencyCode;
		$instance->shortName = $shortName;

		try {
			$instance->save();
		} catch (Exception $e) {
			echo $e->getMessage();
		}

 		break;




     case 'updateAdminAlertEmail':
 		$alertEmailAddressID = $_POST['alertEmailAddressID'];
 		$emailAddress = $_POST['emailAddress'];

		if ($alertEmailAddressID != ''){
			$instance = new AlertEmailAddress(new NamedArguments(array('primaryKey' => $alertEmailAddressID)));
		}else{
			$instance = new AlertEmailAddress();
		}

		$instance->emailAddress = $emailAddress;

		try {
			$instance->save();
		} catch (Exception $e) {
			echo $e->getMessage();
		}

 		break;




     case 'updateAdminAlertDays':
 		$alertDaysInAdvanceID = $_POST['alertDaysInAdvanceID'];
 		$daysInAdvanceNumber = $_POST['daysInAdvanceNumber'];

		if ($alertDaysInAdvanceID != ''){
			$instance = new AlertDaysInAdvance(new NamedArguments(array('primaryKey' => $alertDaysInAdvanceID)));
		}else{
			$instance = new AlertDaysInAdvance();
		}

		$instance->daysInAdvanceNumber = $daysInAdvanceNumber;

		try {
			$instance->save();
		} catch (Exception $e) {
			echo $e->getMessage();
		}

 		break;





     case 'updateUserData':

 		$orgloginID = $_POST['orgloginID'];

		if ($orgloginID){
			$user = new User(new NamedArguments(array('primaryKey' => $orgloginID)));
		}else{
			$user = new User();
			$user->loginID=$_POST['loginID'];
		}


		$user->firstName=$_POST['firstName'];
		$user->lastName=$_POST['lastName'];
		$user->privilegeID=$_POST['privilegeID'];
		$user->emailAddress=$_POST['emailAddress'];
		if ($_POST['accountTabIndicator'] == '1'){
			$user->accountTabIndicator='1';
		}else{
			$user->accountTabIndicator='0';
		}


		try {
			$user->save();
			echo "User successfully saved.";
		} catch (Exception $e) {
			echo $e->getMessage();
		}

 		break;

	//used for the parent resource list in the edit resource box
    case 'getResourceList':

		if (isset($_GET['searchMode'])) $searchMode = $_GET['searchMode']; else $searchMode='';
		if (isset($_GET['limit'])) $v = $_GET['limit']; else $limit='';

		$q = $_GET['q'];
		$q = str_replace(" ", "+",$q);
		$q = str_replace("&", "%",$q);

		$resource = new Resource();
		$resourceArray = $resource->resourceAutocomplete($q);

		echo implode("\n", $resourceArray);

		break;


    case 'getOrganizationList':

		if (isset($_GET['searchMode'])) $searchMode = $_GET['searchMode']; else $searchMode='';
		if (isset($_GET['limit'])) $v = $_GET['limit']; else $limit='';

		$q = $_GET['q'];
		$q = str_replace(" ", "+",$q);
		$q = str_replace("&", "%",$q);

		$resource = new Resource();
		$resourceArray = $resource->organizationAutocomplete($q);

		echo implode("\n", $resourceArray);

		break;



    case 'getLicenseList':

		if (isset($_GET['searchMode'])) $searchMode = $_GET['searchMode']; else $searchMode='';
		if (isset($_GET['limit'])) $v = $_GET['limit']; else $limit='';

		$q = $_GET['q'];
		$q = str_replace(" ", "+",$q);
		$q = str_replace("&", "%",$q);

		$resource = new Resource();
		$resourceArray = $resource->licenseAutocomplete($q);

		echo implode("\n", $resourceArray);

		break;






	case 'getExistingOrganizationName':
		$name = $_GET['name'];
		if (isset($organizationID)) $organizationID = $_GET['organizationID']; else $organizationID = '';


		$organization = new Organization();
		$orgArray = array();

		$exists = 0;

		foreach ($organization->allAsArray() as $orgArray) {
			if ((strtoupper($orgArray['name']) == strtoupper($name)) && ($orgArray['organizationID'] != $organizationID)) {
				$exists++;
			}
		}

		echo $exists;

		break;



	//used to verify resource name/title isn't already being used as it's added
	case 'getExistingTitle':
		$name = $_GET['name'];


		$resource = new Resource();


		if ($name){
			echo count($resource->getResourceByTitle($name));
		}


		break;



	////////////////////////////////////////////////////////////////////////////
	//
	//Following are for workflow
	//
	////////////////////////////////////////////////////////////////////////////



	case 'markComplete':
		if ($_GET['resourceStepID']){
			$resourceStepID = $_GET['resourceStepID'];
			$resourceStep = new ResourceStep(new NamedArguments(array('primaryKey' => $resourceStepID)));

			try {
				$resourceStep->completeStep();
			} catch (Exception $e) {
				echo $e->getMessage();
			}

		}

		break;



	case 'restartWorkflow':
		if ($_GET['resourceID']){
			$resourceID = $_GET['resourceID'];
			$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

			//log who set off the restart
			$resource->workflowRestartLoginID = $loginID;
			$resource->workflowRestartDate = date( 'Y-m-d' );

			try {
				$resource->save();
				$resource->enterNewWorkflow();
			} catch (Exception $e) {
				echo $e->getMessage();
			}

		}

		break;



	case 'markResourceComplete':
		if ($_GET['resourceID']){
			$resourceID = $_GET['resourceID'];
			$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));
			//log who set off the completion
			$resource->workflowRestartLoginID = $loginID;
			$resource->workflowRestartDate = date( 'Y-m-d' );

			try {
				$resource->save();

				//updates status and sends notification
				$resource->completeWorkflow();
			} catch (Exception $e) {
				echo $e->getMessage();
			}

		}

		break;




	case 'submitUserGroup':

 		$userGroupID = $_POST['userGroupID'];

		if ($userGroupID!=''){
			$userGroup = new UserGroup(new NamedArguments(array('primaryKey' => $userGroupID)));
		}else{
			$userGroup = new UserGroup();
		}

		$userGroup->groupName = $_POST['groupName'];
		$userGroup->emailAddress = $_POST['emailAddress'];

		try {
			$userGroup->save();

			$userGroupID=$userGroup->primaryKey;

			$usersArray = array();
			$usersArray = explode(':::',$_POST['usersList']);

			//first remove all payment records, then we'll add them back
			$userGroup->removeUsers();

			foreach ($usersArray as $key => $value){
				if ($value){
					$userGroupLink = new UserGroupLink();
					$userGroupLink->loginID = $value;
					$userGroupLink->userGroupID = $userGroupID;

					try {
						$userGroupLink->save();
					} catch (Exception $e) {
						echo $e->getMessage();
					}
				}
			}



		} catch (Exception $e) {
			echo $e->getMessage();
		}

		break;






	case 'submitWorkflow':

 		$workflowID = $_POST['workflowID'];

		if ($workflowID!=''){
			$workflow = new Workflow(new NamedArguments(array('primaryKey' => $workflowID)));
		}else{
			$workflow = new Workflow();
		}

		$workflow->workflowName = '';
		$workflow->resourceFormatIDValue = $_POST['resourceFormatID'];
		$workflow->resourceTypeIDValue = $_POST['resourceTypeID'];
		$workflow->acquisitionTypeIDValue = $_POST['acquisitionTypeID'];

		try {
			$workflow->save();

			$workflowID=$workflow->primaryKey;

			//first remove all step records, then we'll add them back
			$workflow->removeSteps();

			$stepNameArray = array();
			$stepNameArray = explode(':::',$_POST['stepNames']);
			$userGroupArray = array();
			$userGroupArray = explode(':::',$_POST['userGroups']);
			$priorStepArray = array();
			$priorStepArray = explode(':::',$_POST['priorSteps']);
			$seqOrderArray = array();
			$seqOrderArray = explode(':::',$_POST['seqOrders']);
			$stepIDArray = array();
			$stepIDPriorArray = array();

			foreach ($stepNameArray as $key => $value){
				if (trim($value)){
					$step = new Step();
					$step->workflowID = $workflowID;
					$step->stepName = trim($value);
					$step->userGroupID = $userGroupArray[$key];
					$step->priorStepID = '';
					$step->displayOrderSequence = $seqOrderArray[$key];

					try {
						$step->save();
						$stepID = $step->primaryKey;

						//if this step has a prior step, put it in an array
						if ($priorStepArray[$key]){
							$stepIDPriorArray[$stepID] = $priorStepArray[$key];
						}


						$stepIDArray[$seqOrderArray[$key]] = $stepID;


					} catch (Exception $e) {
						echo $e->getMessage();
					}
				}
			}

			//now that all of the stepIDs have been set up, fix the prior step IDs
			foreach ($stepIDPriorArray as $stepID => $key){
				if ($stepID){

					$step = new Step(new NamedArguments(array('primaryKey' => $stepID)));
					$step->priorStepID = $stepIDArray[$key];

					try {
						$step->save();
					} catch (Exception $e) {
						echo $e->getMessage();
					}
				}
			}



		} catch (Exception $e) {
			echo $e->getMessage();
		}

		break;

	case 'deleteGeneralSubject':
		
 		$className = $_GET['class'];
 		$deleteID = $_GET['id'];

		$instance = new $className(new NamedArguments(array('primaryKey' => $deleteID)));

			try {
				$instance->deleteGeneralSubject();
			} catch (Exception $e) {
				//print out a friendly message...
				echo "Unable to delete.  Please make sure no resources are set up with this information.";
			}

		break;
		
		
	case 'deleteDetailedSubject':

 		$className = $_GET['class'];
 		$deleteID = $_GET['id'];

		$instance = new $className(new NamedArguments(array('primaryKey' => $deleteID)));

			try {
				$instance->delete();
			} catch (Exception $e) {
				//print out a friendly message...
				echo "Unable to delete.  Please make sure no resources are set up with this information.";
			}

		break;


	case 'submitDetailSubject':
	
 		$generalSubjectID = $_POST['generalSubjectID'];

		if ($generalSubjectID!=''){
			$generalSubject = new GeneralSubject(new NamedArguments(array('primaryKey' => $generalSubjectID)));
		}else{
			$generalSubject = new GeneralSubject();
		}
							
		// Update the General Subject if needed
		$generalSubject->shortName = str_replace("'", "''", $_POST['shortName']);

		try {
			$generalSubject->save();

			$generalSubjectID=$generalSubject->primaryKey;

			$detailSubjectArray = array();
			$detailSubjectArray = explode(':::',$_POST['detailSubjectsList']);

			$detailSubjectIDs = "(-1";
			
			// Update the GeneralDetailSubject Links
			foreach ($detailSubjectArray as $key => $value){
				if ($value){
				
					$generalDetailSubjectLink = new GeneralDetailSubjectLink();
					$generalDetailSubjectLink->detailedSubjectID = $value;
					$generalDetailSubjectLink->generalSubjectID = $generalSubjectID;
					
					// Add any Detail Subject Links that are new
					if ($generalDetailSubjectLink->duplicateCheck() == 0 ) {
						// Add the new link
						try {
							$generalDetailSubjectLink->save();
						} catch (Exception $e) {
							echo $e->getMessage();
						}
					}
					// Build list of detailid's that are in use
					$detailSubjectIDs = $detailSubjectIDs . ', ' . $value;
				}
			}
			
			$detailSubjectIDs = $detailSubjectIDs . ')';
			$generalDetailSubjectLink = new GeneralDetailSubjectLink();
			// Delete the links that are no longer in use.
			$generalDetailSubjectLink->deleteNotInuse($generalSubjectID, $detailSubjectIDs);

		} catch (Exception $e) {
			echo $e->getMessage();
		}

		break;

	case 'updateResourceSubject':

		$resourceSubject = new ResourceSubject();

		$resourceID = $_GET['resourceID'];
		$generalSubjectID = $_GET['generalSubjectID'];
		$detailSubjectID = $_GET['detailSubjectID'];

		if (!isset($detailSubjectID)) {
			$detailSubjectID = -1;
		}

		if (!isset($generalSubjectID)) {
			$generalSubjectID = -1;
		}
		
		$generalDetailSubjectLink = new GeneralDetailSubjectLink();
		$generalDetailSubjectLinkID = $generalDetailSubjectLink->getGeneralDetailID($generalSubjectID, $detailSubjectID);
		
		$resourceSubject->resourceID = $resourceID;
		$resourceSubject->generalDetailSubjectLinkID = $generalDetailSubjectLinkID;
		
		// Check to see if the subject has already been associated with the resouce.  If not then save.
		if ($resourceSubject->duplicateCheck($resourceID, $generalDetailSubjectLinkID) == 0) {
			try {
				$resourceSubject->save();
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}

		break;

		
		
	case 'removeResourceSubjectRelationship':
		$generalDetailSubjectID = $_GET['generalDetailSubjectID'];
		$resourceID = $_GET['resourceID'];

		$resourceSubject = new ResourceSubject();

		try {

			$resourceSubject->removeResourceSubject($resourceID, $generalDetailSubjectID);
			echo "Subject successfully removed.";
		} catch (Exception $e) {
			echo $e->getMessage();
		}		
		
		break;		

    case 'updateGeneralSubject':
 		$className = $_POST['className'];	
 		$updateID = $_POST['updateID'];
 		$shortName = trim($_POST['shortName']);

		if ($updateID != ''){
			$instance = new $className(new NamedArguments(array('primaryKey' => $updateID)));
		}else{
			$instance = new $className();
		}

		$instance->shortName = $shortName;
		// Check to see if the general subject name exists.  If not then save.
		if ($instance->duplicateCheck($shortName) == 0)  {
			try {
				$instance->save();
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		} else {
			echo "A duplicate " . strtolower(preg_replace("/[A-Z]/", " \\0" , lcfirst($className))) . " exists.";				
		}		
		
 		break;

    case 'updateDetailedSubject':
 		$className = $_POST['className'];
 		$updateID = $_POST['updateID'];
 		$shortName = trim($_POST['shortName']);

		if ($updateID != ''){
			$instance = new $className(new NamedArguments(array('primaryKey' => $updateID)));
		}else{
			$instance = new $className();
		}

		$instance->shortName = $shortName;

		// Check to see if the detailed subject name exists.  If not then save.
		if ($instance->duplicateCheck($shortName) == 0) {
			try {
				$instance->save();
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		} else {
			echo "A duplicate " . strtolower(preg_replace("/[A-Z]/", " \\0" , lcfirst($className))) . " exists.";		
		}		

		
 		break;	







	default:
       echo "Action " . $action . " not set up!";
       break;


}

?>
