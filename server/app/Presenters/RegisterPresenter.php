<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components;
use Nette\Application\UI\Form;


class RegisterPresenter extends BasePresenter
{

	/** @var Components\RegisterFormFactory */
	private $registerFactory;

	public function __construct(Components\RegisterFormFactory $registerFactory)
	{
		$this->registerFactory = $registerFactory;
	}

	public function renderDefault(): void
	{
		if ($this->getUser()->isLoggedIn()) {
			$this->redirect('Account:');
		}
		$this->template->header_height = 'masthead';
		$this->template->titlepage = 'Webgame Register';
		$this->template->home_attr = 'nav-link';
		$this->template->play_attr = 'nav-link';
		$this->template->account_attr = 'nav-link active';
		$this->template->signin_attr = 'nav-link active';
	}

	/**
	 * Register form factory.
	 */
	protected function createComponentRegisterForm(): Form
	{
		return $this->registerFactory->create(function (): void {
			$this->redirect('Message:registerlinksent');
		});
	}
}
