<?php declare(strict_types = 1);

namespace Contributte\Anabelle\Generator;

use Contributte\Anabelle\Console\Utils\Logger;
use Contributte\Anabelle\Generator\Exception\DocuFileGeneratorException;
use Contributte\Anabelle\Generator\Exception\DocuGeneratorException;
use Contributte\Anabelle\Http\AuthCredentials;
use Contributte\Anabelle\Markdown\DocuScope;
use Contributte\Anabelle\Markdown\Parser;

final class DocuGenerator
{

	private Parser $parser;

	public function __construct(
		private string $inputDirectory,
		private string $outputDirectory,
		?string $addCss,
		private AuthCredentials $authCredentials,
		Logger $logger
	)
	{
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
