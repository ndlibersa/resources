<?php
		//if this is an existing attachment
		$attachmentID=$_POST['attachmentID'];

		if ($attachmentID){
			$attachment = new Attachment(new NamedArguments(array('primaryKey' => $attachmentID)));
		}else{
			//set up new attachment
			$attachment = new Attachment();
			$attachment->attachmentID =  '';
		}

		$attachment->resourceID 			= $_POST['resourceID'];
		$attachment->attachmentTypeID 		= $_POST['attachmentTypeID'];
		$attachment->shortName				= $_POST['shortName'];
		$attachment->descriptionText		= $_POST['descriptionText'];
		$attachment->attachmentURL			= $_POST['uploadDocument'];
		$attachment->shortName				= $_POST['shortName'];
		$attachment->descriptionText		= $_POST['descriptionText'];

		try {
			$attachment->save();
		} catch (Exception $e) {
			echo $e->getMessage();
		}

?>

