<?php

declare(strict_types=1);

namespace App\Components;

use App\Model;
use Nette;
use Nette\Application\UI\Form;

final class UsernameFormFactory
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
		$form->addText('username', 'Pick a new username:')
			->setRequired('Please pick a new username.');
		$form->addSubmit('send', 'New username');

		$form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess): void {
			try {
				$this->userManager->changeUsername($values->username);
			} catch (Model\DuplicateNameException | Model\NoNameException | Model\GenericSQLException $e) {
				$form->addError($e->getMessage());
				return;
			}
			$onSuccess();
		};
		return $form;
	}
}
