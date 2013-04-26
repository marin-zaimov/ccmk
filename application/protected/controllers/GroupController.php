<?php

class GroupController extends BaseController
{




	public function actionIndex()
	{
		$this->render('index');
	}




	//http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/group/getbyId?groupId=1
	public function actionGetById()
	{

		$response = new AjaxResponse;
		try {
		  $groupId = $this->request('groupId');
		  $group = Group::getById($groupId);

	  	$gAttr = $group->attributes(array('users', 'receipts'));
	  	$updatedReceipts = array();
	  	foreach ($group->receipts as $r) {
	  		$rAttr = $r->attributes(array('payments'));
		  	$rAttr->paid = $r->isPaid();
	  		$updatedReceipts[] = $rAttr;
	  	}
	  	$gAttr->receipts = $updatedReceipts;

			$response->setStatus(true, 'Retreived successfully');
			$response->addData('group', $gAttr);
		}
		catch (Exception $e) {
			$response->setStatus(false, $e->getMessage());
		}
		echo $response->asJson();
	}


	//http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/group/create?Group[creator]=1&Group[name]=booooomswag
	public function actionCreate()
	{
		$response = new AjaxResponse;
		try {
			$groupData = $this->request('Group');
			$userEmailsData = $this->request('Emails');
			$group = Group::createFromForm($groupData);

			Group::store($group);

			$userGroup = UserGroup::createFromArray(array(
				'userId' => $groupData['creator'],
				'groupId' => $group->id,
				'invitedBy' => $groupData['creator'],
			));
			UserGroup::store($userGroup);

			$creatorUser = User::getById($groupData['creator']);

			foreach ($userEmailsData as $email) {
				$user = User::model()->findByAttributes(array('email'=>$email));
				if (!empty($user) && $creatorUser->id != $user->id) {
					$userGroupData = array(
						'userId' => $user->id,
						'groupId' => $group->id,
						'invitedBy' => $group->creator,
					);
					$userGroup = UserGroup::createFromForm($userGroupData);
					//commented so it doesnt thrpw an exception using ::store()
					//UserGroup::store($userGroup);
					$userGroup->save();
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
		  	$gAttr = $g->attributes(array('users', 'receipts'));
		  	$updatedReceipts = array();
		  	foreach ($g->receipts as $r) {
		  		$rAttr = $r->attributes(array('payments'));
		  		$rAttr->paid = $r->isPaid();
		  		$updatedReceipts[] = $rAttr;
		  	}
		  	$gAttr->receipts = $updatedReceipts;
		  	$retGroups[] = $gAttr;
		  }
			$response->setStatus(true, 'Coolness');
			$response->addData('groups', $retGroups);
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
			$groupData = $this->request('Group');
			$group = Group::getById($groupData['id']);
			$group->setAttributes($groupData);
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

	//http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/group/delete?groupId=1
	public function actionDelete()
	{
		$response = new AjaxResponse;
		try {
			$groupId = $this->request('groupId');
			$group = Group::getById($groupId);
			$group->delete();
			$response->setStatus(true, 'Deleted successfully');
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

//http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/group/removeUserFromGroup?UserGroup[userId]=3&UserGroup[groupId]=1
	public function actionRemoveUserFromGroup()
	{
		$response = new AjaxResponse;
		try {
			$userGroupData = $this->request('UserGroup');

			UserGroup::remove(array(
				'userId' => $userGroupData['userId'],
				'groupId' => $userGroupData['groupId'],
			));

			$response->setStatus(true, 'Removed successfully');
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



  public function actionGetAllUsersGroups()
  {

		$response = new AjaxResponse;
		try {

      $ppEmail = Yii::app()->user->getState('paypal_account');
      $user = User::model()->findByAttributes(array('paypal_account' => $ppEmail));

		  $groups = $user->groups;
		  $retGroups = array();
		  foreach ($groups as $g) {
		  	$gAttr = $g->attributes(array('users', 'receipts'));
		  	$updatedReceipts = array();
		  	foreach ($g->receipts as $r) {
		  		$rAttr = $r->attributes(array('payments'));
		  		$rAttr->paid = $r->isPaid();
		  		$updatedReceipts[] = $rAttr;
		  	}
		  	$gAttr->receipts = $updatedReceipts;
		  	$retGroups[] = $gAttr;
		  }
			$response->setStatus(true, 'Coolness');
			$response->addData('groups', $retGroups);
		}
		catch (Exception $e) {
			$response->setStatus(false, $e->getMessage());
		}
		echo $response->asJson();


  }



}
