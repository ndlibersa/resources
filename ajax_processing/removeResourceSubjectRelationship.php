<?php
		$generalDetailSubjectID = $_GET['generalDetailSubjectID'];
		$resourceID = $_GET['resourceID'];

		$resourceSubject = new ResourceSubject();

		try {

			$resourceSubject->removeResourceSubject($resourceID, $generalDetailSubjectID);
			echo _("Subject successfully removed.");
		} catch (Exception $e) {
			echo $e->getMessage();
		}

?>
