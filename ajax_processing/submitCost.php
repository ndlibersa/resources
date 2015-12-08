<?php
		$resourceID = $_POST['resourceID'];
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		try {
			$resource->save();

			//first remove all payment records, then we'll add them back
			$resource->removeResourcePayments();

			$yearArray          = array();  $yearArray          = explode(':::',$_POST['years']);
			$subStartArray      = array();  $subStartArray      = explode(':::',$_POST['subStarts']);
			$subEndArray        = array();  $subEndArray        = explode(':::',$_POST['subEnds']);
			$fundIDArray        = array();  $fundIDArray      = explode(':::',$_POST['fundIDs']);
			$paymentAmountArray = array();  $paymentAmountArray = explode(':::',$_POST['paymentAmounts']);
			$currencyCodeArray  = array();  $currencyCodeArray  = explode(':::',$_POST['currencyCodes']);
			$orderTypeArray     = array();  $orderTypeArray     = explode(':::',$_POST['orderTypes']);
			$costDetailsArray   = array();  $costDetailsArray   = explode(':::',$_POST['costDetails']);
			$costNoteArray      = array();  $costNoteArray      = explode(':::',$_POST['costNotes']);
			$invoiceArray       = array();  $invoiceArray       = explode(':::',$_POST['invoices']);
			foreach ($orderTypeArray as $key => $value){
				if (($value) && ($paymentAmountArray[$key] || $yearArray[$key] || $fundIDArray[$key] || $costNoteArray[$key])){
					$resourcePayment = new ResourcePayment();
					$resourcePayment->resourceID    = $resourceID;
					$resourcePayment->year          = $yearArray[$key];
					$start = $subStartArray[$key] ? date("Y-m-d", strtotime($subStartArray[$key])) : null;
					$end   = $subEndArray[$key]   ? date("Y-m-d", strtotime($subEndArray[$key]))   : null;
					$resourcePayment->subscriptionStartDate = $start;
					$resourcePayment->subscriptionEndDate   = $end;
					$resourcePayment->fundID        = $fundIDArray[$key];
					$resourcePayment->paymentAmount = cost_to_integer($paymentAmountArray[$key]);
					$resourcePayment->currencyCode  = $currencyCodeArray[$key];
					$resourcePayment->orderTypeID   = $value;
					$resourcePayment->costDetailsID = $costDetailsArray[$key];
					$resourcePayment->costNote      = $costNoteArray[$key];
					$resourcePayment->invoiceNum    = $invoiceArray[$key];
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
