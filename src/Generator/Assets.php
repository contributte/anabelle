<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Generator;

use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;
use Ublaboo\Anabelle\Generator\Exception\DocuGeneratorException;
use Ublaboo\Anabelle\Http\AuthCredentials;
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
	private $layoutStylesPaths;

	/**
	 * @var string
	 */
	private $layoutSriptsPaths;

	/**
	 * @var string|null
	 */
	private $layoutStyles;

	/**
	 * @var string
	 */
	private $layoutFavicon;

	/**
	 * @var string|null
	 */
	private $sectionStyles;

	/**
	 * @var AuthCredentials
	 */
	private $authCredentials;


	public function __construct(AuthCredentials $authCredentials)
	{
		$this->layoutFile = __DIR__ . '/../../assets/layout.php';
		$this->layoutStylesPaths = [
			__DIR__ . '/../../assets/highlight-json.css',
			__DIR__ . '/../../assets/layout.css'
		];
		$this->layoutSriptsPaths = [
			__DIR__ . '/../../assets/highlight-json.min.js',
			__DIR__ . '/../../assets/layout.js'
		];
		$this->layoutFavicon = __DIR__ . '/../../assets/favicon.ico';

		$this->sectionFile = __DIR__ . '/../../assets/section.php';

		$this->authCredentials = $authCredentials;
	}


	public function saveFile(string $content, string $outputFile, bool $isLayout): void
	{
		if ($isLayout) {
			$this->saveLayout($content, $outputFile);
			copy($this->layoutFavicon, dirname($outputFile) . '/favicon.ico');
		} else {
			$this->saveSection($content, $outputFile);
		}
	}


	private function saveLayout(string $content, string $outputFile): void
	{
		$template = file_get_contents($this->layoutFile);

		$this->replaceHttpAuth($template);
		$this->replaceTitle($template, $content);

		$content = preg_replace('/^<h1>.*<\/h1>\w*$/mU', '', $content);
		$this->replaceContent($template, $content);

		$template = str_replace('{styles}', $this->getLayoutStyles(), $template);
		$template = str_replace('{scripts}', $this->getLayoutSripts(), $template);

		file_put_contents($outputFile, $this->minifyHtml($template));
	}


	private function saveSection(string $content, string $outputFile): void
	{
		file_put_contents($outputFile, $this->minifyHtml($content));
	}


	public function replaceHttpAuth(& $template): void // Intentionally &
	{
		$template = str_replace('{httpAuth}', $this->getHttpAuthSnippet(), $template);
	}


	private function replaceTitle(& $template, $content): void // Intentionally &
	{
		if (preg_match('/<h1>(.+)<\/h1>/', $content, $matches)) {
			$template = str_replace('{title}', $matches[1], $template);
		} else {
			$template = str_replace('{title}', 'API Docu', $template);
		}
	}


	private function replaceContent(& $template, $content): void // Intentionally &
	{
		$template = str_replace('{content}', $content, $template);
	}


	private function getLayoutStyles(): string
	{
		if ($this->layoutStyles === null) {
			$minifier = new CSS;

			foreach ($this->layoutStylesPaths as $file) {
				$minifier->add($file);
			}

			$this->layoutStyles = $minifier->minify();
		}

		return $this->layoutStyles;
	}


	private function getLayoutSripts(): string
	{
		$minifier = new JS;

		foreach ($this->layoutSriptsPaths as $file) {
			$minifier->add($file);
		}

		return $minifier->minify();
	}


	private function minifyHtml(string $html): string
	{
		return preg_replace(
			'#(?ix)(?>[^\S ]\s*|\s{2,})(?=(?:(?:[^<]++|<(?!/?(?:textarea|pre)\b))*+)(?:<(?>textarea|pre)\b|\z))#',
			' ',
			$html
		);
	}


	public function getHttpAuthSnippet(): string
	{
		if ($this->authCredentials->getUser()) {
			$u = $this->authCredentials->getUser();
			$p = $this->authCredentials->getPass();

			return "<?php if (!isset(\$_SERVER['PHP_AUTH_USER']) || \$_SERVER['PHP_AUTH_USER'] !== '{$u}' || \$_SERVER['PHP_AUTH_PW'] !== '{$p}') {"
				. "	header('WWW-Authenticate: Basic realm=\"API Docu\"');"
				. "	header('HTTP/1.0 401 Unauthorized');"
				. "	die('Invalid authentication');"
				. '} ?>';
		}

		return '';
	}
}
