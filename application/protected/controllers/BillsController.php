<?php

class BillsController extends BaseController
{

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/bills/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}


	public function actionGetUnpaid()
	{
    //$postData = json_decode(file_get_contents("php://input"));
    //$senderId = $postData->senderId;
		try {
	    //$user = User::getById($senderId);
      $user = User::model()->findByAttributes(array('paypal_account' => Yii::app()->user->getState('paypal_account')));
      $senderId = $user->id;
      $response = new AjaxResponse;
      
      $paymentsOwed = Payment::model()->findAllByAttributes(array('senderId' => $senderId),'amountPaid = 0');

	    $payments = array();
	    foreach ($paymentsOwed as $p) {
	    	$pAttr = $p->attributes(array('receiver', 'receipt'), false);
	    	$pAttr['group'] = $p->receipt->group->attributes;
	    	$payments[] = (object) $pAttr;
	    }
		  $response->setStatus(true, 'Retrieved successfully');
		  $response->addData('payments', $payments);
		}
		catch (ValidationException $vex)
		{
			$response->setStatus(false);
			$response->addMessages($vex->getErrors());
		}
		catch (Exception $e) {
		  $response->setStatus(false, $e->getMessage());
		}
		echo $response->asJson();

	}

  public function actionPay()
  {

    try {
      $postData = json_decode(file_get_contents("php://input"));
      $userId = $postData->userId;
      $receiptId = $postData->receiptId;

      $response = new AjaxResponse;
      $payment = Payment::model()->findByAttributes(array('senderId'=>$userId, 'receiptId'=>$receiptId));
      $payment->amountPaid = $payment->amountDue;
      $sender = User::model()->findByPk($userId);

      // try to go to paypal based on who is logged in
      $paypalAccount = Yii::app()->user->getState('paypal_account');


      // note: production server would just fetch the config details for the corresponding users
      // from the d.b. hard code for simplicity here
      $srcBox = getcwd() . "/protected/extensions/paypal/vendor/paypal/sdk-core-php/config/sdk_config.ini.box";
      $srcOj = getcwd() . "/protected/extensions/paypal/vendor/paypal/sdk-core-php/config/sdk_config.ini.oj";
      $src = $srcOj;
      $dst = getcwd() . "/protected/extensions/paypal/vendor/paypal/sdk-core-php/config/sdk_config.ini";
      if($paypalAccount == "ostrichjockey@hotmail.com"){
        $src = $srcBox; // person getting paid is opposite of whoever is logged in
      }

      copy($src, $dst);


      $addr = new PayPal\Api\Address();
      $addr->setLine1('14th Street');
      $addr->setCity('Atlanta');
      $addr->setCountry_code('US');
      $addr->setPostal_code('30318');
      $addr->setState('GA');

      $card = new PayPal\Api\CreditCard();
      $card->setNumber('4417119669820331');
      $card->setType('visa');
      $card->setExpire_month('11');
      $card->setExpire_year('2018');
      $card->setCvv2('874');
      $card->setFirst_name($sender->firstName);
      $card->setLast_name($sender->lastName);
      $card->setBilling_address($addr);

      $fi = new PayPal\Api\FundingInstrument();
      $fi->setCredit_card($card);

      $payer = new PayPal\Api\Payer();
      $payer->setPayment_method('credit_card');
      $payer->setFunding_instruments(array($fi));

      $amountDetails = new PayPal\Api\AmountDetails();
      $amountDetails->setSubtotal(round($payment->amountPaid, 2));
      $amountDetails->setTax('0.00');
      $amountDetails->setShipping('0.00');

      $amount = new PayPal\Api\Amount();
      $amount->setCurrency('USD');
      $amount->setTotal(round($payment->amountPaid, 2));
      $amount->setDetails($amountDetails);

      $transaction = new PayPal\Api\Transaction();
      $transaction->setAmount($amount);
      $transaction->setDescription('This is the payment transaction description.');

      $ppPayment = new PayPal\Api\Payment();
      $ppPayment->setIntent('sale');
      $ppPayment->setPayer($payer);
      $ppPayment->setTransactions(array($transaction));

      $ppResponse = $ppPayment->create();
      if($ppResponse->state == "approved"){
        BaseActiveRecord::store($payment);
        $response->setStatus(true, 'Saved successfully');
      }else{
        $response->setStatus(false, 'Error processing PayPal transaction');
      }
    }
    catch (ValidationException $vex)
    {
      $response->setStatus(false);
      $response->addMessages($vex->getErrors());
    }
    catch (Exception $e) {
      $response->setStatus(false, $e->getMessage());
    }
    echo $response->asJson();
  }





}
