<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
    Yii::app()->user->setState('paypal_account', null);
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
  
    $state = Yii::app()->user->getState('paypal_account');
    if(isset($state)){
      $this->redirect('/marin-ccmk/index.php/bills/index');
    }

		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data

		if(isset($_POST['LoginForm']))
		{
			/*$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);*/

      try {
        $postData = ($_POST['LoginForm']);

        //$user = User::model()->findByAttributes(array('paypal_account' => $postData['username'], 'password' => $postData['password']));
        $user = User::model()->findByAttributes(array('paypal_account' => $postData['username']));

        if ($user) {
          Yii::app()->user->setState('paypal_account', $postData['username']);
				  $this->redirect('/marin-ccmk/index.php/bills/index');//Yii::app()->user->returnUrl);
          //echo $this->widget('zii.widgets.CMenu', 'id');
          /*$this->widget('zii.widgets.CMenu', array(
            'items'=>array(
              array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
              array('label'=>'Bills', 'url'=>array('/bills/index')),
              //array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
              //array('label'=>'Contact', 'url'=>array('/site/contact')),
              //array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
            ),
          ));*/


        }else{
          // not found... show errors
          $model->validate();
          $model->login();
        }


          /*$suppliedPassword = PasswordHelper::hashPassword($postData['password'], $user->password_salt);
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
        }*/
      }
      catch (Exception $e) {
        echo "Exception: " . $e->getMessage();
      }
      



		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
