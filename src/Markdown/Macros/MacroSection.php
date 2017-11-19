<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown\Macros;

use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;
use Ublaboo\Anabelle\Markdown\Parser;

final class MacroSection
{

	/**
	 * @var Parser
	 */
	private $parser;


	public function __construct()
	{
		$this->parser = new Parser(false);
	}


	/**
	 * @throws DocuGeneratorException
	 */
	public function runMacro(
		string $inputDirectory,
		string $outputDirectory,
		string & $content
	): void // Intentionally &
	{
		/**
		 * Substitute "#include" macros with actual files
		 */
		$content = preg_replace_callback(
			'/^#section (.+[^:]):(.+\.md)/m',
			function(array $input) use ($inputDirectory, $outputDirectory): string {
				$inputFile = $inputDirectory . '/' . dirname($input[2]) . '/' . basename($input[2]);

				/**
				 * Output file is .html
				 */
				$outputFile = preg_replace('/md$/', 'html', $inputFile);

				/**
				 * Substitute input dir for output dir
				 */
				$outputFile = str_replace($inputDirectory, $outputDirectory, $outputFile);

				$this->parser->parseFile($inputFile, $outputFile);

				return preg_replace('/md$/', 'html', $input[0]);
			},
			$content
		);
	}
}
