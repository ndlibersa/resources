<?php
$resourceID = $_GET['resourceID'];
$archivedFlag = (!empty($_GET['archived']) && $_GET['archived'] == 1) ? true:false;

$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));
$util = new Utility();


//shared html template for organization and resource issues
function generateIssueHTML($issue,$associatedEntities=null) {
	$html = "
	<div class=\"issue\">";
	if (!$issue->dateClosed) {
		$html .= "
		<a class=\"thickbox closeIssueBtn\" href=\"ajax_forms.php?action=getCloseIssueForm&issueID={$issue->issueID}&height=120&width=345&modal=true\">close</a>";
	}
	$html .= "
	  	<dl>
	  		<dt>Date reported:</dt> 
	  		<dd>{$issue->dateCreated}</dd>
	  		
	  		<dt>Contact(s):</dt> 
	  		<dd>";
	$contacts = $issue->getContacts();
	if($contacts) {
		$html .= "<ul class=\"contactList\">";
		foreach($contacts as $contact) {
			$html .= "<li><a href=\"mailto:".urlencode($contact->emailAddress)."?Subject=RE: {$issue->subjectText}\">{$contact->name}</a></li>";
		}
		$html .= "</ul>";
	}


	$html .= "	</dd> 
	  		<dt>Applies to:</dt> 
	  		<dd>";
	if ($associatedEntities) {
		$temp ='';
		foreach ($associatedEntities as $entity) {
			$temp .= " {$entity['name']},";
		}
		$html .= rtrim($temp,',');
	}
	$html .= "</dd> 
	  		<dt>Subject:</dt> 
	  		<dd>{$issue->subjectText}</dd> 
	  		
	  		<dt class=\"block\">Body:</dt> 
	  		<dd>{$issue->bodyText}</dd>
	  	</dl>
	</div>";
	return $html;
}

//display any organization level issues for the resource
$organizationArray = $resource->getOrganizationArray();

if (count($organizationArray) > 0) {
	echo '<h3 class="text-center">Organizational</h3>';

	foreach ($organizationArray as $orgData) {

		$organization = new Organization(new NamedArguments(array('primaryKey' => $orgData['organizationID'])));

		$orgIssues = $organization->getIssues($archivedFlag);

		if(count($orgIssues) > 0) {
			foreach ($orgIssues as $issue) {
				echo generateIssueHTML($issue,array(array("name"=>$organization->shortName,"id"=>$organization->organizationID,"entityType"=>1)));
			}
		} else {
			echo "<br><p>There are no organization level issues.</p><br>";
		}

		$orgIssues = null;

	}
}

//display any resource level issues for the resource (shows any other resources associated with the issue, too)
$resourceIssues = $resource->getIssues($archivedFlag);
echo '<h3 class="text-center">Resources</h3>';
foreach ($resourceIssues as $issue) {
	$associatedEntities = array();
	if ($associatedResources = $issue->getAssociatedResources()) {
		foreach ($associatedResources as $resource) {
			$associatedEntities[] = array("name"=>$resource->titleText,"id"=>$resource->resourceID,"entityType"=>2);
		}
	}
	echo generateIssueHTML($issue,$associatedEntities);
}
?>