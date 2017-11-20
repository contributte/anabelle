<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown;

use Ublaboo\Anabelle\Console\Utils\Logger;
use Ublaboo\Anabelle\Generator\Assets;
use Ublaboo\Anabelle\Generator\Exception\DocuFileGeneratorException;
use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;
use Ublaboo\Anabelle\Markdown\DocuScope;
use Ublaboo\Anabelle\Markdown\Macros\MacroCleanIndex;
use Ublaboo\Anabelle\Markdown\Macros\MacroInclude;
use Ublaboo\Anabelle\Markdown\Macros\MacroInlineVariable;
use Ublaboo\Anabelle\Markdown\Macros\MacroInlineVariableOutput;
use Ublaboo\Anabelle\Markdown\Macros\MacroSection;
use Ublaboo\Anabelle\Parsedown\CustomParsedown;

final class Parser
{

	/**
	 * @var bool
	 */
	private $isLayout;

	/**
	 * @var Logger
	 */
	private $logger;

	/**
	 * @var DocuScope
	 */
	private $docuScope;

	/**
	 * @var CustomParsedown
	 */
	private $parsedown;

	/**
	 * @var IMacro[]
	 */
	private $macros = [];

	/**
	 * @var Assets
	 */
	private $assets;


	public function __construct(bool $isLayout, Logger $logger, DocuScope $docuScope)
	{
		$this->isLayout = $isLayout;
		$this->logger = $logger;
		$this->docuScope = $docuScope;

		$this->parsedown = new CustomParsedown;
		$this->assets = new Assets;

		$this->setupMacros();
	}


	/**
	 * @throws DocuGeneratorException
	 * @throws DocuFileGeneratorException
	 */
	public function parseFile(string $inputFile, string $outputFile): void
	{
		$this->logger->logProcessingFile($inputFile);

		if (!file_exists($inputFile)) {
			throw new DocuGeneratorException("Missing file [$inputFile]");
		}

		$content = file_get_contents($inputFile);

		foreach ($this->macros as $macro) {
			try {
				$macro->runMacro(dirname($inputFile), dirname($outputFile), $content);
			} catch (DocuGeneratorException $e) {
				throw new DocuFileGeneratorException(
					$inputFile,
					$e->getMessage(),
					$e->getCode(),
					$e
				);
			}
		}

		if (!file_exists(dirname($outputFile))) {
			mkdir(dirname($outputFile), 0777, true);
		}

		$this->assets->saveFile(
			$this->parsedown->text($content),
			$outputFile,
			$this->isLayout
		);
	}


	private function setupMacros(): void
	{
		$this->macros[] = new MacroInclude;
		$this->macros[] = new MacroInlineVariable($this->docuScope);
		$this->macros[] = new MacroInlineVariableOutput($this->docuScope);

		if ($this->isLayout) {
			$this->macros[] = new MacroCleanIndex;
			$this->macros[] = new MacroSection($this->logger, $this->docuScope);
		}
	}
}
