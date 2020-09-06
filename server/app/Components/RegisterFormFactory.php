<?php

declare(strict_types=1);

namespace App\Components;

use App\Model;
use Nette;
use Nette\Application\UI\Form;

final class RegisterFormFactory
{
	use Nette\SmartObject;

	private const PASSWORD_MIN_LENGTH = 5;

	/** @var FormFactory */
	private $factory;

	/** @var Model\UserManager */
	private $userManager;

	/** @var Nette\Database\Context */
	private $database;

	public function __construct(Nette\Database\Context $database, FormFactory $factory, Model\UserManager $userManager)
	{
		$this->database = $database;
		$this->factory = $factory;
		$this->userManager = $userManager;
	}


	public function create(callable $onSuccess): Form
	{
		$form = $this->factory->create();
		$form->addText('username', 'Pick a username:')
			->setRequired('Please pick a username.');

		$form->addEmail('email', 'Your e-mail:')
			->setRequired('Please enter your e-mail.');

		$form->addPassword('password', 'Create a password:')
			->setOption('description', sprintf('at least %d characters', self::PASSWORD_MIN_LENGTH))
			->setRequired('Please create a password.')
			->addRule($form::MIN_LENGTH, null, self::PASSWORD_MIN_LENGTH);

		$form->addSubmit('send', 'Sign up');

		$form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess): void {
			try {
				$this->userManager->add($values->username, $values->email, $values->password);
			} catch (Model\DuplicateNameException | Model\NoNameException | Model\GenericSQLException $e) {
				$form->addError($e->getMessage());
				return;
			}
			$onSuccess();
		};
		return $form;
	}
}
