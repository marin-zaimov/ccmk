<?php
require("PPBootStrap.php");
//require("PPLoggingManager.php");
require("protected/extensions/paypal/vendor/paypal/sdk-core-php/lib/PPLoggingManager.php");

class TestController extends BaseController
{

	//http://ec2-50-17-177-44.compute-1.amazonaws.com/marin-ccmk/index.php/group/getbyId?groupId=1
	public function actionTest()
  {

    $logger = new PPLoggingManager('PaymentDetails');
    /*$payment = new Payment();

    $payment->setIntent("Sale");*/

    $f = new ReflectionClass('PPLoggingManager');
    $methods = array();
    foreach ($f->getMethods() as $m) {
          if ($m->class == 'PPLoggingManager') {
                    $methods[] = $m->name;
                        }
    }
    print_r($methods);
    die;



  }
		
}
