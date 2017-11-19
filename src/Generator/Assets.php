<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Generator;

use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;
use Ublaboo\Anabelle\Markdown\Parser;

final class Assets
{

	/**
	 * @var string
	 */
	private $layoutFile;

	/**
	 * @var string
	 */
	private $layoutStyles;

	/**
	 * @var string
	 */
	private $sectionFile;

	/**
	 * @var string
	 */
	private $sectionStyles;


	public function __construct()
	{
		$this->layoutFile = __DIR__ . '/../assets/layout.php';
		$this->layoutStyles = __DIR__ . '/../assets/layout.css';

		$this->sectionFile = __DIR__ . '/../assets/section.php';
		$this->sectionStyles = __DIR__ . '/../assets/section.css';
	}


	public function saveFile(string $content, string $outputFile, bool $isLayout): void
	{
		if ($isLayout) {
			$this->saveLayout($content, $outputFile);
		} else {
			$this->saveSection($content, $outputFile);
		}
	}


	private function saveLayout(string $content, string $outputFile): void
	{
		$template = file_get_contents($this->layoutFile);

		$this->replaceTitle($template, $content);
		$this->replaceContent($template, $content);

		$template = str_replace('{styles}', file_get_contents($this->layoutStyles), $template);

		file_put_contents($outputFile, $template);
	}


	private function saveSection(string $content, string $outputFile): void
	{
		$template = file_get_contents($this->sectionFile);

		$this->replaceTitle($template, $content);
		$this->replaceContent($template, $content);

		$template = str_replace('{styles}', file_get_contents($this->sectionStyles), $template);

		file_put_contents($outputFile, $template);
	}


	public function replaceTitle(& $template, $content): void // Intentionally &
	{
		if (preg_match('/<h1>(.+)<\/h1>/', $content, $matches)) {
			$template = str_replace('{title}', $matches[1], $template);
		} else {
			$template = str_replace('{title}', 'Docu', $template);
		}
	}


	public function replaceContent(& $template, $content): void // Intentionally &
	{
		$template = str_replace('{content}', $content, $template);
	}
}
