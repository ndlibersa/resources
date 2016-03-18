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
	$aliasOptions = "";
	foreach($aliasTypeArray as $aliasType) {
		$aliasOptions .= "<option value='" . $aliasType['aliasTypeID'] . "'>" . $aliasType['shortName'] . "</option>";
	}


	//get all note types for output in drop-down menu
	$noteTypeArray = array();
	$noteTypeObj = new NoteType();
	$noteTypeArray = $noteTypeObj->allAsArrayForDD();
	$noteOptions = "";
	foreach($noteTypeArray as $noteType) {
		$noteOptions .= "<option value='" . $noteType['noteTypeID'] . "'>" . $noteType['shortName'] . "</option>";
	}

	//get all organization roles for output in drop-down menu
	$organizationRoleArray = array();
	$organizationRoleObj = new OrganizationRole();
	$organizationRoleArray = $organizationRoleObj->getArray();
	$organizationOptions = "";
	foreach($organizationRoleArray as $organizationRoleID => $organizationRoleShortName) {
		$organizationOptions .= "<option value='" . $organizationRoleID . "'>" . $organizationRoleShortName . "</option>";
	}


	$configuration=json_decode($instance->configuration,true);
?>
<div id='div_updateForm'>
	<input type='hidden' id='importConfigID' value='<?php echo $updateID; ?>'>
	<div class='formTitle' style='min-width:1000px;'><span class='headerText' style='margin-left:7px;'><?php if ($updateID){ echo _("Edit Import Configuration"); } else { echo _("Add Import Configuration"); } ?></span></div>
		<span class='smallDarkRedText' id='span_errors'></span>
		<span><?php echo _("Configuration Name");?></span><span><input id='shortName' value='<?php echo $instance->shortName;?>'</span>
		<div class='ic-content'>
			<div id='importConfigColumns'>
				<div id='importConfigColumnsLeft'>
					<div id='ic-left-column'>
						<p><span class="ic-label"><?php echo _("Resource Title");?></span><span class='import-text-left'><input id="resource_titleCol" class="ic-column" value="<?php echo $configuration["title"]?>" /></span></p>
						<div id='resource_alias'>
							<?php
								if(count($configuration["alias"]) > 0) {
									foreach($configuration["alias"] as $alias) {
										echo "<div class='alias-record'><p><span class='ic-label'>" . _("Alias") . "</span><span><input class='ic-column' value='".$alias["column"]."' /></span></p><p><span class='ic-label'>" . _('Alias Type') . "</span><span><select class='ic-dropdown'>";
										foreach($aliasTypeArray as $aliasType) {
											echo "<option value='" . $aliasType['aliasTypeID'] . "'";
											if($alias['aliasType'] == $aliasType['aliasTypeID']) {
												echo " selected";
											}
											echo ">" . $aliasType['shortName'] . "</option>";
										}
										echo "</select></span></p></div>";
									}
								}
								else {
									echo "<div class='alias-record'><p><span class='ic-label'>" . _("Alias") . "</span><span><input class='ic-column' value='' /></span></p><p><span class='ic-label'>" . _('Alias Type') . "</span><span><select class='ic-dropdown'>";
									foreach($aliasTypeArray as $aliasType) {
										echo "<option value='" . $aliasType['aliasTypeID'] . "'>" . $aliasType['shortName'] . "</option>";
									}
									echo "</select></span></p></div>";
								}
							?>
						</div>
						<p><a id='add_alias' href='#'><?php echo _("+ Add another alias set");?></a></p>
						<p><span class="ic-label"><?php echo _("Resource URL");?></span><span><input id='resource_urlCol' class="ic-column" value="<?php echo $configuration["url"]?>" /></span></p>
						<p><span class="ic-label"><?php echo _("Alternate URL");?></span><span><input id='resource_altUrlCol' class="ic-column" value="<?php echo $configuration["altUrl"]?>" /></span></p>
						<div id='resource_parent'>
							<?php
								if(count($configuration["parent"]) > 0) {
									foreach($configuration["parent"] as $parent) {
										echo "<p><span class='ic-label'>" . _("Parent Resource") . "</span><span><input class='ic-column' value='" . $parent . "' /></span></p>";
									}
								}
								else {
									echo "<p><span class='ic-label'>" . _("Parent Resource") . "</span><span><input class='ic-column' value='' /></span></p>";
								}
							?>
						</div>
						<p><a id='add_parent' href='#'><?php echo _("+ Add another parent resource")?></a></p>
						<div id='resource_isbnOrIssn'>
							<?php
								if(count($configuration["isbnOrIssn"]) > 0) {
									foreach($configuration["isbnOrIssn"] as $isbnOrIssn) {
										echo "<p><span class='ic-label'>" . _("ISBN or ISSN") . "</span><span><input class='ic-column' value='" . $isbnOrIssn . "' /></span></p>";
									}
								}
								else {
									echo "<p><span class='ic-label'>" . _("ISBN OR ISSN") . "</span><span><input class='ic-column' value='' /></span></p>";
								}
							?>
						</div>
						<p><a id='add_isbnorissn' href='#'><?php echo _("+ Add another ISBN or ISSN");?></a></p>
						<p><span class="ic-label"><?php echo _("Resource Format");?></span><span><input id="resource_format" class="ic-column" value="<?php echo $configuration["resourceFormat"]?>" /></span></p>
						<p><span class="ic-label"><?php echo _("Resource Type");?></span><span><input id="resource_type" class="ic-column" value="<?php echo $configuration["resourceType"]?>" /></span></p>
						<div id='resource_subject'>
							<?php
								if(count($configuration["subject"]) > 0) {
									foreach($configuration["subject"] as $subject) {
										echo "<p><span class='ic-label'>". _("Subject") . "</span><span><input class='ic-column' value='" . $subject . "' /></span></p>";
									}
								}
								else {
									echo "<p><span class='ic-label'>" . _("Subject") . "</span><span><input class='ic-column' value='' /></span></p>";
								}
							?>
						</div>
						<p><a id='add_subject' href='#'><?php echo _("+ Add another subject");?></a></p>
						<div id='resource_note'>
							<?php
								if(count($configuration["note"]) > 0) {
									foreach($configuration["note"] as $note) {
										echo "<div class='note-record'><p><span class='ic-label'>" . _("Note") . "</span><span><input class='ic-column' value='" . $note['column'] . "' /></span></p><p><span class='ic-label'>" . _('Note Type') . "</span><span><select class='ic-dropdown'>";
										foreach($noteTypeArray as $noteType) {
											echo "<option value='" . $noteType['noteTypeID'] . "'";
											if($note['noteType'] == $noteType['noteTypeID']) {
												echo " selected";
											}
											echo ">" . $noteType['shortName'] . "</option>";
										}
										echo "</select></span></p></div>";
									}
								}
								else {
									echo "<div class='note-record'><p><span class='ic-label'>" . _("Note") . "</span><span><input class='ic-column' value='' /></span></p><p><span class='ic-label'>" . _('Note Type') . "</span><span><select class='ic-dropdown'>";
									foreach($noteTypeArray as $noteType) {
										echo "<option value='" . $noteType['noteTypeID'] . "'>" . $noteType['shortName'] . "</option>";
									}
									echo "</select></span></p></div>";
								}
							?>
						</div>
						<p><a id='add_note' href='#'><?php echo _("+ Add another note set");?></a></p>
					</div>
				</div>
				<div id='importConfigColumnsRight'>
					<div id='ic-right-column'>
						<div id='resource_organization'>
							<?php
								if(count($configuration["organization"]) > 0) {
									foreach($configuration["organization"] as $organization) {
										echo "<div class='organization-record'><p><span class='ic-label'>" . _("Organization") . "</span><span><input class='ic-column' value='".$organization['column']."' /></span></p><p><span class='ic-label'>" . _('Organization Role') . "</span><span><select class='ic-dropdown'>";
										foreach($organizationRoleArray as $organizationRoleID => $organizationRoleShortName) {
											echo "<option value='" . $organizationRoleID . "'";
											if($organization["organizationRole"] == $organizationRoleID) {
												echo " selected";
											}
											echo ">" . $organizationRoleShortName . "</option>";
										}
										echo "</select></span></p></div>";
									}
								}
								else {
									echo "<div class='organization-record'><p><span class='ic-label'>" . _("Organization") . "</span><span><input class='ic-column' value='' /></span></p><p><span class='ic-label'>" . _('Organization Role') . "</span><span><select class='ic-dropdown'>";
									foreach($organizationRoleArray as $organizationRoleID => $organizationRoleShortName) {
										echo "<option value='" . $organizationRoleID . "'>" . $organizationRoleShortName . "</option>";
									}
									echo "</select></span></p></div>";
								}
							?>
						</div>
						<p><a id='add_organization' href='#'><?php echo _("+ Add another organization set");?></a></p>

					</div>
				</div>
				<div style='clear: both;'></div>
			</div>
			<div id='importConfigOrgMapping'>
				<table>
					<tr>
						<th><?php echo _("Text in CSV File");?></th>
						<th><?php echo _("Will Change To");?></th>
					</tr>
					<?php
						foreach($orgMappings as $orgMapping) {
							echo "<tr><td><input value='".htmlspecialchars($orgMapping->orgNameImported)."' /></td><td><input value='".htmlspecialchars($orgMapping->orgNameMapped)."' /></td></tr>";
						}
					?>
				</table>
			</div>
		</div>
		<br />
		<table class='noBorderTable' style='width:125px;'>
			<tr>
				<td style='text-align:left'><input type='button' value='submit' id ='submitAddUpdate'></td>
				<td style='text-align:right'><input type='button' value='cancel' onclick="window.parent.tb_remove(); return false;"></td>
			</tr>
		</table>
	</div>
