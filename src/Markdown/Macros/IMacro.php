<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown\Macros;

use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;
use Ublaboo\Anabelle\Markdown\Parser;

interface IMacro
{

	/**
	 * @throws DocuGeneratorException
	 */
	public function runMacro(
		string $inputDirectory,
		string $outputDirectory,
		string & $content // Intentionally &
	): void;
}
