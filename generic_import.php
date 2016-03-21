<?php
	function searchForShortName($shortName, $array)
	{
		foreach($array as $key=> $val)
		{
			if(strtolower($val['shortName']) == strtolower($shortName)) {
				return $key;
				break;
			}
		}
		return null;
	}
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
		//get necessary configuration instances
		$importConfigInstanceArray = array();
		$instance = new ImportConfig();
		$importConfigInstanceArray = $instance->allAsArray();
		$orgMappingInstance = new OrgNameMapping();
		$orgMappings=array();

		$configuration=json_decode($instance->configuration,true);

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
			print "<p>"._("Please select the import configuration to load: ")."<select id='importConfiguration'>";
			print "<option value='' disabled selected>"._("Select Configuration")."</option>";
			foreach($importConfigInstanceArray as $importConfiguration)
			{
				print "<option value='".$importConfiguration['importConfigID']."'>".$importConfiguration['shortName']."</option>";
			}
			print "</select></p>";

			print "<p>"._("Please choose columns from your CSV file:")."</p>";
			print "<form id='config_form' action=\"generic_import.php\" method=\"post\">";
?>
			<script type='text/javascript'>
				$('#importConfiguration').change(function (){
					var configID=$(this).val();
					$.ajax({
						 type:       "POST",
						 url:        "ajax_forms.php?action=getImportConfigForm",
						 cache:      false,
						 data:       "configID=" + configID,
						 success:    function(html) {
							$("#configDiv").html(html);
						 }
					});
				});
			</script>
			<div id='configDiv'>
				<?php include 'ajax_forms/getImportConfigForm.php';?>
			</div>
<?php
			print "<input type=\"hidden\" name=\"delimiter\" value=\"$delimiter\" />";
			print "<input type=\"hidden\" name=\"uploadfile\" value=\"$uploadfile\" />";
			print "<input type=\"submit\" name=\"matchsubmit\" id=\"matchsubmit\" /></form>";
?>
			<script type='text/javascript'>
				$('#config_form').submit(function () {
			        var jsonData = {};
			        jsonData.title = $('#resource_titleCol').val();
			        jsonData.alias = [];
			        $('div.alias-record').each(function() {
			            var aliasObject={}
			            aliasObject.column=$(this).find('input').val();
			            aliasObject.aliasType=$(this).find('select').val();
			            jsonData.alias.push(aliasObject);
			        });
			        jsonData.url = $('#resource_urlCol').val();
			        jsonData.altUrl = $("#resource_altUrlCol").val();
			        jsonData.parent = [];
			        $('div#resource_parent').find('input').each(function() {
			            jsonData.parent.push($(this).val());
			        });
			        jsonData.isbnOrIssn = [];
			        $('div#resource_isbnOrIssn').find('input').each(function() {
			            jsonData.isbnOrIssn.push($(this).val());
			        });
			        jsonData.resourceFormat = $("#resource_format").val();
			        jsonData.resourceType = $("#resource_type").val();
			        jsonData.subject = [];
			        $('div#resource_subject').find('input').each(function() {
			            jsonData.subject.push($(this).val());
			        });
			        jsonData.note = [];
			        $('div.note-record').each(function() {
			            var noteObject={}
			            noteObject.column=$(this).find('input').val();
			            noteObject.noteType=$(this).find('select').val();
			            jsonData.note.push(noteObject);
			        });
			        jsonData.organization = [];
			        $('div.organization-record').each(function() {
			            var organizationObject={}
			            organizationObject.column=$(this).find('input').val();
			            organizationObject.organizationRole=$(this).find('select').val();
			            jsonData.organization.push(organizationObject);
			        });
			        var configuration = JSON.stringify(jsonData);
			        var orgNameImported = '';
			        $('.ic-org-imported').each(function() {
			            orgNameImported += $(this).val() + ":::";
			        });

			        var orgNameMapped = '';
			        $('.ic-org-mapped').each(function() {
			            orgNameMapped += $(this).val() + ":::";
			        });

			        var newinput = document.createElement("input");
			        newinput.id = 'jsonData';
			        newinput.name = 'jsonData';
			        newinput.type = "hidden";
			        newinput.value = JSON.stringify(jsonData);
			        document.getElementById("config_form").appendChild(newinput);
				});
			</script>
