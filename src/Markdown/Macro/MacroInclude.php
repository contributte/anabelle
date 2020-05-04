<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Markdown\Macro;

use Contributte\Anabelle\Generator\Exception\DocuGeneratorException;

final class MacroInclude implements IMacro
{

	private const INCLUDE_PATTERN = '/^#include (.+\.\w{1,4})/um';


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
			self::INCLUDE_PATTERN,
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
		if (!file_exists(sprintf('%s/%s', $directory, $filename))) {
			throw new DocuGeneratorException(
				sprintf('Can not include non-existing file %s/%s', $directory, $filename)
			);
		}

		$includeContent = file_get_contents("$directory/$filename");

		if ($includeContent === false) {
			throw new DocuGeneratorException(
				sprintf('Can not include non-existing file %s/%s', $directory, $filename)
			);
		}

		$includeContent = preg_replace_callback(
			self::INCLUDE_PATTERN,
			function(array $input) use ($directory): string {
				$dir = $directory . '/' . dirname($input[1]);

				return $this->includeFile($dir, basename($input[1]));
			},
			$includeContent
		);

		if ($includeContent === null) {
			throw new \UnexpectedValueException;
		}

		return $includeContent;
	}
}
