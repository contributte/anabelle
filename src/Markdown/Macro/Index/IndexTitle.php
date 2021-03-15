<?php declare(strict_types = 1);

namespace Contributte\Anabelle\Markdown\Macro\Index;

final class IndexTitle extends AbstractIndexSection
{

	/** @var string */
	protected static $pattern = '/^# .+/';

}
