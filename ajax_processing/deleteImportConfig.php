<?php
		$importConfigID = $_GET['importConfigID'];
		$importConfig = new ImportConfig(new NamedArguments(array('primaryKey' => $importConfigID)));

		try {
			$importConfig->removeImportConfig();
			echo _("Import configuration successfully deleted.");
		} catch (Exception $e) {
			echo $e->getMessage();
		}
?>
