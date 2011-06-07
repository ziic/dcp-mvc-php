<?php

require_once dirname(__FILE__) . '/../Source/MVCApplication.php';
require_once 'MVCApplication.php';
use MVC\MVCApplication as MVCApplication;

class MVCTest extends MVCApplication
{
    
    protected function Application_Start()
    {
        parent::$Routes[] = new Route("{controller}/{action}/{id}", 
                                 array("{controller}" => "Home","{action}" => "Index", "{id}" => 5));
    }     
    
}
?>
