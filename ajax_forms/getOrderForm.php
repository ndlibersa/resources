<?php
    	$resourceID = $_GET['resourceID'];
    	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		//used to get default currency
		$config = new Configuration();

		//some dates get in as 0000-00-00
		if (($resource->subscriptionStartDate == "0000-00-00") || ($resource->subscriptionStartDate == "")){
			$startDate='';
		}else{
			$startDate=format_date($resource->subscriptionStartDate);
		}

		if (($resource->subscriptionEndDate == "0000-00-00") || ($resource->subscriptionEndDate == "")){
			$endDate='';
		}else{
			$endDate=format_date($resource->subscriptionEndDate);
		}

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
				<td style='vertical-align:top;text-align:left;font-weight:bold'><label for='subscriptionStartDate'>Subscription Start:</label></td>
				<td><input class='date-pick' id='subscriptionStartDate' name='subscriptionStartDate' value = '<?php echo $startDate; ?>' style='width:75px;' /></td>
				</tr>

				<tr>
				<td style='vertical-align:top;text-align:left;font-weight:bold'><label for='subscriptionEndDate'>Subscription End:</label></td>
				<td><input class='date-pick' id='subscriptionEndDate' name='subscriptionEndDate' value = '<?php echo $endDate; ?>' style='width:75px;' />
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

			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='resourcePayments'><b>Initial Cost</b></label>&nbsp;&nbsp;</span>

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

