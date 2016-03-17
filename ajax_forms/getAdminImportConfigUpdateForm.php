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

	//get all alias types for output in drop-down menu
	$aliasTypeArray = array();
	$aliasTypeObj = new AliasType();
	$aliasTypeArray = $aliasTypeObj->allAsArray();

	$configuration=json_decode($instance->configuration,true);
?>
	<div id='div_updateForm'>
		<input type='hidden' id='imortConfigID' value='<?php echo $updateID; ?>'>
		<div class='formTitle' style='min-width:1000px;'><span class='headerText' style='margin-left:7px;'><?php if ($updateID){ echo "Edit Import Configuration"; } else { echo "Add Import Configuration"; } ?></span></div>
			<span class='smallDarkRedText' id='span_errors'></span>
			<div id='importConfigColumns'>
				<span>Configuration Name</span><span><input value='<?php echo $instance->shortName;?>'</span>
				<div id='importConfigColumnsLeft'>
					<p><span>Resource Title</span><span class='import-text-left'><input value="<?php echo $configuration["title"]?>" /></span></p>
					<div id='resource_alias'>
						<?php
							if(count($configuration["alias"]) > 0) {
								foreach($configuration["alias"] as $alias) {
									echo "<p><span>Alias</span><span><input value='".$alias."' /></span><span><select>";
									foreach($aliasTypeArray as $aliasType) {
										echo "<option value='" . $aliasType['aliasTypeID'] . "'>" . $aliasType['shortName'] . "</option>";
									}
									echo "</select></span></p>";
								}
							}
							else {
								echo "<p><span>Alias</span><span><input value='' /></span><span><select>";
								foreach($aliasTypeArray as $aliasType) {
									echo "<option value='" . $aliasType['aliasTypeID'] . "'>" . $aliasType['shortName'] . "</option>";
								}
								echo "</select></span></p>";
							}
						?>
						<p><a href='#'>+ Add another alias</a></p>
					</div>
					<p><span>Resource URL</span><span><input value="<?php echo $configuration["url"]?>" /></span></p>
					<p><span>Alternate URL</span><span><input value="<?php echo $configuration["altUrl"]?>" /></span></p>
					<div id='resource_parent'>
						<?php
							if(count($configuration["parent"]) > 0) {
								foreach($configuration["parent"] as $parent) {
									echo "<p><span>Parent Resource</span><span><input value='" . $parent . "' /></span></p>";
								}
							}
							else {
								echo "<p><span>Parent Resource</span><span><input value='' /></span></p>";
							}
						?>
						<p><a href='#'>+ Add another parent resource</a></p>
					</div>
					<div id='resource_isbnOrIssn'>
						<?php
							if(count($configuration["isbnOrIssn"]) > 0) {
								foreach($configuration["isbnOrIssn"] as $isbnOrIssn) {
									echo "<p><span>ISBN or ISSN</span><span><input value='" . $isbnOrIssn . "' /></span></p>";
								}
							}
							else {
								echo "<p><span>ISBN OR ISSN</span><span><input value='' /></span></p>";
							}
						?>
						<p><a href='#'>+ Add another ISBN or ISSN</a></p>
					</div>
					<p><span>Resource Format</span><span><input value="<?php echo $configuration["resourceFormat"]?>" /></span></p>
					<p><span>Resource Type</span><span><input value="<?php echo $configuration["resourceType"]?>" /></span></p>
					<div id='resource_subject'>
						<?php
							if(count($configuration["subject"]) > 0) {
								foreach($configuration["subject"] as $subject) {
									echo "<p><span>Subject</span><span><input value='" . $subject . "' /></span></p>";
								}
							}
							else {
								echo "<p><span>Subject</span><span><input value='' /></span></p>";
							}
						?>
						<p><a href='#'>+ Add another subject</a></p>
					</div>
				</div>
				<div id='importConfigColumnsRight'>
					Right Content
				</div>
				<div style='clear: both;''></div>
			</div>
			<div id='importConfigOrgMapping'>
				<table>
					<tr>
						<th>Text in CSV File</th>
						<th>Will Change To</th>
					</tr>
<?php
	foreach($orgMappings as $orgMapping) {
		echo "<tr><td><input value='".htmlspecialchars($orgMapping->orgNameImported)."' /></td><td><input value='".htmlspecialchars($orgMapping->orgNameMapped)."' /></td></tr>";
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
