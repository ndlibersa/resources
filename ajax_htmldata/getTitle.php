<?php
	//update main header title
		$resourceID = $_GET['resourceID'];
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));
		echo $resource->titleText;
?>

