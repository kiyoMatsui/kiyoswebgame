<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
		$router->addRoute('/', 'Homepage:default');
		$router->addRoute('/signin', 'Signin:default');
		$router->addRoute('/register', 'Register:default');
		$router->addRoute('/account', 'Account:default');
		$router->addRoute('/servers', 'Servers:default');
		$router->addRoute('/game', 'Game:default');
		$router->addRoute('/message', 'Message:default');
		$router->addRoute('/registerlinksent', 'Message:registerlinksent');
		$router->addRoute('/messagereg/<ac>', 'Message:registered');
		$router->addRoute('/password', 'Account:changepassword');
		$router->addRoute('/email', 'Account:changeemail');
		$router->addRoute('/resetpasswordconfirmed', 'Message:resetpasswordconfirmed');
		$router->addRoute('/resetpassword', 'Signin:forgotpw');
		return $router;
	}
}
