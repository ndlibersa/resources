<?php
	session_start();
	include_once 'directory.php';
	$pageTitle=_('Resources import');
	include 'templates/header.php';
?>
<html>
<table id="import_configuration_table">
	<tr>
		<td class="config_label"><?php echo _("Resource Title:");?></td>
		<td><input id="title" class="import_column_num" type="text" maxlength="3" /></td>
	</tr>
	<tr>
		<td class="config_label"><?php echo _("Alternate Title:");?></td>
		<td><input id="alt_title" class="import_column_num" type="text" maxlength="3" /></td>
	</tr>
	<tr>
		<td class="config_label"><?php echo _("Resource URL:");?></td>
		<td><input id="url" class="import_column_num" type="text" maxlength="3" /></td>
	</tr>
	<tr>
		<td class="config_label"><?php echo _("Alternate URL:");?></td>
		<td><input id="alt_url" class="import_column_num" type="text" maxlength="3" /></td>
	</tr>
	<tr>
		<td class="config_label"><?php echo _("Parent Resource:");?></td>
		<td><input id="parent_resource" class="import_column_num" type="text" maxlength="3" /></td>
	</tr>
	<tr>
		<td class="config_label"><?php echo _("Publisher:");?></td>
		<td><input id="publisher" class="import_column_num" type="text" maxlength="3" /></td>
	</tr>
	<tr>
		<td></td>
		<td><input id="pub_as_org" type="checkbox" /><label class="check_label" for="pub_as_org"><?php echo _("Add publisher as an organization");?></label></td>
	</tr>
	<tr>
		<td class="config_label"><?php echo _("ISSN:");?></td>
		<td><input id="issn" class="import_column_num" type="text" maxlength="3" />
	</tr>
	<tr>
		<td></td>
		<td><input id="multiple_issn" type="checkbox" /><label class="check_label" for="multiple_issn"><?php echo _("Has Multiple ISSNs");?></label><span class="delimited_by"><?php echo _("Delimited by:");?></span><input id="issn_delimiter" class="delimiter" type= "text" /></td>
	</tr>
	<tr>
		<td class="config_label"><?php echo _("ISBN:");?></td>
		<td><input id="isbn" class="import_column_num" type="text" maxlength="3" />
	</tr>
	<tr>
		<td></td>
		<td><input id="multiple_isbn" type="checkbox" /><label class="check_label" for="multiple_isbn"><?php echo _("Has Multiple ISBNs");?></label><span class="delimited_by"><?php echo _("Delimited by:");?></span><input id="isbn_delimiter" class="delimiter" type= "text" /></td>
	</tr>
	<tr>
		<td class="config_label"><?php echo _("Resource Format:");?></td>
		<td><input id="format" class="import_column_num" type="text" maxlength="3" /></td>
	</tr>
	<tr>
		<td></td>
		<td><input id="create_format" type="checkbox" /><label class="check_label" for="create_format"><?php echo _("Add unique formats to defined resource formats");?></label></td>
	</tr>
	<tr>
		<td class="config_label"><?php echo _("Resource Type:");?></td>
		<td><input id="restype" class="import_column_num" type="text" maxlength="3" /></td>
	</tr>
	<tr>
		<td></td>
		<td><input id="create_restype" type="checkbox" /><label class="check_label" for="create_restype"><?php echo _("Add unique types to defined resource types");?></label></td>
	</tr>
	<tr>
		<td class="config_label"><?php echo _("Subject:");?></td>
		<td><input id="subject" class="import_column_num" type="text" maxlength="3" /></td>
	</tr>


	<tr>
		<td></td>
	</tr>
	<tr>
		<td></td>
	</tr>
	<tr>
		<td></td>
	</tr>
	<tr>
		<td></td>
	</tr>
	<tr>
		<td></td>
	</tr>
	<tr>
		<td></td>
	</tr>
</table>
<div id="configuration_group">
	<select id="configuration" size="10">
		<?php
			$dir = './import_configs';
			$files = array_slice(scandir($dir), 2);
			foreach($files as $configuration)
			{
				echo "<option value='" . $configuration . "'>" . $configuration . "</option>";
			}
		?>
	</select>
	<input id="load_configuration" type="button" value="Load Configuration" />
	<input id="save_configuration" type="button" value="Save Configuration" onclick="tb_show(null,'ajax_forms.php?action=getImportSaveCfgForm&height=178&width=260&modal=true',null);" />
	
</div>
<script src="js/import.js"></script>
</html>