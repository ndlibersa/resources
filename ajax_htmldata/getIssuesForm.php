<?php
	$resourceID = $_GET['resourceID'];
	$archived = $_GET['archived'];

	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

	$util = new Utility();
	
	$issues = $resource->getIssues();
	
	foreach($issues as $issue) {
		$contacts = $issue->getContacts();
		$associatedEntities = $issue->getAssociatedEntities();
		echo "
		<div class=\"issue\">
		  	<a class=\"closeBtn\" href=\"\">close</a>
		  	<dl>
		  		<dt>Date reported:</dt> 
		  		<dd>{$issue->attributes['dateCreated']}</dd>
		  		
		  		<dt>Contact(s):</dt> 
		  		<dd>";

		if($contacts) {
			echo "<ul class=\"contactList\">";
			foreach($contacts as $contact) {
				echo "<li><a href=\"mailto:".urlencode($contact->attributes['emailAddress'])."?Subject=RE: {$issue->attributes['subjectText']}\">{$contact->attributes['name']}</a></li>";
			}
			echo "</ul>";
		}


		echo "	</dd> 
		  		<dt>Applies to:</dt> 
		  		<dd>";
		if ($associatedEntities) {
			$temp ='';
			foreach ($associatedEntities as $entity) {
				$temp .= " {$entity->attributes['titleText']},";
			}
			echo rtrim($temp,',');
		}
		echo "	</dd> 
		  		<dt>Subject:</dt> 
		  		<dd>{$issue->attributes['subjectText']}</dd> 
		  		
		  		<dt class=\"block\">Body:</dt> 
		  		<dd>{$issue->attributes['bodyText']}</dd>
		  	</dl>
		</div>";
	}

?>