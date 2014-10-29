<?php
	//used to verify resource name/title isn't already being used as it's added
	$name = $_GET['name'];
	$resource = new Resource();
	if ($name){
		echo count($resource->getResourceByTitle($name));
	}
?>
