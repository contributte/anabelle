<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown\Macros;

use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;

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
		/**
		 * Everything except for title and sections
		 */
		$heading = $this->findHeading($content);

		$lines = explode("\n", $content);

		[1 => $siteSections, 2 => $methodSections] = $this->findSections($lines);

		/**
		 * Now put allowed lines back together
		 */
		$content = "$heading\n\n";

		foreach ($siteSections as $siteSection) {
			$content .= "$siteSection\n";
		}

		foreach ($methodSections as $methodSection) {
			$content .= "$methodSection\n";
		}
	}


	private function findHeading(string $content): string
	{
		if (preg_match('/^# ?[^#].+/m', $content, $matches)) {
			return $matches[0];
		}

		return '# API Docu';
	}


	private function findSections(array $lines): array
	{
		$return = [1 => [], 2 => []];

		foreach ($lines as $line) {
			if (preg_match('/^(@@?) ?.+[^:]:.+\.md/', $line, $matches)) { // Section
				$return[sizeof($matches[1])][] = $line;
			}
		}

		return $return;
	}
}
