<?php
namespace MVC;
require_once 'mvc.config';
require_once 'UrlRouteModule.php';
require_once 'ControllerContext.php';
require_once 'DefaultControllerFactory.php';
require_once 'EmptyAuthenticationService.php';
require_once 'DefaultModelBinder.php';

abstract class MVCApplication
{
    protected static $Routes = array();    
    private $_urlRouteModule;
    protected $_authenticationService;
    protected $_controllerFactory;    
    
    function __construct() 
    {
        $this->_controllerFactory = new DefaultControllerFactory();
        $this->_authenticationService = new EmptyAuthenticationService();
    }
    
    protected function Application_Start()
    {
        
    }   
    
    public function Run()
    {
        $this->Application_Start();
        
        $this->_urlRouteModule = new UrlRouteModule(MVCApplication::$Routes);
        
        $context = new ControllerContext;        
        
        $this->Routing($context);
        
        $this->OnAuthenticate($context);
        
        $this->RequestExecute($context);
        
    }
    
    private function Routing(ControllerContext &$context)
    {
        $url =$_SERVER["REQUEST_URI"];        
        $url = substr($url, 1);
        $context->RouteData = $this->_urlRouteModule->GetRouteData($url);
    }
    
    private function OnAuthenticate(ControllerContext &$context)
    {
        $context->User = $this->_authenticationService->AuthenticateRequest();            
    }
    
    private function _autoLoadFile($classFile)
    {
        $fullPath = $_SERVER{'DOCUMENT_ROOT'}."/".MVC_CONTROLLERS_FOLDER."/".$classFile;
        
        if (file_exists($fullPath))
        {
             $namespace = require_once($fullPath);
        }
        else
        {
            $message = "File $fullPath for controller not found";
            throw new \Exception("$message");
        }
        return $namespace;
    }
    
    private function RequestExecute(ControllerContext $context)
    {
        $controllerName = $context->RouteData["{controller}"];
        
        $classFile = $controllerName."Controller.php";
        $namespace = $this->_autoLoadFile($classFile);
        
        $fullControllerName = $namespace.$controllerName;
        $controller = $this->_controllerFactory->GetControllerInstance($fullControllerName);
        
        $action = $context->RouteData["{action}"];
        $action .= "_".$_SERVER['REQUEST_METHOD'];
        
        if (array_key_exists($action, $controller->ModelBinders))
        {
            $modelBinder = $controller->ModelBinders[$action];
        }
        else
            $modelBinder = new DefaultModelBinder();
        
        $modelValues = $modelBinder->BindModel($context);
        
        $strVal = "";
        if (is_array($modelValues))
        {        
            $strVal = implode(',', $modelValues);
        }
        $phpCode = "\$actionResult = \$controller->$action($strVal);";
        eval($phpCode);       
        
    }
    
}
?>
