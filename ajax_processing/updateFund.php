<?php
		$editFundCode = $_POST['editFundCode'];
		$fundCode = $_POST['fundCode'];
		$shortName = $_POST['shortName'];

		if ($editFundCode != ''){
			$instance = new Fund(new NamedArguments(array('primaryKey' => $editFundCode)));
		}else{
			$instance = new Fund();
		}


		$instance->fundCode = $fundCode;
		$instance->shortName = $shortName;

		try {
			$instance->save();
		} catch (Exception $e) {
			echo $e->getMessage();
		}

?>
