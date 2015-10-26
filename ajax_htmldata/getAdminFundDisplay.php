<?php

		$instanceArray = array();
		$obj = new Fund();

		$instanceArray = $obj->allAsArray();

		echo "<div class='adminRightHeader'>Fund</div>";

		if (count($instanceArray) > 0){
			?>
			<table  class='linedDataTable' >
				<tr>
				<th style='width:25px;'>Code</th>
				<th style='width:100%;'>Name</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				</tr>
				<?php

				foreach($instanceArray as $instance) {
					echo "<tr>";
					echo "<td>" . $instance['fundCode'] . "</td>";
					echo "<td>" . $instance['shortName'] . "</td>";
					echo "<td><a href='ajax_forms.php?action=getAdminFundUpdateForm&updateID=" . $instance['fundID'] . "&height=178&width=260&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit'></a></td>";
					echo "<td><a href='javascript:deleteFund(\"Fund\", \"" . $instance['fundID'] . "\");'><img src='images/cross.gif' alt='remove' title='remove'></a></td>";
<<<<<<< HEAD
					if ($instance['archived'] == 1)
					{
						echo "<td><input type='checkbox' title='Archive' id='archived' checked value=" . $instance['archived'] . "  onclick='javascript:archiveFund(this.checked, \"" . $instance['fundID'] . "\", \"" . $instance['fundCode'] . "\", \"" . $instance['shortName'] . "\");' > </input></td>";
					}
					else
					{
						echo "<td><input type='checkbox' title='Archive' id='archived' onclick='javascript:archiveFund( this.checked, \"" . $instance['fundID'] . "\", \"" . $instance['fundCode'] . "\", \"" . $instance['shortName'] . "\");' > </input></td>";
					}
=======
>>>>>>> 2c7dc01fbce73f5f86e5ccc1bdb8eaf4b5c9440b
					echo "</tr>";
				}
				?>
				<br />
			</table>
			<?php

		}else{
			echo "(none found)<br />";
		}

<<<<<<< HEAD
echo "<a href='ajax_forms.php?action=getAdminFundUpdateForm&updateID=&height=178&width=260&modal=true' class='thickbox'>add new fund</a>";
=======
		echo "<a href='ajax_forms.php?action=getAdminFundUpdateForm&updateID=&height=178&width=260&modal=true' class='thickbox'>add new fund</a><br/>";
		echo "<a href='importFunds.php?action=getAdminFundUpdateForm&updateID=&height=175&width=300&modal=true' class='thickbox'>import funds</a>";
>>>>>>> 2c7dc01fbce73f5f86e5ccc1bdb8eaf4b5c9440b

?>
