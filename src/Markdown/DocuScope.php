<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Markdown;

use Contributte\Anabelle\Generator\Exception\DocuGeneratorException;

final class DocuScope
{

	/**
	 * @var string[]
	 */
	private $inlineVariables = [];

	/**
	 * @var string[]
	 */
	private $blockVariables = [];


	public function __construct(private string $outputDirectory) {}


	public function getOutputDirectory(): string
	{
		return $this->outputDirectory;
	}


	/**
	 * @throws DocuGeneratorException
	 */
	public function addInlineVariable(string $varName, string $value): void
	{
		if (isset($this->inlineVariables[$varName])) {
			throw new DocuGeneratorException(
				"You have already defined inline variable [\$$varName]"
			);
		}

		$this->inlineVariables[$varName] = $value;
	}


	/**
	 * @throws DocuGeneratorException
	 */
	public function getInlineVariable(string $varName): string
	{
		if (!isset($this->inlineVariables[$varName])) {
			throw new DocuGeneratorException(
				"Undefined inline variable [\$$varName]"
			);
		}

		return $this->inlineVariables[$varName];
	}


	/**
	 * @throws DocuGeneratorException
	 */
	public function addBlockVariable(string $varName, string $value): void
	{
		if (isset($this->blockVariables[$varName])) {
			throw new DocuGeneratorException(
				"You have already defined block variable [\$$varName]"
			);
		}

		$this->blockVariables[$varName] = $value;
	}


	/**
	 * @throws DocuGeneratorException
	 */
	public function getBlockVariable(string $varName): string
	{
		if (!isset($this->blockVariables[$varName])) {
			throw new DocuGeneratorException(
				"Undefined block variable [\$$varName]"
			);
		}

		return $this->blockVariables[$varName];
	}
}
