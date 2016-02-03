<?php
	$resourceID = $_GET['resourceID'];
	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		//used to get default currency
		$config = new Configuration();
		$enhancedCostFlag = ($config->settings->enhancedCostHistory == 'Y') ? 1 : 0;

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

		// Table geometry is different if enhanced cost history is enabled
		$baseWidth = 345;
		$numCols = 6;
		if ($enhancedCostFlag){
			$baseWidth += 388;
			$numCols += 5; // year, sub start, sub end, cost details, invoice num
		}
?>
		<div id='div_resourceForm'>
		<form id='resourceForm'>
		<input type='hidden' name='editResourceID' id='editResourceID' value='<?php echo $resourceID; ?>'>

		<div class='formTitle' style='width:<?php echo $baseWidth + 46 ?>px; margin-bottom:5px;'><span class='headerText'>Edit Cost Information</span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class='noBorder' style='width:<?php echo $baseWidth + 45 ?>px;'>
		<tr style='vertical-align:top;'>
		<td>
			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='resourcePayments'><b>Cost History</b></label>&nbsp;&nbsp;</span>
			<table class='surroundBox' style='width:<?php echo $baseWidth - 65; ?>px;'>
			<tr>
			<td>
				<table class='noBorder smallPadding newPaymentTable' style='margin:7px 15px 0 15px;'>
				<tr>
					<?php if ($enhancedCostFlag){ ?>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'>Year</td>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'>Sub Start</td>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'>Sub End</td>
					<?php } ?>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'>Fund</td>
					<td style='vertical-align:top;text-align:left;font-weight:bold;' colspan=2>Payment</td>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'>Type</td>
					<?php if ($enhancedCostFlag){ ?>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'>Cost Details</td>
					<?php } ?>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'>Note</td>
					<?php if ($enhancedCostFlag){ ?>
					<td style='vertical-align:top;text-align:left;font-weight:bold;'>Invoice</td>
					<?php } ?>
					<td>&nbsp;</td>
				</tr>

		<tr class='newPaymentTR'>
		<?php if ($enhancedCostFlag){ ?>
		<td style='vertical-align:top;text-align:left;background:white;'>
		<input type='text' value='' style='width:53px;' class='changeDefaultWhite changeInput year' /></td>
		<td style='vertical-align:top;text-align:left;background:white;'>
		<input type='text' value='' style='width:60px;' class='date-pick changeDefaultWhite changeInput subscriptionStartDate' /></td>
		<td style='vertical-align:top;text-align:left;background:white;'>
		<input type='text' value='' style='width:60px;' class='date-pick changeDefaultWhite changeInput subscriptionEndDate' /></td>
		<?php } ?>
		<td style='vertical-align:top;text-align:left;background:white;'>
			<select class='changeDefaultWhite changeInput fundID' id='searchFundID' style='width:150px'>
				<option value='' selected></option>
				<?php
						$FundType = new Fund();

//						foreach($FundType->getUnArchivedFunds() as $fund) {
//							echo "<option value='" . $fund['fundID'] . "'>" . $fund['shortName'] . " [" . $fund['fundCode'] . "]</option>";
//						}

		foreach($FundType->getUnArchivedFunds() as $fund) {
				$fundCodeLength = strlen($fund['fundCode']) + 3;
				$combinedLength = strlen($fund['shortName']) + $fundCodeLength;
				$fundName = ($combinedLength <=50) ? $fund['shortName'] : substr($fund['shortName'],0,49-$fundCodeLength) . "&hellip;";
				$fundName .= " [" . $fund['fundCode'] . "]</option>";
				echo "<option value='" . $fund['fundID'] . "'>" . $fundName . "</option>";
		}

						?>
			</select>
		</td>
		<td style='vertical-align:top;text-align:left;background:white;'>
		<input type='text' value='' style='width:50px;' class='changeDefaultWhite changeInput paymentAmount' />
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
			<select style='width:60px;' class='changeSelect orderTypeID'>
			<option value='' selected></option>
			<?php
			foreach ($orderTypeArray as $orderType){
				echo "<option value='" . $orderType['orderTypeID'] . "'>" . $orderType['shortName'] . "</option>\n";
			}
			?>
			</select>
		</td>
		<?php if ($enhancedCostFlag){ ?>
		<td style='vertical-align:top;text-align:left;'>
			<select style='width:75px;' class='changeSelect costDetailsID'>
			<option value=''></option>
			<?php
			foreach ($costDetailsArray as $costDetails){
				echo "<option value='" . $costDetails['costDetailsID'] . "'>" . $costDetails['shortName'] . "</option>\n";
			}
			?>
			</select>
		</td>
		<?php } ?>
		<td style='vertical-align:top;text-align:left;background:white;'>
		<input type='text' value='' style='width:70px;' class='changeDefaultWhite changeInput costNote' />
		</td>
		<?php if ($enhancedCostFlag){ ?>
			<td style='vertical-align:top;text-align:left;background:white;'>
			<input type='text' value='' style='width:50px;' class='changeDefaultWhite changeInput invoiceNum' />
			</td>
		<?php } ?>
