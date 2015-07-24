<?php
	$resourceID = $_GET['resourceID'];
	$archived = $_GET['archived'];

	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

	$util = new Utility();
	
	$issues = $resource->getIssues();

	foreach($issues as $issue) {

		echo "<div class=\"issue\">
			  	<a class=\"issueCloseBtn\" href=\"\">close</a>
			  	<dl>
			  		<dt>Date reported:</dt> 
			  		<dd>{$issue["dateCreated"]}</dd>
			  		
			  		<dt>Subject:</dt> 
			  		<dd>{$issue["subjectText"]}</dd> 
			  		
			  		<dt class=\"block\">Body:</dt> 
			  		<dd>{$issue["bodyText"]}</dd>
			  	</dl>
			</div>";
	}
	
?>