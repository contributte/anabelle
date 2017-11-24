<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown\Macros;

use Ublaboo\Anabelle\Console\Utils\Logger;
use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;
use Ublaboo\Anabelle\Markdown\Parser;

final class MacroCleanIndex implements IMacro
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
		$heading = 'Docu';
		$siteSections = [];
		$methodSections = [];

		/**
		 * Everything except for title and sections
		 */
		$lines = explode("\n", $content);

		foreach ($lines as $line) {
			if (preg_match('/^(@@?) ?.+[^:]:.+\.md/', $line, $matches)) { // Section
				if (sizeof($matches[1]) == 1) {
					$siteSections[] = $line;
				} else {
					$methodSections[] = $line;
				}
			} elseif(preg_match('/^# ?[^#].+/', $line)) { // Title
				if ($heading !== 'API Docu') { // Take only first heading
					$heading = $line;
				}
			}
		}

		/**
		 * Now put allowed lines back together
		 */
		$content = "$heading\n\n";

		if (!empty($siteSections)) {
			foreach ($siteSections as $siteSection) {
				$content .= "$siteSection\n";
			}
		}

		if (!empty($methodSections)) {
			foreach ($methodSections as $methodSection) {
				$content .= "$methodSection\n";
			}
		}
	}
}
