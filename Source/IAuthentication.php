<?php
namespace MVC;

interface IAuthentication
{
	public function AuthenticateRequest();
        //public function AuthenticateByToken($token);
	//public function Authenticate($name, $password);
	//public function SignIn($name);
	//public function SignOut();
}

?>