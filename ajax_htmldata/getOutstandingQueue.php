<?php


		$resourceArray = array();
		$resourceArray = $user->getOutstandingTasks();

		echo "<div class='adminRightHeader'>"._("Outstanding Tasks")."</div>";



		if (count($resourceArray) == "0"){
			echo "<i>"._("No outstanding requests")."</i>";
		}else{
		?>


			<table class='dataTable' style='width:646px;padding:0x;margin:0px;height:100%;'>
			<tr>
				<th style='width:45px;'><?php echo _("ID");?></th>
				<th style='width:300px;'><?php echo _("Name");?></th>
				<th style='width:95px;'><?php echo _("Acquisition Type");?></th>
				<th style='width:125px;'><?php echo _("Routing Step");?></th>
				<th style='width:75px;'><?php echo _("Start Date");?></th>
			</tr>

		<?php
			$i=0;
			foreach ($resourceArray as $resource){
				$taskArray = $user->getOutstandingTasksByResource($resource['resourceID']);
				$countTasks = count($taskArray);

				//for shading every other row
				$i++;
				if ($i % 2 == 0){
					$classAdd="";
				}else{
					$classAdd="class='alt'";
				}



				$acquisitionType = new AcquisitionType(new NamedArguments(array('primaryKey' => $resource['acquisitionTypeID'])));
				$status = new Status(new NamedArguments(array('primaryKey' => $resource['statusID'])));

		?>
				<tr id='tr_<?php echo $resource['resourceID']; ?>' style='padding:0x;margin:0px;height:100%;'>
					<td <?php echo $classAdd; ?>><a href='resource.php?resourceID=<?php echo $resource['resourceID']; ?>'><?php echo $resource['resourceID']; ?></a></td>
					<td <?php echo $classAdd; ?>><a href='resource.php?resourceID=<?php echo $resource['resourceID']; ?>'><?php echo $resource['titleText']; ?></a></td>
					<td <?php echo $classAdd; ?>><?php echo $acquisitionType->shortName; ?></td>

					<?php
						$j=0;


						if (count($taskArray) > 0){
							foreach ($taskArray as $task){
								if ($j > 0){
								?>
								<tr>
								<td <?php echo $classAdd; ?> style='border-top-style:none;'>&nbsp;</td>
								<td <?php echo $classAdd; ?> style='border-top-style:none;'>&nbsp;</td>
								<td <?php echo $classAdd; ?> style='border-top-style:none;'>&nbsp;</td>

								<?php
									$styleAdd=" style='border-top-style:none;'";
								}else{
									$styleAdd="";
								}


								echo "<td " . $classAdd . " " . $styleAdd . ">" . $task['stepName'] . "</td>";
								echo "<td " . $classAdd . " " . $styleAdd . ">" . format_date($task['startDate']) . "</td>";
								echo "</tr>";

								$j++;
							}

						}else{
							echo "<td " . $classAdd . ">&nbsp;</td><td " . $classAdd . ">&nbsp;</td></tr>";
						}


			}

			echo "</table>";


		}

?>

