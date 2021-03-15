<?php declare(strict_types = 1);

namespace Contributte\Anabelle\Markdown;

use Contributte\Anabelle\Console\Utils\Logger;
use Contributte\Anabelle\Generator\Assets;
use Contributte\Anabelle\Generator\Exception\DocuFileGeneratorException;
use Contributte\Anabelle\Generator\Exception\DocuGeneratorException;
use Contributte\Anabelle\Http\AuthCredentials;
use Contributte\Anabelle\Markdown\Macro\IMacro;
use Contributte\Anabelle\Markdown\Macro\MacroBlockVariable;
use Contributte\Anabelle\Markdown\Macro\MacroBlockVariableOutput;
use Contributte\Anabelle\Markdown\Macro\MacroInclude;
use Contributte\Anabelle\Markdown\Macro\MacroIndex;
use Contributte\Anabelle\Markdown\Macro\MacroInlineFileLink;
use Contributte\Anabelle\Markdown\Macro\MacroInlineVariable;
use Contributte\Anabelle\Markdown\Macro\MacroInlineVariableOutput;
use Contributte\Anabelle\Markdown\Macro\MacroSection;
use Contributte\Anabelle\Parsedown\CustomParsedown;

final class Parser
{

	/** @var bool */
	private $isLayout;

	/** @var AuthCredentials */
	private $authCredentials;

	/** @var Logger */
	private $logger;

	/** @var DocuScope */
	private $docuScope;

	/** @var CustomParsedown */
	private $parsedown;

	/** @var IMacro[] */
	private $macros = [];

	/** @var Assets */
	private $assets;

	public function __construct(
		bool $isLayout,
		AuthCredentials $authCredentials,
		Logger $logger,
		DocuScope $docuScope,
		?string $addCss
	)
	{
		$this->isLayout = $isLayout;
		$this->authCredentials = $authCredentials;
		$this->logger = $logger;
		$this->docuScope = $docuScope;

		$this->parsedown = new CustomParsedown();
		$this->assets = new Assets($authCredentials, $addCss);

		$this->setupMacros();
	}


	/**
	 * @throws DocuGeneratorException
	 * @throws DocuFileGeneratorException
	 */
	public function parseFile(string $inputFile, string $outputFile): void
	{
		$this->logger->logProcessingFile($inputFile);

		if (!file_exists($inputFile)) {
			throw new DocuGeneratorException(sprintf('Missing file [%s]', $inputFile));
		}

		$content = file_get_contents($inputFile);

		foreach ($this->macros as $macro) {
			try {
				$macro->runMacro(dirname($inputFile), dirname($outputFile), $content);
			} catch (DocuGeneratorException $e) {
				throw new DocuFileGeneratorException(
					$inputFile,
					$e->getMessage(),
					$e->getCode(),
					$e
				);
			}
		}

		if (!file_exists(dirname($outputFile))) {
			mkdir(dirname($outputFile), 0777, true);
		}

		$this->assets->saveFile(
			$this->parsedown->text($content),
			$outputFile,
			$this->isLayout
		);
	}


	private function setupMacros(): void
	{
		$this->macros[] = new MacroInclude();
		$this->macros[] = new MacroInlineVariable($this->docuScope);
		$this->macros[] = new MacroInlineVariableOutput($this->docuScope);
		$this->macros[] = new MacroInlineFileLink($this->docuScope);
		$this->macros[] = new MacroBlockVariable($this->docuScope);
		$this->macros[] = new MacroBlockVariableOutput($this->docuScope);

		if ($this->isLayout) {
			$this->macros[] = new MacroIndex();
			$this->macros[] = new MacroSection(
				$this->logger,
				$this->authCredentials,
				$this->docuScope
			);
		}
	}

}
