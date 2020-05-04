<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Generator;

use Contributte\Anabelle\Console\Utils\Logger;
use Contributte\Anabelle\Generator\Exception\DocuFileGeneratorException;
use Contributte\Anabelle\Generator\Exception\DocuGeneratorException;
use Contributte\Anabelle\Http\AuthCredentials;
use Contributte\Anabelle\Markdown\DocuScope;
use Contributte\Anabelle\Markdown\Parser;

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
	 * @var Parser
	 */
	private $parser;


	public function __construct(
		string $inputDirectory,
		string $outputDirectory,
		?string $addCss,
		AuthCredentials $authCredentials,
		Logger $logger
	) {
		$this->inputDirectory = $inputDirectory;
		$this->outputDirectory = $outputDirectory;
		$this->authCredentials = $authCredentials;

		$this->parser = new Parser(
			true,
			$authCredentials,
			$logger,
			new DocuScope($outputDirectory),
			$addCss
		);
	}


	/**
	 * @throws DocuGeneratorException
	 * @throws DocuFileGeneratorException
	 */
	public function run(): void
	{
		$fileType = $this->authCredentials->getUser() === null
			? 'html'
			: 'php';

		$this->parser->parseFile(
			$this->inputDirectory . '/index.md',
			$this->outputDirectory . "/index.{$fileType}"
		);
	}
}
