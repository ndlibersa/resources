<?php
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


?>

