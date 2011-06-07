<?php
namespace MVC;
require_once 'IModelBinder.php';
require_once 'ControllerContext.php';
use MVC\IModelBinder as IModelBinder;
use MVC\ControllerContext as ControllerContext;

class DefaultModelBinder implements IModelBinder
{
    function __construct() 
    {
        
    }
    
    public function BindModel(ControllerContext $context)
    {        
        //route values
        $values = array();
        foreach ($context->RouteData as $key => $value)
        {
            if (($key != "{controller}") && ($key != "{action}"))
                $values[$key] = $value;
        }
        return $values;
    }
    
}

?>
