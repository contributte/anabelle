<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Markdown\Macro;

use Contributte\Anabelle\Console\Utils\Logger;
use Contributte\Anabelle\Generator\Exception\DocuGeneratorException;
use Contributte\Anabelle\Http\AuthCredentials;
use Contributte\Anabelle\Markdown\DocuScope;
use Contributte\Anabelle\Markdown\Parser;

final class MacroSection implements IMacro
{

	/**
	 * @var Parser
	 */
	private $parser;

	/**
	 * @var AuthCredentials
	 */
	private $authCredentials;


	public function __construct(
		Logger $logger,
		AuthCredentials $authCredentials,
		DocuScope $docuScope
	) {
		$this->authCredentials = $authCredentials;

		$this->parser = new Parser(false, $authCredentials, $logger, $docuScope, null);
	}


	/**
	 * @throws DocuGeneratorException
	 */
	public function runMacro(
		string $inputDirectory,
		string $outputDirectory,
		string &$content // Intentionally &
	): void
	{
		$fileType = $this->authCredentials->getUser() === null
			? 'html'
			: 'php';

		/**
		 * Find "@@" sections and parse their child .md file
		 * 	== normal section with json-rpc methods
		 * 
		 * Find "@" sections and parse their child .md file
		 * 	== home section, aditional description etc
		 */
		$content = preg_replace_callback(
			'/^@@? (.+[^:]):(.+\.md)/m',
			function(array $input) use ($inputDirectory, $outputDirectory, $fileType): string {
				$inputFile = $inputDirectory . '/' . dirname($input[2]) . '/' . basename($input[2]);

				/**
				 * Output file is of type .(php|html)
				 */
				$outputFile = preg_replace('/md$/', $fileType, $inputFile);

				if ($outputFile === null) {
					throw new \UnexpectedValueException;
				}

				/**
				 * Substitute input dir for output dir
				 */
				$outputFile = str_replace($inputDirectory, $outputDirectory, $outputFile);

				$this->parser->parseFile($inputFile, $outputFile);

				$return = preg_replace('/md$/', $fileType, $input[0]);

				if (!is_string($return)) {
					throw new \UnexpectedValueException;
				}

				return $return;
			},
			$content
		);
	}
}