</div>
<script type="text/javascript">
   $('#submitAddUpdate').click(function () {
		submitImportConfigData();
   });
   $('#add_alias').click(function () {
   		$('#resource_alias').append(
   			"<div class='alias-record'><p><span class='ic-label'><?php echo _('Alias');?></span><span><input class='ic-column' value='' /></span></p><p><span class='ic-label'><?php echo _('Alias Type');?></span><span><select class='ic-dropdown'><?php echo $aliasOptions?></select></span></p></div>"
   		);
   });
   $('#add_parent').click(function () {
   		$('#resource_parent').append (
   			"<p><span class='ic-label'><?php echo _('Parent Resource');?></span><span><input class='ic-column' value='' /></span></p>"
   		);
   });
   $('#add_isbnorissn').click(function () {
   		$('#resource_isbnOrIssn').append (
   			"<p><span class='ic-label'><?php echo _('ISBN or ISSN');?></span><span><input class='ic-column' value='' /></span></p>"
   		);
   });
   $('#add_subject').click(function () {
   		$('#resource_subject').append(
   			"<p><span class='ic-label'><?php echo _('Subject');?></span><span><input class='ic-column' value='' /></span></p>"
   		);
   });
   $('#add_note').click(function () {
   		$('#resource_note').append (
			"<div class='note-record'><p><span class='ic-label'><?php echo _('Note');?></span><span><input class='ic-column' value='' /></span></p><p><span class='ic-label'><?php echo _('Note Type');?></span><span><select class='ic-dropdown'><?php echo $noteOptions?></select></span></p></div>"
   		);
   });
   $('#add_organization').click(function () {
   		$('#resource_organization').append (
			"<div class='organization-record'><p><span class='ic-label'><?php echo _('Organization');?></span><span><input class='ic-column' value='' /></span></p><p><span class='ic-label'><?php echo _('Organization Role');?></span><span><select class='ic-dropdown'><?php echo $organizationOptions?></select></span></p></div>"
   		);
   });
</script>
