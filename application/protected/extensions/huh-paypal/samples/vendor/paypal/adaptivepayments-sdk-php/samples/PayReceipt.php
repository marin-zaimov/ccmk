<?php
/**
 * PayReceipt.php
 * This file is called after the user clicks on a button during
 * the Pay process to use PayPal's AdaptivePayments Pay features'. The
 * user logs in to their PayPal account.
 * Called by Pay.php
 */
require_once('PPBootStrap.php');
require_once('Common/Constants.php');
define("DEFAULT_SELECT", "- Select -");

$logger = new PPLoggingManager('Pay');
if(isset($_POST['receiverEmail'])) {
	$receiver = array();
	for($i=0; $i<count($_POST['receiverEmail']); $i++) {
		$receiver[$i] = new Receiver();
		$receiver[$i]->email = $_POST['receiverEmail'][$i];
		$receiver[$i]->amount = $_POST['receiverAmount'][$i];
		$receiver[$i]->primary = $_POST['primaryReceiver'][$i];

		if($_POST['invoiceId'][$i] != "") {
			$receiver[$i]->invoiceId = $_POST['invoiceId'][$i];
		}
		if($_POST['paymentType'][$i] != "" && $_POST['paymentType'][$i] != DEFAULT_SELECT) {
			$receiver[$i]->paymentType = $_POST['paymentType'][$i];
		}
		if($_POST['paymentSubType'][$i] != "") {
			$receiver[$i]->paymentSubType = $_POST['paymentSubType'][$i];
		}
		if($_POST['phoneCountry'][$i] != "" && $_POST['phoneNumber'][$i]) {
			$receiver[$i]->phone = new PhoneNumberType($_POST['phoneCountry'][$i], $_POST['phoneNumber'][$i]);
			if($_POST['phoneExtn'][$i] != "") {
				$receiver[$i]->phone->extension = $_POST['phoneExtn'][$i];
			}
		}
	}
	$receiverList = new ReceiverList($receiver);
}


$payRequest = new PayRequest(new RequestEnvelope("en_US"), $_POST['actionType'], $_POST['cancelUrl'], $_POST['currencyCode'], $receiverList, $_POST['returnUrl']);
// Add optional params
if($_POST["feesPayer"] != "") {
	$payRequest->feesPayer = $_POST["feesPayer"];
}
if($_POST["preapprovalKey"] != "") {
	$payRequest->preapprovalKey  = $_POST["preapprovalKey"];
}
if($_POST['ipnNotificationUrl'] != "") {
	$payRequest->ipnNotificationUrl = $_POST['ipnNotificationUrl'];
}
if($_POST["memo"] != "") {
	$payRequest->memo = $_POST["memo"];
}
if($_POST["pin"] != "") {
	$payRequest->pin  = $_POST["pin"];
}
if($_POST['preapprovalKey'] != "") {
	$payRequest->preapprovalKey  = $_POST["preapprovalKey"];
}
if($_POST['reverseAllParallelPaymentsOnError'] != "") {
	$payRequest->reverseAllParallelPaymentsOnError  = $_POST["reverseAllParallelPaymentsOnError"];
}
if($_POST['senderEmail'] != "") {
	$payRequest->senderEmail  = $_POST["senderEmail"];
}
if($_POST['trackingId'] != "") {
	$payRequest->trackingId  = $_POST["trackingId"];
}
if($_POST['fundingConstraint'] != "" && $_POST['fundingConstraint'] != DEFAULT_SELECT) {
	$payRequest->fundingConstraint = new FundingConstraint();
	$payRequest->fundingConstraint->allowedFundingType = new FundingTypeList();
	$payRequest->fundingConstraint->allowedFundingType->fundingTypeInfo = array();
	$payRequest->fundingConstraint->allowedFundingType->fundingTypeInfo[]  = new FundingTypeInfo($_POST["fundingConstraint"]);
}

