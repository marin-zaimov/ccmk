<?php

class GroupController extends BaseController
{
	//http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/group/create?Group[creator]=1&Group[name]=booooomswag
	public function actionCreate()
	{
		$response = new AjaxResponse;
		try {
			$groupData = $this->request('Group');
			$group = Group::createFromForm($groupData);

			Group::store($group);

			$userGroup = UserGroup::createFromArray(array(
				'userId' => $groupData['creator'],
				'groupId' => $group->id,
			));
			UserGroup::store($userGroup);

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

//http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/group/getAllByUser?userId=1
	public function actionGetAllByUser()
	{

		$response = new AjaxResponse;
		try {
		  $userId = $this->request('userId');
		  $user = User::getById($userId);
		  $groups = $user->groups;
		  $retGroups = array();
		  foreach ($groups as $g) {
		  	$retGroups[] = $g->attributes;
		  }
			$response->setStatus(true, 'Coolness');
			$response->addData('groups', $retGroups);
		}
		catch (Exception $e) {
			$response->setStatus(false, $e->getMessage());
		}
		echo $response->asJson();

	  
		//$this->render('getAllByUser');
	}

	public function actionUpdate()
	{
		
		//$this->render('update');
	}

//http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/group/addUserToGroup?UserGroup[userId]=3&UserGroup[groupId]=2&UserGroup[invitedBy]=1
	public function actionAddUserToGroup()
	{
		$response = new AjaxResponse;
		try {
			$userGroupData = $this->request('UserGroup');
			$userGroup = UserGroup::createFromForm($userGroupData);

			UserGroup::store($userGroup);

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
