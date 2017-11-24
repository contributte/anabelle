<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown\Macros;

use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;
use Ublaboo\Anabelle\Markdown\DocuScope;

abstract class AbstractMacroVariable
{

	/**
	 * @var DocuScope
	 */
	protected $docuScope;


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
		$this->runVariableMacro($content);
	}


	abstract protected function runVariableMacro(string & $content): void; // Intentionally &
}
