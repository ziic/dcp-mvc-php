<?php
namespace MVC;

require_once 'IPrincipal.php';

class GeneralPrincipal implements IPrincipal
{
    public $IsAuthenticated = false;
    public $Name = "";
}

?>
