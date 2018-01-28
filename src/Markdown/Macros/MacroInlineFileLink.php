<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown\Macros;

use Nette\Utils\Html;
use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;

final class MacroInlineFileLink implements IMacro
{

	const LINK_PATTERN = '/\[((?:[^][]++|(?R))*+)\]\(([^\)]*)\)/um';


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
			self::LINK_PATTERN,
			function(array $input) use ($inputDirectory, $outputDirectory): string {
				$text = $input[1];
				$path = trim($input[2], '/');

				if (file_exists($inputDirectory . '/' . $path)) {
					$targetPath = str_replace(['../', './'], '', $outputDirectory . '/' . $path);

					if (!file_exists(dirname($targetPath))) {
						mkdir(dirname($targetPath), 0755, true);
					}

					copy($inputDirectory . '/' . $path, $targetPath);

					$targetPath = preg_replace('~^[^/]+/~', '', $targetPath);

					return (string) Html::el('a')->href($targetPath)
						->setText($text)
						->target('_blank');
				}

				return "[$text]($path)";
			},
			$content
		);
	}
}
