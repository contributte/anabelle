<?php declare(strict_types = 1);

namespace Contributte\Anabelle\Tests\Markdown\Macro;

require_once __DIR__ . '/../../bootstrap.php';

use Contributte\Anabelle\Markdown\Macro\MacroInclude;
use Tester\Assert;
use Tester\TestCase;

/**
 * @testCase
 */
class MacroIncludeTest extends TestCase
{

	public function testIncludeMdFile()
	{
		$content = file_get_contents(__DIR__ . '/files/input_MacroIncludeTest.md');
		$expected = file_get_contents(__DIR__ . '/files/expected_MacroIncludeTest.md');

		$macro = new MacroInclude();
		$macro->runMacro(__DIR__ . '/files', '', $content);

		Assert::same($expected, $content);
	}

}

(new MacroIncludeTest())->run();
