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

	$configuration=json_decode($instance->configuration,true);
?>
<div id='div_updateForm'>
	<input type='hidden' id='importConfigID' value='<?php echo $updateID; ?>'>
	<div class='formTitle' style='min-width:1000px;'>
		<span class='headerText' style='margin-left:7px;'>
			<?php
				if ($updateID) {
					echo _("Edit Import Configuration");
				} else {
					echo _("Add Import Configuration");
				}
			?>
		</span>
	</div>
	<span class='smallDarkRedText' id='span_errors'></span>
	<div id='config-name'>
		<?php echo _("Configuration Name");?><input id='shortName' value='<?php echo $instance->shortName;?>'>
	</div>
	<div class='ic-content'>
		<?php include 'getImportConfigForm.php';?>
	</div>
	<br />
	<table class='noBorderTable' style='width:125px;'>
		<tr>
			<td style='text-align:left'><input type='button' value='<?php echo _("submit");?>' id ='submitAddUpdate' class='submit-button'></td>
			<td style='text-align:right'><input type='button' value='<?php echo _("cancel");?>' onclick="window.parent.tb_remove(); return false;" class='cancel-button'></td>
		</tr>
	</table>
</div>
<script type="text/javascript">
   $('#submitAddUpdate').click(function () {
		submitImportConfigData();
   });
</script>
