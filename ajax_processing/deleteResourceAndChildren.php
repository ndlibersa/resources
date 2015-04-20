<?php
		$resourceID = $_GET['resourceID'];
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));


    try {
      $resource->removeResourceAndChildren();
      echo "Resource successfully deleted.";
    } catch (Exception $e) {
      echo $e->getMessage();
    }

?>
