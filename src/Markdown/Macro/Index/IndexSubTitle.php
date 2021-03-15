<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Markdown\Macro\Index;

final class IndexSubTitle extends AbstractIndexSection
{

	/**
	 * @var string
	 */
	protected static $pattern = '/^(##) .+/';
}
