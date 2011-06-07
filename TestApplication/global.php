<?php

require_once dirname(__FILE__) .'/../Source/MVCApplication.php';
use MVC\MVCApplication as MVCApplication;

class MVC1 extends MVCApplication
{
    
    protected function Application_Start()
    {
        parent::$Routes[] = new MVC\Route("{controller}/{action}.php?id={id}", 
                                 array("{controller}" => "Home","{action}" => "Index", "{id}" => 5));
        
        parent::$Routes[] = new MVC\Route("{controller}/{action}/{id}", 
                                 array("{controller}" => "Home","{action}" => "Index", "{id}" => 5));        
                 
    }     
    
}

$program = new MVC1;
$program->Run();
?>
