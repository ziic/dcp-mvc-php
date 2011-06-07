<?php
namespace MVC;
require_once ('IFormsAuthentication.php');
require_once (__DIR__.'../Services/Abstraction/IMembership.php');
use MVC\IFormsAuthentication as IFormsAuthentication;
use Services\Abstraction\IMemberShip as IMemberShip;

class FormsAuthentication implements IFormsAuthentication
{
	private $_membershipProvider;

	function __construct(IMemberShip $membershipProvider) {
		$this->_membershipProvider = $membershipProvider;
	}

	public function AuthenticateByToken($token)
	{
		$values = str_split($token);
		$user = $values[0];
		$password = $values[1];

		return $this->Auth($user, $password);
	}

	public function Authenticate($user, $password)
	{
		return $this->_membershipProvider->ValidateUser($name, $password);
	}
}

?>