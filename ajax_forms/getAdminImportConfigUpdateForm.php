<?php
	$updateID = $_GET['updateID'];

	if ($updateID){
		$instance = new ImportConfig(new NamedArguments(array('primaryKey' => $updateID)));
		$orgMappingInstance = new OrgNameMapping();
		$orgMappings=$orgMappingInstance->getOrgNameMappingByImportConfigID($updateID);
	}else{
		$instance = new ImportConfig();
		$orgMappingInstance = new OrgNameMapping();
		$orgMappings=array();
	}
?>
		<div id='div_updateForm'>
		<?php print $orgMappingInstance->getOrgNameMappingByImportConfigID($updateID)[0]->orgNameImported;
		      print $orgMappingInstance->getOrgNameMappingByImportConfigID($updateID)[1]->orgNameImported;
		?>

		<input type='hidden' id='imortConfigID' value='<?php echo $updateID; ?>'>

		<div class='formTitle' style='min-width:485px;'><span class='headerText' style='margin-left:7px;'><?php if ($updateID){ echo "Edit Import Configuration"; } else { echo "Add Import Configuration"; } ?></span></div>

		<span class='smallDarkRedText' id='span_errors'></span>
		<div id='importConfigColumns'>
			<div style="float:left; width:50%">
				Left Content
			</div>
			<div style="float:right; width:50%">
				Right Content
			</div>
		</div>
		<div id='importConfigOrgMapping'>
			<table>
				<tr>
					<th>Text in CSV File</th>
					<th>Will Change To</th>
				</tr>
<?php
	foreach($orgMappings as $orgMapping) {
		echo "<tr><td>".$orgMapping->orgNameImported."</td><td>".$orgMapping->orgNameMapped."</td></tr>";
	}
?>
			</table>
		</div>

		<!--
		<table class="surroundBox" style="width:490px;">
		<tr>
		<td>

			<table class='noBorder' style='width:200px; margin:10px;'>
			<tr>
			<td>Name</td><td><input type='text' id='shortName' value='<?php echo $instance->shortName; ?>' style='width:150px;'/></td>
			</tr>
			</table>

		</td>
		</tr>
		</table>
		-->
		<br />
		<table class='noBorderTable' style='width:125px;'>
			<tr>
				<td style='text-align:left'><input type='button' value='submit' id ='submitAddUpdate'></td>
				<td style='text-align:right'><input type='button' value='cancel' onclick="window.parent.tb_remove(); return false;"></td>
			</tr>
		</table>


		</form>
		</div>

		<script type="text/javascript">
		   //attach enter key event to new input and call add data when hit
		   $('#fundCode').keyup(function(e) {
				   if(e.keyCode == 13) {
					   window.parent.submitFundData();
				   }
		});

		   $('#shortName').keyup(function(e) {
				   if(e.keyCode == 13) {
					   window.parent.submitFundData();
				   }
		});

		   $('#submitAddUpdate').click(function () {
			       window.parent.submitFundData();
		   });


	</script>
