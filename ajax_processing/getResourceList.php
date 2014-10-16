<?php
        //used for the parent resource list in the edit resource box

		if (isset($_GET['searchMode'])) $searchMode = $_GET['searchMode']; else $searchMode='';
		if (isset($_GET['limit'])) $v = $_GET['limit']; else $limit='';

		$q = $_GET['q'];
		$q = str_replace(" ", "+",$q);
		$q = str_replace("&", "%",$q);

		$resource = new Resource();
		$resourceArray = $resource->resourceAutocomplete($q);

		echo implode("\n", $resourceArray);

?>

