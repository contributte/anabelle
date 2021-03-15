<?php declare(strict_types = 1);

namespace Contributte\Anabelle\Markdown\Macro\Index;

use Contributte\Anabelle\Markdown\Macro\Index\Exception\PartDidNotMatchException;

abstract class AbstractIndexSection implements IIndexSection
{

	/** @var string */
	protected static $pattern = '/.+/';

	/** @var string */
	private $contentString;

	final private function __construct(string $contentString)
	{
		$this->contentString = $contentString;
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
