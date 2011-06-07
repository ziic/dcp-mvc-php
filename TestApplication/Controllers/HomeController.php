<?php
require_once dirname(__FILE__) .'/../../Source/Controller.php';

class HomeController extends MVC\Controller
{
    
    function __construct() {
            parent::__construct("Home");            
    }
        
    public function Index_GET($id)
    {
        echo($id);
    }
}

return __NAMESPACE__;
?>
