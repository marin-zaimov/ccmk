<?php

class GroupController extends BaseController
{
	public function actionCreate()
	{
		$this->render('create');
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