<?php
		}
	}
	elseif ($_POST['matchsubmit'])
	{
		//get the configuration as a php array
		$jsonData = $_POST['jsonData'];
		$jsonData = json_decode($jsonData,true);

		//Get Columns
		$resourceTitleColumn=intval($jsonData['title'])-1;
		$resourceURLColumn=intval($jsonData['url'])-1;
		$resourceAltURLColumn=intval($jsonData['altUrl'])-1;
		$resourceTypeColumn=intval($jsonData['resourceType'])-1;
		$resourceFormatColumn=intval($jsonData['resourceFormat'])-1;
		error_log($resourceFormatColumn);

		//get all resource formats for output in drop down
		$resourceFormatArray = array();
		$resourceFormatObj = new ResourceFormat();
		$resourceFormatArray = $resourceFormatObj->sortedArray();

		//get all resource types for output in drop down
		$resourceTypeArray = array();
		$resourceTypeObj = new ResourceType();
		$resourceTypeArray = $resourceTypeObj->allAsArray();

		$delimiter = $_POST['delimiter'];
		$deduping_config = explode(',', $config->settings->importISBNDedupingColumns); 
		$uploadfile = $_POST['uploadfile'];
		 // Let's analyze this file
		if (($handle = fopen($uploadfile, "r")) !== FALSE)
		{
			$row = 0;
			$inserted = 0;
			$parentInserted = 0;
			$parentAttached = 0;
		 	$organizationsInserted = 0;
			$organizationsAttached = 0;
			$resourceTypeInserted = 0;
			$arrayOrganizationsCreated = array();
			while (($data = fgetcsv($handle, 0, $delimiter)) !== FALSE)
			{
		    	// Getting column names again for deduping
		    	if ($row == 0)
		    	{
		      		print "<h2>"._("Settings")."</h2>";
		      		print "<p>"._("Importing and deduping isbnOrISSN on the following columns: ") ;
		        	foreach ($data as $key => $value)
		        	{
		          		if (in_array($value, $deduping_config))
		          		{
		            		$deduping_columns[] = $key;
		            		print $value . " ";
						}
					} 
					print ".</p>";
				}
				else
				{
		        	// Deduping
					unset($deduping_values);
					$resource = new Resource(); 
					$resourceObj = new Resource(); 
//					foreach ($deduping_columns as $value)
//					{
//						$deduping_values[] = $data[$value];
//					}
//
//Remove this//
$deduping_count=0;
//					$deduping_count = count($resourceObj->getResourceByIsbnOrISSN($deduping_values));
					if ($deduping_count == 0)
					{
						// Convert to UTF-8
						$data = array_map(function($row) { return mb_convert_encoding($row, 'UTF-8'); }, $data);
		        
						// If Resource Type is mapped, check to see if it exists
						$resourceTypeIndex = null;
						if($jsonData['resourceType'] != '')
						{
							$resourceTypeIndex = searchForShortName($data[$resourceTypeColumn], $resourceTypeArray);
							if($resourceTypeIndex === null && $data[$resourceTypeColumn] != '') //If Resource Type does not exist, add it to the database
							{
								$resourceTypeObj = new ResourceType();
								$resourceTypeObj->shortName = $data[$resourceTypeColumn];
								$resourceTypeObj->save();
								$resourceTypeIndex = $resourceTypeObj->primaryKey;
								$resourceTypeArray = $resourceTypeObj->allAsArray;
								$resourceTypeInserted++;
							}
						}

						// Let's insert data
						$resource->createLoginID    = $loginID;
						$resource->createDate       = date( 'Y-m-d' );
						$resource->updateLoginID    = '';
						$resource->updateDate       = '';
						$resource->titleText        = $data[$resourceTitleColumn];
						$resource->resourceURL      = $data[$resourceURLColumn];
						$resource->resourceAltURL   = $data[$resourceAltURLColumn];
						$resource->resourceTypeID   = $resourceTypeIndex;
						//$resource->providerText     = $data[$_POST['providerText']];
						//$resource->statusID         = 1;
						//$resource->save();
						//$resource->setIsbnOrIssn($deduping_values);
						//$inserted++;
					//remove this
					}
					//
					/*
						// Do we have to create an organization or attach the resource to an existing one?
						if($data[$jsonData['organization']['column']])
						//if ($data[$_POST['organization']])
						{
							$organizationName = $data[$jsonData['organization']['column']];
							$organization = new Organization();
							$organizationRole = new OrganizationRole();
							$organizationID = false;
							if ($config->settings->organizationsModule == 'Y') // If we use the Organizations module
							{
								$dbName = $config->settings->organizationsDatabaseName;
								// Does the organization already exists?
								$query = "SELECT count(*) AS count FROM $dbName.Organization WHERE UPPER(name) = '" . str_replace("'", "''", strtoupper($organizationName)) . "'";
								$result = $organization->db->processQuery($query, 'assoc');
								// If not, we try to create it
								if ($result['count'] == 0)
								{
									$query = "INSERT INTO $dbName.Organization SET createDate=NOW(), createLoginID='$loginID', name='" . $organization->db->escapeString($organizationName) . "'";
									try
									{
										$result = $organization->db->processQuery($query);
										$organizationID = $result;
										$organizationsInserted++;
										array_push($arrayOrganizationsCreated, $organizationName);
									}
									catch (Exception $e)
									{
										print "<p>"._("Organization ").$organizationName._(" could not be added.")."</p>";
									}
              					}
              					// If yes, we attach it to our resource
              					elseif ($result['count'] == 1)
              					{
									$query = "SELECT name, organizationID FROM $dbName.Organization WHERE UPPER(name) = '" . str_replace("'", "''", strtoupper($organizationName)) . "'";
									$result = $organization->db->processQuery($query, 'assoc');
									$organizationID = $result['organizationID'];
									$organizationsAttached++;
								}
								else
								{
									print "<p>"._("Error: more than one organization is called ").$organizationName._(". Please consider deduping.")."</p>";
								}
								if ($organizationID)
								{
									$dbName = $config->settings->organizationsDatabaseName;
									// Get role
									$query = "SELECT organizationRoleID from OrganizationRole WHERE shortName='" . $organization->db->escapeString($data[$jsonData['organization']['organizationRole']]) . "'";
									$result = $organization->db->processQuery($query);
									// If role is not found, fallback to the first one.
									$roleID = ($result[0]) ? $result[0] : 1;
									// Does the organizationRole already exists?
									$query = "SELECT count(*) AS count FROM $dbName.OrganizationRoleProfile WHERE organizationID=$organizationID AND organizationRoleID=$roleID";
									$result = $organization->db->processQuery($query, 'assoc');
									// If not, we try to create it
									if ($result['count'] == 0)
									{
										$query = "INSERT INTO $dbName.OrganizationRoleProfile SET organizationID=$organizationID, organizationRoleID=$roleID";
										try
										{
											$result = $organization->db->processQuery($query);
											if (!in_array($organizationName, $arrayOrganizationsCreated))
											{
												$organizationsInserted++;
												array_push($arrayOrganizationsCreated, $organizationName);
											}
										}
										catch (Exception $e)
										{
											print "<p>"._("Unable to associate organization ").$organizationName._(" with its role.")."</p>";
										}
									}
								}
							}
							else // If we do not use the Organizations module
							{
								// Search if such organization already exists
								$organizationExists = $organization->alreadyExists($organizationName);
								$parentID = null;
								if (!$organizationExists)
								{
									// If not, create it
									$organization->shortName = $organizationName;
									$organization->save();
									$organizationID = $organization->organizationID();
									$organizationsInserted++;
									array_push($arrayOrganizationsCreated, $organizationName);
								}
								elseif ($organizationExists == 1)
								{
									$organizationID = $organization->getOrganizationIDByName($organizationName);
									$organizationsAttached++;
								}
								else
								{
									print "<p>"._("Error: more than one organization is called ").$organizationName._(" Please consider deduping.")."</p>";
								}
								// Find role
								$organizationRoles = $organizationRole->getArray();
								if (($roleID = array_search($data[$jsonData['organization']['organizationRole']], $organizationRoles)) == 0)
								{
									// If role is not found, fallback to the first one.
									$roleID = '1';
								} 
							}
							// Let's link the resource and the organization.
							// (this has to be done whether the module Organization is in use or not)
							if ($organizationID && $roleID)
							{
								$organizationLink = new ResourceOrganizationLink();
								$organizationLink->organizationRoleID = $roleID;
								$organizationLink->resourceID = $resource->resourceID;
								$organizationLink->organizationID = $organizationID;
								$organizationLink->save();
							}
						}
					}

					elseif ($deduping_count == 1)
					{
						$resources = $resourceObj->getResourceByIsbnOrISSN($deduping_values);
						$resource = $resources[0];
					}
					if ($data[$_POST['parentResource']] && ($deduping_count == 0 || $deduping_count == 1) ) // Do we have a parent resource to create?
					{
						// Search if such parent exists
						$numberOfParents = count($resourceObj->getResourceByTitle($data[$_POST['parentResource']]));
						$parentID = null;
						if ($numberOfParents == 0)
						{
							// If not, create parent
							$parentResource = new Resource();
							$parentResource->createLoginID = $loginID;
							$parentResource->createDate    = date( 'Y-m-d' );
							$parentResource->titleText     = $data[$_POST['parentResource']];
							$parentResource->statusID      = 1;
							$parentResource->save();
							$parentID = $parentResource->resourceID;
							$parentInserted++;
						}
						elseif ($numberOfParents == 1)
						{
							// Else, attach the resource to its parent.
							$parentResource = $resourceObj->getResourceByTitle($data[$_POST['parentResource']]);
							$parentID = $parentResource[0]->resourceID;
							$parentAttached++; 
						}
						if ($numberOfParents == 0 || $numberOfParents == 1)
						{
							$resourceRelationship = new ResourceRelationship();
							$resourceRelationship->resourceID = $resource->resourceID;
							$resourceRelationship->relatedResourceID = $parentID;
							$resourceRelationship->relationshipTypeID = '1';  //hardcoded because we're only allowing parent relationships
							if (!$resourceRelationship->exists())
							{
								$resourceRelationship->save();
							}
						}
					}
					*/ 
				}
				$row++;
			}
			print "<h2>"._("Results")."</h2>";
			print "<p>" . ($row - 1) . _(" rows have been processed. ").$inserted._(" rows have been inserted.")."</p>";
			print "<p>".$parentInserted._(" parents have been created. ").$parentAttached._(" resources have been attached to an existing parent.")."</p>";
			print "<p>".$organizationsInserted._(" organizations have been created");
			if (count($arrayOrganizationsCreated) > 0)
			{
				print " (" . implode(',', $arrayOrganizationsCreated) . ")";
			}
			print ". $organizationsAttached"._(" resources have been attached to an existing organization.")."</p>";
		}
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

