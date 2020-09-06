<?php

declare(strict_types=1);

namespace App\Components;

use Nette\Application\UI;
use Nette\Utils\DateTime;

/**
 * The Fifteen game control
 */
class ServerrowControl extends UI\Control
{

	public function __construct()
	{
	}

	public function render(): void
	{
		$template = $this->template;
		$template->ping1 = rand(10, 100);
		$template->ping2 = rand(10, 100);
		$template->ping3 = rand(10, 100);
		$template->render(__DIR__ . '/../Presenters/templates/components/serverrow.latte');
	}

	public function handleRefresh(): void
	{
		$this->redrawControl();
	}
}
