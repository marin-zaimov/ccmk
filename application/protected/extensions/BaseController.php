<?php

/**
 * class TCPBaseController
 * @package tcp.extensions.TCP
 */
abstract class TCPBaseController extends CController
{
	private $_viewData = null;
	private $_layout = array();

	public $user = null;
	public $request = null;
	public $app = null;
	public $scriptVariableManager = null;

	/* View variables */
	public $currentSection= '';
	public $layout = 'column2';
	public $breadcrumbs = null;
	public $sidebar = null;

	public function init()
	{
		if ($this->needToSetActiveDomain()) {
			$this->redirect('/login/activedomain');
		}
 
    if (!$this->user()->isGuest && !$this->isLoginController() && $this->user()->needsToResetPassword()) {
      $this->redirect('/login/passwordresetview?email=' . $this->user()->email);
    }

		$this->app = Yii::app();
		$this->user = $this->app->user;
		$this->request = $this->app->request;

		$this->scriptVariableManager = new ScriptVariableManager();
		$this->setLayoutViewData();

		$this->postInit();
	}
  
  private function isLoginController()
  {
    return $this->getId() == 'login';
  }

	private function needToSetActiveDomain() {
		// if the user is logged in,
		// has not set an active domain already
		// and we are not on the login controller
		// then the user needs to set an active domain
		return 	!$this->user()->isGuest &&
				!$this->user()->activeDomainIsSet() &&
				!$this->isLoginController();
	}

	public abstract function postInit();

	public function setLayoutViewData()
	{
		$activeDomain = $this->user->getActiveDomain();
		if (isset($activeDomain) && $activeDomain != '') {
			$activeDomain = "Active Domain: {$activeDomain}";
		}
		else {
			$activeDomain = 'Domain not set';
		}

		$this->layout('activeDomainLabel', $activeDomain);
		$this->layout('baseUrl', $this->request()->baseUrl);
		$this->layout('pageContext', '');
	}

	public function layout($key, $data = null, $throwExceptionOnOverwrite = true, $throwExceptionOnInvalidKey = true)
	{
		if (isset($data)) {
			if (isset($this->_layout[$key]) && $throwExceptionOnOverwrite) {
				throw new Exception("duplicate layout key: {$key}");
			}

			$this->_layout[$key] = $data;
		}
		else {
			if (!isset($this->_layout[$key]) && $throwExceptionOnInvalidKey) {
				throw new Exception("invalid layout key: {$key}");
			}

			return isset($this->_layout[$key]) ? $this->_layout[$key] : '';
		}
	}
	//TODO: This is not currently being used in new code. Remove this once it is not being used anywhere.
	public function viewData($data = null)
	{
		if (isset($data)) {
			$this->_viewData = $data;
		}
		else {
			return (isset($this->_viewData)) ? $this->_viewData : array();
		}
	}

	public function getMenu($menuName)
	{
		if (empty($menuName) || empty($this->app->params['menus']->$menuName)) {
			throw new Exception('Invalid menu name');
		}

		return $this->app->params['menus']->$menuName;
	}

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

	public function addScriptVar($key, $value)
	{
		$this->scriptVariableManager->add($key, $value);
	}

	public function throwAuthorizationException($message = "") 
	{
			$message = empty($message) ? 'User does not have permission to perform this action' : $message;
			throw new CHttpException(403, $message);
	}
	
	protected function setToNullIfNotSetOrEmpty($variable)
	{
		if (!isset($variable) || empty($variable)) {
			$variable = null;
		}
		return $variable;
	}
}
