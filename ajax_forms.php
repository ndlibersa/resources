<?php

/*
**************************************************************************************************************************
** CORAL Resources Module v. 1.2
**
** Copyright (c) 2010 University of Notre Dame
**
** This file is part of CORAL.
**
** CORAL is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
**
** CORAL is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License along with CORAL.  If not, see <http://www.gnu.org/licenses/>.
**
**************************************************************************************************************************
*/


include_once 'directory.php';
include_once 'user.php';
include_once 'util.php';

switch ($_GET['action']) {



    case 'getNoteForm':
    	$resourceID = $_GET['resourceID'];
    	if (isset($_GET['resourceNoteID'])) $resourceNoteID = $_GET['resourceNoteID']; else $resourceNoteID = '';
		if (isset($_GET['tab'])) $tabName = $_GET['tab']; else $tabName = '';
    	$resourceNote = new ResourceNote(new NamedArguments(array('primaryKey' => $resourceNoteID)));

		//get all note types for output in drop down
		$noteTypeArray = array();
		$noteTypeObj = new NoteType();
		$noteTypeArray = $noteTypeObj->allAsArrayForDD();

		?>
		<div id='div_noteForm'>
		<form id='noteForm'>
		<input type='hidden' name='editResourceID' id='editResourceID' value='<?php echo $resourceID; ?>'>
		<input type='hidden' name='editResourceNoteID' id='editResourceNoteID' value='<?php echo $resourceNoteID; ?>'>
		<input type='hidden' name='tab' id='tab' value='<?php echo $tabName; ?>'>

		<div class='formTitle' style='width:395px;'><span class='headerText' style='margin-left:7px;'><?php if ($resourceNoteID){ echo "Edit Note"; } else { echo "Add Note"; } ?></span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class="surroundBox" style="width:400px;">
		<tr>
		<td>

			<table class='noBorder' style='width:360px; margin:10px 15px;'>
			<tr>
			<td style='vertical-align:top;text-align:left;border:0px;'><label for='noteTypeID'><b>Note Type:</b></label></td>
			<td style='vertical-align:top;text-align:left;border:0px;'>

			<select name='noteTypeID' id='noteTypeID'>
			<option value=''></option>
			<?php
			foreach ($noteTypeArray as $noteType){
				if (!(trim(strval($noteType['noteTypeID'])) != trim(strval($resourceNote->noteTypeID)))){
					echo "<option value='" . $noteType['noteTypeID'] . "' selected>" . $noteType['shortName'] . "</option>\n";
				}else{
					echo "<option value='" . $noteType['noteTypeID'] . "'>" . $noteType['shortName'] . "</option>\n";
				}
			}
			?>
			</select>

			</td>
			</tr>

			<tr>
			<td style='vertical-align:top;text-align:left;'><label for='noteText'><b>Notes:</b></label></td>
			<td><textarea rows='5' id='noteText' name='noteText' style='width:270px'><?php echo $resourceNote->noteText; ?></textarea><span class='smallDarkRedText' id='span_error_noteText'></span></td>
			</td>
			</tr>
			</table>

		</td>
		</tr>
		</table>

		<br />
		<table class='noBorderTable' style='width:125px;'>
			<tr>
				<td style='text-align:left'><input type='button' value='submit' name='submitResourceNoteForm' id ='submitResourceNoteForm'></td>
				<td style='text-align:right'><input type='button' value='cancel' onclick="tb_remove()"></td>
			</tr>
		</table>


		</form>
		</div>

		<script type="text/javascript" src="js/forms/resourceNoteForm.js?random=<?php echo rand(); ?>"></script>

		<?php

        break;




    case 'getNewResourceForm':

		$resourceID = $_GET['resourceID'];
		if ($resourceID){
    		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));
		}else{
			$resource = new Resource();
		}

		//used for default currency
		$config = new Configuration();

		//get all acquisition types for output in drop down
		$acquisitionTypeArray = array();
		$acquisitionTypeObj = new AcquisitionType();
		$acquisitionTypeArray = $acquisitionTypeObj->sortedArray();

		//get all resource formats for output in drop down
		$resourceFormatArray = array();
		$resourceFormatObj = new ResourceFormat();
		$resourceFormatArray = $resourceFormatObj->sortedArray();

		//get all resource types for output in drop down
		$resourceTypeArray = array();
		$resourceTypeObj = new ResourceType();
		$resourceTypeArray = $resourceTypeObj->allAsArray();


		//get all currency for output in drop down
		$currencyArray = array();
		$currencyObj = new Currency();
		$currencyArray = $currencyObj->allAsArray();

		//get all Order Types for output in drop down
		$orderTypeArray = array();
		$orderTypeObj = new OrderType();
		$orderTypeArray = $orderTypeObj->allAsArray();


		//get payments
		$paymentArray = array();
		if ($resourceID){
			$sanitizedInstance = array();
			$instance = new ResourcePayment();
			foreach ($resource->getResourcePayments() as $instance) {
				foreach (array_keys($instance->attributeNames) as $attributeName) {
					$sanitizedInstance[$attributeName] = $instance->$attributeName;
				}

				$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;

				array_push($paymentArray, $sanitizedInstance);
			}
		}

		//get notes
		if ($resourceID){
			$resourceNote = $resource->getInitialNote;
		}else{
			$resourceNote = new ResourceNote();
		}

		$orgArray = $resource->getOrganizationArray();
		if (count($orgArray)>0){
			foreach ($orgArray as $org){
				$providerText = $org['organization'];
				$orgID = $org['organizationID'];
			}
		}else{
			$providerText = $resource->providerText;
			$orgID = '';
		}

		?>
		<div id='div_resourceSubmitForm'>
		<form id='resourcePromptForm'>


		<input type='hidden' id='organizationID' value='<?php echo $orgID; ?>' />
		<input type='hidden' id='editResourceID' value='<?php echo $resourceID; ?>' />
		<div class='formTitle' style='width:745px;'><span class='headerText'><?php if ($resourceID) { echo "Edit Saved Resource"; }else{ echo "Add New Resource"; } ?></span></div>
		<div class='smallDarkRedText' style='height:14px;margin:3px 0px 0px 0px;'>&nbsp;* required fields</div>

		<table class='noBorder'>
		<tr style='vertical-align:top;'>
		<td style='vertical-align:top; padding-right:35px;'>

			<span class='surroundBoxTitle'>&nbsp;&nbsp;<b>Product</b>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:350px;'>
			<tr>
			<td>

				<table class='noBorder' style='width:310px; margin:5px 15px;'>

					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='titleText'>Name:&nbsp;&nbsp;<span class='bigDarkRedText'>*</span></label></td>
					<td><input type='text' id='titleText' style='width:220px;' class='changeInput' value="<?php echo $resource->titleText; ?>" /><span id='span_error_titleText' class='smallDarkRedText'></span></td>
					</tr>

					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='descriptionText'>Description:</label></td>
					<td><textarea rows='3' id='descriptionText' style='width:223px'><?php echo $resource->descriptionText; ?></textarea></td>
					</tr>

					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='providerText'>Provider:</label></td>
					<td><input type='text' id='providerText' style='width:220px;' class='changeInput' value='<?php echo $providerText; ?>' /><span id='span_error_providerText' class='smallDarkRedText'></span></td>
					</tr>

					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='resourceURL'>URL:</label></td>
					<td><input type='text' id='resourceURL' style='width:220px;' class='changeInput' value='<?php echo $resource->resourceURL; ?>' /><span id='span_error_resourceURL' class='smallDarkRedText'></span></td>
					</tr>

					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='resourceAltURL'>Alt URL:</label></td>
					<td><input type='text' id='resourceAltURL' style='width:220px;' class='changeInput' value='<?php echo $resource->resourceAltURL; ?>' /><span id='span_error_resourceAltURL' class='smallDarkRedText'></span></td>
					</tr>

				</table>
			</td>
			</tr>
			</table>



			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='resourceFormatID'><b>Format</b></label>&nbsp;<span class='bigDarkRedText'>*</span>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:350px;'>
			<tr>
			<td>

				<table class='noBorder' style='width:310px; margin:5px 15px;'>
				<?php
				$i=0;

				foreach ($resourceFormatArray as $resourceFormat){
					$i++;
					if(($i % 2)==1){
						echo "<tr>\n";
					}

					//determine default
					if ($resourceID){
						if ($resourceFormat['resourceFormatID'] == $resource->resourceFormatID) $checked = 'checked'; else $checked = '';
					//otherwise default to electronic
					}else{
						if (strtoupper($resourceFormat['shortName']) == 'ELECTRONIC') $checked = 'checked'; else $checked = '';
					}

					echo "<td><input type='radio' name='resourceFormatID' id='resourceFormatID' value='" . $resourceFormat['resourceFormatID'] . "' " . $checked . " />  " . $resourceFormat['shortName'] . "</td>\n";

					if(($i % 2)==0){
						echo "</tr>\n";
					}
				}

				if(($i % 2)==1){
					echo "<td>&nbsp;</td></tr>\n";
				}

				?>

				</table>

			</td>
			</tr>
			</table>


			<span class='surroundBoxTitle'>&nbsp;&nbsp;<b>Acquisition Type</b>&nbsp;<span class='bigDarkRedText'>*</span>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:350px;'>
			<tr>
			<td>

				<table class='noBorder smallPadding' style='width:310px; margin:5px 15px;'>
				<?php
				$i=0;

				foreach ($acquisitionTypeArray as $acquisitionType){
					$i++;
					if(($i % 3)==1){
						echo "<tr>\n";
					}

					//set default
					if ($resourceID){
						if ($acquisitionType['acquisitionTypeID'] == $resource->acquisitionTypeID) $checked = 'checked'; else $checked = '';
					}else{
						if (strtoupper($acquisitionType['shortName']) == 'PAID') $checked = 'checked'; else $checked = '';
					}

					echo "<td><input type='radio' name='acquisitionTypeID' id='acquisitionTypeID' value='" . $acquisitionType['acquisitionTypeID'] . "' " . $checked . " />  " . $acquisitionType['shortName'] . "</td>\n";

					if(($i % 3)==0){
						echo "</tr>\n";
					}
				}

				if(($i % 3)==1){
					echo "<td>&nbsp;</td><td>&nbsp;</td></tr>\n";
				}else if(($i % 3)==2){
					echo "<td>&nbsp;</td></tr>\n";
				}

				?>

				</table>

			</td>
			</tr>
			</table>

		</td>
		<td>

			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='resourceFormatID'><b>Cost</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:350px;'>
			<tr>
			<td>


				<table class='noBorder smallPadding newPaymentTable' style='width:320px;margin:7px 15px 0px 15px;'>
				<tr>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'>Fund:</td>
					<td style='vertical-align:top;text-align:left;font-weight:bold;' colspan='2'>Price:</td>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'>Type:</td>
					<td>&nbsp;</td>
				</tr>


				<tr class='newPaymentTR'>

				<td style='vertical-align:top;text-align:left;background:white;'>
				<input type='text' value = '' style='width:60px;' class='changeDefaultWhite changeInput fundName' />
				</td>

				<td style='vertical-align:top;text-align:left;background:white;'>
				<input type='text' value = '' style='width:50px;' class='changeDefaultWhite changeInput paymentAmount' />
				</td>


				<td style='vertical-align:top;text-align:left;'>
					<select style='width:50px; padding:0px; margin:0px;' class='changeSelect currencyCode'>
					<?php
					foreach ($currencyArray as $currency){
						if ($currency['currencyCode'] == $config->settings->defaultCurrency){
							echo "<option value='" . $currency['currencyCode'] . "' selected class='changeSelect'>" . $currency['currencyCode'] . "</option>\n";
						}else{
							echo "<option value='" . $currency['currencyCode'] . "' class='changeSelect'>" . $currency['currencyCode'] . "</option>\n";
						}
					}
					?>
					</select>
				</td>


				<td style='vertical-align:top;text-align:left;'>
					<select style='width:70px;' class='changeSelect orderTypeID'>
					<option value='' selected></option>
					<?php
					foreach ($orderTypeArray as $orderType){
						echo "<option value='" . $orderType['orderTypeID'] . "'>" . $orderType['shortName'] . "</option>\n";
					}
					?>
					</select>
				</td>



				<td style='vertical-align:center;text-align:left;width:37px;'>
				<a href='javascript:void();'><img src='images/add.gif' class='addPayment' alt='add this payment' title='add payment'></a>
				</td>
				</tr>

				</table>



				<table class='noBorder smallPadding paymentTable' style='width:320px;margin:7px 15px;'>

				<tr>
				<td colspan='5'>
				<div class='smallDarkRedText' id='div_errorPayment' style='margin:0px 20px 0px 26px;'></div>

				<hr style='width:280px;margin:0px 0px 5px 5px;' />
				</td>
				</tr>


				<?php
				if (count($paymentArray) > 0){

					foreach ($paymentArray as $payment){
					?>
						<tr>
						<td style='vertical-align:top;text-align:left;'>
						<input type='text' value = '<?php echo $payment['fundName']; ?>' style='width:60px;' class='changeInput fundName' />
						</td>

						<td style='vertical-align:top;text-align:left;'>
						<input type='text' value = '<?php echo integer_to_cost($payment['paymentAmount']); ?>' style='width:50px;' class='changeInput paymentAmount' />
						</td>

						<td style='vertical-align:top;text-align:left;'>
							<select style='width:50px;' class='changeSelect currencyCode'>
							<?php
							foreach ($currencyArray as $currency){
								if ($currency['currencyCode'] == $payment['currencyCode']){
									echo "<option value='" . $currency['currencyCode'] . "' selected class='changeSelect'>" . $currency['currencyCode'] . "</option>\n";
								}else{
									echo "<option value='" . $currency['currencyCode'] . "' class='changeSelect'>" . $currency['currencyCode'] . "</option>\n";
								}
							}
							?>
							</select>
						</td>

						<td style='vertical-align:top;text-align:left;'>
						<select style='width:70px;' class='changeSelect orderTypeID'>
						<option value=''></option>
						<?php
						foreach ($orderTypeArray as $orderType){
							if (!(trim(strval($orderType['orderTypeID'])) != trim(strval($payment['orderTypeID'])))){
								echo "<option value='" . $orderType['orderTypeID'] . "' selected class='changeSelect'>" . $orderType['shortName'] . "</option>\n";
							}else{
								echo "<option value='" . $orderType['orderTypeID'] . "' class='changeSelect'>" . $orderType['shortName'] . "</option>\n";
							}
						}
						?>
						</select>
						</td>


						<td style='vertical-align:top;text-align:center;width:37px;'>
							<a href='javascript:void();'><img src='images/cross.gif' alt='remove this payment' title='remove this payment' class='remove' /></a>
						</td>
						</tr>

					<?php
					}

				}

				?>

				</table>


			</td>
			</tr>
			</table>



			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='resourceTypeID'><b>Resource Type</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:350px;'>
			<tr>
			<td>

				<table class='noBorder' style='width:320px; margin:5px 15px;'>
				<?php
				$i=0;

				foreach ($resourceTypeArray as $resourceType){
					$i++;
					if(($i % 3)==1){
						echo "<tr>\n";
					}

					$checked='';
					//determine default checked
					if ($resourceID){
						if (strtoupper($resourceType['resourceTypeID']) == $resource->resourceTypeID) $checked = 'checked';
					}

					echo "<td><input type='radio' name='resourceTypeID' id='resourceTypeID' value='" . $resourceType['resourceTypeID'] . "'" . $checked . "/>  " . $resourceType['shortName'] . "</td>\n";

					if(($i % 3)==0){
						echo "</tr>\n";
					}
				}

				if(($i % 3)==1){
					echo "<td>&nbsp;</td><td>&nbsp;</td></tr>\n";
				}else if(($i % 3)==2){
					echo "<td>&nbsp;</td></tr>\n";
				}

				?>

				</table>
				<span id='span_error_resourceTypeID' class='smallDarkRedText'></span>

			</td>
			</tr>
			</table>



			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='resourceFormatID'><b>Notes</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:350px;'>
			<tr>
			<td>

				<table class='noBorder smallPadding' style='width:320px; margin:7px 15px;'>

					<tr>
					<td style='vertical-align:top;text-align:left;'><span class='smallGreyText'>Include any additional information</span><br />
					<textarea rows='5' id='noteText' name='noteText' style='width:310px'><?php echo $resourceNote->noteText; ?></textarea></td>
					</tr>
				</table>
			</td>
			</tr>
			</table>

		</td>
		</tr>
		</table>

		<hr style='width:745px;margin:15px 0px 10px 0px;' />

		<table class='noBorderTable' style='width:175px;'>
			<tr>
				<td style='text-align:left'><input type='button' value='save' class='submitResource' id ='save'></td>
				<td style='text-align:left'><input type='button' value='submit' class='submitResource' id ='progress'></td>
				<td style='text-align:left'><input type='button' value='cancel' onclick="kill(); tb_remove()"></td>
			</tr>
		</table>


		</form>
		</div>

		<script type="text/javascript" src="js/forms/resourceNewForm.js?random=<?php echo rand(); ?>"></script>

		<?php

        break;






    case 'getUpdateProductForm':
    	$resourceID = $_GET['resourceID'];
    	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));


		if (normalize_date($resource->archiveDate) != '') {
			$archiveChecked = 'checked';
		}else{
			$archiveChecked = '';
		}


		//get all resource formats for output in drop down
		$resourceFormatArray = array();
		$resourceFormatObj = new ResourceFormat();
		$resourceFormatArray = $resourceFormatObj->sortedArray();

		//get all resource types for output in drop down
		$resourceTypeArray = array();
		$resourceTypeObj = new ResourceType();
		$resourceTypeArray = $resourceTypeObj->allAsArray();

		//only returns one - ResourceRelationship object
		$resourceRelationship = new ResourceRelationship();
		$resourceRelationship = $resource->getParentResource();
		$parentResource = new Resource(new NamedArguments(array('primaryKey' => $resourceRelationship->relatedResourceID)));

		//get all alias types for output in drop down
		$aliasTypeArray = array();
		$aliasTypeObj = new AliasType();
		$aliasTypeArray = $aliasTypeObj->allAsArray();


		//get aliases
		$sanitizedInstance = array();
		$instance = new Alias();
		$aliasArray = array();
		foreach ($resource->getAliases() as $instance) {
			foreach (array_keys($instance->attributeNames) as $attributeName) {
				$sanitizedInstance[$attributeName] = $instance->$attributeName;
			}

			$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;

			$aliasType = new AliasType(new NamedArguments(array('primaryKey' => $instance->aliasTypeID)));
			$sanitizedInstance['aliasTypeShortName'] = $aliasType->shortName;

			array_push($aliasArray, $sanitizedInstance);
		}


		//get all organization roles for output in drop down
		$organizationRoleArray = array();
		$organizationRoleObj = new OrganizationRole();
		$organizationRoleArray = $organizationRoleObj->getArray();


		//get organizations (already returned in an array)
		$orgArray = $resource->getOrganizationArray();




		?>
		<div id='div_resourceForm'>
		<form id='resourceForm'>
		<input type='hidden' name='editResourceID' id='editResourceID' value='<?php echo $resourceID; ?>'>

		<div class='formTitle' style='width:715px; margin-bottom:5px;position:relative;'><span class='headerText'>Edit Resource</span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class='noBorder' style='width:100%;'>
		<tr style='vertical-align:top;'>
		<td style='vertical-align:top;position:relative;' colspan='2'>


			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='resourceFormatID'><b>Product</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:710px;'>
			<tr>
			<td>
				<table class='noBorder' style='width:670px; margin:15px 20px 10px 20px;'>
				<tr>
				<td style='width:360px;'>
					<table>
					<tr>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='titleText'>Name:</label></td>
					<td><input type='text' id='titleText' name='titleText' value = "<?php echo $resource->titleText; ?>" style='width:260px;' class='changeInput' /><span id='span_error_titleText' class='smallDarkRedText'></span></td>
					</tr>

					<tr>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='descriptionText'>Description:</label></td>
					<td><textarea rows='4' id='descriptionText' name='descriptionText' style='width:260px' class='changeInput' ><?php echo $resource->descriptionText; ?></textarea></td>
					</tr>

					<tr>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='resourceURL'>URL:</label></td>
					<td><input type='text' id='resourceURL' name='resourceURL' value = '<?php echo $resource->resourceURL; ?>' style='width:260px;' class='changeInput'  /></td>
					</tr>

					<tr>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='resourceAltURL'>Alt URL:</label></td>
					<td><input type='text' id='resourceAltURL' name='resourceAltURL' value = '<?php echo $resource->resourceAltURL; ?>' style='width:260px;' class='changeInput'  /></td>
					</tr>

					</table>

				</td>
				<td>
					<table>


					<tr>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='titleText'>Parent:</label></td>
					<td>

					<input type='text' id='parentResourceName' name='parentResourceName' value = '<?php echo $parentResource->titleText; ?>' style='width:180px;' class='changeInput' /><span id='span_error_parentResourceName' class='smallDarkRedText'></span></td>
					<input type='hidden' id='parentResourceID' name='parentResourceID' value = '<?php echo $resourceRelationship->relatedResourceID; ?>' />

					</td>
					</tr>

					<tr>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='isbnOrISSN'>ISSN / ISBN:</label></td>
					<td><input type='text' id='isbnOrISSN' name='isbnOrISSN' value = '<?php echo $resource->isbnOrISSN; ?>' style='width:97px;' class='changeInput'  /><span id='span_errors_isbnOrISSN' class='smallDarkRedText'></span></td>
					</tr>


					<tr>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='resourceFormatID'>Format:</label></td>
					<td>
					<select name='resourceFormatID' id='resourceFormatID' style='width:100px;' class='changeSelect'>
					<option value=''></option>
					<?php
					foreach ($resourceFormatArray as $resourceFormat){
						if (!(trim(strval($resourceFormat['resourceFormatID'])) != trim(strval($resource->resourceFormatID)))){
							echo "<option value='" . $resourceFormat['resourceFormatID'] . "' selected>" . $resourceFormat['shortName'] . "</option>\n";
						}else{
							echo "<option value='" . $resourceFormat['resourceFormatID'] . "'>" . $resourceFormat['shortName'] . "</option>\n";
						}
					}
					?>
					</select>
					</td>
					</tr>


					<tr>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='resourceTypeID'>Type:</label></td>
					<td>
					<select name='resourceTypeID' id='resourceTypeID' style='width:100px;' class='changeSelect' >
					<option value=''></option>
					<?php
					foreach ($resourceTypeArray as $resourceType){
						if (!(trim(strval($resourceType['resourceTypeID'])) != trim(strval($resource->resourceTypeID)))){
							echo "<option value='" . $resourceType['resourceTypeID'] . "' selected>" . $resourceType['shortName'] . "</option>\n";
						}else{
							echo "<option value='" . $resourceType['resourceTypeID'] . "'>" . $resourceType['shortName'] . "</option>\n";
						}
					}
					?>
					</select>

					</td>
					</tr>

					<tr>
					<td style='text-align:left'><label for='archiveInd'><b>Archived:</b></label></td>
					<td>
					<input type='checkbox' id='archiveInd' name='archiveInd' <?php echo $archiveChecked; ?> />
					</td>
					</tr>

					</table>
				</td>
				</tr>
				</table>
			</td>
			</tr>
			</table>

			<div style='height:10px;'>&nbsp;</div>

			</td>
			</tr>
			<tr style='vertical-align:top;'>
			<td>

			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='resourceFormatID'><b>Organizations</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:380px;'>
			<tr>
			<td>

				<table class='noBorder smallPadding newOrganizationTable' style='width:330px;  margin:15px 20px 0px 20px;'>
				<tr>
					<td style='vertical-align:top;text-align:left;font-weight:bold;width:103px;'>Role:</td>
					<td style='vertical-align:top;text-align:left;font-weight:bold;width:160px;'>Organization:</td>
					<td>&nbsp;</td>
				</tr>

				<tr class='newOrganizationTR'>
				<td style='vertical-align:top;text-align:left;'>
					<select style='width:100px; background:#f5f8fa;' class='changeSelect organizationRoleID'>
					<option value=''></option>
					<?php
					foreach ($organizationRoleArray as $organizationRoleID => $organizationRoleShortName){
						echo "<option value='" . $organizationRoleID . "'>" . $organizationRoleShortName . "</option>\n";
					}
					?>
					</select>
				</td>

				<td style='vertical-align:top;text-align:left;'>
				<input type='text' value = '' style='width:160px;background:#f5f8fa;' class='changeAutocomplete organizationName' />
				<input type='hidden' class='organizationID' value = '' />
				</td>

				<td style='vertical-align:top;text-align:left;width:40px;'>
				<a href='javascript:void();'><img src='images/add.gif' class='addOrganization' alt='add organization' title='add organization'></a>
				</td>
				</tr>
				</table>
				<div class='smallDarkRedText' id='div_errorOrganization' style='margin:0px 20px 7px 26px;'></div>

				<table class='noBorder smallPadding organizationTable' style='width:330px;margin:0px 20px 10px 20px;'>
				<tr>
				<td colspan='3'>
					<hr style='width:310px;margin:0px 0px 5px 5px;' />
				</td>
				</tr>

				<?php
				if (count($orgArray) > 0){

					foreach ($orgArray as $organization){
					?>
						<tr>
						<td style='vertical-align:top;text-align:left;'>
						<select style='width:100px;' class='organizationRoleID changeSelect'>
						<option value=''></option>
						<?php
						foreach ($organizationRoleArray as $organizationRoleID => $organizationRoleShortName){
							if (!(trim(strval($organizationRoleID)) != trim(strval($organization['organizationRoleID'])))){
								echo "<option value='" . $organizationRoleID . "' selected>" . $organizationRoleShortName . "</option>\n";
							}else{
								echo "<option value='" . $organizationRoleID . "'>" . $organizationRoleShortName . "</option>\n";
							}
						}
						?>
						</select>
						</td>

						<td style='vertical-align:top;text-align:left;'>
						<input type='text' class='changeInput organizationName' value = '<?php echo $organization['organization']; ?>' style='width:160px;' class='changeInput' />
						<input type='hidden' class='organizationID' value = '<?php echo $organization['organizationID']; ?>' />
						</td>

						<td style='vertical-align:top;text-align:left;width:40px;'>
							<a href='javascript:void();'><img src='images/cross.gif' alt='remove organization' title='remove <?php echo $resourceOrganization['shortName']; ?> organization' class='remove' /></a>
						</td>

						</tr>

					<?php
					}
				}

				?>

				</table>



			</td>
			</tr>
			</table>

		</td>
		<td>

			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='resourceFormatID'><b>Aliases</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:300px;'>
			<tr>
			<td>

				<table class='noBorder smallPadding newAliasTable' style='width:260px; margin:15px 20px 0px 20px;'>
				<tr>
					<td style='vertical-align:top;text-align:left;font-weight:bold;width:98px;'>Type:</td>
					<td style='vertical-align:top;text-align:left;font-weight:bold;width:125px;'>Alias:</td>
					<td>&nbsp;</td>
				</tr>


				<tr class='newAliasTR'>
				<td style='vertical-align:top;text-align:left;'>
					<select style='width:98px; background:#f5f8fa;' class='changeSelect aliasTypeID'>
					<option value='' selected></option>
					<?php
					foreach ($aliasTypeArray as $aliasType){
						echo "<option value='" . $aliasType['aliasTypeID'] . "' class='changeSelect'>" . $aliasType['shortName'] . "</option>\n";
					}
					?>
					</select>
				</td>

				<td style='vertical-align:top;text-align:left;'>
				<input type='text' value = '' style='width:125px;' class='changeDefault aliasName' />
				</td>

				<td style='vertical-align:center;text-align:left;width:37px;'>
				<a href='javascript:void();'><img src='images/add.gif' class='addAlias' alt='add this alias' title='add alias'></a>
				</td>
				</tr>
				</table>
				<div class='smallDarkRedText' id='div_errorAlias' style='margin:0px 20px 7px 26px;'></div>


				<table class='noBorder smallPadding aliasTable' style='width:260px; margin:0px 20px 10px 20px;'>
				<tr>
				<td colspan='3'>
				<hr style='width:240px;margin:0px 0px 5px 5px;' />
				</td>
				</tr>

				<?php
				if (count($aliasArray) > 0){

					foreach ($aliasArray as $resourceAlias){
					?>
						<tr>
						<td style='vertical-align:top;text-align:left;'>
						<select style='width:98px;' class='changeSelect aliasTypeID'>
						<option value=''></option>
						<?php
						foreach ($aliasTypeArray as $aliasType){
							if (!(trim(strval($aliasType['aliasTypeID'])) != trim(strval($resourceAlias['aliasTypeID'])))){
								echo "<option value='" . $aliasType['aliasTypeID'] . "' selected class='changeSelect'>" . $aliasType['shortName'] . "</option>\n";
							}else{
								echo "<option value='" . $aliasType['aliasTypeID'] . "' class='changeSelect'>" . $aliasType['shortName'] . "</option>\n";
							}
						}
						?>
						</select>
						</td>

						<td style='vertical-align:top;text-align:left;'>

						<input type='text' value = '<?php echo htmlentities($resourceAlias['shortName'], ENT_QUOTES); ?>' style='width:125px;' class='changeInput aliasName' />
						</td>

						<td style='vertical-align:top;text-align:left;width:37px;'>
							<a href='javascript:void();'><img src='images/cross.gif' alt='remove this alias' title='remove this alias' class='remove' /></a>
						</td>
						</tr>

					<?php
					}

				}

				?>

				</table>




			</td>
			</tr>
			</table>

		</td>
		</tr>
		</table>


		<hr style='width:715px;margin:15px 0px 10px 0px;' />

		<table class='noBorderTable' style='width:125px;'>
			<tr>
				<td style='text-align:left'><input type='button' value='submit' name='submitProductChanges' id ='submitProductChanges'></td>
				<td style='text-align:right'><input type='button' value='cancel' onclick="kill(); tb_remove();"></td>
			</tr>
		</table>


		<script type="text/javascript" src="js/forms/resourceUpdateForm.js?random=<?php echo rand(); ?>"></script>

		<?php

        break;







    case 'getOrderForm':
    	$resourceID = $_GET['resourceID'];
    	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		//used to get default currency
		$config = new Configuration();

		$startDate=normalize_date($resource->currentStartDate);
		$endDate=normalize_date($resource->currentEndDate);

		//get all purchase sites for output in checkboxes
		$purchaseSiteArray = array();
		$purchaseSiteObj = new PurchaseSite();
		$purchaseSiteArray = $purchaseSiteObj->allAsArray();


		//get all acquisition types for output in drop down
		$acquisitionTypeArray = array();
		$acquisitionTypeObj = new AcquisitionType();
		$acquisitionTypeArray = $acquisitionTypeObj->allAsArray();

		//get all currency for output in drop down
		$currencyArray = array();
		$currencyObj = new Currency();
		$currencyArray = $currencyObj->allAsArray();

		//get purchase sites
		$sanitizedInstance = array();
		$instance = new PurchaseSite();
		$resourcePurchaseSiteArray = array();
		foreach ($resource->getResourcePurchaseSites() as $instance) {
			$resourcePurchaseSiteArray[] = $instance->purchaseSiteID;
		}


		//get payments
		$sanitizedInstance = array();
		$instance = new ResourcePayment();
		$paymentArray = array();
		foreach ($resource->getResourcePayments() as $instance) {
			foreach (array_keys($instance->attributeNames) as $attributeName) {
				$sanitizedInstance[$attributeName] = $instance->$attributeName;
			}

			$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;

			array_push($paymentArray, $sanitizedInstance);
		}


		//get all Order Types for output in drop down
		$orderTypeArray = array();
		$orderTypeObj = new OrderType();
		$orderTypeArray = $orderTypeObj->allAsArray();


		?>
		<div id='div_resourceForm'>
		<form id='resourceForm'>
		<input type='hidden' name='editResourceID' id='editResourceID' value='<?php echo $resourceID; ?>'>

		<div class='formTitle' style='width:740px; margin-bottom:5px;'><span class='headerText'>Edit Acquisitions Information</span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class='noBorder' style='width:735px;'>
		<tr style='vertical-align:top;'>
		<td style='vertical-align:top; padding-right:35px;'>


			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='orderInformation'><b>Order</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:350px;'>
			<tr>
			<td>
				<table class='noBorder' style='width:310px; margin:15px 20px;'>
				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold'><label for='acquisitionTypeID'>Acquisition Type:</label></td>
				<td>
				<select name='acquisitionTypeID' id='acquisitionTypeID' style='width:100px;' class='changeSelect'>
				<option value=''></option>
				<?php
				foreach ($acquisitionTypeArray as $acquisitionType){
					if (!(trim(strval($acquisitionType['acquisitionTypeID'])) != trim(strval($resource->acquisitionTypeID)))){
						echo "<option value='" . $acquisitionType['acquisitionTypeID'] . "' selected>" . $acquisitionType['shortName'] . "</option>\n";
					}else{
						echo "<option value='" . $acquisitionType['acquisitionTypeID'] . "'>" . $acquisitionType['shortName'] . "</option>\n";
					}
				}
				?>
				</select>
				</td>
				</tr>

				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold'><label for='orderNumber'>Order Number:</label></td>
				<td><input type='text' id='orderNumber' name='orderNumber' value = '<?php echo $resource->orderNumber; ?>' style='width:95px;' class='changeInput' /></td>
				</tr>

				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold'><label for='systemNumber'>System Number:</label></td>
				<td><input type='text' id='systemNumber' name='systemNumber' value = '<?php echo $resource->systemNumber; ?>' style='width:95px;' class='changeInput' /></td>
				</tr>

				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold'><label for='currentStartDate'>Sub Start:</label></td>
				<td><input class='date-pick' id='currentStartDate' name='currentStartDate' value = '<?php echo $startDate; ?>' style='width:75px;' /></td>
				</tr>

				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold'><label for='currentEndDate'>Current Sub End:</label></td>
				<td><input class='date-pick' id='currentEndDate' name='currentEndDate' value = '<?php echo $endDate; ?>' style='width:75px;' />
				</td>
				</tr>

				<?php if ($config->settings->enableAlerts == 'Y'){ ?>
				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold'>&nbsp;</td>
				<td>
				<div class="checkboxes" style='text-align:left;'>
					<label><input id='subscriptionAlertEnabledInd' type='checkbox' style='text-align:bottom' value='1' <?php if($resource->subscriptionAlertEnabledInd == 1) { echo "checked"; } ?> />&nbsp;<span>Enable Alert</span></label>
				</div>
				</td>
				</tr>
				<?php } ?>

				</table>

			</td>
			</tr>
			</table>


		</td>
		<td>

			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='resourcePayments'><b>Cost</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:340px;'>
			<tr>
			<td>

				<table class='noBorder smallPadding newPaymentTable' style='width:320px;margin:7px 15px 0px 15px;'>
				<tr>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'>Fund:</td>
					<td style='vertical-align:top;text-align:left;font-weight:bold;' colspan='2'>Price:</td>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'>Type:</td>
					<td>&nbsp;</td>
				</tr>


				<tr class='newPaymentTR'>

				<td style='vertical-align:top;text-align:left;background:white;'>
				<input type='text' value = '' style='width:70px;' class='changeDefaultWhite changeInput fundName' />
				</td>

				<td style='vertical-align:top;text-align:left;background:white;'>
				<input type='text' value = '' style='width:50px;' class='changeDefaultWhite changeInput paymentAmount' />
				</td>


				<td style='vertical-align:top;text-align:left;'>
					<select style='width:50px;' class='changeSelect currencyCode'>
					<?php
					foreach ($currencyArray as $currency){
						if ($currency['currencyCode'] == $config->settings->defaultCurrency){
							echo "<option value='" . $currency['currencyCode'] . "' selected class='changeSelect'>" . $currency['currencyCode'] . "</option>\n";
						}else{
							echo "<option value='" . $currency['currencyCode'] . "' class='changeSelect'>" . $currency['currencyCode'] . "</option>\n";
						}
					}
					?>
					</select>
				</td>


				<td style='vertical-align:top;text-align:left;'>
					<select style='width:70px;' class='changeSelect orderTypeID'>
					<option value='' selected></option>
					<?php
					foreach ($orderTypeArray as $orderType){
						echo "<option value='" . $orderType['orderTypeID'] . "'>" . $orderType['shortName'] . "</option>\n";
					}
					?>
					</select>
				</td>

				<td style='vertical-align:center;text-align:center;width:37px;'>
				<a href='javascript:void();'><img src='images/add.gif' class='addPayment' alt='add this payment' title='add payment'></a>
				</td>
				</tr>

				</table>



				<table class='noBorder smallPadding paymentTable' style='width:320px;margin:7px 15px;'>

				<tr>
				<td colspan='5'>
				<div class='smallDarkRedText' id='div_errorPayment' style='margin:0px 20px 0px 26px;'></div>

				<hr style='width:300px;margin:0px 0px 5px 5px;' />
				</td>
				</tr>

				<?php
				if (count($paymentArray) > 0){

					foreach ($paymentArray as $payment){
					?>
						<tr>
						<td style='vertical-align:top;text-align:left;'>
						<input type='text' value = '<?php echo $payment['fundName']; ?>' style='width:70px;' class='changeInput fundName' />
						</td>

						<td style='vertical-align:top;text-align:left;'>
						<input type='text' value = '<?php echo integer_to_cost($payment['paymentAmount']); ?>' style='width:50px;' class='changeInput paymentAmount' />
						</td>

						<td style='vertical-align:top;text-align:left;'>
							<select style='width:50px;' class='changeSelect currencyCode'>
							<?php
							foreach ($currencyArray as $currency){
								if ($currency['currencyCode'] == $payment['currencyCode']){
									echo "<option value='" . $currency['currencyCode'] . "' selected class='changeSelect'>" . $currency['currencyCode'] . "</option>\n";
								}else{
									echo "<option value='" . $currency['currencyCode'] . "' class='changeSelect'>" . $currency['currencyCode'] . "</option>\n";
								}
							}
							?>
							</select>
						</td>

						<td style='vertical-align:top;text-align:left;'>
						<select style='width:70px;' class='changeSelect orderTypeID'>
						<option value=''></option>
						<?php
						foreach ($orderTypeArray as $orderType){
							if (!(trim(strval($orderType['orderTypeID'])) != trim(strval($payment['orderTypeID'])))){
								echo "<option value='" . $orderType['orderTypeID'] . "' selected class='changeSelect'>" . $orderType['shortName'] . "</option>\n";
							}else{
								echo "<option value='" . $orderType['orderTypeID'] . "' class='changeSelect'>" . $orderType['shortName'] . "</option>\n";
							}
						}
						?>
						</select>
						</td>


						<td style='vertical-align:top;text-align:center;width:37px;'>
							<a href='javascript:void();'><img src='images/cross.gif' alt='remove this payment' title='remove this payment' class='remove' /></a>
						</td>
						</tr>

					<?php
					}

				}

				?>

				</table>



			</td>
			</tr>
			</table>

		</td>
		</tr>
		<tr>
		<td>




			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='sitePurchaserID'><b>Purchasing Site(s)</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:350px;'>
			<tr>
			<td>
				<table class='noBorder' style='width:310px; margin:15px 20px;'>
				<?php
				$i=0;
				if (count($purchaseSiteArray) > 0){
					foreach ($purchaseSiteArray as $purchaseSiteIns){
						$i++;
						if(($i % 2)==1){
							echo "<tr>\n";
						}
						if (in_array($purchaseSiteIns['purchaseSiteID'],$resourcePurchaseSiteArray)){
							echo "<td><input class='check_purchaseSite' type='checkbox' name='" . $purchaseSiteIns['purchaseSiteID'] . "' id='" . $purchaseSiteIns['purchaseSiteID'] . "' value='" . $purchaseSiteIns['purchaseSiteID'] . "' checked />   " . $purchaseSiteIns['shortName'] . "</td>\n";
						}else{
							echo "<td><input class='check_purchaseSite' type='checkbox' name='" . $purchaseSiteIns['purchaseSiteID'] . "' id='" . $purchaseSiteIns['purchaseSiteID'] . "' value='" . $purchaseSiteIns['purchaseSiteID'] . "' />   " . $purchaseSiteIns['shortName'] . "</td>\n";
						}
						if(($i % 2)==0){
							echo "</tr>\n";
						}
					}

					if(($i % 2)==1){
						echo "<td>&nbsp;</td></tr>\n";
					}
				}
				?>
				</table>
			</td>
			</tr>
			</table>


		</td>
		<td>

		&nbsp;

		</td>
		</tr>
		</table>


		<hr style='width:100%;margin:15px 0px 10px 0px;' />

		<table class='noBorderTable' style='width:125px;'>
			<tr>
				<td style='text-align:left'><input type='button' value='submit' name='submitOrder' id ='submitOrder'></td>
				<td style='text-align:right'><input type='button' value='cancel' onclick="kill(); tb_remove();"></td>
			</tr>
		</table>


		<script type="text/javascript" src="js/forms/acquisitionsForm.js?random=<?php echo rand(); ?>"></script>

		<?php

        break;






    case 'getAcqForm':
    	$resourceID = $_GET['resourceID'];
    	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		//used to get default currency
		$config = new Configuration();

		$startDate=normalize_date($resource->currentStartDate);
		$endDate=normalize_date($resource->currentEndDate);

		//get all purchase sites for output in checkboxes
		$purchaseSiteArray = array();
		$purchaseSiteObj = new PurchaseSite();
		$purchaseSiteArray = $purchaseSiteObj->allAsArray();


		//get all acquisition types for output in drop down
		$acquisitionTypeArray = array();
		$acquisitionTypeObj = new AcquisitionType();
		$acquisitionTypeArray = $acquisitionTypeObj->allAsArray();

		//get all currency for output in drop down
		$currencyArray = array();
		$currencyObj = new Currency();
		$currencyArray = $currencyObj->allAsArray();

		//get purchase sites
		$sanitizedInstance = array();
		$instance = new PurchaseSite();
		$resourcePurchaseSiteArray = array();
		foreach ($resource->getResourcePurchaseSites() as $instance) {
			$resourcePurchaseSiteArray[] = $instance->purchaseSiteID;
		}


		//get payments
		$sanitizedInstance = array();
		$instance = new ResourcePayment();
		$paymentArray = array();
		foreach ($resource->getResourcePayments() as $instance) {
			foreach (array_keys($instance->attributeNames) as $attributeName) {
				$sanitizedInstance[$attributeName] = $instance->$attributeName;
			}

			$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;

			array_push($paymentArray, $sanitizedInstance);
		}


		//get all Order Types for output in drop down
		$orderTypeArray = array();
		$orderTypeObj = new OrderType();
		$orderTypeArray = $orderTypeObj->allAsArray();


		?>
		<div id='div_resourceForm'>
		<form id='resourceForm'>
		<input type='hidden' name='editResourceID' id='editResourceID' value='<?php echo $resourceID; ?>'>

		<div class='formTitle' style='width:740px; margin-bottom:5px;'><span class='headerText'>Edit Acquisitions Information</span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class='noBorder' style='width:735px;'>
		<tr style='vertical-align:top;'>
		<td style='vertical-align:top; padding-right:35px;'>


			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='orderInformation'><b>Order</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:350px;'>
			<tr>
			<td>
				<table class='noBorder' style='width:310px; margin:15px 20px;'>
				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold'><label for='acquisitionTypeID'>Acquisition Type:</label></td>
				<td>
				<select name='acquisitionTypeID' id='acquisitionTypeID' style='width:100px;' class='changeSelect'>
				<option value=''></option>
				<?php
				foreach ($acquisitionTypeArray as $acquisitionType){
					if (!(trim(strval($acquisitionType['acquisitionTypeID'])) != trim(strval($resource->acquisitionTypeID)))){
						echo "<option value='" . $acquisitionType['acquisitionTypeID'] . "' selected>" . $acquisitionType['shortName'] . "</option>\n";
					}else{
						echo "<option value='" . $acquisitionType['acquisitionTypeID'] . "'>" . $acquisitionType['shortName'] . "</option>\n";
					}
				}
				?>
				</select>
				</td>
				</tr>

				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold'><label for='orderNumber'>Order Number:</label></td>
				<td><input type='text' id='orderNumber' name='orderNumber' value = '<?php echo $resource->orderNumber; ?>' style='width:95px;' class='changeInput' /></td>
				</tr>

				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold'><label for='systemNumber'>System Number:</label></td>
				<td><input type='text' id='systemNumber' name='systemNumber' value = '<?php echo $resource->systemNumber; ?>' style='width:95px;' class='changeInput' /></td>
				</tr>

				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold'><label for='currentStartDate'>Sub Start:</label></td>
				<td><input class='date-pick' id='currentStartDate' name='currentStartDate' value = '<?php echo $startDate; ?>' style='width:75px;' /></td>
				</tr>

				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold'><label for='currentEndDate'>Current Sub End:</label></td>
				<td><input class='date-pick' id='currentEndDate' name='currentEndDate' value = '<?php echo $endDate; ?>' style='width:75px;' />
				</td>
				</tr>

				<?php if ($config->settings->enableAlerts == 'Y'){ ?>
				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold'>&nbsp;</td>
				<td>
				<div class="checkboxes" style='text-align:left;'>
					<label><input id='subscriptionAlertEnabledInd' type='checkbox' style='text-align:bottom' value='1' <?php if($resource->subscriptionAlertEnabledInd == 1) { echo "checked"; } ?> />&nbsp;<span>Enable Alert</span></label>
				</div>
				</td>
				</tr>
				<?php } ?>

				</table>

			</td>
			</tr>
			</table>


		</td>
		</tr>
		<tr>
		<td>




			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='sitePurchaserID'><b>Purchasing Site(s)</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:350px;'>
			<tr>
			<td>
				<table class='noBorder' style='width:310px; margin:15px 20px;'>
				<?php
				$i=0;
				if (count($purchaseSiteArray) > 0){
					foreach ($purchaseSiteArray as $purchaseSiteIns){
						$i++;
						if(($i % 2)==1){
							echo "<tr>\n";
						}
						if (in_array($purchaseSiteIns['purchaseSiteID'],$resourcePurchaseSiteArray)){
							echo "<td><input class='check_purchaseSite' type='checkbox' name='" . $purchaseSiteIns['purchaseSiteID'] . "' id='" . $purchaseSiteIns['purchaseSiteID'] . "' value='" . $purchaseSiteIns['purchaseSiteID'] . "' checked />   " . $purchaseSiteIns['shortName'] . "</td>\n";
						}else{
							echo "<td><input class='check_purchaseSite' type='checkbox' name='" . $purchaseSiteIns['purchaseSiteID'] . "' id='" . $purchaseSiteIns['purchaseSiteID'] . "' value='" . $purchaseSiteIns['purchaseSiteID'] . "' />   " . $purchaseSiteIns['shortName'] . "</td>\n";
						}
						if(($i % 2)==0){
							echo "</tr>\n";
						}
					}

					if(($i % 2)==1){
						echo "<td>&nbsp;</td></tr>\n";
					}
				}
				?>
				</table>
			</td>
			</tr>
			</table>


		</td>
		<td>

		&nbsp;

		</td>
		</tr>
		</table>


		<hr style='width:100%;margin:15px 0px 10px 0px;' />

		<table class='noBorderTable' style='width:125px;'>
			<tr>
				<td style='text-align:left'><input type='button' value='submit' name='submitOrder' id ='submitOrder'></td>
				<td style='text-align:right'><input type='button' value='cancel' onclick="kill(); tb_remove();"></td>
			</tr>
		</table>


		<script type="text/javascript" src="js/forms/acquisitionsForm.js?random=<?php echo rand(); ?>"></script>

		<?php

        break;






    case 'getLicenseForm':
    	$config = new Configuration();
    	$resourceID = $_GET['resourceID'];
    	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));


		//get license statuses
		$sanitizedInstance = array();
		$instance = new ResourceLicenseStatus();
		$resourceLicenseStatusArray = array();
		foreach ($resource->getResourceLicenseStatuses() as $instance) {
				foreach (array_keys($instance->attributeNames) as $attributeName) {
					$sanitizedInstance[$attributeName] = $instance->$attributeName;
				}

				$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;

				$changeUser = new User(new NamedArguments(array('primaryKey' => $instance->licenseStatusChangeLoginID)));
				if (($changeUser->firstName) || ($changeUser->lastName)) {
					$sanitizedInstance['changeName'] = $changeUser->firstName . " " . $changeUser->lastName;
				}else{
					$sanitizedInstance['changeName'] = $instance->licenseStatusChangeLoginID;
				}


				$licenseStatus = new LicenseStatus(new NamedArguments(array('primaryKey' => $instance->licenseStatusID)));
				$sanitizedInstance['licenseStatus'] = $licenseStatus->shortName;


				array_push($resourceLicenseStatusArray, $sanitizedInstance);

		}

		$currentLicenseStatusID = $resource->getCurrentResourceLicenseStatus();

		//get licenses (already returned in array)
		$licenseArray = $resource->getLicenseArray();



		//get all resource licenses for output in drop down
		$licenseStatusArray = array();
		$licenseStatusObj = new LicenseStatus();
		$licenseStatusArray = $licenseStatusObj->allAsArray();


		?>
		<div id='div_licenseForm'>
		<form id='licenseForm'>
		<input type='hidden' name='editResourceID' id='editResourceID' value='<?php echo $resourceID; ?>'>

		<div class='formTitle' style='width:360px; margin-bottom:5px;'><span class='headerText'>Edit Licenses</span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class='noBorder' style='width:360px;'>
		<tr style='vertical-align:top;'>
		<td style='vertical-align:top;'>


			<?php if ($config->settings->licensingModule == 'Y'){ ?>
			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='licenseRecords'><b>License Records</b></label>&nbsp;&nbsp;</span>


			<table class='surroundBox' style='width:350px;'>
			<tr>
			<td>

				<table class='noBorder smallPadding newLicenseTable' style='width:310px; margin:15px 20px 0px 20px'>
				<tr class='newLicenseTR'>

				<td style='vertical-align:top;text-align:left;'>
				<input type='text' value = '' style='width:260px;background:#f5f8fa;' class='changeAutocomplete licenseName' />
				<input type='hidden' class='licenseID' value = '' />
				</td>

				<td style='vertical-align:top;text-align:left;width:40px;'>
				<a href='javascript:void();' class='addLicense'><img src='images/add.gif' class='addLicense' alt='add license' title='add license'></a>
				</td>
				</tr>
				</table>
				<div class='smallDarkRedText' id='div_errorLicense' style='margin:0px 20px 7px 26px;'></div>


				<table class='noBorder smallPadding licenseTable' style='width:310px; margin:0px 20px 15px 20px;'>
				<tr>
				<td colspan='2'>
					<hr style='width:290px;margin:0px 0px 5px 5px;' />
				</td>
				</tr>

				<?php
				if (count($licenseArray) > 0){

					foreach ($licenseArray as $license){
					?>
						<tr>

						<td style='vertical-align:top;text-align:left;'>
						<input type='text' class='changeInput licenseName' value = '<?php echo $license['license']; ?>' style='width:260px;' class='changeInput' />
						<input type='hidden' class='licenseID' value = '<?php echo $license['licenseID']; ?>' />
						</td>

						<td style='vertical-align:top;text-align:left;width:40px;'>
							<a href='javascript:void();'><img src='images/cross.gif' alt='remove license link' title='remove <?php echo $license['license']; ?> license' class='remove' /></a>
						</td>

						</tr>

					<?php
					}
				}

				?>

				</table>




			</td>
			</tr>
			</table>


			<div style='height:15px;'>&nbsp;</div>

			<?php } ?>

			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='licenseStatus'><b>Licensing Status</b></label></span>

			<table class='surroundBox' style='width:350px;'>
			<tr>
			<td>

				<table class='noBorder smallPadding' style='width:310px; margin:15px 20px 0px 20px'>
				<tr>
				<td style='vertical-align:top;text-align:left;width:60px;'>Status:</td>
				<td style='vertical-align:top;text-align:left;'>
				<select class='changeSelect' id='licenseStatusID'>
				<option value=''></option>
				<?php
				foreach ($licenseStatusArray as $licenseStatus){
					if (!(trim(strval($licenseStatus['licenseStatusID'])) != trim(strval($currentLicenseStatusID)))){
						echo "<option value='" . $licenseStatus['licenseStatusID'] . "' selected class='changeSelect'>" . $licenseStatus['shortName'] . "</option>\n";
					}else{
						echo "<option value='" . $licenseStatus['licenseStatusID'] . "' class='changeSelect'>" . $licenseStatus['shortName'] . "</option>\n";
					}
				}
				?>
				</select>
				</td>

				</tr>
				</table>

				<hr style='width:310px;margin:8px 20px 7px 20px;' />


				<table class='noBorder' style='width:310px; margin:5px 15px;'>
				<tr>
				<td style='vertical-align:top;width:60px;'>History:</td>
				<td>

				<?php
				if (count($resourceLicenseStatusArray) > 0){
					foreach ($resourceLicenseStatusArray as $licenseStatus){
						echo $licenseStatus['licenseStatus'] . " - <i>" . format_date($licenseStatus['licenseStatusChangeDate']) . " by " . $licenseStatus['changeName'] . "</i><br />";
					}
				}else{
					echo "<i>No license status information available.</i>";
				}

				?>
				</td>
				</tr>

				</table>


			</td>
			</tr>
			</table>

		</td>
		</tr>
		</table>

		<br />

		<table class='noBorderTable' style='width:125px;'>
			<tr>
				<td style='text-align:left'><input type='button' value='submit' name='submitLicense' id ='submitLicense'></td>
				<td style='text-align:right'><input type='button' value='cancel' onclick="kill(); tb_remove();"></td>
			</tr>
		</table>

		<?php if ($config->settings->licensingModule == 'Y'){ ?>
		<script type="text/javascript" src="js/forms/licenseForm.js?random=<?php echo rand(); ?>"></script>
		<?php }else{ ?>
		<script type="text/javascript" src="js/forms/licenseStatusOnlyForm.js?random=<?php echo rand(); ?>"></script>
		<?php } ?>

		<?php

        break;






    case 'getAccessForm':
    	$resourceID = $_GET['resourceID'];
    	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		//get all authentication types for output in drop down
		$authenticationTypeArray = array();
		$authenticationTypeObj = new AuthenticationType();
		$authenticationTypeArray = $authenticationTypeObj->allAsArray();

		//get all access methods for output in drop down
		$accessMethodArray = array();
		$accessMethodObj = new AccessMethod();
		$accessMethodArray = $accessMethodObj->allAsArray();

		//get all user limits for output in drop down
		//overridden for better sort
		$userLimitArray = array();
		$userLimitObj = new UserLimit();
		$userLimitArray = $userLimitObj->allAsArray();

		//get all storage locations for output in drop down
		$storageLocationArray = array();
		$storageLocationObj = new StorageLocation();
		$storageLocationArray = $storageLocationObj->allAsArray();

		//get all administering sites for output in checkboxes
		$administeringSiteArray = array();
		$administeringSiteObj = new AdministeringSite();
		$administeringSiteArray = $administeringSiteObj->allAsArray();


		//get administering sites for this resource
		$sanitizedInstance = array();
		$instance = new AdministeringSite();
		$resourceAdministeringSiteArray = array();
		foreach ($resource->getResourceAdministeringSites() as $instance) {
			$resourceAdministeringSiteArray[] = $instance->administeringSiteID;
		}


		//get all authorized sites for output in checkboxes
		$authorizedSiteArray = array();
		$authorizedSiteObj = new AuthorizedSite();
		$authorizedSiteArray = $authorizedSiteObj->allAsArray();


		//get authorized sites for this resource
		$sanitizedInstance = array();
		$instance = new AuthorizedSite();
		$resourceAuthorizedSiteArray = array();
		foreach ($resource->getResourceAuthorizedSites() as $instance) {
			$resourceAuthorizedSiteArray[] = $instance->authorizedSiteID;
		}





		?>
		<div id='div_accessForm'>
		<form id='accessForm'>
		<input type='hidden' name='editResourceID' id='editResourceID' value='<?php echo $resourceID; ?>'>

		<div class='formTitle' style='width:617px; margin-bottom:5px;'><span class='headerText'>Edit Access</span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class='noBorder' style='width:610px;'>
		<tr style='vertical-align:top;'>
		<td style='vertical-align:top;' colspan='2'>


			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='accessHead'><b>Access</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:610px;'>
			<tr>
			<td>
				<table class='noBorder' style='width:570px; margin:15px 20px 10px 20px;'>
				<tr>
					<td style='vertical-align:top;text-align:left;font-weight:bold;white-space: nowrap;'><label for='authenticationTypeID'>Authentication Type:</label></td>
					<td>
						<select name='authenticationTypeID' id='authenticationTypeID' style='width:100px;' class='changeSelect'>
						<option value=''></option>
						<?php
						foreach ($authenticationTypeArray as $authenticationType){
							if (!(trim(strval($authenticationType['authenticationTypeID'])) != trim(strval($resource->authenticationTypeID)))){
								echo "<option value='" . $authenticationType['authenticationTypeID'] . "' selected>" . $authenticationType['shortName'] . "</option>\n";
							}else{
								echo "<option value='" . $authenticationType['authenticationTypeID'] . "'>" . $authenticationType['shortName'] . "</option>\n";
							}
						}
						?>
						</select>
					</td>
					<td style='vertical-align:top;text-align:left;font-weight:bold;white-space: nowrap;'><label for='authenticationUserName'>Username:</label></td>
					<td><input type='text' id='authenticationUserName' name='authenticationUserName' value = '<?php echo $resource->authenticationUserName; ?>' style='width:95px;' class='changeInput'  /></td>
				</tr>
				<tr>
					<td style='vertical-align:top;text-align:left;font-weight:bold;white-space: nowrap;'><label for='accessMethodID'>Access Method:</label></td>
					<td>
						<select name='accessMethodID' id='accessMethodID' style='width:100px;' class='changeSelect'>
						<option value=''></option>
						<?php
						foreach ($accessMethodArray as $accessMethod){
							if (!(trim(strval($accessMethod['accessMethodID'])) != trim(strval($resource->accessMethodID)))){
								echo "<option value='" . $accessMethod['accessMethodID'] . "' selected>" . $accessMethod['shortName'] . "</option>\n";
							}else{
								echo "<option value='" . $accessMethod['accessMethodID'] . "'>" . $accessMethod['shortName'] . "</option>\n";
							}
						}
						?>
						</select>
					</td>
					<td style='vertical-align:top;text-align:left;font-weight:bold;white-space: nowrap;'><label for='authenticationPassword'>Password:</label></td>
					<td><input type='text' id='authenticationPassword' name='authenticationPassword' value = '<?php echo $resource->authenticationPassword; ?>' style='width:95px;' class='changeInput'  /></td>
				</tr>
				<tr>
					<td style='vertical-align:top;text-align:left;font-weight:bold;white-space: nowrap;'><label for='storageLocationID'>Storage Location:</label></td>
					<td>
						<select name='storageLocationID' id='storageLocationID' style='width:100px;' class='changeSelect'>
						<option value=''></option>
						<?php
						foreach ($storageLocationArray as $storageLocation){
							if (!(trim(strval($storageLocation['storageLocationID'])) != trim(strval($resource->storageLocationID)))){
								echo "<option value='" . $storageLocation['storageLocationID'] . "' selected>" . $storageLocation['shortName'] . "</option>\n";
							}else{
								echo "<option value='" . $storageLocation['storageLocationID'] . "'>" . $storageLocation['shortName'] . "</option>\n";
							}
						}
						?>
						</select>
					</td>
					<td style='vertical-align:top;text-align:left;font-weight:bold;white-space: nowrap;'><label for='userLimitID'>Simultaneous User Limit:</label></td>
					<td>
						<select name='userLimitID' id='userLimitID' style='width:100px;' class='changeSelect' >
						<option value=''></option>
						<?php
						foreach ($userLimitArray as $userLimit){
							if (!(trim(strval($userLimit['userLimitID'])) != trim(strval($resource->userLimitID)))){
								echo "<option value='" . $userLimit['userLimitID'] . "' selected>" . $userLimit['shortName'] . "</option>\n";
							}else{
								echo "<option value='" . $userLimit['userLimitID'] . "'>" . $userLimit['shortName'] . "</option>\n";
							}
						}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td style='vertical-align:top;text-align:left;font-weight:bold;white-space: nowrap;'><label for='coverageText'>Coverage:</label></td>
					<td colspan='3'>
						<input type='text' id='coverageText' name='coverageText' value = "<?php echo $resource->coverageText; ?>" style='width:405px;' class='changeInput'  />
					</td>
				</tr>
				</table>
			</td>
			</tr>
			</table>

		</td>
		</tr>

		<tr style='vertical-align:top;'>
		<td>



			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='authorizedSiteID'><b>Authorized Site(s)</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:295px;'>
			<tr>
			<td>
				<?php
				$i=0;
				if (count($authorizedSiteArray) > 0){
					echo "<table class='noBorder' style='width:255px; margin:15px 20px;'>";
					foreach ($authorizedSiteArray as $authorizedSiteIns){
						$i++;
						if(($i % 2)==1){
							echo "<tr>\n";
						}
						if (in_array($authorizedSiteIns['authorizedSiteID'],$resourceAuthorizedSiteArray)){
							echo "<td><input class='check_authorizedSite' type='checkbox' name='" . $authorizedSiteIns['authorizedSiteID'] . "' id='" . $authorizedSiteIns['authorizedSiteID'] . "' value='" . $authorizedSiteIns['authorizedSiteID'] . "' checked />   " . $authorizedSiteIns['shortName'] . "</td>\n";
						}else{
							echo "<td><input class='check_authorizedSite' type='checkbox' name='" . $authorizedSiteIns['authorizedSiteID'] . "' id='" . $authorizedSiteIns['authorizedSiteID'] . "' value='" . $authorizedSiteIns['authorizedSiteID'] . "' />   " . $authorizedSiteIns['shortName'] . "</td>\n";
						}
						if(($i % 2)==0){
							echo "</tr>\n";
						}
					}

					if(($i % 2)==1){
						echo "<td>&nbsp;</td></tr>\n";
					}
					echo "</table>";
				}
				?>

			</td>
			</tr>
			</table>


		</td>
		<td>







			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='authorizedSiteID'><b>Administering Site(s)</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:295px;'>
			<tr>
			<td>
				<?php
				$i=0;
				if (count($administeringSiteArray) > 0){
					echo "<table class='noBorder' style='width:255px; margin:15px 20px;'>";
					foreach ($administeringSiteArray as $administeringSiteIns){
						$i++;
						if(($i % 2)==1){
							echo "<tr>\n";
						}
						if (in_array($administeringSiteIns['administeringSiteID'],$resourceAdministeringSiteArray)){
							echo "<td><input class='check_administeringSite' type='checkbox' name='" . $administeringSiteIns['administeringSiteID'] . "' id='" . $administeringSiteIns['administeringSiteID'] . "' value='" . $administeringSiteIns['administeringSiteID'] . "' checked />   " . $administeringSiteIns['shortName'] . "</td>\n";
						}else{
							echo "<td><input class='check_administeringSite' type='checkbox' name='" . $administeringSiteIns['administeringSiteID'] . "' id='" . $administeringSiteIns['administeringSiteID'] . "' value='" . $administeringSiteIns['administeringSiteID'] . "' />   " . $administeringSiteIns['shortName'] . "</td>\n";
						}
						if(($i % 2)==0){
							echo "</tr>\n";
						}
					}

					if(($i % 2)==1){
						echo "<td>&nbsp;</td></tr>\n";
					}
					echo "</table>";
				}
				?>

			</td>
			</tr>
			</table>

		</td>
		</table>


		<hr style='width:620px;margin:15px 0px 10px 0px;' />

		<table class='noBorderTable' style='width:125px;'>
			<tr>
				<td style='text-align:left'><input type='button' value='submit' name='submitAccessChanges' id ='submitAccessChanges'></td>
				<td style='text-align:right'><input type='button' value='cancel' onclick="kill(); tb_remove();"></td>
			</tr>
		</table>


		<script type="text/javascript" src="js/forms/accessForm.js?random=<?php echo rand(); ?>"></script>

		<?php

        break;





    case 'getContactForm':
    	$resourceID = $_GET['resourceID'];
    	if (isset($_GET['contactID'])) $contactID = $_GET['contactID']; else $contactID = '';
    	$contact = new Contact(new NamedArguments(array('primaryKey' => $contactID)));

		if (normalize_date($contact->archiveDate) != '') {
			$invalidChecked = 'checked';
		}else{
			$invalidChecked = '';
		}

		//get contact roles
		$sanitizedInstance = array();
		$instance = new ContactRole();
		$contactRoleProfileArray = array();
		foreach ($contact->getContactRoles() as $instance) {
			$contactRoleProfileArray[] = $instance->contactRoleID;
		}

		//get all contact roles for output in drop down
		$contactRoleArray = array();
		$contactRoleObj = new ContactRole();
		$contactRoleArray = $contactRoleObj->allAsArray();

		?>
		<div id='div_contactForm'>
		<form id='contactForm'>
		<input type='hidden' name='editResourceID' id='editResourceID' value='<?php echo $resourceID; ?>'>
		<input type='hidden' name='editContactID' id='editContactID' value='<?php echo $contactID; ?>'>

		<div class='formTitle' style='width:603px;'><span class='headerText' style='margin-left:7px;'><?php if ($contactID){ echo "Edit Contact"; } else { echo "Add Contact"; } ?></span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class="surroundBox" style="width:600;">
		<tr>
		<td>
			<table class='noBorder' style='width:560px; margin:15px 20px 10px 20px;'>
			<tr>
			<td>

				<table class='noBorder'>
					<tr>
					<td style='text-align:left'><label for='contactName'><b>Name:</b></label></td>
					<td>
					<input type='text' id='contactName' name='contactName' value = '<?php echo $contact->name; ?>' style='width:150px' class='changeInput' /><span id='span_error_contactName' class='smallDarkRedText'>
					</td>
					</tr>

					<tr>
					<td style='text-align:left'><label for='contactTitle'><b>Title:</b></label></td>
					<td>
					<input type='text' id='contactTitle' name='contactTitle' value = '<?php echo $contact->title; ?>' style='width:150px' class='changeInput' />
					</td>
					</tr>

					<tr>
					<td style='text-align:left'><label for='phoneNumber'><b>Phone:</b></label></td>
					<td>
					<input type='text' id='phoneNumber' name='phoneNumber' value = '<?php echo $contact->phoneNumber; ?>' style='width:150px' class='changeInput' />
					</td>
					</tr>

					<tr>
					<td style='text-align:left'><label for='altPhoneNumber'><b>Alt Phone:</b></label></td>
					<td>
					<input type='text' id='altPhoneNumber' name='altPhoneNumber' value = '<?php echo $contact->altPhoneNumber; ?>' style='width:150px' class='changeInput' />
					</td>
					</tr>

					<tr>
					<td style='text-align:left'><label for='faxNumber'><b>Fax:</b></label></td>
					<td>
					<input type='text' id='faxNumber' name='faxNumber' value = '<?php echo $contact->faxNumber; ?>' style='width:150px' class='changeInput' />
					</td>
					</tr>

					<tr>
					<td style='text-align:left'><label for='emailAddress'><b>Email:</b></label></td>
					<td>
					<input type='text' id='emailAddress' name='emailAddress' value = '<?php echo $contact->emailAddress; ?>' style='width:150px' class='changeInput' />
					</td>
					</tr>

					<tr>
					<td style='text-align:left'><label for='addressText'><b>Address:</b></label></td>
					<td>
					<textarea rows='3' id='addressText' style='width:150px'><?php echo $contact->addressText; ?></textarea>
					</td>
					</tr>

					<tr>
					<td style='text-align:left'><label for='invalidInd'><b>Archived:</b></label></td>
					<td>
					<input type='checkbox' id='invalidInd' name='invalidInd' <?php echo $invalidChecked; ?> />
					</td>
					</tr>
				</table>
			</td>
			<td>
				<table class='noBorder'>
				<tr>
				<td style='vertical-align:top;text-align:left;'><label for='orgRoles'><b>Role(s):</b></label></td>
				<td>

					<table>
					<?php
					$i=0;
					if (count($contactRoleArray) > 0){
						foreach ($contactRoleArray as $contactRoleIns){
							$i++;
							if(($i % 3)==1){
								echo "<tr>\n";
							}
							if (in_array($contactRoleIns['contactRoleID'],$contactRoleProfileArray)){
								echo "<td style='vertical-align:top;text-align:left;'><input class='check_roles' type='checkbox' name='" . $contactRoleIns['contactRoleID'] . "' id='" . $contactRoleIns['contactRoleID'] . "' value='" . $contactRoleIns['contactRoleID'] . "' checked />   <span class='smallText'>" . $contactRoleIns['shortName'] . "</span></td>\n";
							}else{
								echo "<td style='vertical-align:top;text-align:left;'><input class='check_roles' type='checkbox' name='" . $contactRoleIns['contactRoleID'] . "' id='" . $contactRoleIns['contactRoleID'] . "' value='" . $contactRoleIns['contactRoleID'] . "' />   <span class='smallText'>" . $contactRoleIns['shortName'] . "</span></td>\n";
							}
							if(($i % 3)==0){
								echo "</tr>\n";
							}
						}

						if(($i % 3)==1){
							echo "<td>&nbsp;</td><td>&nbsp;</td></tr>\n";
						}else if(($i % 3)==2){
							echo "<td>&nbsp;</td></tr>\n";
						}
					}
					?>
					</table>
					<span id='span_error_contactRole' class='smallDarkRedText'></span>
				</td>
				</tr>


				<tr>
				<td style='text-align:left'><label for='addressText'><b>Notes:</b></label></td>
				<td>
				<textarea rows='3' id='noteText' style='width:220px'><?php echo $contact->noteText; ?></textarea>
				</td>
				</tr>

				</table>


			</td>
			</tr>
			</table>

		</td>
		</tr>
		</table>



		<hr style='width:610px;margin:15px 0px 10px 0px;' />

		<table class='noBorderTable' style='width:125px;'>
			<tr>
				<td style='text-align:left'><input type='button' value='submit' name='submitContactForm' id ='submitContactForm'></td>
				<td style='text-align:right'><input type='button' value='cancel' onclick="tb_remove();"></td>
			</tr>
		</table>

		</form>
		</div>


		<script type="text/javascript" src="js/forms/contactForm.js?random=<?php echo rand(); ?>"></script>

		<?php

        break;






    case 'getAccountForm':
    	$resourceID = $_GET['resourceID'];
    	if (isset($_GET['externalLoginID'])) $externalLoginID = $_GET['externalLoginID']; else $externalLoginID = '';
    	$externalLogin = new ExternalLogin(new NamedArguments(array('primaryKey' => $externalLoginID)));


		//get all contact roles for output in drop down
		$externalLoginTypeArray = array();
		$externalLoginTypeObj = new ExternalLoginType();
		$externalLoginTypeArray = $externalLoginTypeObj->allAsArray();

		?>
		<div id='div_accountForm'>
		<form id='accountForm'>
		<input type='hidden' name='editResourceID' id='editResourceID' value='<?php echo $resourceID; ?>'>
		<input type='hidden' name='editExternalLoginID' id='editExternalLoginID' value='<?php echo $externalLoginID; ?>'>


		<div class='formTitle' style='width:385px;'><span class='headerText' style='margin-left:7px;'><?php if ($externalLoginID){ echo "Edit Account"; } else { echo "Add Account"; } ?></span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class="surroundBox" style="width:390px;">
		<tr>
		<td>

			<table class='noBorder' style='width:350px; margin:10px 15px;'>
				<tr>
				<td style='vertical-align:top;text-align:left;'><label for='externalLoginTypeID'><b>Login Type:</b></label></td>
				<td>
				<select name='externalLoginTypeID' id='externalLoginTypeID' class='changeSelect'>
				<?php
				foreach ($externalLoginTypeArray as $externalLoginType){
					if ($externalLoginType['externalLoginTypeID'] == $externalLogin->externalLoginTypeID){
						echo "<option value='" . $externalLoginType['externalLoginTypeID'] . "' selected>" . $externalLoginType['shortName'] . "</option>\n";
					}else{
						echo "<option value='" . $externalLoginType['externalLoginTypeID'] . "'>" . $externalLoginType['shortName'] . "</option>\n";
					}
				}
				?>
				</select>
				</td>
				</tr>

				<tr>
				<td style='vertical-align:top;text-align:left;'><label for='loginURL'><b>URL:</b></label></td>
				<td>
				<input type='text' id='loginURL' name='loginURL' value = '<?php echo $externalLogin->loginURL; ?>' style='width:200px' class='changeInput' />
				</td>
				</tr>

				<tr>
				<td style='vertical-align:top;text-align:left;'><label for='emailAddress'><b>Registered Email:</b></label></td>
				<td>
				<input type='text' id='emailAddress' name='emailAddress' value = '<?php echo $externalLogin->emailAddress; ?>' style='width:200px' class='changeInput' />
				</td>
				</tr>

				<tr>
				<td style='vertical-align:top;text-align:left;'><label for='username'><b>Username:</b></label></td>
				<td>
				<input type='text' id='username' name='username' value = '<?php echo $externalLogin->username; ?>' style='width:200px' class='changeInput' />
				</td>
				</tr>

				<tr>
				<td style='vertical-align:top;text-align:left;'><label for='password'><b>Password:</b></label></td>
				<td>
				<input type='text' id='password' name='password' value = '<?php echo $externalLogin->password; ?>' style='width:200px' class='changeInput' />
				</td>
				</tr>

				<tr>
				<td style='vertical-align:top;text-align:left;'><label for='noteText'><b>Notes:</b></label></td>
				<td><textarea rows='3' id='noteText' name='noteText' style='width:200px'><?php echo $externalLogin->noteText; ?></textarea></td>
				</td>
				</tr>
			</table>

		</td>
		</tr>
		</table>

		<br />

		<table class='noBorderTable' style='width:125px;'>
			<tr>
				<td style='text-align:left'><input type='button' value='submit' name='submitExternalLoginForm' id ='submitExternalLoginForm'></td>
				<td style='text-align:right'><input type='button' value='cancel' onclick="tb_remove();"></td>
			</tr>
		</table>

		</form>
		</div>


		<script type="text/javascript" src="js/forms/externalLoginForm.js?random=<?php echo rand(); ?>"></script>

		<?php

        break;




    case 'getAttachmentForm':
    	$resourceID = $_GET['resourceID'];
    	if (isset($_GET['attachmentID'])) $attachmentID = $_GET['attachmentID']; else $attachmentID = '';
    	$attachment = new Attachment(new NamedArguments(array('primaryKey' => $attachmentID)));

		//get all attachment types for output in drop down
		$attachmentTypeArray = array();
		$attachmentTypeObj = new AttachmentType();
		$attachmentTypeArray = $attachmentTypeObj->allAsArray();

		?>
		<div id='div_attachmentForm'>
		<form id='attachmentForm'>
		<input type='hidden' name='editResourceID' id='editResourceID' value='<?php echo $resourceID; ?>'>
		<input type='hidden' name='editAttachmentID' id='editAttachmentID' value='<?php echo $attachmentID; ?>'>

		<div class='formTitle' style='width:345px;'><span class='headerText' style='margin-left:7px;'><?php if ($attachmentID){ echo "Edit Attachment"; } else { echo "Add Attachment"; } ?></span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class="surroundBox" style="width:350px;">
		<tr>
		<td>

			<table class='noBorder' style='width:310px; margin:10px 15px;'>

			<tr>
			<td style='vertical-align:top;text-align:left;'><label for='shortName'><b>Name:</b></label></td>
			<td>
			<input type='text' class='changeInput' id='shortName' name='shortName' value = '<?php echo $attachment->shortName; ?>' style='width:230px' /><span id='span_error_shortName' class='smallDarkRedText'></span>
			</td>
			</tr>

			<tr>

			<td style='vertical-align:top;text-align:left;border:0px;'><label for='attachmentTypeID'><b>Type:</b></label></td>
			<td style='vertical-align:top;text-align:left;border:0px;'>

			<select name='attachmentTypeID' id='attachmentTypeID'>
			<option value=''></option>
			<?php
			foreach ($attachmentTypeArray as $attachmentType){
				if (!(trim(strval($attachmentType['attachmentTypeID'])) != trim(strval($attachment->attachmentTypeID)))){
					echo "<option value='" . $attachmentType['attachmentTypeID'] . "' selected>" . $attachmentType['shortName'] . "</option>\n";
				}else{
					echo "<option value='" . $attachmentType['attachmentTypeID'] . "'>" . $attachmentType['shortName'] . "</option>\n";
				}
			}
			?>
			</select>
			<span id='span_error_attachmentTypeID' class='smallDarkRedText'></span>
			</td>
			</tr>

			<tr>
			<td style='text-align:left;vertical-align:top;'><label for="uploadAttachment"><b>File:</b></label></td>
			<td>
			<?php

			//if editing
			if ($attachmentID){
				echo "<div id='div_uploadFile'>" . $attachment->attachmentURL . "<br /><a href='javascript:replaceFile();'>replace with new file</a>";
				echo "<input type='hidden' id='upload_button' name='upload_button' value='" . $attachment->attachmentURL . "'></div>";

			//if adding
			}else{
				echo "<div id='div_uploadFile'><input type='file' name='upload_button' id='upload_button' /></div>";
			}


			?>
			<span id='div_file_message'></span>
			</td>
			</tr>

			<tr>
			<td style='vertical-align:top;text-align:left;'><label for='descriptionText'><b>Details:</b></label></td>
			<td><textarea rows='5' class='changeInput' id='descriptionText' name='descriptionText' style='width:230px'><?php echo $attachment->descriptionText; ?></textarea></td>
			</td>
			</tr>
			</table>

		</td>
		</tr>
		</table>

		<br />
		<table class='noBorderTable' style='width:125px;'>
			<tr>
				<td style='text-align:left'><input type='button' value='submit' name='submitAttachmentForm' id ='submitAttachmentForm'></td>
				<td style='text-align:right'><input type='button' value='cancel' onclick="tb_remove()"></td>
			</tr>
		</table>


		</form>
		</div>

		<script type="text/javascript" src="js/forms/attachmentForm.js?random=<?php echo rand(); ?>"></script>

		<?php

        break;




	case 'getAdminUpdateForm':
		$className = $_GET['className'];
		$updateID = $_GET['updateID'];

		if ($updateID){
			$instance = new $className(new NamedArguments(array('primaryKey' => $updateID)));
		}else{
			$instance = new $className();
		}


		?>
		<div id='div_updateForm'>

		<input type='hidden' id='editClassName' value='<?php echo $className; ?>'>
		<input type='hidden' id='editUpdateID' value='<?php echo $updateID; ?>'>

		<div class='formTitle' style='width:245px;'><span class='headerText' style='margin-left:7px;'><?php if ($updateID){ echo "Edit " . preg_replace("/[A-Z]/", " \\0" , $className); } else { echo "Add " . preg_replace("/[A-Z]/", " \\0" , $className); } ?></span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class="surroundBox" style="width:250px;">
		<tr>
		<td>

			<table class='noBorder' style='width:200px; margin:10px;'>
			<tr>
			<td><input type='text' id='updateVal' value='<?php echo $instance->shortName; ?>' style='width:190px;'/></td>
			</tr>
			<?php
				if($className == 'ResourceType' && ($config->settings->usageModule == 'Y')){
					if($instance->includeStats == 1){$stats = 'checked';}else{$stats='';}
					echo "<tr><td><label for='stats'>Show stats button?</label>";
					echo "<input type='checkbox' id='stats' ".$stats." /></td></tr>";
				}
			?>
			</table>

		</td>
		</tr>
		</table>

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
		   $('#updateVal').keyup(function(e) {
				   if(e.keyCode == 13) {
					   window.parent.submitData();
				   }
        	});


		   $('#submitAddUpdate').click(function () {
			       window.parent.submitData();
		   });


        </script>


		<?php

		break;

	case 'getAdminCurrencyUpdateForm':
		$updateID = $_GET['updateID'];

		if ($updateID){
			$instance = new Currency(new NamedArguments(array('primaryKey' => $updateID)));
		}else{
			$instance = new Currency();
		}


		?>
		<div id='div_updateForm'>

		<input type='hidden' id='editCurrencyCode' value='<?php echo $updateID; ?>'>

		<div class='formTitle' style='width:245px;'><span class='headerText' style='margin-left:7px;'><?php if ($updateID){ echo "Edit Currency"; } else { echo "Add Currency"; } ?></span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class="surroundBox" style="width:250px;">
		<tr>
		<td>

			<table class='noBorder' style='width:200px; margin:10px;'>
			<tr>
			<td>Code</td><td><input type='text' id='currencyCode' value='<?php echo $instance->currencyCode; ?>' style='width:150px;'/></td>
			</tr>
			<tr>
			<td>Name</td><td><input type='text' id='shortName' value='<?php echo $instance->shortName; ?>' style='width:150px;'/></td>
			</tr>
			</table>

		</td>
		</tr>
		</table>

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
		   $('#currencyCode').keyup(function(e) {
				   if(e.keyCode == 13) {
					   window.parent.submitCurrencyData();
				   }
        	});

		   $('#shortName').keyup(function(e) {
				   if(e.keyCode == 13) {
					   window.parent.submitCurrencyData();
				   }
        	});

		   $('#submitAddUpdate').click(function () {
			       window.parent.submitCurrencyData();
		   });


        </script>


		<?php

		break;








	case 'getAdminUserUpdateForm':
		if (isset($_GET['loginID'])) $loginID = $_GET['loginID']; else $loginID = '';

		$user = new User(new NamedArguments(array('primaryKey' => $loginID)));

		//get all roles for output in drop down
		$privilegeArray = array();
		$privilegeObj = new Privilege();
		$privilegeArray = $privilegeObj->allAsArray();

		if ($user->accountTabIndicator == '1') {
			$accountTab = 'checked';
		}else{
			$accountTab = '';
		}

		?>


		<div id='div_updateForm'>

		<input type='hidden' id='editLoginID' value='<?php echo $loginID; ?>'>

		<div class='formTitle' style='width:295px;'><span class='headerText' style='margin-left:7px;'><?php if ($loginID){ echo "Edit User"; } else { echo "Add New User"; } ?></span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class="surroundBox" style="width:300px;">
		<tr>
		<td>

			<table class='noBorder' style='width:260px; margin:10px;'>


				<tr><td><label for='loginID'><b>Login ID</b></label</td><td><?php if (!$loginID) { ?><input type='text' id='loginID' value='<?php echo $loginID; ?>' style='width:150px;'/> <?php } else { echo $loginID; } ?></td></tr>
				<tr><td><label for='firstName'><b>First Name</b></label</td><td><input type='text' id='firstName' value="<?php echo $user->firstName; ?>" style='width:150px;'/></td></tr>
				<tr><td><label for='lastName'><b>Last Name</b></label</td><td><input type='text' id='lastName' value="<?php echo $user->lastName; ?>" style='width:150px;'/></td></tr>
				<tr><td><label for='emailAddress'><b>Email Address</b></label</td><td><input type='text' id='emailAddress' value="<?php echo $user->emailAddress; ?>" style='width:150px;'/></td></tr>
				<tr><td><label for='privilegeID'><b>Privilege</b></label</td>
				<td>
				<select id='privilegeID' style='width:155px'>
				<?php

				foreach ($privilegeArray as $privilege){
					if ($privilege['privilegeID'] == $user->privilegeID){
						echo "<option value='" . $privilege['privilegeID'] . "' selected>" . $privilege['shortName'] . "</option>\n";
					}else{
						echo "<option value='" . $privilege['privilegeID'] . "'>" . $privilege['shortName'] . "</option>\n";
					}
				}

				?>
				</select>
				</td>
				</tr>

				<tr><td><label for='accountTab'><b>View Accounts</b></label</td><td><input type='checkbox' id='accountTab' value='1' <?php echo $accountTab; ?> /></td></tr>


			</table>

		</td>
		</tr>
		</table>

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
		   $('#loginID').keyup(function(e) {
				   if(e.keyCode == 13) {
					   window.parent.submitUserData();
				   }
        	});

		   $('#firstName').keyup(function(e) {
				   if(e.keyCode == 13) {
					   window.parent.submitUserData();
				   }
        	});

		   $('#lastName').keyup(function(e) {
				   if(e.keyCode == 13) {
					   window.parent.submitUserData();
				   }
        	});

		   $('#emailAddress').keyup(function(e) {
				   if(e.keyCode == 13) {
					   window.parent.submitUserData();
				   }
        	});

		   $('#privilegeID').keyup(function(e) {
				   if(e.keyCode == 13) {
					   window.parent.submitUserData();
				   }
        	});

		   $('#submitAddUpdate').click(function () {
			       window.parent.submitUserData();
		   });


        </script>


		<?php

		break;






	case 'getAdminAlertEmailForm':
		$alertEmailAddressID = $_GET['alertEmailAddressID'];

		if ($alertEmailAddressID){
			$instance = new AlertEmailAddress(new NamedArguments(array('primaryKey' => $alertEmailAddressID)));
		}else{
			$instance = new AlertEmailAddress();
		}


		?>
		<div id='div_updateForm'>

		<input type='hidden' id='editAlertEmailAddressID' value='<?php echo $alertEmailAddressID; ?>'>

		<div class='formTitle' style='width:245px;'><span class='headerText' style='margin-left:7px;'><?php if ($alertEmailAddressID){ echo "Edit Alert Email Address"; } else { echo "Add Alert Email Address"; } ?></span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class="surroundBox" style="width:250px;">
		<tr>
		<td>

			<table class='noBorder' style='width:200px; margin:10px;'>
			<td><input type='text' id='emailAddress' value='<?php echo $instance->emailAddress; ?>' style='width:190px;'/></td>
			</td>
			</tr>
			</table>

		</td>
		</tr>
		</table>
		<div class='smallDarkRedText' id='div_form_error'>&nbsp;</div>
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
		   $('#emailAddress').keyup(function(e) {
				   if(e.keyCode == 13) {
					   window.parent.submitAdminAlertEmail();
				   }
        	});


		   $('#submitAddUpdate').click(function () {
			       window.parent.submitAdminAlertEmail();
		   });


        </script>


		<?php

		break;








	case 'getAdminAlertDaysForm':
		$alertDaysInAdvanceID = $_GET['alertDaysInAdvanceID'];

		if ($alertDaysInAdvanceID){
			$instance = new AlertDaysInAdvance(new NamedArguments(array('primaryKey' => $alertDaysInAdvanceID)));
		}else{
			$instance = new AlertDaysInAdvance();
		}


		?>
		<div id='div_updateForm'>

		<input type='hidden' id='editAlertDaysInAdvanceID' value='<?php echo $alertDaysInAdvanceID; ?>'>

		<div class='formTitle' style='width:245px;'><span class='headerText' style='margin-left:7px;'><?php if ($alertDaysInAdvanceID){ echo "Edit Alert Days In Advance"; } else { echo "Add Alert Days In Advance"; } ?></span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class="surroundBox" style="width:250px;">
		<tr>
		<td>

			<table class='noBorder' style='width:200px; margin:10px;'>
			<td><input type='text' id='daysInAdvanceNumber' value='<?php echo $instance->daysInAdvanceNumber; ?>' style='width:190px;'/></td>
			</td>
			</tr>
			</table>

		</td>
		</tr>
		</table>
		<div class='smallDarkRedText' id='div_form_error'>&nbsp;</div>
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
		   $('#daysInAdvanceNumber').keyup(function(e) {
				   if(e.keyCode == 13) {
					   window.parent.submitAdminAlertDays();
				   }
        	});


		   $('#submitAddUpdate').click(function () {
			       window.parent.submitAdminAlertDays();
		   });


        </script>


		<?php

		break;






	case 'getAdminWorkflowForm':
		$workflowID = $_GET['workflowID'];

		if ($workflowID){
			$workflow = new Workflow(new NamedArguments(array('primaryKey' => $workflowID)));
		}else{
			$workflow = new Workflow();
		}

		$stepArray = $workflow->getSteps();
		$stepDDArray = $workflow->getSteps();

		//get all acquisition types for output in drop down
		$acquisitionTypeArray = array();
		$acquisitionTypeObj = new AcquisitionType();
		$acquisitionTypeArray = $acquisitionTypeObj->sortedArray();

		//get all resource formats for output in drop down
		$resourceFormatArray = array();
		$resourceFormatObj = new ResourceFormat();
		$resourceFormatArray = $resourceFormatObj->sortedArray();

		//get all resource types for output in drop down
		$resourceTypeArray = array();
		$resourceTypeObj = new ResourceType();
		$resourceTypeArray = $resourceTypeObj->allAsArray();


		//get all acquisition types for output in drop down
		$userGroupArray = array();
		$userGroupObj = new UserGroup();
		$userGroupArray = $userGroupObj->allAsArray();


		//used to determine ordering - default to empty
		$key = '0';

		?>
		<div id='div_resourceForm'>
		<form id='resourceForm'>
		<input type='hidden' name='editWFID' id='editWFID' value='<?php echo $workflowID; ?>'>

		<div class='formTitle' style='width:705px; margin-bottom:5px;position:relative;'><span class='headerText'>Edit Workflow</span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class='noBorder' style='width:100%;'>
		<tr style='vertical-align:top;'>
		<td style='vertical-align:top;position:relative;'>


			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='rule'><b>Resource Entry Requirements</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:700px;'>
			<tr>
			<td>
				<table class='noBorder' style='width:660px; margin:15px 20px 10px 20px;'>
				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='acquisitionTypeID'>Acquisition Type:</label></td>
				<td>
				<select name='acquisitionTypeID' id='acquisitionTypeID' style='width:100px;' class='changeSelect' >
				<?php
				foreach ($acquisitionTypeArray as $acquisitionType){
					if (!(trim(strval($acquisitionType['acquisitionTypeID'])) != trim(strval($workflow->acquisitionTypeIDValue)))){
						echo "<option value='" . $acquisitionType['acquisitionTypeID'] . "' selected>" . $acquisitionType['shortName'] . "</option>\n";
					}else{
						echo "<option value='" . $acquisitionType['acquisitionTypeID'] . "'>" . $acquisitionType['shortName'] . "</option>\n";
					}
				}
				?>
				</select>
				</td>


				<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='resourceFormatID'>Format:</label></td>
				<td>
				<select name='resourceFormatID' id='resourceFormatID' style='width:100px;' class='changeSelect'>
				<?php
				foreach ($resourceFormatArray as $resourceFormat){
					if (!(trim(strval($resourceFormat['resourceFormatID'])) != trim(strval($workflow->resourceFormatIDValue)))){
						echo "<option value='" . $resourceFormat['resourceFormatID'] . "' selected>" . $resourceFormat['shortName'] . "</option>\n";
					}else{
						echo "<option value='" . $resourceFormat['resourceFormatID'] . "'>" . $resourceFormat['shortName'] . "</option>\n";
					}
				}
				?>
				</select>
				</td>

				<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='resourceTypeID'>Type:</label></td>
				<td>
				<select name='resourceTypeID' id='resourceTypeID' style='width:100px;' class='changeSelect' >
				<option value=''></option>
				<?php
				foreach ($resourceTypeArray as $resourceType){
					if (!(trim(strval($resourceType['resourceTypeID'])) != trim(strval($workflow->resourceTypeIDValue)))){
						echo "<option value='" . $resourceType['resourceTypeID'] . "' selected>" . $resourceType['shortName'] . "</option>\n";
					}else{
						echo "<option value='" . $resourceType['resourceTypeID'] . "'>" . $resourceType['shortName'] . "</option>\n";
					}
				}
				?>
				</select>

				</td>
				</tr>
				</table>
			</td>
			</tr>
			</table>

			<div style='height:20px;'>&nbsp;</div>


			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='workflowSteps'><b>Workflow Steps</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:700px;'>
			<tr>
			<td>

				<table class='noBorder noMargin newStepTable' style='width:660px;  margin:15px 20px 0px 20px;'>
				<tr>
					<td style='vertical-align:top;text-align:left;font-weight:bold;width:48px;'>&nbsp;</td>
					<td style='vertical-align:top;text-align:left;font-weight:bold;width:218px;'>Name:</td>
					<td style='vertical-align:top;text-align:left;font-weight:bold;width:175px;'>Approval/Notification Group:</td>
					<td style='vertical-align:top;text-align:left;font-weight:bold;width:110px;'>Parent Step</td>
					<td style='vertical-align:top;text-align:center;width:40px;'>&nbsp;</td>
				</tr>

				<tr class='newStepTR' id=''>

				<td style='vertical-align:top;text-align:left;width:48px;' class='seqOrder' key=''><img src='images/transparent.gif' style='width:43px;height:20px;' /></td>

				<td style='vertical-align:top;text-align:left;width:218px;'>
					<input type='text' value = '' style='width:200px;' class='stepName changeInput' />
				</td>

				<td style='vertical-align:top;text-align:left;width:175px;'>
					<select style='width:150px; ' class='changeSelect userGroupID'>
					<?php
					foreach ($userGroupArray as $userGroup){
						echo "<option value='" . $userGroup['userGroupID'] . "'>" . $userGroup['groupName'] . "</option>\n";
					}
					?>
					</select>
				</td>

				<td style='vertical-align:top;text-align:left;width:175px;'>
					<select style='width:150px;' class='changeSelect priorStepID'>
					<option value=''></option>
					</select>
					<input type='hidden' class='priorStepKey' key='' value=''>
				</td>

				<td style='vertical-align:top;text-align:center;width:40px;'>
				<a href='javascript:void();'><img src='images/add.gif' class='addStep' alt='add step' title='add step'></a>
				</td>

				</tr>
				</table>
				<div class='smallDarkRedText' id='div_errorStep' style='margin:0px 20px 5px 26px;'></div>

				<table class='noBorder noMargin stepTable' style='width:660px;margin:0px 20px 10px 20px;'>
				<tr>
				<td colspan='5'>
					<hr style='width:650px;margin:0px 0px 15px 5px;' />
				</td>
				</tr>

				<?php
				$stepCount = count($stepArray);

				if ($stepCount > 0){

					foreach ($stepArray as $key => $step){
					$key=$key+1;

					if ($step->priorStepID){
						$priorStep= new Step(new NamedArguments(array('primaryKey' => $step->priorStepID)));
					}else{
						$priorStep= new Step();
					}
					?>
						<tr class='stepTR'>

						<td style='vertical-align:top;text-align:left;width:48px;' class='seqOrder <?php if ($key == ($stepCount)){ echo "lastClass"; } ?>' id='<?php echo $step->stepID; ?>' key='<?php echo $key; ?>'>
							<?php

								$arrowDown = "<a href='javascript:void(0);' class='moveArrow' direction='down'><img src='images/arrow_down.gif'></a>";
								$arrowUp = "<a href='javascript:void(0);' class='moveArrow' direction='up' ><img src='images/arrow_up.gif'></a>";
								$trans = "<img src='images/transparent.gif' style='width:20px;height:20px;' />";

								if ($key == 1){

									//if this is the only step, display the large transparent gif instead of arrows
									if (($stepCount) == 1){
										echo "<img src='images/transparent.gif' style='width:43px;height:10px;' />";
									}else{
										echo $trans . "&nbsp;" . $arrowDown;
									}


								}else if ($key == ($stepCount)){
									echo $arrowUp . "&nbsp;" . $trans;
								}else{
									echo $arrowUp . "&nbsp;" . $arrowDown;
								}
							?>
						</td>

						<td style='vertical-align:top;text-align:left;width:218px;'>
						<input type='text' value = '<?php echo $step->stepName; ?>' style='width:200px;' class='stepName changeInput' />
						</td>

						<td style='vertical-align:top;text-align:left;width:175px;'>
							<select style='width:150px;' class='changeSelect userGroupID'>
							<?php
							foreach ($userGroupArray as $userGroup){
								if ($userGroup['userGroupID'] == $step->userGroupID){
									echo "<option value='" . $userGroup['userGroupID'] . "' selected>" . $userGroup['groupName'] . "</option>\n";
								}else{
									echo "<option value='" . $userGroup['userGroupID'] . "'>" . $userGroup['groupName'] . "</option>\n";
								}
							}
							?>
							</select>
						</td>

						<td style='vertical-align:top;text-align:left;width:175px;'>
							<select style='width:150px;' class='changeSelect priorStepID'>
							<option value=''></option>
							</select>

							<input type='hidden' class='priorStepKey' key='<?php echo $key; ?>' value='<?php echo $priorStep->displayOrderSequence; ?>'>
						</td>


						<td style='vertical-align:top;text-align:center;width:40px;'>
							<a href='javascript:void();'><img src='images/cross.gif' alt='remove this step' title='remove this step' class='removeStep' /></a>
						</td>

						</tr>

					<?php
					}
				}

				?>

				</table>



			</td>
			</tr>
			</table>

		</td>
		</tr>
		</table>


		<hr style='width:708px;margin:15px 0px 10px 0px;' />

		<table class='noBorderTable' style='width:125px;'>
			<tr>
				<td style='text-align:left'><input type='button' value='submit' name='submitWorkflowForm' id ='submitWorkflowForm'></td>
				<td style='text-align:right'><input type='button' value='cancel' onclick="kill(); tb_remove();"></td>
			</tr>
		</table>

		<input type='hidden' id='finalKey' value='<?php echo $key; ?>' />

		<script type="text/javascript" src="js/forms/workflowForm.js?random=<?php echo rand(); ?>"></script>

		<?php

		break;






    case 'getAdminUserGroupForm':
    	if (isset($_GET['userGroupID'])) $userGroupID = $_GET['userGroupID']; else $userGroupID = '';
    	$userGroup = new UserGroup(new NamedArguments(array('primaryKey' => $userGroupID)));


		//get all users for output in drop down
		$allUserArray = array();
		$userObj = new User();
		$allUserArray = $userObj->allAsArray();

		//get users already set up for this user group in case it's an edit
		$ugUserArray = $userGroup->getUsers();

		?>
		<div id='div_userGroupForm'>
		<form id='userGroupForm'>
		<input type='hidden' name='editUserGroupID' id='editUserGroupID' value='<?php echo $userGroupID; ?>'>

		<div class='formTitle' style='width:280px; margin-bottom:5px;position:relative;'><span class='headerText'><?php if ($userGroupID){ echo "Edit User Group"; } else { echo "Add User Group"; } ?></span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class='noBorder' style='width:100%;'>
		<tr style='vertical-align:top;'>
		<td style='vertical-align:top;position:relative;'>


			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='rule'><b>User Group</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:275px;'>
			<tr>
			<td>

				<table class='noBorder' style='width:235px; margin:15px 20px 10px 20px;'>
				<tr>
				<td style='vertical-align:top;text-align:left;'><label for='groupName'><b>Group Name:</b></label></td>
				<td>
				<input type='text' id='groupName' name='groupName' value = '<?php echo $userGroup->groupName; ?>' style='width:110px' class='changeInput' /><span id='span_error_groupName' class='smallDarkRedText'></span>
				</td>
				</tr>

				<tr>
				<td style='vertical-align:top;text-align:left;'><label for='emailAddress'><b>Email Address:</b></label></td>
				<td>
				<input type='text' id='emailAddress' name='emailAddress' value = '<?php echo $userGroup->emailAddress; ?>' style='width:110px' class='changeInput' />
				</td>
				</tr>
				</table>
			</td>
			</tr>
			</table>

			<div style='height:10px;'>&nbsp;</div>

			</td>
			</tr>
			<tr style='vertical-align:top;'>
			<td>

			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='loginID'><b>Assigned Users</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:275px;'>
			<tr>
			<td>

				<table class='noBorder smallPadding newUserTable' style='width:205px; margin:15px 35px 0px 35px;'>

				<tr class='newUserTR'>
				<td>
				<select class='changeSelect loginID' style='width:145px;'>
				<option value=''></option>
				<?php

				foreach ($allUserArray as $ugUser){
					$userObj = new User(new NamedArguments(array('primaryKey' => $ugUser['loginID'])));
					$ddDisplayName = $userObj->getDDDisplayName;
					echo "<option value='" . $ugUser['loginID'] . "'>" . $ddDisplayName . "</option>\n";
				}
				?>
				</select>
				</td>

				<td style='vertical-align:top;text-align:left;width:40px;'>
				<a href='javascript:void();'><img src='images/add.gif' class='addUser' alt='add user' title='add user'></a>
				</td>
				</tr>
				</table>
				<div class='smallDarkRedText' id='div_errorUser' style='margin:0px 35px 7px 35px;'></div>

				<table class='noBorder smallPadding userTable' style='width:205px; margin:0px 35px 0px 35px;'>
				<tr>
				<td colspan='2'>
					<hr style='width:200px;' />
				</td>
				</tr>

				<?php
				if (count($ugUserArray) > 0){

					foreach ($ugUserArray as $ugUser){
					?>
						<tr class='newUser'>
						<td>
						<select class='changeSelect loginID' style='width:145px;'>
						<option value=''></option>
						<?php
						foreach ($allUserArray as $userGroupUser){

							$userObj = new User(new NamedArguments(array('primaryKey' => $userGroupUser['loginID'])));
							$ddDisplayName = $userObj->getDDDisplayName;

							if ($ugUser->loginID == $userGroupUser['loginID']){
								echo "<option value='" . $userGroupUser['loginID'] . "' selected>" . $ddDisplayName . "</option>\n";
							}else{
								echo "<option value='" . $userGroupUser['loginID'] . "'>" . $ddDisplayName . "</option>\n";
							}
						}
						?>
						</select>
						</td>

						<td style='vertical-align:top;text-align:left;width:40px;'>
							<a href='javascript:void();'><img src='images/cross.gif' alt='remove user from group' title='remove user from group' class='remove' /></a>
						</td>
						</tr>
					<?php
					}
				}

				?>

				</table>



			</td>
			</tr>
			</table>

		</td>
		</tr>
		</table>


		<hr style='width:283px;margin-top:15px; margin-bottom:10px;' />

		<table class='noBorderTable' style='width:125px;'>
			<tr>
				<td style='text-align:left'><input type='button' value='submit' name='submitUserGroupForm' id ='submitUserGroupForm'></td>
				<td style='text-align:right'><input type='button' value='cancel' onclick="kill(); tb_remove();"></td>
			</tr>
		</table>

		</form>
		</div>

		<script type="text/javascript" src="js/forms/userGroupForm.js?random=<?php echo rand(); ?>"></script>

		<?php

        break;

	case 'getGeneralSubjectUpdateForm':
		$className = $_GET['className'];
		$updateID = $_GET['updateID'];

		if ($updateID){
			$instance = new $className(new NamedArguments(array('primaryKey' => $updateID)));
		}else{
			$instance = new $className();
		}


		?>
		<div id='div_updateForm'>

		<input type='hidden' id='editClassName' value='<?php echo $className; ?>'>
		<input type='hidden' id='editUpdateID' value='<?php echo $updateID; ?>'>

		<div class='formTitle' style='width:245px;'><span class='headerText' style='margin-left:7px;'><?php if ($updateID){ echo "Edit " . preg_replace("/[A-Z]/", " \\0" , $className); } else { echo "Add " . preg_replace("/[A-Z]/", " \\0" , $className); } ?></span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class="surroundBox" style="width:250px;">
		<tr>
		<td>

			<table class='noBorder' style='width:200px; margin:10px;'>
			<tr>
			<td><input type="text" id="updateVal" value="<?php echo $instance->shortName; ?>" style="width:190px;"/></td>
			</tr>
			</table>

		</td>
		</tr>
		</table>

		<br />
		<table class='noBorderTable' style='width:125px;'>
			<tr>

				<td style='text-align:left'><input type='button' value='submit' name='submitGeneralSubjectForm' id ='submitGeneralSubjectForm'></td>
				<td style='text-align:right'><input type='button' value='cancel' onclick="window.parent.tb_remove(); return false;"></td>
			</tr>
		</table>


		</form>
		</div>

		<script type="text/javascript" src="js/forms/generalSubjectForm.js?random=<?php echo rand(); ?>"></script>
		<?php

		break;

	case 'getDetailSubjectUpdateForm':
		$className = $_GET['className'];
		$updateID = $_GET['updateID'];

		if ($updateID){
			$instance = new $className(new NamedArguments(array('primaryKey' => $updateID)));
		}else{
			$instance = new $className();
		}


		?>
		<div id='div_updateForm'>

		<input type='hidden' id='editClassName' value='<?php echo $className; ?>'>
		<input type='hidden' id='editUpdateID' value='<?php echo $updateID; ?>'>

		<div class='formTitle' style='width:245px;'><span class='headerText' style='margin-left:7px;'><?php if ($updateID){ echo "Edit " . preg_replace("/[A-Z]/", " \\0" , $className); } else { echo "Add " . preg_replace("/[A-Z]/", " \\0" , $className); } ?></span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class="surroundBox" style="width:250px;">
		<tr>
		<td>

			<table class='noBorder' style='width:200px; margin:10px;'>
			<tr>
			<td><input type="text" id="updateVal" value="<?php echo $instance->shortName; ?>" style="width:190px;"/></td>
			</tr>
			</table>

		</td>
		</tr>
		</table>

		<br />
		<table class='noBorderTable' style='width:125px;'>
			<tr>

				<td style='text-align:left'><input type='button' value='submit' name='submitDetailedSubjectForm' id ='submitDetailedSubjectForm'></td>
				<td style='text-align:right'><input type='button' value='cancel' onclick="window.parent.tb_remove(); return false;"></td>
			</tr>
		</table>


		</form>
		</div>

		<script type="text/javascript" src="js/forms/detailedSubjectForm.js?random=<?php echo rand(); ?>"></script>
		<?php

		break;



    case 'getGeneralDetailSubjectForm':
    	if (isset($_GET['generalSubjectID'])) $generalSubjectID = $_GET['generalSubjectID']; else $generalSubjectID = '';
    	$generalSubject = new GeneralSubject(new NamedArguments(array('primaryKey' => $generalSubjectID)));


		//get all users for output in drop down
		$allDetailedSubjectArray = array();
		$detailedSubjectObj = new DetailedSubject();
		$allDetailedSubjectArray = $detailedSubjectObj->allAsArray();

		//get Detail Subjects already set up for this General subject in case it's an edit
		$dsSubjectArray = $generalSubject->getDetailedSubjects();

		?>
		<div id='div_detailedSubjectForm'>
		<form id='detailedSubjectForm'>
		<input type='hidden' name='editgeneralSubjectID' id='editgeneralSubjectID' value='<?php echo $generalSubjectID; ?>'>

		<div class='formTitle' style='width:280px; margin-bottom:5px;position:relative;'><span class='headerText'><?php echo "Add / Edit Subject Relationships"; ?></span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class='noBorder' style='width:100%;'>
		<tr style='vertical-align:top;'>
		<td style='vertical-align:top;position:relative;'>


			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='rule'><b>General Subject</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:275px;'>
			<tr>
			<td>

				<table class='noBorder' style='width:235px; margin:15px 20px 10px 20px;'>
				<tr>
				<td>&nbsp;</td>
				<td>
				<input type='text' id='shortName' name='shortName' value = '<?php echo $generalSubject->shortName; ?>' style='width:110px' class='changeInput' /><span id='span_error_groupName' class='smallDarkRedText'></span>
				</td>
				</tr>

				</table>
			</td>
			</tr>
			</table>

			<div style='height:10px;'>&nbsp;</div>

			</td>
			</tr>
			<tr style='vertical-align:top;'>
			<td>

			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='detailedSubjectID'><b>Detailed Subjects</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:275px;'>
			<tr>
			<td>

				<table class='noBorder smallPadding newdetailedSubjectTable' style='width:205px; margin:15px 35px 0px 35px;'>

					<tr class='newdetailedSubjectTR'>
						<td>
							<select class='changeSelect detailedSubjectID' style='width:145px;'>
								<option value=''></option>
								<?php

								foreach ($allDetailedSubjectArray as $dSubject){
									echo "<option value='" . $dSubject['detailedSubjectID'] . "'>" . $dSubject['shortName'] . "</option>\n";
								}
								?>
							</select>
						</td>

				<td style='vertical-align:top;text-align:left;width:40px;'>
				<a href='javascript:void();'><img src='images/add.gif' class='adddetailedSubject' alt='add detail subject' title='add detail subject'></a>
				</td>
				</tr>
				</table>
				<div class='smallDarkRedText' id='div_errordetailedSubject' style='margin:0px 35px 7px 35px;'></div>

				<table class='noBorder smallPadding detailedSubjectTable' style='width:205px; margin:0px 35px 0px 35px;'>
				<tr>
				<td colspan='2'>
					<hr style='width:200px;' />
				</td>
				</tr>

				<?php

				if (count($dsSubjectArray) > 0){
					foreach ($dsSubjectArray as $dsSubject){
					?>
						<tr class='newdetailedSubject'>
						<td>
						<select class='changeSelect detailedSubjectID' style='width:145px;'>
						<option value='<?php echo $dsSubject->detailedSubjectID ?>'><?php echo $dsSubject->shortName ?></option>
						</select>
						</td>

						<td style='vertical-align:top;text-align:left;width:40px;'>
						<?php
							// Check to see if detail subject is in use.  If not allow removal.
							$subjectObj = new DetailedSubject();
							if ($subjectObj->inUse($dsSubject->detailedSubjectID, $generalSubject->generalSubjectID) == 0)  { ?>
								<a href='javascript:void();'><img src='images/cross.gif' alt='remove detailed subject' title='remove detailed subject' class='remove' /></a>
						<?php } else { ?>
								<img src='images/do_not_enter.png' alt='subject in use' title='subject in use' />
						<?php }  ?>
						</td>
						</tr>
					<?php
					}
				}
				?>
				</table>
			</td>
			</tr>
			</table>
		</td>
		</tr>
		</table>


		<hr style='width:283px;margin-top:15px; margin-bottom:10px;' />

		<table class='noBorderTable' style='width:125px;'>
			<tr>
				<td style='text-align:left'><input type='button' value='submit' name='submitDetailSubjectForm' id ='submitDetailSubjectForm'></td>
				<td style='text-align:right'><input type='button' value='cancel' onclick="kill(); tb_remove();"></td>
			</tr>
		</table>

		</form>
		</div>

		<script type="text/javascript" src="js/forms/generalDetailSubjectForm.js?random=<?php echo rand(); ?>"></script>

		<?php

        break;



	case 'getResourceSubjectForm':
	    $resourceID = $_GET['resourceID'];
		$generalSubject = new GeneralSubject();
		$generalSubjectArray = $generalSubject->allAsArray();

	?>
		<div id='div_updateForm'>
		<div class='formTitle' style='width:403px;'><span class='headerText' style='margin-left:7px;'></span>Add General / Detail Subject Link</div>

	<?php
		if (count($generalSubjectArray) > 0){
			?>
			<table class='linedDataTable' style='width:100%'>
				<tr>
				<th>General Subject Name</th>
				<th>Detail Subject Name</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				</tr>
				<?php

				foreach($generalSubjectArray as $ug) {
					$generalSubject = new GeneralSubject(new NamedArguments(array('primaryKey' => $ug['generalSubjectID'])));

					echo "<tr>";
					echo "<td>" . $generalSubject->shortName . "</td>";
					echo "<td></td>";
					echo "<td><a href='javascript:void(0);' class='resourcesSubjectLink' resourceID='" . $resourceID . " 'generalSubjectID='" . $ug['generalSubjectID'] . " 'detailSubjectID='" . -1 . "'><img src='images/add.gif' alt='add' title='add'></a></td>";

					foreach ($generalSubject->getDetailedSubjects() as $detailedSubjects){
						echo "<tr>";
						echo "<td></td>";
						echo "<td>";
						echo $detailedSubjects->shortName . "</td>";
						echo "<td><a href='javascript:void(0);' class='resourcesSubjectLink' resourceID='" . $resourceID . " 'generalSubjectID='" . $ug['generalSubjectID'] . " 'detailSubjectID='" . $detailedSubjects->detailedSubjectID . "'><img src='images/add.gif' alt='add' title='add'></a></td>";
						echo "</tr>";
					}
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo "(none found)<br />";
		}
		?>

		<td style='text-align:right'><input type='button' value='cancel' onclick="window.parent.tb_remove(); return false;"></td>
		</div>

		<script type="text/javascript" src="js/forms/resourceSubject.js?random=<?php echo rand(); ?>"></script>

		<?php

		break;





	default:
       echo "Action " . $action . " not set up!";
       break;


}


?>


