<?php

class GroupController extends BaseController
{
	public function actionCreate()
	{
		$response = new AjaxResponse;
		try {
			$groupData = $this->request('Group');
			$group = Group::createFromForm($groupData);

			Group::store($group);
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


	public function actionGetAllByUser()
	{
	  $userId = $_GET['userId'];
	  
	  $groups = Group::model()->findAllByAttributes(array('creator' => $userId)):
	  
		//$this->render('getAllByUser');
	}

	public function actionUpdate()
	{
		
		//$this->render('update');
	}

	public function actionGen() 
	{
		$group = new Group;

		$group->setAttributes(array(
			'creator' => '1',
			'name' => 'Sup Test Group',
			));

		if (!$group->save()) {
			var_dump($user->errors);
			die;
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
