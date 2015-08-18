<?php
$resourceID = $_GET['resourceID'];
$archivedFlag = (!empty($_GET['archived']) && $_GET['archived'] == 1) ? true:false;

$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));
$util = new Utility();


//shared html template for organization and resource downtimes
function generateDowntimeHTML($downtime,$associatedEntities=null) {

	$html = "
	<div class=\"downtime\">";
	
	$html .= "
	  	<dl>
	  		<dt>Type:</dt> 
	  		<dd>{$downtime->name}</dd>

	  		<dt>Downtime Start:</dt> 
	  		<dd>{$downtime->startDate}</dd>

	  		<dt>Downtime Resolved:</dt> 
	  		<dd>{$downtime->endDate}</dd>";

	if($downtime->subjectText) {
		$html .= "
	  		<dt>Linked issue:</dt> 
	  		<dd>{$downtime->subjectText}</dd>";
	}

	$html .= "		
		</dl>
	</div>";	
	
	return $html;
}

//display any organization level downtimes for the resource
$organizationArray = $resource->getOrganizationArray();

if (count($organizationArray) > 0) {
	echo '<h3 class="text-center">Organizational</h3>';

	$downtimedOrgs = array();
	foreach ($organizationArray as $orgData) {
		if (!in_array($orgData['organizationID'],$downtimedOrgs)) {
			$organization = new Organization(new NamedArguments(array('primaryKey' => $orgData['organizationID'])));

			$orgDowntimes = $organization->getDowntime($archivedFlag);

			if(count($orgDowntimes) > 0) {
				foreach ($orgDowntimes as $downtime) {
					echo generateDowntimeHTML($downtime,array(array("name"=>$orgData['organization'],"id"=>$organization->organizationID,"entityType"=>1)));
				}
			} else {
				echo "<br><p>There are no organization level downtimes.</p><br>";
			}

			$orgDowntimes = null;
			$downtimedOrgs[] = $orgData['organizationID'];
		}
	}
}

//display any resource level downtimes for the resource (shows any other resources associated with the downtime, too)
$resourceDowntimes = $resource->getDowntime($archivedFlag);
echo '<h3 class="text-center">Resources</h3>';
if(count($resourceDowntimes) > 0) {
	foreach ($resourceDowntimes as $downtime) {
		$associatedEntities = array();
		if ($associatedResources = $downtime->getAssociatedResources()) {
			foreach ($associatedResources as $resource) {
				$associatedEntities[] = array("name"=>$resource->titleText,"id"=>$resource->resourceID,"entityType"=>2);
			}
		} 
		echo generateDowntimeHTML($downtime,$associatedEntities);
	}
} else {
	echo "<br><p>There are no resource level downtimes.</p><br>";
}
?>