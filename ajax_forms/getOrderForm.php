<?php
	$resourceID = $_GET['resourceID'];
	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		//used to get default currency
		$config = new Configuration();

		$startDate = normalize_date($resource->currentStartDate);
		$endDate = normalize_date($resource->currentEndDate);

		//get all purchase sites for output in checkboxes
		$purchaseSiteArray = array();
		$purchaseSiteObj = new PurchaseSite();
		$purchaseSiteArray = $purchaseSiteObj->allAsArray();

		//get all acquisition types for output in drop down
		$acquisitionTypeArray = array();
		$acquisitionTypeObj = new AcquisitionType();
		$acquisitionTypeArray = $acquisitionTypeObj->allAsArray();

		//get purchase sites
		$sanitizedInstance = array();
		$instance = new PurchaseSite();
		$resourcePurchaseSiteArray = array();
		foreach ($resource->getResourcePurchaseSites() as $instance) {
			$resourcePurchaseSiteArray[] = $instance->purchaseSiteID;
		}
?>
		<div id='div_resourceForm'>
		<form id='resourceForm'>
		<input type='hidden' name='editResourceID' id='editResourceID' value='<?php echo $resourceID; ?>'>

		<div class='formTitle' style='width:420px; margin-bottom:5px;'><span class='headerText'><?php echo _("Edit Acquisitions Information");?></span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class='noBorder' style='width:435px;'>
		<tr style='vertical-align:top;'>
		<td style='vertical-align:top; padding-right:35px;'>
			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='orderInformation'><b><?php echo _("Order");?></b></label>&nbsp;&nbsp;</span>
			<table class='surroundBox' style='width:350px;'>
			<tr>
			<td>
				<table class='noBorder' style='width:310px; margin:15px 20px;'>
				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold'><label for='acquisitionTypeID'><?php echo _("Acquisition Type:");?></label></td>
				<td>
				<select name='acquisitionTypeID' id='acquisitionTypeID' style='width:100px;' class='changeSelect'>
				<option value=''></option>
				<?php
				foreach ($acquisitionTypeArray as $acquisitionType){
					if (trim(strval($acquisitionType['acquisitionTypeID'])) == trim(strval($resource->acquisitionTypeID))){
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
				<td style='vertical-align:top;text-align:left;font-weight:bold'><label for='orderNumber'><?php echo _("Order Number:");?></label></td>
				<td><input type='text' id='orderNumber' name='orderNumber' value = '<?php echo $resource->orderNumber; ?>' style='width:95px;' class='changeInput' /></td>
				</tr>

				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold'><label for='systemNumber'><?php echo _("System Number:");?></label></td>
				<td><input type='text' id='systemNumber' name='systemNumber' value = '<?php echo $resource->systemNumber; ?>' style='width:95px;' class='changeInput' /></td>
				</tr>

				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold'><label for='currentStartDate'><?php echo _("Sub Start:");?></label></td>
				<td><input class='date-pick' id='currentStartDate' name='currentStartDate' value = '<?php echo $startDate; ?>' style='width:75px;' /></td>
				</tr>

				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold'><label for='currentEndDate'><?php echo _("Current Sub End:");?></label></td>
				<td><input class='date-pick' id='currentEndDate' name='currentEndDate' value = '<?php echo $endDate; ?>' style='width:75px;' />
				</td>
				</tr>

<?php if ($config->settings->enableAlerts == 'Y'){ ?>
				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold'>&nbsp;</td>
				<td>
				<div class="checkboxes" style='text-align:left;'>
					<label><input id='subscriptionAlertEnabledInd' type='checkbox' style='text-align:bottom' value='1' <?php if($resource->subscriptionAlertEnabledInd == 1) { echo "checked"; } ?> />&nbsp;<span><?php echo _("Enable Alert");?></span></label>
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




			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='sitePurchaserID'><b><?php echo _("Purchasing Site(s)");?></b></label>&nbsp;&nbsp;</span>

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
				<td style='text-align:left'><input type='button' value='<?php echo _("submit");?>' name='submitOrder' id ='submitOrder' class='submit-button'></td>
				<td style='text-align:right'><input type='button' value='<?php echo _("cancel");?>' onclick="kill(); tb_remove();" class='cancel-button'></td>
			</tr>
		</table>


		<script type="text/javascript" src="js/forms/acquisitionsForm.js?random=<?php echo rand(); ?>"></script>

