<?php declare(strict_types = 1);

namespace Contributte\Anabelle\Markdown\Macro\Index;

final class IndexSection extends AbstractIndexSection
{

	/** @var string */
	protected static $pattern = '/^(@@?) ?.+[^:]:.+\.md/';

}
