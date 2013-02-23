<?php

class UserController extends BaseController
{

  //use this as a url in the browser to create
  //http://localhost/ccmk/index.php/user/create?User[firstName]=Cliffton&User[lastName]=Thomas&User[email]=cliftot64@gmail.com
	public function actionCreate()
	{
	  $response = new AjaxResponse;
	  try {
	    $userData = $_GET['User'];

	    $user = User::createFromForm($userData);
		
		  User::store($user);
		  $response->setStatus(true, 'Saved successfully');
		}
		catch (ValidationException $vex)
		{
			$response->setStatus(false);
			$response->addMessages($vex->getErrors());
		}
		catch (Exception $e) {
		  $response->setStatus(false, 'Saved failed');
		
		}
		
		echo $response->asJson();
		
	  
	}

	public function actionDelete()
	{
		$this->render('delete');
	}

	public function actionGet()
	{
		$this->render('get');
	}

	public function actionUpdate()
	{
		$this->render('update');
	}
	
	public function actionGen()
	{
	  $user  = new User;
		
		$user->setAttributes(array(
		  'firstName' => 'Chudy',
		  'lastName' => 'Chudy',
		  'email' => 'chudy@gatech.edu',
		));
		
		
		if (!$user->save())
		{
		  var_dump($user->errors);
		}
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
