<?php
namespace MVC;

interface IControllerFactory
{
	public function GetControllerInstance($controllerName);
}

?>