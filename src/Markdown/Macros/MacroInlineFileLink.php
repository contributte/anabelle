<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown\Macros;

use Nette\Utils\Html;
use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;
use Ublaboo\Anabelle\Markdown\DocuScope;
use Ublaboo\Anabelle\Markdown\Macros\Utils\FileHash;

final class MacroInlineFileLink implements IMacro
{

	const LINK_PATTERN = '/\[((?:[^][]++|(?R))*+)\]\(([^\)]*)\)/um';

	/**
	 * @var DocuScope
	 */
	private $docuScope;

	/**
	 * @var callable
	 */
	private $fileHashAlgo;


	public function __construct(DocuScope $docuScope, callable $fileHashAlgo = null)
	{
		$this->docuScope = $docuScope;
		$this->fileHashAlgo = $fileHashAlgo ?: [FileHash::class, 'md5File'];
	}


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
			function(array $input) use ($inputDirectory): string {
				$text = $input[1];
				$path = trim($input[2], '/');

				if (file_exists($inputDirectory . '/' . $path)) {
					$fileHash = call_user_func($this->fileHashAlgo, $inputDirectory . '/' . $path);
					$fileName = $fileHash . '.' . pathinfo($path, PATHINFO_EXTENSION);

					$targetPath = $this->docuScope->getOutputDirectory() . '/_files' . '/' . $fileName;

					if (!file_exists(dirname($targetPath))) {
						mkdir(dirname($targetPath), 0755, true);
					}

					copy($inputDirectory . '/' . $path, $targetPath);

					$targetPath = preg_replace('~^[^/]+/~', '', $targetPath);

					return (string) Html::el('a')->href('_files/' . basename($targetPath))
						->setText($text)
						->target('_blank');
				}

				return "[$text]($path)";
			},
			$content
		);
	}
}
