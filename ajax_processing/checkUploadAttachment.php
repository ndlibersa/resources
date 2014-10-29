<?php
	//verify that the new attachment name doesn't have bad characters and the name isn't already being used
	$uploadAttachment = $_GET['uploadAttachment'];
	$attachment = new Attachment();

	$exists = 0;

	if (!is_writable("attachments")) {
		echo 3;
		break;
	}


	//first check that it doesn't have any offending characters
	if ((strpos($uploadAttachment,"'") > 0) || (strpos($uploadAttachment,'"') > 0) || (strpos($uploadAttachment,"&") > 0) || (strpos($uploadAttachment,"<") > 0) || (strpos($uploadAttachment,">") > 0)){
		echo "2";
	}else{
		//loop through each existing attachment to verify this name isn't already being used
		foreach ($attachment->allAsArray() as $attachmentTestArray) {
			if (strtoupper($attachmentTestArray['attachmentURL']) == strtoupper($uploadAttachment)) {
				$exists = 1;
			}
		}

		echo $exists;
	}

?>
