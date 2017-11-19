<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown;

use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;
use Ublaboo\Anabelle\Markdown\Macros\MacroInclude;
use Ublaboo\Anabelle\Markdown\Macros\MacroSection;
use Ublaboo\Anabelle\Parsedown\CustomParsedown;

final class Parser
{

	/**
	 * @var CustomParsedown
	 */
	private $parsedown;

	/**
	 * @var array
	 */
	private $macros = [];


	public function __construct(bool $enableSections)
	{
		$this->parsedown = new CustomParsedown;

		$this->setupMacros($enableSections);
	}


	/**
	 * @throws DocuGeneratorException
	 */
	public function parseFile(string $inputFile, string $outputFile): void
	{
		$content = file_get_contents($inputFile);

		foreach ($this->macros as $macro) {
			$macro->runMacro(dirname($inputFile), dirname($outputFile), $content);
		}

		if (!file_exists(dirname($outputFile))) {
			mkdir(dirname($outputFile), 0777, true);
		}

		file_put_contents($outputFile, $this->parsedown->text($content));
	}


	private function setupMacros(bool $enableSections): void
	{
		$this->macros[] = new MacroInclude;

		if ($enableSections) {
			$this->macros[] = new MacroSection;
		}
	}
}
