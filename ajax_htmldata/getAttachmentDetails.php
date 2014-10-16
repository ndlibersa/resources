<?php
    	$resourceID = $_GET['resourceID'];
    	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));


 		//get attachments
 		$sanitizedInstance = array();
 		$attachmentArray = array();
 		foreach ($resource->getAttachments() as $instance) {
 			foreach (array_keys($instance->attributeNames) as $attributeName) {
 				$sanitizedInstance[$attributeName] = $instance->$attributeName;
 			}

 			$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;

 			$attachmentType = new AttachmentType(new NamedArguments(array('primaryKey' => $instance->attachmentTypeID)));
 			$sanitizedInstance['attachmentTypeShortName'] = $attachmentType->shortName;

 			array_push($attachmentArray, $sanitizedInstance);
		}

		if (count($attachmentArray) > 0){
			foreach ($attachmentArray as $attachment){
			?>
				<table class='linedFormTable' style='width:460px;max-width:460px;'>
				<tr>
				<th colspan='2'>
					<span style='float:left; vertical-align:bottom;'>
						<?php echo $attachment['shortName']; ?>&nbsp;&nbsp;
						<a href='attachments/<?php echo $attachment['attachmentURL']; ?>' style='font-weight:normal;' target='_blank'><img src='images/arrow-up-right-blue.gif' alt='view attachment' title='view attachment' style='vertical-align:top;'></a></a>
					</span>
					<span style='float:right;'>
					<?php
						if ($user->canEdit()){ ?>
							<a href='ajax_forms.php?action=getAttachmentForm&height=305&width=360&attachmentID=<?php echo $attachment['attachmentID']; ?>&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit attachment'></a>  <a href='javascript:void(0);' class='removeAttachment' id='<?php echo $attachment['attachmentID']; ?>'><img src='images/cross.gif' alt='remove this attachment' title='remove this attachment'></a>
							<?php
						}else{
							echo "&nbsp;";
						}
					?>
					</span>
				</th>
				</tr>

				<?php if ($attachment['attachmentTypeShortName']) { ?>
				<tr>
				<td style='vertical-align:top; width:110px;'>Type:</td>
				<td style='vertical-align:top; width:350px;'><?php echo $attachment['attachmentTypeShortName']; ?></td>
				</tr>
				<?php
				}

				if ($attachment['descriptionText']) { ?>
				<tr>
				<td style='vertical-align:top; width:110px;'>Details:</td>
				<td style='vertical-align:top; width:350px;'><?php echo $attachment['descriptionText']; ?></td>
				</tr>
				<?php
				}
				?>

				</table>
				<br /><br />
			<?php
			}
		} else {
			echo "<i>No attachments available</i><br /><br />";
		}

		if ($user->canEdit()){
		?>
		<a href='ajax_forms.php?action=getAttachmentForm&height=305&width=360&modal=true&resourceID=<?php echo $resourceID; ?>' class='thickbox' id='newAttachment'>add new attachment</a><br /><br />
		<?php
		}
?>

