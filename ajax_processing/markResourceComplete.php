<?php
		if ($_GET['resourceID']){
			$resourceID = $_GET['resourceID'];
			$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));
			//log who set off the completion
			$resource->workflowRestartLoginID = $loginID;
			$resource->workflowRestartDate = date( 'Y-m-d' );

			try {
				$resource->save();

				//updates status and sends notification
				$resource->completeWorkflow();
			} catch (Exception $e) {
				echo $e->getMessage();
			}

		}

?>

