<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown\Macros;

use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;
use Ublaboo\Anabelle\Parsedown\CustomParsedown;

final class MacroInclude
{

	/**
	 * @var string
	 */
	private $inputDirectory;


	public function __construct(string $inputDirectory)
	{
		$this->inputDirectory = $inputDirectory;
	}


	/**
	 * @throws DocuGeneratorException
	 */
	public function runMacro(string & $content): void // Intentionally &
	{
		/**
		 * Substitute "#include" macros with actual files
		 */
		$content = preg_replace_callback(
			'/^#include (.+\.md)/m',
			function(array $input): string {
				$dir = $this->inputDirectory . '/' . dirname($input[1]);

				return $this->includeFile($dir, basename($input[1]));
			},
			$content
		);
	}


	/**
	 * @throws DocuGeneratorException
	 */
	public function includeFile(string $directory, string $filename): string
	{
		if (!file_exists("$directory/$filename")) {
			throw new DocuGeneratorException("Can not include non-existing file $includePath");
		}

		$includeContent = file_get_contents("$directory/$filename");

		$includeContent = preg_replace_callback(
			'/^#include (.+\.md)/m',
			function(array $input) use ($directory): string {
				$dir = $directory . '/' . dirname($input[1]);

				return $this->includeFile($dir, basename($input[1]));
			},
			$includeContent
		);

		return $includeContent;
	}
}
