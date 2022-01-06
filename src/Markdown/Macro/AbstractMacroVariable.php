<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Markdown\Macro;

use Contributte\Anabelle\Generator\Exception\DocuGeneratorException;
use Contributte\Anabelle\Markdown\DocuScope;

abstract class AbstractMacroVariable
{

	protected const MAX_EXECUTE_DEPTH = 3;


	public function __construct(protected DocuScope $docuScope) {}


	/**
	 * @throws DocuGeneratorException
	 */
	public function runMacro(
		string $inputDirectory,
		string $outputDirectory,
		string &$content // Intentionally &
	): void
	{
		$this->runVariableMacro($content, 1);
	}


	abstract protected function runVariableMacro(
		string &$content,
		int $depth
	): void; // Intentionally &


	/**
	 * @return AbstractMacroVariable[]
	 */
	protected function getMacrosToRunOnBlockVariables(): array
	{
		return [
			new MacroInlineVariable($this->docuScope),
			new MacroInlineVariableOutput($this->docuScope),
			new MacroBlockVariable($this->docuScope),
			new MacroBlockVariableOutput($this->docuScope),
		];
	}


	/**
	 * @return AbstractMacroVariable[]
	 */
	protected function getMacrosToRunOnInlineVariables(): array
	{
		return [
			new MacroInlineVariable($this->docuScope),
			new MacroInlineVariableOutput($this->docuScope),
		];
	}
}
