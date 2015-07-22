<?php
		$className = $_GET['className'];


		$instanceArray = array();
		$obj = new $className();

		$instanceArray = $obj->allAsArray();

		echo "<div class='adminRightHeader'>" . preg_replace("/[A-Z]/", " \\0" , $className) . "</div>";

		if (count($instanceArray) > 0){
			?>
			<table class='linedDataTable'>
				<tr>
				<th style='width:100%;'><?php echo _("Value");?></th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				</tr>
				<?php

				foreach($instanceArray as $instance) {
					echo "<tr>";
					echo "<td>" . $instance['shortName'] . "</td>";
					echo "<td><a href='ajax_forms.php?action=getAdminUpdateForm&className=" . $className . "&updateID=" . $instance[lcfirst($className) . 'ID'] . "&height=128&width=260&modal=true' class='thickbox'><img src='images/edit.gif' alt='"._("edit")."' title='"._("edit")."'></a></td>";
					echo "<td><a href='javascript:void(0);' class='removeData' cn='" . $className . "' id='" . $instance[lcfirst($className) . 'ID'] . "'><img src='images/cross.gif' alt='"._("remove")."' title='"._("remove")."'></a></td>";
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo _("(none found)")."<br />";
		}

		echo "<a href='ajax_forms.php?action=getAdminUpdateForm&className=" . $className . "&updateID=&height=128&width=260&modal=true' class='thickbox'>"._("add new ") . strtolower(preg_replace("/[A-Z]/", " \\0" , lcfirst($className))) . "</a>";

?>

