<?php

declare(strict_types=1);

namespace App\Components;

use App\Model;
use Nette;
use Nette\Application\UI\Form;

final class ChangepasswordFormFactory
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
		$form->addPassword('password1', 'Input old password.')
			->setOption('description', sprintf('at least %d characters', self::PASSWORD_MIN_LENGTH))
			->setRequired('Input old password.')
			->addRule($form::MIN_LENGTH, null, self::PASSWORD_MIN_LENGTH);

		$form->addPassword('password2', 'Input new password.')
			->setOption('description', sprintf('at least %d characters', self::PASSWORD_MIN_LENGTH))
			->setRequired('Input new password.')
			->addRule($form::MIN_LENGTH, null, self::PASSWORD_MIN_LENGTH);

		$form->addPassword('password3', 'Confirm new password.')
			->setOption('description', sprintf('at least %d characters', self::PASSWORD_MIN_LENGTH))
			->setRequired('Confirm new password.')
			->addRule($form::MIN_LENGTH, null, self::PASSWORD_MIN_LENGTH);

		$form->addSubmit('send', 'Change Password');

		$form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess): void {
			try {
				$this->userManager->changePassword($values->password1, $values->password2, $values->password3);
			} catch (Model\NameMatchException | Model\DuplicateNameException | Model\NoNameException | Model\GenericSQLException $e) {
				$form->addError($e->getMessage());
				return;
			}
			$onSuccess();
		};
		return $form;
	}
}
