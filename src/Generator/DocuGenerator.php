<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Generator;

use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;
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


	public function __construct(string $inputDirectory, string $outputDirectory)
	{
		$this->inputDirectory = $inputDirectory;
		$this->outputDirectory = $outputDirectory;

		$this->parsedown = new CustomParsedown;
	}


	/**
	 * @throws DocuGeneratorException
	 */
	public function run(): void
	{
		$content = file_get_contents($this->inputDirectory . '/index.md');

		/**
		 * Substitute "#include" macros with actual files
		 */
		$content = preg_replace_callback('/^#include (.+\.md)/m', function(array $input): string {
			$includePath = "{$this->inputDirectory}/{$input[1]}";

			if (!file_exists($includePath)) {
				throw new DocuGeneratorException("Can not include non-existing file $includePath");
			}

			return file_get_contents($includePath);
		}, $content);

		file_put_contents(
			$this->outputDirectory . '/index.html',
			$this->parsedown->text($content)
		);
	}
}