<!--
		<td style='vertical-align:center;text-align:center;width:37px;'>
		<a href='javascript:void();'><img src='images/add.gif' class='addPayment' alt='add this payment' title='add payment'></a>
		</td>
-->
		</tr>

		<tr>
			<td colspan='<?php echo $numCols; ?>' style='text-align:right;'>
				<a href='javascript:void();' class='addPayment'>add a row</a>
			</td>
		</tr>

		<tr>
		<td colspan='<?php echo $numCols; ?>'>
		<div class='smallDarkRedText' id='div_errorPayment' style='margin:0px 20px 0px 26px;'></div>
		<hr style='width:<?php echo $baseWidth; ?>px;margin:0px 0px 5px 5px;' />
		</td>
		</tr>
	</table>
	<table class='noBorder smallPadding paymentTable' style='margin:7px 15px 0 15px;'>
<?php
if (count($paymentArray) > 0){
	foreach ($paymentArray as $payment){
?>
		<tr>
		<?php if ($enhancedCostFlag){ ?>
		<td style='vertical-align:top;text-align:left;'>
		<input type='text' value='<?php echo $payment['year']; ?>' style='width:53px;' class='changeInput year' />
		</td>
		<td style='vertical-align:top;text-align:left;'>
		<input type='text' value='<?php echo normalize_date($payment['subscriptionStartDate']); ?>' style='width:60px;' class='date-pick changeInput subscriptionStartDate' /></td>
		<td style='vertical-align:top;text-align:left;'>
		<input type='text' value='<?php echo normalize_date($payment['subscriptionEndDate']); ?>' style='width:60px;' class='date-pick changeInput subscriptionEndDate' /></td>
		<?php } ?>
		<td style='vertical-align:top;text-align:left;'>
			<select class='changeDefaultWhite changeInput fundID' id='searchFundID' style='width:150px'>
				<option value=''></option>
				<?php
						$FundType = new Fund();
						$Funds = array();
						if (array_key_exists('fundID', $payment) && isset($payment['fundID'])) {
							$Funds = $FundType->getUnArchivedFundsForCostHistory($payment['fundID']);
						}else{
							$Funds = $FundType->getUnArchivedFunds();
						}
				foreach($Funds as $fund) {
						$fundCodeLength = strlen($fund['fundCode']) + 3;
						$combinedLength = strlen($fund['shortName']) + $fundCodeLength;
						$fundName = ($combinedLength <=50) ? $fund['shortName'] : substr($fund['shortName'],0,49-$fundCodeLength) . "&hellip;";
						$fundName .= " [" . $fund['fundCode'] . "]</option>";
						echo "<option";
						if ($payment['fundID'] == $fund['fundID'])
						{
														echo " selected";
						}
						echo " value='" . $fund['fundID'] . "'>" . $fundName . "</option>";
				}

						?>
			</select>
		</td>
		<td style='vertical-align:top;text-align:left;'>
		<input type='text' value='<?php echo integer_to_cost($payment['paymentAmount']); ?>' style='width:50px;' class='changeInput paymentAmount' />
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
		<select style='width:60px;' class='changeSelect orderTypeID'>
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

		<?php if ($enhancedCostFlag){ ?>
			<td style='vertical-align:top;text-align:left;'>
			<select style='width:75px;' class='changeSelect costDetailsID'>
			<option value=''></option>
			<?php
			foreach ($costDetailsArray as $costDetails){
				if (trim(strval($costDetails['costDetailsID'])) == trim(strval($payment['costDetailsID']))){
				echo "<option value='" . $costDetails['costDetailsID'] . "' selected class='changeSelect'>" . $costDetails['shortName'] . "</option>\n";
				}else{
				echo "<option value='" . $costDetails['costDetailsID'] . "' class='changeSelect'>" . $costDetails['shortName'] . "</option>\n";
				}
			}
			?>
			</select>
			</td>
		<?php } ?>
		<td style='vertical-align:top;text-align:left;'>
		<input type='text' value='<?php echo $payment['costNote']; ?>' style='width:70px;' class='changeInput costNote' />
		</td>
		<?php if ($enhancedCostFlag){ ?>
			<td style='vertical-align:top;text-align:left;'>
			<input type='text' value='<?php echo $payment['invoiceNum']; ?>' style='width:50px;' class='changeInput invoiceNum' />
			</td>
		<?php } ?>
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
				<td style='text-align:left'><input type='button' value='submit' name='submitCost' id ='submitCost'></td>
				<td style='text-align:right'><input type='button' value='cancel' onclick="kill(); tb_remove();"></td>
			</tr>
		</table>


		<script type="text/javascript" src="js/forms/costForm.js?random=<?php echo rand(); ?>"></script>
