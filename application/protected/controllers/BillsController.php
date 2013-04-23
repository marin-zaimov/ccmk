<?php

class BillsController extends BaseController
{

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}


	public function actionBySender()
	{
    $postData = json_decode(file_get_contents("php://input"));
    $senderId = $postData->senderId;

		$response = new AjaxResponse;
		try {
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





}
