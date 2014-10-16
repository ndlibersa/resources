<?php
        //number of attachments, used to display on the tab so user knows whether to look on tab
		$resourceID = $_GET['resourceID'];
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		echo count($resource->getAttachments());

?>

