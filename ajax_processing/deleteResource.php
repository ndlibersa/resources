<?php
		$resourceID = $_GET['resourceID'];
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		try {
			$resource->removeResource();
			echo _("Resource successfully deleted.");
		} catch (Exception $e) {
			echo $e->getMessage();
		}
?>