if($_POST['emailIdentifier'] != "" || $_POST['senderCountryCode'] != "" || $_POST['senderPhoneNumber'] != "" 
		|| $_POST['senderExtension'] != "" || $_POST['useCredentials'] != "" ) {
	$payRequest->sender = new SenderIdentifier();
	if($_POST['emailIdentifier'] != "") {
		$payRequest->sender->email  = $_POST["emailIdentifier"];
	}
	if($_POST['senderCountryCode'] != "" || $_POST['senderPhoneNumber'] != "" || $_POST['senderExtension'] != "") {
		$payRequest->sender->phone = new PhoneNumberType();
		if($_POST['senderCountryCode'] != "") {
			$payRequest->sender->phone->countryCode  = $_POST["senderCountryCode"];
		}
		if($_POST['senderPhoneNumber'] != "") {
			$payRequest->sender->phone->phoneNumber  = $_POST["senderPhoneNumber"];
		}
		if($_POST['senderExtension'] != "") {
			$payRequest->sender->phone->extension  = $_POST["senderExtension"];
		}
	}
	if($_POST['useCredentials'] != "") {
		$payRequest->sender->useCredentials  = $_POST["useCredentials"];
	}
}
$service = new AdaptivePaymentsService();
try {
	$response = $service->Pay($payRequest);
} catch(Exception $ex) {
	require_once 'Common/Error.php';
	exit;
}
$logger->log("Received payResponse:");
/* Make the call to PayPal to get the Pay token
 If the API call succeded, then redirect the buyer to PayPal
to begin to authorize payment.  If an error occured, show the
resulting errors */


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
<title>PayPal Adaptive Payments - Pay Response</title>
<link href="Common/sdk.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="Common/tooltip.js">
    </script>
</head>

<body>	
	<div id="wrapper">
		<div id="response_form">
			<h3>Pay - Response</h3>			
<?php
$ack = strtoupper($response->responseEnvelope->ack);
if($ack != "SUCCESS") {
	echo "<b>Error </b>";
	echo "<pre>";
	print_r($response);
	echo "</pre>";
} else {
	$payKey = $response->payKey;
	if(($response->paymentExecStatus == "COMPLETED" )) {
		$case ="1";
	} else if(($_POST['actionType']== "PAY") && ($response->paymentExecStatus == "CREATED" )) {
		$case ="2";
	} else if(($_POST['preapprovalKey'] != null ) && ($_POST['actionType'] == "CREATE") && ($response->paymentExecStatus == "CREATED" )) {
		$case ="3";
	} else if(($_POST['actionType']== "PAY_PRIMARY")) {
		$case ="4";
	} else if(($_POST['actionType']== "CREATE") && ($response->paymentExecStatus == "CREATED" )) {
		$apiCred = PPCredentialManager::getInstance()->getCredentialObject(null);
		if(str_replace('_api1.', '@', $apiCred->getUserName()) == $_POST["senderEmail"]) {
			$case ="3";
		} else {
			$case ="2";
		}
	}
	$token = $response->payKey;
	$payPalURL = PAYPAL_REDIRECT_URL . '_ap-payment&paykey=' . $token;
	switch($case) {
		case "1" :
			echo "<table>";
			echo "<tr><td>Ack :</td><td><div id='Ack'>$ack</div> </td></tr>";
			echo "<tr><td>PayKey :</td><td><div id='PayKey'>$payKey</div> </td></tr>";
			echo "</table>";
			break;
		case "2" :
			echo "<table>";
			echo "<tr><td>Ack :</td><td><div id='Ack'>$ack</div> </td></tr>";
			echo "<tr><td>PayKey :</td><td><div id='PayKey'>$payKey</div> </td></tr>";
			echo "<tr><td><a href=$payPalURL><b>Redirect URL to Complete Payment </b></a></td></tr>";
			echo "</table>";
			break;
		case "3" :
			echo "<table>";
			echo "<tr><td>Ack :</td><td><div id='Ack'>$ack</div> </td></tr>";
			echo "<tr><td>PayKey :</td><td><div id='PayKey'>$payKey</div> </td></tr>";
			echo "<tr><td><a href=$payPalURL><b>Redirect URL to Complete Payment </b></a></td></tr>";
			echo "<tr><td><a href=SetPaymentOption.php?payKey=$payKey><b>Set Payment Options(optional)</b></a></td></tr>";
			echo "<tr><td><a href=ExecutePaymentOption.php?payKey=$payKey><b>Execute Payment Options</b></a></td></tr>";
			echo "</table>";
			break;
		case "4" :
			echo"Payment to \"Primary Receiver\" is Complete<br/>";
			echo"<a href=ExecutePaymentOption.php?payKey=$payKey><b>* \"Execute Payment\" to pay to the secondary receivers</b></a><br>";
			break;
	}
	echo "<pre>";
	print_r($response);
	echo "</pre>";	
}
require_once 'Common/Response.php';
?>
		</div>
	</div>
</body>
</html>
