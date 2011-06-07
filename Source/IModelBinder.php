<?php
namespace MVC;
require_once 'ControllerContext.php';
use MVC\ControllerContext as ControllerContext;

interface IModelBinder {
    public function BindModel(ControllerContext $context);
}

?>
