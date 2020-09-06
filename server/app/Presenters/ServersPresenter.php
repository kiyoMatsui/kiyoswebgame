<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components;
use Nette\Utils\DateTime;


class ServersPresenter extends BasePresenter
{
	public function actionDefault(): void
	{
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Signin:');
		}
	}

	public function renderDefault(): void
	{
		$this->template->header_height = 'masthead';
		$this->template->titlepage = 'Webgame Servers';
		$this->template->home_attr = 'nav-link';
		$this->template->play_attr = 'nav-link active';
		$this->template->account_attr = 'nav-link';
		$this->template->signin_attr = 'nav-link';
	}

	protected function createComponentServerrow(): Components\ServerrowControl
	{
		$servers = new Components\ServerrowControl;
		return $servers;
	}
}
