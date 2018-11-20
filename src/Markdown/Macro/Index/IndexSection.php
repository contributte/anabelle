<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Markdown\Macro\Index;

final class IndexSection extends AbstractIndexSection
{

	protected static $pattern = '/^(@@?) ?.+[^:]:.+\.md/';
}
