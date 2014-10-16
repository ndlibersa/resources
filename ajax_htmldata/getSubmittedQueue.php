<?php

		$resourceArray = array();
		$resourceArray = $user->getResourcesInQueue('progress');

		echo "<div class='adminRightHeader'>Submitted Requests</div>";



		if (count($resourceArray) == "0"){
			echo "<i>No submitted requests</i>";
		}else{
		?>

			<table class='dataTable' style='width:570px;margin-bottom:5px;'>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Date Created</th>
				<th>Acquisition Type</th>
				<th>Status</th>
			</tr>

		<?php
			$i=0;
			foreach ($resourceArray as $resource){

				$i++;
				if ($i % 2 == 0){
					$classAdd="";
				}else{
					$classAdd="class='alt'";
				}

				$acquisitionType = new AcquisitionType(new NamedArguments(array('primaryKey' => $resource['acquisitionTypeID'])));
				$status = new Status(new NamedArguments(array('primaryKey' => $resource['statusID'])));

		?>
				<tr id='tr_<?php echo $resource['resourceID']; ?>'>
					<td <?php echo $classAdd; ?>><a href='resource.php?resourceID=<?php echo $resource['resourceID']; ?>'><?php echo $resource['resourceID']; ?></a></td>
					<td <?php echo $classAdd; ?>><a href='resource.php?resourceID=<?php echo $resource['resourceID']; ?>'><?php echo $resource['titleText']; ?></a></td>
					<td <?php echo $classAdd; ?>><?php echo $resource['createDate']; ?></td>
					<td <?php echo $classAdd; ?>><?php echo $acquisitionType->shortName; ?></td>
					<td <?php echo $classAdd; ?>><?php echo $status->shortName; ?></td>
					</td>
				</tr>



			<?php
			}

			echo "</table>";

		}


?>


