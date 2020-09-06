<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;

class HomepagePresenter extends BasePresenter
{

	public function __construct()
	{
	}


	public function renderDefault(): void
	{
		$this->template->header_height = 'masthead mb-auto';
		$this->template->titlepage = 'Webgame Homepage';
		$this->template->home_attr = 'nav-link active';
		$this->template->play_attr = 'nav-link';
		$this->template->account_attr = 'nav-link';
		$this->template->signin_attr = 'nav-link';
	}
}
