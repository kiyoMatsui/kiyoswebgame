<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components;
use Nette\Application\UI\Form;

class SigninPresenter extends BasePresenter
{
	/** @var Components\SignInFormFactory */
	private $signInFactory;

	/** @var Components\ResetPasswordFormFactory */
	private $resetPasswordFactory;

	public function __construct(
		Components\SignInFormFactory $signInFactory,
		Components\ResetPasswordFormFactory $resetPasswordFactory
	) {
		$this->signInFactory = $signInFactory;
		$this->resetPasswordFactory = $resetPasswordFactory;
	}

	public function renderDefault(): void
	{
		if ($this->getUser()->isLoggedIn()) {
			$this->redirect('Account:');
		}
		$this->template->header_height = 'masthead';
		$this->template->titlepage = 'Webgame Sign in';
		$this->template->home_attr = 'nav-link';
		$this->template->play_attr = 'nav-link';
		$this->template->account_attr = 'nav-link active';
		$this->template->signin_attr = 'nav-link active';
	}

	public function renderForgotpw(): void
	{
		if ($this->getUser()->isLoggedIn()) {
			$this->redirect('Account:');
		}
		$this->template->header_height = 'masthead';
		$this->template->titlepage = 'Webgame Sign in';
		$this->template->home_attr = 'nav-link';
		$this->template->play_attr = 'nav-link';
		$this->template->account_attr = 'nav-link';
		$this->template->signin_attr = 'nav-link';
	}

	/**
	 * Sign in form factory.
	 */
	protected function createComponentSignInForm(): Form
	{
		return $this->signInFactory->create(function (): void {
			$this->redirect('Servers:default');
		});
	}

	protected function createComponentResetPasswordForm(): Form
	{
		return $this->resetPasswordFactory->create(function (): void {
			$this->redirect('Message:resetpasswordconfirmed');
		});
	}
}
