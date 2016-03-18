<?php
		$importConfigID = $_POST['importConfigID'];
		$shortName = $_POST['shortName'];
		$configuration = $_POST['configuration'];

		error_log($importConfigID);
		error_log($shortName);
		error_log($configuration);

		if ($importConfigID != '') {
			$instance = new ImportConfig(new NamedArguments(array('primaryKey' => $importConfigID)));
		} else {
			$instance = new ImportConfig();
		}

		$instance->shortName = $shortName;
		$instance->configuration = $configuration;

		try {
			$instance->save();
		} catch (Exception $e) {
			echo $e->getMessage();
		}
?>
