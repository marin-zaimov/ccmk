<?php

class ReceiptController extends BaseController
{


	public function actionIndex()
	{
		$this->render('index');
	}

 
	// http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/receipt/create?Receipt[amountDue]=30.57&Receipt[userId]=3&Receipt[groupId]=1&Receipt[name]=something%20swags%20here
	public function actionCreate()
	{
		$response = new AjaxResponse;
		try {
			$receiptData = $this->request('Receipt');
			$receipt = Receipt::createFromForm($receiptData);

			Receipt::store($receipt);

			$group = $receipt->group;
			$groupUsers = $group->users;
			$numUsers = count($groupUsers);
			if ($numUsers) {
				$amountPerUser = $receipt->amountDue/$numUsers;
				foreach ($groupUsers as $user) {
					$payment = Payment::createFromArray(array(
						'receiverId' => $receipt->userId,
						'senderId' => $user->id,
						'receiptId' => $receipt->id,
						'amountDue' => $amountPerUser,
						'amountPaid' => 0
					));
					if ($user->id == $receipt->userId) {
						$payment->amountPaid = $amountPerUser;
					}
					Payment::store($payment);
				}
			}

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

	public function actionUpdate()
	{
		$response = new AjaxResponse;
	  try {
	    $receiptData = $this->request('Receipt');
	    $receipt = Receipt::getById($receiptData['id']);
			$receipt->setAttributes($receiptData);
		  Receipt::store($receipt);
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


	// http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/receipt/getAllByUserId?userId=1
	public function actionGetAllByUserId()
	{
		$response = new AjaxResponse;
	  try {
	    $userId = $this->request('userId');
	    $user = User::getById($userId);
			$receipts = array();

		  foreach ($user->receipts as $r) {
		  	$rAttr = $r->attributes(array('payments'));
		  	$rAttr->groupName = $r->group->name;
		  	$rAttr->paid = $r->isPaid();
		  	$receipts[] = $rAttr;
		  }
		  $response->setStatus(true, 'Retreived successfully');
		  $response->addData('receipts', $receipts);
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

	// http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/receipt/getAllByGroupId?groupId=2
	public function actionGetAllByGroupId()
	{
		$response = new AjaxResponse;
	  try {
	    $groupId = $this->request('groupId');
	    $group = Group::getById($groupId);
	    $receipts = array();

	    foreach ($group->receipts as $r) {
		  	$rAttr = $r->attributes(array('payments'));
		  	$rAttr->paid = $r->isPaid();
		  	$receipts[] = $rAttr;
		  }
		  $response->setStatus(true, 'Retreived successfully');
		  $response->addData('receipts', $receipts);
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

	public function actionGetById()
	{
		$response = new AjaxResponse;
	  try {
	    $receiptId = $this->request('receiptId');
	    $receipt = Receipt::getById($receiptId);

	  	$rAttr = $receipt->attributes(array('payments'));
	  	$rAttr->paid = $receipt->isPaid();
		  
		  $response->setStatus(true, 'Retreived successfully');
		  $response->addData('receipt', $rAttr);
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



	public function actionGetAllUsersReceipts()
	{
		$response = new AjaxResponse;

	  try {
      $ppEmail = Yii::app()->user->getState('paypal_account');
      $user = User::model()->findByAttributes(array('paypal_account' => $ppEmail));

			$receipts = array();
		  foreach ($user->receipts as $r) {
		  	$rAttr = $r->attributes(array('payments'));
		  	$rAttr->groupName = $r->group->name;
		  	$rAttr->paid = $r->isPaid();
		  	$receipts[] = $rAttr;
		  }
		  $response->setStatus(true, 'Retreived successfully');
		  $response->addData('receipts', $receipts);
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
