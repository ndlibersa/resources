<?php
	$resourceID = $_GET['resourceID'];
	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		$config=new Configuration();

		//get parent resource
		$resourceRelationship = new ResourceRelationship();
		$resourceRelationship = $resource->getParentResource();
		$parentResource = new Resource(new NamedArguments(array('primaryKey' => $resourceRelationship->relatedResourceID)));



		//get children resources
		$sanitizedInstance = array();
		$instance = new Resource();
		$childResourceArray = array();
		foreach ($resource->getChildResources() as $instance) {
			foreach (array_keys($instance->attributeNames) as $attributeName) {
				$sanitizedInstance[$attributeName] = $instance->$attributeName;
			}

			$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;

			array_push($childResourceArray, $sanitizedInstance);
		}


		//get organizations (already returned in an array)
		$orgArray = $resource->getDistinctOrganizationArray();

		//get licenses (already returned in array)
		$licenseArray = $resource->getLicenseArray();

		echo "<div style='background-color:white; width:219px; padding:7px;'>";
		echo "<div class='rightPanelLink'><a href='summary.php?resourceID=" . $resource->resourceID . "' target='_blank' class='helpfulLink'>Print View</a></div>";
		if (($resource->systemNumber) && ($config->settings->catalogURL != '')) {
			echo "<div class='rightPanelLink'><a href='" . $config->settings->catalogURL . $resource->systemNumber . "' target='_blank'>Catalog View</a></div>";
		}
		echo "</div>";

		if (($parentResource->titleText) || (count($childResourceArray) > 0)){

		?>
			<div style='background-color:white; width:219px; padding:7px;'>
				<?php

				if ($parentResource->titleText){
					echo "<div class='rightPanelHeader'>Parent Record</div>";
					echo "<div class='rightPanelLink'><a href='resource.php?resourceID=" . $parentResource->resourceID . "' target='_BLANK' class='helpfulLink'>" . $parentResource->titleText . "</a></div>";
					echo "</br>";
				}

				if ((count($childResourceArray) > 0)){
					echo "<div class='rightPanelHeader'>Child Record(s)</div>";

					foreach ($childResourceArray as $childResource){
						$childResourceObj = new Resource(new NamedArguments(array('primaryKey' => $childResource['resourceID'])));
						echo "<div class='rightPanelLink'><a href='resource.php?resourceID=" . $childResourceObj->resourceID . "' target='_BLANK' class='helpfulLink'>" . $childResourceObj->titleText . "</a></div>";
					}
				}

				?>
			</div>

		<?php
		}

		if ((count($orgArray) > 0) && ($config->settings->organizationsModule == 'Y')){

		?>

			<div style='background-color:white; width:219px; padding:7px;'>
				<div class='rightPanelHeader'>Organizations Module</div>

				<?php
				foreach ($orgArray as $organization){
					echo "<div class='rightPanelLink'><a href='" . $util->getOrganizationURL() . $organization['organizationID'] . "' target='_blank' class='helpfulLink'>" . $organization['organization'] . "</a></div>";
				}

				?>
			</div>
		<?php
		}

		if ((count($licenseArray) > 0) && ($config->settings->licensingModule == 'Y')){

		?>
			<div style='background-color:white; width:219px; padding:7px;'>
				<div class='rightPanelHeader'>Licensing Module</div>

				<?php
				foreach ($licenseArray as $license){
					echo "<div class='rightPanelLink'><a href='" . $util->getLicensingURL() . $license['licenseID'] . "' target='_blank' class='helpfulLink'>" . $license['license'] . "</a></div>";
				}

				?>

			</div>

		<?php
		}
		$resourceType = new ResourceType(new NamedArguments(array('primaryKey' => $resource->resourceTypeID)));
		//echo $resourceType->shortName . " " . $resource->resourceTypeID;
		if (($resourceType->includeStats ==  1) && ($config->settings->usageModule == 'Y')){
		?>
			<div style='background-color:white; width:219px; padding:7px;'>
				<div class='rightPanelHeader'>Usage Statistics Module</div>

				<?php
			echo "<form method='post' action='/reports/report.php' target='_blank'>";
			echo "<input type='hidden' name='reportID' value='1'>";
			echo "<input type='hidden' name='prm_21' value='".$resource->titleText."'>";
			echo "<input type='submit' value='Get Statistics'>";
			echo "</form>";
							?>

			</div>

		<?php
		}

?>

