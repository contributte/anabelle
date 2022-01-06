<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Markdown\Macro;

use Contributte\Anabelle\Generator\Exception\DocuGeneratorException;
use Contributte\Anabelle\Markdown\DocuScope;
use Contributte\Anabelle\Markdown\Macro\Utils\FileHash;
use Nette\Utils\Html;

final class MacroInlineFileLink implements IMacro
{

	private const LINK_PATTERN = '/\[((?:[^][]++|(?R))*+)\]\(([^\)]*)\)/um';

	/**
	 * @var callable
	 */
	private $fileHashAlgo;


	public function __construct(private DocuScope $docuScope, ?callable $fileHashAlgo = null)
	{
		$this->fileHashAlgo = $fileHashAlgo ?? [FileHash::class, 'md5File'];
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

					if ($targetPath === null) {
						throw new \UnexpectedValueException;
					}

					return (string) Html::el('a')->href('_files/' . basename($targetPath))
						->setText($text)
						->setAttribute('target', '_blank');
				}

				return "[$text]($path)";
			},
			$content
		);
	}
}
