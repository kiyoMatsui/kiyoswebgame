<?php

declare(strict_types=1);

namespace App\Components;

use App\Model;
use Nette;
use Nette\Application\UI\Form;

final class ResetPasswordFormFactory
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
		$form->addEmail('email', 'Your new e-mail:')
			->setRequired('Please enter your new e-mail.');

		$form->addSubmit('send', 'Change Password');

		$form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess): void {
			try {
				$this->userManager->userResetPassword($values->email);
			} catch (Model\DuplicateNameException | Model\NoNameException | Model\GenericSQLException $e) {
				$form->addError($e->getMessage());
				return;
			}
			$onSuccess();
		};
		return $form;
	}
}
