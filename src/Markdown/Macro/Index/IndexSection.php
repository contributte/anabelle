<?php declare(strict_types = 1);

namespace Contributte\Anabelle\Markdown\Macro\Index;

final class IndexSection extends AbstractIndexSection
{

	protected static string $pattern = '/^(@@?) ?.+[^:]:.+\.md/';

}
