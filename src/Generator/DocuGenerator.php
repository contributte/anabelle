<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Generator;

use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;
use Ublaboo\Anabelle\Markdown\Parser;

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
	 * @var Parser
	 */
	private $parser;


	public function __construct(string $inputDirectory, string $outputDirectory)
	{
		$this->inputDirectory = $inputDirectory;
		$this->outputDirectory = $outputDirectory;

		$this->parser = new Parser(true);
	}


	/**
	 * @throws DocuGeneratorException
	 */
	public function run(): void
	{
		$this->parser->parseFile(
			$this->inputDirectory . '/index.md',
			$this->outputDirectory . '/index.html'
		);
	}
}
