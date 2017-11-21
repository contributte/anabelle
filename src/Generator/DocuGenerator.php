<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Generator;

use Ublaboo\Anabelle\Console\Utils\Logger;
use Ublaboo\Anabelle\Generator\Exception\DocuFileGeneratorException;
use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;
use Ublaboo\Anabelle\Http\AuthCredentials;
use Ublaboo\Anabelle\Markdown\DocuScope;
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
	 * @var AuthCredentials
	 */
	private $authCredentials;

	/**
	 * @var Logger
	 */
	private $logger;

	/**
	 * @var Parser
	 */
	private $parser;


	public function __construct(
		string $inputDirectory,
		string $outputDirectory,
		AuthCredentials $authCredentials,
		Logger $logger
	) {
		$this->inputDirectory = $inputDirectory;
		$this->outputDirectory = $outputDirectory;
		$this->authCredentials = $authCredentials;
		$this->logger = $logger;

		$this->parser = new Parser(true, $authCredentials, $logger, new DocuScope);
	}


	/**
	 * @throws DocuGeneratorException
	 * @throws DocuFileGeneratorException
	 */
	public function run(): void
	{
		$fileType = $this->authCredentials->getUser() ? 'php' : 'html';

		$this->parser->parseFile(
			$this->inputDirectory . '/index.md',
			$this->outputDirectory . "/index.{$fileType}"
		);
	}
}
