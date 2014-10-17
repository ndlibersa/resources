<?php
		if ($_GET['resourceStepID']){
			$resourceStepID = $_GET['resourceStepID'];
			$resourceStep = new ResourceStep(new NamedArguments(array('primaryKey' => $resourceStepID)));

			try {
				$resourceStep->completeStep();
			} catch (Exception $e) {
				echo $e->getMessage();
			}

		}

?>


