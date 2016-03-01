<?php

		$instanceArray = array();
		$obj = new Currency();

		$instanceArray = $obj->allAsArray();

		echo "<div class='adminRightHeader'>"._("Currency")."</div>";

		if (count($instanceArray) > 0){
			?>
			<table class='linedDataTable'>
				<tr>
				<th style='width:180px;'><?php echo _("Code");?></th>
				<th style='width:100%;'><?php echo _("Name");?></th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				</tr>
				<?php

				foreach($instanceArray as $instance) {
					echo "<tr>";
					echo "<td>" . $instance['currencyCode'] . "</td>";
					echo "<td>" . $instance['shortName'] . "</td>";
					echo "<td><a href='ajax_forms.php?action=getAdminCurrencyUpdateForm&updateID=" . $instance['currencyCode'] . "&height=178&width=260&modal=true' class='thickbox'><img src='images/edit.gif' alt='"._("edit")."' title='"._("edit")."'></a></td>";
					echo "<td><a href='javascript:deleteCurrency(\"Currency\", \"" . $instance['currencyCode'] . "\");'><img src='images/cross.gif' alt='"._("remove")."' title='"._("remove")."'></a></td>";
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo "(none found)<br />";
		}

		echo "<a href='ajax_forms.php?action=getAdminCurrencyUpdateForm&updateID=&height=178&width=260&modal=true' class='thickbox'>"._("add new currency")."</a>";

?>

