<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown\Macro\Index;

use Ublaboo\Anabelle\Markdown\Macro\Index\Exception\PartDidNotMatchException;

interface IIndexSection
{

	/**
	 * @throws PartDidNotMatchException
	 */
	public static function createFromLine(string $line): IIndexSection;


	public function getContentString(): string;
}
