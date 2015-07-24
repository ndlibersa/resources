<?php
	$resourceID = $_GET['resourceID'];
	$archived = $_GET['archived'];

	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

	$util = new Utility();
	
	$issues = $resource->getIssues();


	echo "<dl>";
	foreach($issues as $issue) {
		echo "<dt>Date reported:</dt> <dd>{$issue["dateCreated"]}</dd>
			  <dt>Subject:</dt> <dd>{$issue["subjectText"]}</dd> 
			  <dt>Body:</dt> <dd>{$issue["bodyText"]}</dd>";
	}
	echo "</dl>";
	
?>