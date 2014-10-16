<?php
		$resourceID = $_POST['resourceID'];
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		//first set current start Date for proper saving
		if ((isset($_POST['currentStartDate'])) && ($_POST['currentStartDate'] != '')){
			$resource->currentStartDate = date("Y-m-d", strtotime($_POST['currentStartDate']));
		}else{
			$resource->currentStartDate= 'null';
		}

		//first set current end Date for proper saving
		if ((isset($_POST['currentEndDate'])) && ($_POST['currentEndDate'] != '')){
			$resource->currentEndDate = date("Y-m-d", strtotime($_POST['currentEndDate']));
		}else{
			$resource->currentEndDate= 'null';
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

			$yearArray          = array();  $yearArray          = explode(':::',$_POST['years']);
			$subStartArray      = array();  $subStartArray      = explode(':::',$_POST['subStarts']);
			$subEndArray        = array();  $subEndArray        = explode(':::',$_POST['subEnds']);
			$fundNameArray      = array();  $fundNameArray      = explode(':::',$_POST['fundNames']);
			$paymentAmountArray = array();  $paymentAmountArray = explode(':::',$_POST['paymentAmounts']);
			$currencyCodeArray  = array();  $currencyCodeArray  = explode(':::',$_POST['currencyCodes']);
			$orderTypeArray     = array();  $orderTypeArray     = explode(':::',$_POST['orderTypes']);
			$costDetailsArray   = array();  $costDetailsArray   = explode(':::',$_POST['costDetails']);
			$costNoteArray      = array();  $costNoteArray      = explode(':::',$_POST['costNotes']);
			$invoiceArray       = array();  $invoiceArray       = explode(':::',$_POST['invoices']);

			foreach ($orderTypeArray as $key => $value){
				if (($value) && ($paymentAmountArray[$key] || $yearArray[$key] || $fundNameArray[$key] || $costNoteArray[$key])){
					$resourcePayment = new ResourcePayment();
					$resourcePayment->resourceID    = $resourceID;
					$resourcePayment->year          = $yearArray[$key];
                    $resourcePayment->subscriptionStartDate = $subStartArray[$key];
                    $resourcePayment->subscriptionEndDate   = $subEndArray[$key];
					$resourcePayment->fundName      = $fundNameArray[$key];
					$resourcePayment->paymentAmount = cost_to_integer($paymentAmountArray[$key]);
					$resourcePayment->currencyCode  = $currencyCodeArray[$key];
					$resourcePayment->orderTypeID   = $value;
					$resourcePayment->costDetails   = $costDetailsArray[$key];
					$resourcePayment->costNote      = $costNoteArray[$key];
					$resourcePayment->invoice       = $invoiceArray[$key];
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

		break;


