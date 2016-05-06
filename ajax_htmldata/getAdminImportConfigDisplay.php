<?php
		$instanceArray = array();
		$obj = new ImportConfig();

		$instanceArray = $obj->allAsArray();

		echo "<div class='adminRightHeader'>" . _("Import Configuration") . "</div>";

		if (count($instanceArray) > 0){
			?>
			<table  class='linedDataTable' >
				<tr>
				<th><?php echo _("Name");?></th>
				<th style='width:20px;'>&nbsp;</th>
				<th style='width:20px;'>&nbsp;</th>
				</tr>
				<?php

				foreach($instanceArray as $instance) {
					echo "<tr>";
					echo "<td>" . $instance['shortName'] . "</td>";
					echo "<td><a href='ajax_forms.php?action=getAdminImportConfigUpdateForm&updateID=" . $instance['importConfigID'] . "&height=700&width=1024&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit'></a></td>";
					echo "<td><a href='javascript:deleteImportConfig(\"ImportConfig\", \"" . $instance['importConfigID'] . "\");'><img src='images/cross.gif' alt='remove' title='remove'></a></td>";
					echo "</tr>";
				}
				?>
				<br />
			</table>
			<?php

		}else{
			echo "(none found)<br />";
		}
		echo "<a href='ajax_forms.php?action=getAdminImportConfigUpdateForm&updateID=&height=760&width=1024&modal=true' class='thickbox'>" . _("add new import configuration") . "</a><br/>";
?>
