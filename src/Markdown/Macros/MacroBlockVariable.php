<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown\Macros;

use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;
use Ublaboo\Anabelle\Markdown\DocuScope;

final class MacroBlockVariable implements IMacro
{

	/**
	 * @var DocuScope
	 */
	private $docuScope;


	public function __construct(DocuScope $docuScope)
	{
		$this->docuScope = $docuScope;
	}


	/**
	 * @throws DocuGeneratorException
	 */
	public function runMacro(
		string $inputDirectory,
		string $outputDirectory,
		string & $content // Intentionally &
	): void
	{
		/**
		 * Remove lines with inline variables definition and put then into DocuScope
		 */
		$content = preg_replace_callback(
			'/^\$\$([a-zA-Z_0-9]+)\n(.+)\$\$$/ms',
			function(array $input): string {
				$this->docuScope->addBlockVariable($input[1], $input[2]);

				return '';
			},
			$content
		);
	}
}
