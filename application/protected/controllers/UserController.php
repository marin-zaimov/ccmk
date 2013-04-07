<?php

class UserController extends BaseController
{

  //http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/user/create
  // POST
  // {"firstName": "chris", "lastName": "porter", "email": "cporter35@gatech.edu",  "password": "password"}
	public function actionCreate()
	{
		try {
			$postData = (array)json_decode(file_get_contents('php://input'));
			

			$existingUser = User::model()->findByAttributes(array('email' => $postData['email']));
			if ($existingUser) {
				echo "Error. Email already exists.";
			}
			else {
				$salt = PasswordHelper::generateRandomSalt();
				$hashedPassword = PasswordHelper::hashPassword($postData['password'], $salt);

				$postData['password_hash'] = $hashedPassword;
				$postData['password_salt'] = $salt;
				$postData['startDate'] = date("Y-m-d H:i:s");;

				$user = new User;
				$user->setAttributes($postData, false);
				$user->save();

				$response['email'] = $user->email;
				$response['id'] = $user->id;

				echo json_encode($response);
			}
		}
		catch (Exception $e) {
			echo "Exception: " . $e->getMessage();
		}

	}
  //http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/user/login
	public function actionLogin()
	{
		try {
			$postData = (array)json_decode(file_get_contents('php://input'));

			$user = User::model()->findByAttributes(array('email' => $postData['email']));
			if ($user) {
				$suppliedPassword = PasswordHelper::hashPassword($postData['password'], $user->password_salt);
				if ($suppliedPassword == $user->password_hash) {
					// SUCCESS
					$response['result'] = true;
					$response['id'] = $user->id;
					$response['message'] = "Success.";
					echo json_encode($response);
				}
				else {
					$response['result'] = false;
					$response['id'] = -1;
					$response['message'] = "Error. Invalid password.";
					//$response['message'] = "Error. Invalid email and password combination.";
					echo json_encode($response);
				}
			}
			else {
				$response['result'] = false;
				$response['id'] = -1;
				$response['message'] = "Error. Email not found.";
				//$response['message'] = "Error. Invalid email and password combination.";
				echo json_encode($response);
			}
		}
		catch (Exception $e) {
			echo "Exception: " . $e->getMessage();
		}
	}
  //http://localhost/ccmk/index.php/user/create?User[firstName]=Cliffton&User[lastName]=Thomas&User[email]=cliftot64@gmail.com
	/*public function actionCreate()
	{
	  $response = new AjaxResponse;
	  try {
	    $userData = $this->request('User');

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
		  $response->setStatus(false, $e->getMessage());
		
		}
		
		echo $response->asJson();
	}*/


	public function actionDelete()
	{
		$this->render('delete');
	}

	public function actionGet()
	{
		$this->render('get');
	}

//http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/user/update?User[id]=4&User[firstName]=CliffSwagn&User[lastName]=Thomas&User[email]=cliftot64@gmail.com
	public function actionUpdate()
	{
		$response = new AjaxResponse;
	  try {
	    $userData = $this->request('User');

	    $user = User::getById($userData['id']);
			
			$user->setAttributes($userData);

		  User::store($user);
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
