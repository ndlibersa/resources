<?php
 		$className = $_POST['className'];
 		$updateID = $_POST['updateID'];
 		$shortName = trim($_POST['shortName']);

		if ($updateID != ''){
			$instance = new $className(new NamedArguments(array('primaryKey' => $updateID)));
		}else{
			$instance = new $className();
		}

		$instance->shortName = $shortName;
		// Check to see if the general subject name exists.  If not then save.
		if ($instance->duplicateCheck($shortName) == 0)  {
			try {
				$instance->save();
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		} else {
			echo "A duplicate " . strtolower(preg_replace("/[A-Z]/", " \\0" , lcfirst($className))) . " exists.";
		}

 		break;

