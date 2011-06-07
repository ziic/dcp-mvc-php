<?php
namespace MVC;
require_once("mvc.config");
require_once(__DIR__.MVC_PATH_TO_SMARTY);

abstract class Controller
{
	private $_smarty = null;
	protected $_baseTemplateDir = "../Views/";
	protected $_controllerTemplateDir = "";
	protected $ViewData;
        public $ModelBinders = array(); //[]

	function __construct($controllerName) {
		$this->_controllerTemplateDir = $controllerName."/";
		$serverPath = pathinfo(__FILE__, PATHINFO_DIRNAME);
		$this->_baseTemplateDir = $serverPath."/".$this->_baseTemplateDir;
		$this->SmartyInit();
	}

	public function SmartyInit()
	{
        $this->_smarty = new \Smarty ();

		$this->_smarty->template_dir = $this->_baseTemplateDir.$this->_controllerTemplateDir;
		$this->_smarty->compile_dir = $this->_baseTemplateDir.'templates_c';
		$this->_smarty->cache_dir = $this->_baseTemplateDir.'cache';
		$this->_smarty->config_dir = $this->_baseTemplateDir.'configs';

	}

	protected function View($view)
	{
		$template = $view . ".tpl";
		$this->_smarty->assign('ViewData', $this->ViewData);
		$this->_smarty->display ($template);
	}

	protected function ViewWithModel($view, $model)
	{
		$template = $view . ".tpl";
		$this->_smarty->assign ("Model", $model);
		$this->_smarty->display ($template);
	}

	protected function RedirectToAction($controller, $action)
	{
		header('Location: http://'.$_SERVER["SERVER_NAME"].':'.$_SERVER["SERVER_PORT"].'/'.$controller.'/'.$action.'.php');
	}

	protected function NotFound()
	{
		header("HTTP/1.0 404 Not Found");
	}

	protected function JSON($value)
	{
	    echo(json_encode($value));
	}
}

?>