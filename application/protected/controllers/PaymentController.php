<?php

class PaymentController extends BaseController
{
// http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/payment/create?Payment[senderId]=1&Payment[receiverId]=3&Payment[amountDue]=50&Payment[receiptId]=1
	public function actionCreate()
	{
		$response = new AjaxResponse;
		try {
			$paymentData = $this->request('Payment');
			$payment = Payment::createFromForm($paymentData);

			Payment::store($payment);
			$response->setStatus(true, 'Saved successfully');
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

// http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/payment/update?Payment[id]=1&Payment[senderId]=1&Payment[receiverId]=3&Payment[amountDue]=50&Payment[receiptId]=1
	public function actionUpdate()
	{
		$response = new AjaxResponse;
	  try {
	    $paymentData = $this->request('Payment');
	    $payment = Payment::getById($paymentData['id']);
			$payment->setAttributes($paymentData);
		  Payment::store($payment);
		  $response->setStatus(true, 'Saved successfully');
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

// http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/payment/pay?userId=1&receiptId=2
	public function actionPay()
	{
		$response = new AjaxResponse;
	  try {
	    $userId = $this->request('userId');
	    $receiptId = $this->request('receiptId');
	    $payment = Payment::model()->findByAttributes(array('senderId'=>$userId, 'receiptId'=>$receiptId));
			$payment->amountPaid = $payment->amountDue;
		  Payment::store($payment);
		  $response->setStatus(true, 'Saved successfully');
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

// http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/payment/bySender?senderId=1
	public function actionBySender()
	{
		$response = new AjaxResponse;
		$senderId = $this->request('senderId');
		try {
	    $senderId = $this->request('senderId');
	    $user = User::getById($senderId);
	    $paymentsOwed = $user->paymentsOwed;
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

// http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/payment/bySender?senderId=1
	public function actionUnpaidBySender()
	{
		$response = new AjaxResponse;
		$senderId = $this->request('senderId');
		try {
	    $senderId = $this->request('senderId');
	    $user = User::getById($senderId);
	    $paymentsOwed = $user->paymentsOwed;
	    $payments = array();
	    foreach ($paymentsOwed as $p) {
	    	if ($p->amountDue != $p->amountPaid) {
		    	$pAttr = $p->attributes(array('receiver', 'receipt'), false);
		    	$pAttr['group'] = $p->receipt->group->attributes;
		    	$payments[] = (object) $pAttr;
		    }
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

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}