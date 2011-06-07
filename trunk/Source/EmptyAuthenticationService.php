<?php
namespace MVC;
require_once 'IAuthentication.php';
require_once 'GeneralPrincipal.php';

class EmptyAuthenticationService implements IAuthentication
{
    public function AuthenticateRequest()
    {
        return new GeneralPrincipal();
    }
}

?>