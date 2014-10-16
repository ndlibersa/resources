<?php
		$resourceID = $_POST['resourceID'];
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		//first set subscription start Date for proper saving
		if ((isset($_POST['subscriptionStartDate'])) && ($_POST['subscriptionStartDate'] != '')){
			$resource->subscriptionStartDate = date("Y-m-d", strtotime($_POST['subscriptionStartDate']));
		}else{
			$resource->subscriptionStartDate= 'null';
		}

		//first set subscription start Date for proper saving
		if ((isset($_POST['subscriptionEndDate'])) && ($_POST['subscriptionEndDate'] != '')){
			$resource->subscriptionEndDate = date("Y-m-d", strtotime($_POST['subscriptionEndDate']));
		}else{
			$resource->subscriptionEndDate= 'null';
		}

		$resource->acquisitionTypeID 				= $_POST['acquisitionTypeID'];
		$resource->orderNumber 						= $_POST['orderNumber'];
		$resource->systemNumber 					= $_POST['systemNumber'];
		$resource->subscriptionAlertEnabledInd 		= $_POST['subscriptionAlertEnabledInd'];

		try {
			$resource->save();

			//first remove all administering sites, then we'll add them back
			$resource->removePurchaseSites();

			foreach (explode(':::',$_POST['purchaseSites']) as $key => $value){
				if ($value){
					$resourcePurchaseSiteLink = new ResourcePurchaseSiteLink();
					$resourcePurchaseSiteLink->resourceID = $resourceID;
					$resourcePurchaseSiteLink->purchaseSiteID = $value;
					try {
						$resourcePurchaseSiteLink->save();
					} catch (Exception $e) {
						echo $e->getMessage();
					}
				}
			}

			//first remove all payment records, then we'll add them back
			$resource->removeResourcePayments();

			$orderTypeArray = array();
			$orderTypeArray = explode(':::',$_POST['orderTypes']);
			$fundNameArray = array();
			$fundNameArray = explode(':::',$_POST['fundNames']);
			$paymentAmountArray = array();
			$paymentAmountArray = explode(':::',$_POST['paymentAmounts']);
			$currencyCodeArray = array();
			$currencyCodeArray = explode(':::',$_POST['currencyCodes']);

			foreach ($orderTypeArray as $key => $value){
				if (($value) && (($paymentAmountArray[$key]) || ($fundNameArray[$key]))){
					$resourcePayment = new ResourcePayment();
					$resourcePayment->resourceID = $resourceID;
					$resourcePayment->orderTypeID = $value;
					$resourcePayment->fundName = $fundNameArray[$key];
					$resourcePayment->currencyCode = $currencyCodeArray[$key];
					$resourcePayment->paymentAmount = cost_to_integer($paymentAmountArray[$key]);

					try {
						$resourcePayment->save();
					} catch (Exception $e) {
						echo $e->getMessage();
					}
				}
			}


		} catch (Exception $e) {
			echo $e->getMessage();
		}

?>

