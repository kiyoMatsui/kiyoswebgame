<?php

declare(strict_types=1);

namespace App\Components;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;
use Nette\Utils\Strings;

final class SignInFormFactory
{
	use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var User */
	private $user;

	/** @var Nette\Database\Context */
	private $database;

	public function __construct(Nette\Database\Context $database, FormFactory $factory, User $user)
	{
		$this->database = $database;
		$this->factory = $factory;
		$this->user = $user;
	}

	public function create(callable $onSuccess): Form
	{
		$form = $this->factory->create();
		$form->addText('email')
			->setHtmlAttribute('placeholder', 'Type your e-mail')
			->addFilter(function ($email) {
				return Strings::lower($email);
			})
			->addRule($form::REQUIRED, 'E-mail is mandatory')
			->addRule($form::EMAIL, 'Given e-mail is not e-mail');

		$form->addPassword('password', 'Password:')
			->setRequired('Please enter your password.');

		$form->addCheckbox('myCheckbox', 'Keep me signed in');

		$form->addSubmit('send', 'Sign in');

		$form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess): void {
			try {
				$this->user->setExpiration($values->myCheckbox ? '1 days' : '10 minutes');
				$this->user->login($values->email, $values->password);
			} catch (Nette\Security\AuthenticationException $e) {
				$form->addError('The email or password you entered is incorrect.');
				return;
			}
			$row = $this->database->table('accounts')
				->where('email', $values->email)
				->fetch();
			$_SESSION['username'] = $row['username'];
			$_SESSION['accountid'] = $row['accountid'];
			$onSuccess();
		};

		return $form;
	}
}
