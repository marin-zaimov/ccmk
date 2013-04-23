<?php
//require("PPBootStrap.php");
//require("PPLoggingManager.php");
require_once("protected/extensions/paypal/lib/PayPal/Api/Payment.php");
require_once("protected/extensions/paypal/lib/PayPal/Api/Resource.php");
require_once("protected/extensions/paypal/lib/PayPal/Api/Address.php");
require_once("protected/extensions/paypal/lib/PayPal/Api/CreditCard.php");
require_once("protected/extensions/paypal/lib/PayPal/Api/FundingInstrument.php");
require_once("protected/extensions/paypal/lib/PayPal/Api/Payer.php");
require_once("protected/extensions/paypal/lib/PayPal/Api/AmountDetails.php");
require_once("protected/extensions/paypal/lib/PayPal/Api/Transaction.php");
require_once("protected/extensions/paypal/lib/PayPal/Api/Amount.php");


class TestController extends BaseController
{

	//http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/group/getbyId?groupId=1
	public function actionTest()
  {

// is cURL installed yet?
/*if (!function_exists('curl_init')){
die('Sorry cURL is not installed!');
}

// OK cool - then let's create a new cURL resource handle
$ch = curl_init();

// Now set some options (most are optional)

// Set URL to download
curl_setopt($ch, CURLOPT_URL, 'www.google.com');

// Set a referer
curl_setopt($ch, CURLOPT_REFERER, "http://www.example.org/yay.htm");

// User agent
curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");

// Include header in result? (0 = yes, 1 = no)
curl_setopt($ch, CURLOPT_HEADER, 0);

// Should cURL return or print out the data? (true = return, false = print)
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Timeout in seconds
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

// Download the given URL, and return output
$output = curl_exec($ch);

// Close the cURL resource, and free system resources
curl_close($ch);

var_dump($output);
die;*/


    $srcOj = getcwd() . "/protected/extensions/paypal/vendor/paypal/sdk-core-php/config/sdk_config.ini.oj";
    $srcBox = getcwd() . "/protected/extensions/paypal/vendor/paypal/sdk-core-php/config/sdk_config.ini.box";
    $dst = getcwd() . "/protected/extensions/paypal/vendor/paypal/sdk-core-php/config/sdk_config.ini";
    copy($srcBox, $dst);

var_dump("hit");
die;

    $addr = new PayPal\Api\Address();
    $addr->setLine1('52 N Main ST');
    $addr->setCity('Johnstown');
    $addr->setCountry_code('US');
    $addr->setPostal_code('43210');
    $addr->setState('OH');

    $card = new PayPal\Api\CreditCard();
    $card->setNumber('4417119669820331');
    $card->setType('visa');
    $card->setExpire_month('11');
    $card->setExpire_year('2018');
    $card->setCvv2('874');
    $card->setFirst_name('Joe');
    $card->setLast_name('Shopper');
    $card->setBilling_address($addr);

    $fi = new PayPal\Api\FundingInstrument();
    $fi->setCredit_card($card);

    $payer = new PayPal\Api\Payer();
    $payer->setPayment_method('credit_card');
    $payer->setFunding_instruments(array($fi));

    //$payee = new PayPal\Api\Payee();
    //$payee->setMerchant_id('Ad4VOhBhGpf3SJX4-YNDTnsjKdg4KGLvD2t3LmRQu0p9XrI9WN-UUUoiAI-G');
    //$payee->setEmail('cporterbox-facilitator@gmail.comm');

    $amountDetails = new PayPal\Api\AmountDetails();
    $amountDetails->setSubtotal('7.00');
    $amountDetails->setTax('0.02');
    $amountDetails->setShipping('0.03');

    $amount = new PayPal\Api\Amount();
    $amount->setCurrency('USD');
    $amount->setTotal('7.05');
    $amount->setDetails($amountDetails);

    $transaction = new PayPal\Api\Transaction();
    $transaction->setAmount($amount);
    $transaction->setDescription('This is the payment transaction description.');
    //$transaction->setPayee($payee);

    $payment = new PayPal\Api\Payment();
    $payment->setIntent('sale');
    $payment->setPayer($payer);
    $payment->setTransactions(array($transaction));


    $response = $payment->create();
    var_dump($response);
    die;



// this jazz works
    /*$addr = new PayPal\Api\Address();
    $addr->setLine1('52 N Main ST');
    $addr->setCity('Johnstown');
    $addr->setCountry_code('US');
    $addr->setPostal_code('43210');
    $addr->setState('OH');

    $card = new PayPal\Api\CreditCard();
    $card->setNumber('4417119669820331');
    $card->setType('visa');
    $card->setExpire_month('11');
    $card->setExpire_year('2018');
    $card->setCvv2('874');
    $card->setFirst_name('Joe');
    $card->setLast_name('Shopper');
    $card->setBilling_address($addr);

    $fi = new PayPal\Api\FundingInstrument();
    $fi->setCredit_card($card);

    $payer = new PayPal\Api\Payer();
    $payer->setPayment_method('credit_card');
    $payer->setFunding_instruments(array($fi));

    $amountDetails = new PayPal\Api\AmountDetails();
    $amountDetails->setSubtotal('7.00');
    $amountDetails->setTax('0.02');
    $amountDetails->setShipping('0.03');

    $amount = new PayPal\Api\Amount();
    $amount->setCurrency('USD');
    $amount->setTotal('7.05');
    $amount->setDetails($amountDetails);

    $transaction = new PayPal\Api\Transaction();
    $transaction->setAmount($amount);
    $transaction->setDescription('This is the payment transaction description.');

    $payment = new PayPal\Api\Payment();
    $payment->setIntent('sale');
    $payment->setPayer($payer);
    $payment->setTransactions(array($transaction));

    $response = $payment->create();
    var_dump($response);
    die;*/


  }

  public function actionTryGet()
  {
    $payment = PayPal\Api\Payment::get('PAY-8VV86607NN839915YKFZV4QY');
    var_dump($payment);
  }
		
}
