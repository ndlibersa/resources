<?php
	session_start();
	include_once 'directory.php';
	$pageTitle=_('Resources import');
	include 'templates/header.php';
?>
<div id="importPage"><h1><?php echo _("Generic Delimited File Import");?></h1>
<?php
	// CSV configuration
	$required_columns = array('titleText' => 0, 'resourceURL' => 0, 'resourceAltURL' => 0, 'parentResource' => 0, 'organization' => 0, 'role' => 0);
	if ($_POST['submit'])
	{
		$delimiter = $_POST['delimiter'];
		$uploaddir = 'attachments/';
		$uploadfile = $uploaddir . basename($_FILES['uploadFile']['name']);
		if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $uploadfile))
		{  
			print '<p>'._("The file has been successfully uploaded.").'</p>';
			// Let's analyze this file
			if (($handle = fopen($uploadfile, "r")) !== FALSE)
			{
				if (($data = fgetcsv($handle, 0, $delimiter)) !== FALSE)
				{
					$columns_ok = true;
					foreach ($data as $key => $value)
					{
						$available_columns[$value] = $key;
	        		} 
				}
				else
				{
					$error = _("Unable to get columns headers from the file");
				}
			}
			else
			{
				$error = _("Unable to open the uploaded file");
			}
		}
		else
		{
			$error = _("Unable to upload the file");
		}
		if ($error)
		{
			print "<p>"._("Error: ").$error.".</p>";
		}
		else
		{
			print "<p>"._("Please choose columns from your CSV file:")."</p>";
			print "<form action=\"generic_import.php\" method=\"post\">";
?>
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
							if($configuration==".gitignore")
							{
								continue;
							}
							echo "<option value='" . $configuration . "'>" . $configuration . "</option>";
						}
					?>
				</select>
				<input id="save_configuration" type="button" value="Save Configuration" onclick="tb_show(null,'ajax_forms.php?action=getImportSaveCfgForm&height=178&width=260&modal=true',null);" />
				
			</div>
			<script src="js/import.js"></script>
<?php
			print "<input type=\"hidden\" name=\"delimiter\" value=\"$delimiter\" />";
			print "<input type=\"hidden\" name=\"uploadfile\" value=\"$uploadfile\" />";
			print "<input type=\"submit\" name=\"matchsubmit\" id=\"matchsubmit\" /></form>";
		}
	}
	elseif ($_POST['matchsubmit'])
	{

	}
	else
	{
?>
		<p><?php echo _("The first line of the CSV file must contain column names, and not data. These names will be used during the import process.");?></p>
		<form enctype="multipart/form-data" action="generic_import.php" method="post" id="importForm">
			<fieldset>
				<legend><?php echo _("File selection");?></legend>
				<label for="uploadFile"><?php echo _("CSV File");?></label>
				<input type="file" name="uploadFile" id="uploadFile" />
			</fieldset>
			<fieldset>
				<legend><?php echo _("Import options");?></legend>
				<label for="CSV delimiter"><?php echo _("CSV delimiter");?></label>
				<select name="delimiter">
					<option value=",">, <?php echo _("(comma)");?></option>
					<option value=";">; <?php echo _("(semicolon)");?></option>
					<option value="|">| <?php echo _("(pipe)");?></option>
				</select>
			</fieldset>
			<input type="submit" name="submit" value="<?php echo _("Upload");?>" />
		</form>
<?php
	}
?>
