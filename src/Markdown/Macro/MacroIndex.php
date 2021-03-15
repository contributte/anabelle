<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Markdown\Macro;

use Contributte\Anabelle\Generator\Exception\DocuGeneratorException;
use Contributte\Anabelle\Markdown\Macro\Index\Exception\PartDidNotMatchException;
use Contributte\Anabelle\Markdown\Macro\Index\IndexSection;
use Contributte\Anabelle\Markdown\Macro\Index\IndexSubTitle;
use Contributte\Anabelle\Markdown\Macro\Index\IndexTitle;

final class MacroIndex implements IMacro
{

	private const DEFAULT_TITLE = '# API Docs';


	/**
	 * @throws DocuGeneratorException
	 */
	public function runMacro(
		string $inputDirectory,
		string $outputDirectory,
		string & $content // Intentionally &
	): void
	{
		$lines = explode("\n", $content);

		$title = IndexTitle::createFromLine(self::DEFAULT_TITLE);

		foreach ($lines as $line) {
			try {
				$title = IndexTitle::createFromLine($line);

				break;
			} catch (PartDidNotMatchException $e) {
			}
		}

		$sections = [];

		foreach ($lines as $line) {
			try {
				$sections[] = IndexSection::createFromLine($line);
			} catch (PartDidNotMatchException $e) {
			}

			try {
				$sections[] = IndexSubTitle::createFromLine($line);
			} catch (PartDidNotMatchException $e) {
			}
		}

		/**
		 * Now put allowed lines back together
		 */
		$content = $title->getContentString() . PHP_EOL . PHP_EOL;

		foreach ($sections as $section) {
			$content .= $section->getContentString() . PHP_EOL;
		}
	}
}
