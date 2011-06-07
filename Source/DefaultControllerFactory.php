<?php
namespace MVC;
require_once("IControllerFactory.php");

use MVC\IControllerFactory as IControllerFactory;

class DefaultControllerFactory implements IControllerFactory
{
    public function GetControllerInstance($controllerName)
    {
        $type = $controllerName."Controller";
        $controller = new $type;       

        return $controller;
    }
}

?>