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

    $addr = new PayPal\Address();
    $addr->setLine1('52 N Main ST');
    $addr->setCity('Johnstown');
    $addr->setCountry_code('US');
    $addr->setPostal_code('43210');
    $addr->setState('OH');

    $card = new PayPal\CreditCard();
    $card->setNumber('4417119669820331');
    $card->setType('visa');
    $card->setExpire_month('11');
    $card->setExpire_year('2018');
    $card->setCvv2('874');
    $card->setFirst_name('Joe');
    $card->setLast_name('Shopper');
    $card->setBilling_address($addr);

    $fi = new PayPal\FundingInstrument();
    $fi->setCredit_card($card);

    $payer = new PayPal\Payer();
    $payer->setPayment_method('credit_card');
    $payer->setFunding_instruments(array($fi));

    $amountDetails = new PayPal\AmountDetails();
    $amountDetails->setSubtotal('7.41');
    $amountDetails->setTax('0.03');
    $amountDetails->setShipping('0.03');

    $amount = new PayPal\Amount();
    $amount->setCurrency('USD');
    $amount->setTotal('7.47');
    $amount->setDetails($amountDetails);

    $transaction = new PayPal\Transaction();
    $transaction->setAmount($amount);
    $transaction->setDescription('This is the payment transaction description.');

    $payment = new PayPal\Payment();
    $payment->setIntent('sale');
    $payment->setPayer($payer);
    $payment->setTransactions(array($transaction));


  }
		
}
