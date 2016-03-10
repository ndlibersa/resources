<?php
	session_start();
	include_once 'directory.php';
	$pageTitle=_('Resources import');
	include 'templates/header.php';
?>
<html>
<table id="import_configuration_table">
	<tr>
		<td class="config_label"><?php echo _("Resource Title:");?><input id="title" class="import_column_num" type="text" maxlength="3" /></td>
		<td></td>
	</tr>
	<tr>
		<td class="config_label"><?php echo _("Alternate Title:");?><input id="alt_title" class="import_column_num" type="text" maxlength="3" /></td>
		<td></td>
	</tr>
	<tr>
		<td class="config_label"><?php echo _("Resource URL:");?><input id="url" class="import_column_num" type="text" maxlength="3" /></td>
		<td></td>
	</tr>
	<tr>
		<td class="config_label"><?php echo _("Alternate URL:");?><input id="alt_url" class="import_column_num" type="text" maxlength="3" /></td>
		<td></td>
	</tr>
	<tr>
		<td class="config_label"><?php echo _("Parent Resource:");?><input id="parent_resource" class="import_column_num" type="text" maxlength="3" /></td>
		<td></td>
	</tr>
	<tr>
		<td class="config_label"><?php echo _("Publisher:");?><input id="publisher" class="import_column_num" type="text" maxlength="3" /></td>
		<td><input id="pub_as_org" type="checkbox" /><label class="check_label" for="pub_as_org"><?php echo _("Add publisher as an organization");?></label></td>
	</tr>

	<tr>
		<td class="config_label"><?php echo _("ISSN:");?><input id="issn" class="import_column_num" type="text" maxlength="3" /></td>
		<td><input id="multiple_issn" type="checkbox" /><label class="check_label" for="multiple_issn"><?php echo _("Has Multiple ISSNs");?></label><span class="delimited_by"><?php echo _("Delimited by:");?></span><input id="issn_delimiter" class="delimiter" type= "text" /></td>

	</tr>

	<tr>
		<td class="config_label"><?php echo _("ISBN:");?><input id="isbn" class="import_column_num" type="text" maxlength="3" /></td>
		<td><input id="multiple_isbn" type="checkbox" /><label class="check_label" for="multiple_isbn"><?php echo _("Has Multiple ISBNs");?></label><span class="delimited_by"><?php echo _("Delimited by:");?></span><input id="isbn_delimiter" class="delimiter" type= "text" /></td>
	</tr>

	<tr>
		<td class="config_label"><?php echo _("Resource Format:");?><input id="format" class="import_column_num" type="text" maxlength="3" /></td>
		<td><input id="create_format" type="checkbox" /><label class="check_label" for="create_format"><?php echo _("Add unique formats to defined resource formats");?></label></td>
	</tr>

	<tr>
		<td class="config_label"><?php echo _("Resource Type:");?><input id="restype" class="import_column_num" type="text" maxlength="3" /></td>
		<td><input id="create_restype" type="checkbox" /><label class="check_label" for="create_restype"><?php echo _("Add unique types to defined resource types");?></label></td>
	</tr>

	<tr>
		<td class="config_label"><?php echo _("Subject:");?><input id="subject" class="import_column_num" type="text" maxlength="3" /></td>
		<td></td>
	</tr>

</table>
<div id="configuration_group">
	<select id="configuration" onchange="loadConfiguration(this.value);">
		<?php
			echo "<option value='__NEW'>[New Configuration]</option>";
			$dir = './import_configs';
			$files = array_slice(scandir($dir), 2);
			foreach($files as $configuration)
			{
				echo "<option value='" . $configuration . "'>" . $configuration . "</option>";
			}
		?>
	</select>
	<input id="save_configuration" type="button" value="Save Configuration" onclick="tb_show(null,'ajax_forms.php?action=getImportSaveCfgForm&height=178&width=260&modal=true',null);" />
	
</div>
<script src="js/import.js"></script>
</html>