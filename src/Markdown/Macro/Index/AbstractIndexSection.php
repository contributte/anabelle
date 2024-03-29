<?php declare(strict_types = 1);

namespace Contributte\Anabelle\Markdown\Macro\Index;

use Contributte\Anabelle\Markdown\Macro\Index\Exception\PartDidNotMatchException;

abstract class AbstractIndexSection implements IIndexSection
{

	protected static string $pattern = '/.+/';

	final private function __construct(private string $contentString)
	{
	}

	/**
	 * @throws PartDidNotMatchException
	 */
	public static function createFromLine(string $line): IIndexSection
	{
		$matched = preg_match(static::$pattern, $line, $matches);

		if ($matched !== 1) {
			throw new PartDidNotMatchException();
		}

		return new static($matches[0]);
	}

	public function getContentString(): string
	{
		return $this->contentString;
	}

}
