<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Generator;

use Contributte\Anabelle\Http\AuthCredentials;
use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;

final class Assets
{

	/**
	 * @var string
	 */
	private $layoutFile;

	/**
	 * @var array
	 */
	private $layoutStylesPaths;

	/**
	 * @var array
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
	 * @var AuthCredentials
	 */
	private $authCredentials;


	public function __construct(
		AuthCredentials $authCredentials,
		?string $addCss
	) {
		$this->layoutFile = __DIR__ . '/../../assets/layout.php';

		$this->layoutStylesPaths = [
			__DIR__ . '/../../assets/highlight-json.css',
			__DIR__ . '/../../assets/layout.css',
		];

		if ($addCss !== null) {
			$this->layoutStylesPaths[] = $addCss;
		}

		$this->layoutSriptsPaths = [
			__DIR__ . '/../../assets/highlight-json.min.js',
			__DIR__ . '/../../assets/layout.js',
		];
		$this->layoutFavicon = __DIR__ . '/../../assets/favicon.ico';

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


	public function replaceHttpAuth(string &$template): void // Intentionally &
	{
		$template = str_replace('{httpAuth}', $this->getHttpAuthSnippet(), $template);
	}


	public function getHttpAuthSnippet(): string
	{
		if ($this->authCredentials->getUser() !== null) {
			$u = $this->authCredentials->getUser();
			$p = $this->authCredentials->getPass();

			return "<?php if (!isset(\$_SERVER['PHP_AUTH_USER']) || \$_SERVER['PHP_AUTH_USER'] !== '{$u}' || \$_SERVER['PHP_AUTH_PW'] !== '{$p}') {"
				. " header('WWW-Authenticate: Basic realm=\"API Docu\"');"
				. " header('HTTP/1.0 401 Unauthorized');"
				. " die('Invalid authentication');"
				. '} ?>';
		}

		return '';
	}


	private function saveLayout(string $content, string $outputFile): void
	{
		$template = file_get_contents($this->layoutFile);

		$this->replaceHttpAuth($template);
		$this->replaceTitle($template, $content);

		$content = preg_replace('/^<h1>.*<\/h1>\w*$/mU', '', $content);

		if ($content === null) {
			throw new \UnexpectedValueException;
		}

		$this->replaceContent($template, $content);

		$template = str_replace('{styles}', $this->getLayoutStyles(), $template);
		$template = str_replace('{scripts}', $this->getLayoutSripts(), $template);

		if (is_array($template)) {
			throw new \UnexpectedValueException;
		}

		file_put_contents($outputFile, $this->minifyHtml($template));
	}


	private function saveSection(string $content, string $outputFile): void
	{
		file_put_contents($outputFile, $this->minifyHtml($content));
	}


	private function replaceTitle(string &$template, string $content): void // Intentionally &
	{
		$template = preg_match('/<h1>(.+)<\/h1>/', $content, $matches) === 1
			? str_replace('{title}', $matches[1], $template)
			: str_replace('{title}', 'API Docs', $template);
	}


	private function replaceContent(string &$template, string $content): void // Intentionally &
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
		$return = preg_replace(
			'#(?ix)(?>[^\S ]\s*|\s{2,})(?=(?:(?:[^<]++|<(?!/?(?:textarea|pre)\b))*+)(?:<(?>textarea|pre)\b|\z))#',
			' ',
			$html
		);

		if ($return === null) {
			throw new \UnexpectedValueException;
		}

		return $return;
	}
}
