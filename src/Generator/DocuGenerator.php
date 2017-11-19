<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Generator;

use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;
use Ublaboo\Anabelle\Markdown\Macros\MacroInclude;
use Ublaboo\Anabelle\Parsedown\CustomParsedown;

final class DocuGenerator
{

	/**
	 * @var string
	 */
	private $inputDirectory;

	/**
	 * @var string
	 */
	private $outputDirectory;

	/**
	 * @var CustomParsedown
	 */
	private $parsedown;

	/**
	 * @var array
	 */
	private $macros = [];


	public function __construct(string $inputDirectory, string $outputDirectory)
	{
		$this->inputDirectory = $inputDirectory;
		$this->outputDirectory = $outputDirectory;

		$this->parsedown = new CustomParsedown;

		$this->setupMacros();
	}


	/**
	 * @throws DocuGeneratorException
	 */
	public function run(): void
	{
		$content = file_get_contents($this->inputDirectory . '/index.md');

		foreach ($this->macros as $macro) {
			$macro->runMacro($content);
		}

		file_put_contents(
			$this->outputDirectory . '/index.html',
			$this->parsedown->text($content)
		);
	}


	private function setupMacros(): void
	{
		$this->macros[] = new MacroInclude($this->inputDirectory);
	}
}
