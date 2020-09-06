<?php

declare(strict_types = 1);

namespace App\Presenters;

use App\Components;
use Nette\Application\UI\Form;
use Nette\Utils\Html;
use Nette\Utils\Strings;

class AccountPresenter extends BasePresenter
{

	/** @var Components\ChangepasswordFormFactory  */
	private $passwordFactory;

	/** @var Components\ChangeemailFormFactory  */
	private $emailFactory;

	/** @var Components\UsernameFormFactory  */
	private $usernameFactory;

	public function __construct(Components\ChangepasswordFormFactory $passwordFactory, 
	Components\ChangeemailFormFactory $emailFactory, Components\UsernameFormFactory $usernameFactory )
	{
		$this->passwordFactory = $passwordFactory;
		$this->emailFactory = $emailFactory;
		$this->usernameFactory = $usernameFactory;
	}

	public function renderDefault(): void
	{
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Homepage:');
		}
		$this->template->header_height = 'masthead';
		$this->template->titlepage = 'Webgame Account';
		$this->template->home_attr = 'nav-link';
		$this->template->play_attr = 'nav-link';
		$this->template->account_attr = 'nav-link active';
		$this->template->signin_attr = 'nav-link active';
	}

	public function renderChangepassword(): void
	{
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Homepage:');
		}
		$this->template->header_height = 'masthead';
		$this->template->titlepage = 'Webgame Account';
		$this->template->home_attr = 'nav-link';
		$this->template->play_attr = 'nav-link';
		$this->template->account_attr = 'nav-link active';
		$this->template->signin_attr = 'nav-link active';
	}

	public function renderChangeemail(): void
	{
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Homepage:');
		}
		$this->template->header_height = 'masthead';
		$this->template->titlepage = 'Webgame Account';
		$this->template->home_attr = 'nav-link';
		$this->template->play_attr = 'nav-link';
		$this->template->account_attr = 'nav-link active';
		$this->template->signin_attr = 'nav-link active';
	}
 
	protected function createComponentChangepasswordForm(): Form
	{
		return $this->passwordFactory->create(function (): void {
			$this->redirect('Account:');
		});
	}

	protected function createComponentChangeemailForm(): Form
	{
		return $this->emailFactory->create(function (): void {
			$this->redirect('Account:');
		});
	}

	protected function createComponentUsernameForm(): Form
	{
		return $this->usernameFactory->create(function (): void {
			$this->redirect('Account:');
		});
	}

	public function handleOut(): void
	{
		$_SESSION['username']= '';
		$_SESSION['accountid']= '';
		$this->getUser()->logout();
		$this->redirect('Message:');
	}

}
