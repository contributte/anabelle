<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Tests\Markdown\Macros;

require_once __DIR__ . '/../../bootstrap.php';

use Tester\Assert;
use Tester\TestCase;
use Ublaboo\Anabelle\Markdown\Macros\MacroInclude;

/**
 * @testCase
 */
class MacroIncludeTest extends TestCase
{

	public function testIncludeMdFile()
	{
		$content = file_get_contents(__DIR__ . '/input/input.md');
		$expected = file_get_contents(__DIR__ . '/input/expected.md');

		$macro = new MacroInclude();
		$macro->runMacro(__DIR__ . '/input', '', $content);

		Assert::same($expected, $content);
	}

}

(new MacroIncludeTest())->run();
