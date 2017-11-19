<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Generator;

use Ublaboo\Anabelle\Console\Utils\Logger;
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
	 * @var Logger
	 */
	private $logger;

	/**
	 * @var Parser
	 */
	private $parser;


	public function __construct(string $inputDirectory, string $outputDirectory, Logger $logger)
	{
		$this->inputDirectory = $inputDirectory;
		$this->outputDirectory = $outputDirectory;
		$this->logger = $logger;

		$this->parser = new Parser(true, $logger);
	}


	/**
	 * @throws DocuGeneratorException
	 */
	public function run(): void
	{
		$this->parser->parseFile(
			$this->inputDirectory . '/index.md',
			$this->outputDirectory . '/index.php',
			true
		);
	}
}
