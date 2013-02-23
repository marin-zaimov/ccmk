<?php

abstract class BaseController extends CController
{

	public $user = null;
	public $request = null;
	public $app = null;


	public function request($param = null)
	{
		if(isset($param)) {
			return Yii::app()->request->getParam($param);
		}
		else {
			return Yii::app()->request;
		}
	}

	public function user()
	{
		return Yii::app()->user;
	}

	public function render($view, $data=null, $return=false)
	{
		if(!isset($_REQUEST["renderType"])) {
			return parent::render($view, $data, $return);
		}

		if($_REQUEST["renderType"] == "part") {
			$retRender = parent::renderPartial($view, $data, $return);
		}
		else if ($_REQUEST["renderType"] == "json") {
		  if (is_object($data) && get_class($data) == 'LogixResponse') {
		    echo $data->asJson();
		  }
		  else {
			  echo json_encode($data);
			}
			exit;
		}
		else {
			$retRender =  parent::render($view, $data, $return);
		}

		if($return) { return $retRender; }
		else { echo $retRender; }
	}

	public function throwAuthorizationException($message = "") 
	{
			$message = empty($message) ? 'User does not have permission to perform this action' : $message;
			throw new CHttpException(403, $message);
	}
}
