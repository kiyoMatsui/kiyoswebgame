<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model;

class MessagePresenter extends BasePresenter
{

	/** @var Model\UserManager */
	private $userManager;

	public function __construct(Model\UserManager $userManager)
	{
		$this->userManager = $userManager;
	}


	public function renderDefault(): void
	{
		$this->template->header_height = 'masthead';
		$this->template->titlepage = 'Webgame message';
		$this->template->home_attr = 'nav-link';
		$this->template->play_attr = 'nav-link';
		$this->template->account_attr = 'nav-link';
		$this->template->signin_attr = 'nav-link';
		$this->flashMessage('Thank you for trying Webgame :-) .', 'alert alert-success');
	}

	public function renderResetpasswordconfirmed(): void
	{
		$this->template->header_height = 'masthead ';
		$this->template->titlepage = 'Webgame message';
		$this->template->home_attr = 'nav-link';
		$this->template->play_attr = 'nav-link';
		$this->template->account_attr = 'nav-link';
		$this->template->signin_attr = 'nav-link';
		$this->flashMessage('Password has been reset, please check your e-mail.', 'alert alert-success');
	}

	public function renderRegisterlinksent(): void
	{
		$this->template->header_height = 'masthead';
		$this->template->titlepage = 'Webgame message';
		$this->template->home_attr = 'nav-link';
		$this->template->play_attr = 'nav-link';
		$this->template->account_attr = 'nav-link';
		$this->template->signin_attr = 'nav-link';
		$this->flashMessage('You have registered, open your e-mails to verify your account.', 'alert alert-success');
	}

	public function renderRegistered(string $ac): void
	{
		if ($this->getUser()->isLoggedIn()) {
			$this->redirect('Account:');
		}
		$this->template->header_height = 'masthead';
		$this->template->titlepage = 'Webgame message';
		$this->template->home_attr = 'nav-link';
		$this->template->play_attr = 'nav-link';
		$this->template->account_attr = 'nav-link';
		$this->template->signin_attr = 'nav-link';
	}

	public function actionRegistered(string $ac): void
	{
		try {
			$this->userManager->userRegistered($ac);
			$this->flashMessage('You have been registered. Sign in to play!', 'alert alert-success');
		} catch (Model\DuplicateNameException | Model\NoNameException | Model\GenericSQLException $e) {
			$this->flashMessage($e->getMessage(), 'alert alert-success');
		}
	}
}
