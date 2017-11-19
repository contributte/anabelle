<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown\Macros;

use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;

final class MacroInclude implements IMacro
{

	/**
	 * @throws DocuGeneratorException
	 */
	public function runMacro(
		string $inputDirectory,
		string $outputDirectory,
		string & $content // Intentionally &
	): void
	{
		/**
		 * Substitute "#include" macros with actual files
		 */
		$content = preg_replace_callback(
			'/^#include (.+\.md)/m',
			function(array $input) use ($inputDirectory): string {
				$dir = $inputDirectory . '/' . dirname($input[1]);

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
