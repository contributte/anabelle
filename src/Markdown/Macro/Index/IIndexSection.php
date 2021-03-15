<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Markdown\Macro\Index;

use Contributte\Anabelle\Markdown\Macro\Index\Exception\PartDidNotMatchException;

interface IIndexSection
{

	/**
	 * @throws PartDidNotMatchException
	 */
	public static function createFromLine(string $line): IIndexSection;


	public function getContentString(): string;
}
