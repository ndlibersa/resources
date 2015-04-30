<?php
		$resourceNoteID = $_GET['resourceNoteID'];
		$resourceNote = new ResourceNote(new NamedArguments(array('primaryKey' => $resourceNoteID)));

		try {
			$resourceNote->delete();
			echo "Note successfully deleted.";
		} catch (Exception $e) {
			echo $e->getMessage();
		}
?>
