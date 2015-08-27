<?php

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

		//get all Cost Details for output in drop down
		$costDetailsArray = array();
		$costDetailsObj = new CostDetails();
		$costDetailsArray = $costDetailsObj->allAsArray();

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
		<div class='smallDarkRedText' style='height:14px;margin:3px 0px 0px 0px;'>&nbsp;* required fields<span class="smallBlueText" id="searchFieldsText">  <img src= 'images/loupe.png'/> search fields</span> </div>

		<table class='noBorder'>
		<tr style='vertical-align:top;'>
		<td style='vertical-align:top; padding-right:15px;'>

			<span class='surroundBoxTitle'>&nbsp;&nbsp;<b>Product</b>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:370px;'>
			<tr>
			<td>

				<table class='noBorder' style='width:310px; margin:5px 15px;'>

					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='titleText'>Name:&nbsp;&nbsp;<span class='bigDarkRedText'>*</span></label></td>
					<td><input type='text' id='titleText' style='width:220px;' class='changeInput' value="<?php echo $resource->titleText; ?>" /><span id='span_error_titleText' class='smallDarkRedText'></span></td>
					<td ><img class="loupe" src= 'images/loupe.png'/></td/>
					</tr>

					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='descriptionText'>Description:</label></td>
					<td><textarea rows='3' id='descriptionText' style='width:223px'><?php echo $resource->descriptionText; ?></textarea></td>
					</tr>

					<tr>
					<td style="vertical-align:top;text-align:left;"><label for="ISSNText">ISSN:</label></td>
					<td><input type="text" id="ISSNText" style="width:220px;" class="changeInput ac_input idleField" value="" autocomplete="off"><span id="span_error_providerText" class="smallDarkRedText"></span></td>
					<td ><img class="loupe" src= 'images/loupe.png'/></td/>
					</tr>

					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='providerText'>Provider:</label></td>
					<td><input type='text' id='providerText' style='width:220px;' class='changeInput' value='<?php echo $providerText; ?>' /><span id='span_error_providerText' class='smallDarkRedText'></span></td>
					<td ><img class="loupe" src= 'images/loupe.png'/></td/>
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

			<table class='surroundBox' style='width:370px;'>
			<tr>
			<td>
<span id='span_error_resourceFormatID' class='smallDarkRedText'></span>

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

					echo "<td><input type='radio' name='resourceFormatID' id='resourceFormatID' value='" . $resourceFormat['resourceFormatID'] . "' " . $checked . " />  " . $resourceFormat['shortName'] . "</td>";

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

			<table class='surroundBox' style='width:370px;'>
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

//					echo "<td><input type='radio' name='resourceTypeID' id='resourceTypeID' value=' " . $resourceType['resourceTypeID'] . "' " . $checked . "/>" . $resourceType['shortName'] . "</td>\n";
            echo "<td><input type='radio' name='resourceTypeID' id='resourceTypeID' value=' " . $resourceType['resourceTypeID'] . "' " . $checked . "/><label for='resourceTypeID'> ".$resourceType['shortName'] . "</label></td>";

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
				<td style="text-align:left"><input type="button" value="Search on GOKb" class="searchGokb" id="search"></td>
				<td style='text-align:left'><input type='button' value='Save' class='submitResource' id ='save'></td>
				<td style='text-align:left'><input type='button' value='Submit' class='submitResource' id ='progress'></td>
				<td style='text-align:left'><input type='button' value='Cancel' onclick="kill(); tb_remove()"></td>
			</tr>
			<tr>
				<span id='span_error_search' class='smallDarkRedText'></span>
			</tr>
		</table>


		</form>
		</div>

		<script type="text/javascript" src="js/forms/resourceNewForm.js?random=<?php echo rand(); ?>"></script>


