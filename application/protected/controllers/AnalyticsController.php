
<?php

class AnalyticsController extends BaseController
{

	public function actionIndex()
	{
		$this->render('index');
	}

  public function actionGetNewUsersByMonth()
  {
    $response = array();
    $users = Yii::app()->db->createCommand()
      ->select('id, startDate')
      ->from('User')
      ->order('startDate ASC')
      ->queryAll();
    //$users = Yii::app()->db->createCommand("select * from User order by startDate ASC")->queryAll();



    $usersByMonth = array();
    foreach($users as $user){
      $date = new DateTime($user['startDate']);
      $month = $date->format('m');
      $year = $date->format('y');
      if(!isset($usersByMonth[$year])){
        $usersByMonth[$year] = array(); 
      }
      if(!isset($usersByMonth[$year][$month])){
        $usersByMonth[$year][$month] = 0;
      }
      $usersByMonth[$year][$month] += 1;
    }

    $response['usersByMonth'] = $usersByMonth;
    echo json_encode($response);
  }

  public function actionGetAverages()
  {
    $response = array();

    $avgReceiptAmount = Yii::app()->db->createCommand("select avg(amountDue) as avgAmount from Receipt")->queryAll();
    $avgReceiptAmount = $avgReceiptAmount[0]['avgAmount'];


    $avgBillAmount = Yii::app()->db->createCommand("select avg(amountDue) as avgAmount from Payment")->queryAll();
    $avgBillAmount = $avgBillAmount[0]['avgAmount'];

    $avgUsersPerGroup = Yii::app()->db->createCommand("
      select avg(a.numInGroup) as avgUsersPerGroup from (select groupId, count(groupId) as numInGroup from User_Group group by groupId) as a
    ")->queryAll();
    $avgUsersPerGroup = $avgUsersPerGroup[0]['avgUsersPerGroup'];

    $response['avgReceiptAmount'] = $avgReceiptAmount;
    $response['avgBillAmount'] = $avgBillAmount;
    $response['avgUsersPerGroup'] = $avgUsersPerGroup;
    echo json_encode($response);
  }

}
