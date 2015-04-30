<?php
	//determine if resource is valid
		$resourceID = $_GET['resourceID'];
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		if($resource->titleText != ''){
			echo "1";
		}else{
			echo "0";
		}
?>

