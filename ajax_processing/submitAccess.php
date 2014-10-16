<?php
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


?>


