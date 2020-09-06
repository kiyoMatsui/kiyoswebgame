<?php

declare(strict_types = 1);

namespace App\Presenters;

class GamePresenter extends BasePresenter
{
	public function renderDefault(): void
	{
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Homepage:');
		}
		$this->template->header_height = 'masthead';
		$this->template->titlepage = 'Webgame Game';
		$this->template->home_attr = 'nav-link';
		$this->template->play_attr = 'nav-link active';
		$this->template->account_attr = 'nav-link';
		$this->template->signin_attr = 'nav-link';
	}
}
