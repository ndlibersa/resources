<?php

		if (isset($_GET['searchMode'])) $searchMode = $_GET['searchMode']; else $searchMode='';
		if (isset($_GET['limit'])) $v = $_GET['limit']; else $limit='';

		$q = $_GET['q'];
		$q = str_replace(" ", "+",$q);
		$q = str_replace("&", "%",$q);

		$resource = new Resource();
		$resourceArray = $resource->organizationAutocomplete($q);

		echo implode("\n", $resourceArray);

?>


