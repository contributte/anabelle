<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Tests\Markdown\Macros;

require_once __DIR__ . '/../../bootstrap.php';

use Tester\Assert;
use Tester\TestCase;
use Ublaboo\Anabelle\Markdown\Macros\MacroCleanIndex;

class MacroCleanIndexTest extends TestCase
{

	public function testHeading(): void
	{
		$content = "\n\n## Nope\n# Test heading\n\n# Foo\n\n";

		$macro = new MacroCleanIndex;
		$macro->runMacro('', '', $content);

		Assert::same("# Test heading\n\n", $content);
	}


	public function testSections(): void
	{
		$content = "# Test heading\n@ home:home.md\nNothing\n@ about:about.md\n@@ method1:method1.md\nFoo\n@@ method1:method1.md\n";

		$macro = new MacroCleanIndex;
		$macro->runMacro('', '', $content);

		Assert::same("# Test heading\n\n@ home:home.md\n@ about:about.md\n@@ method1:method1.md\n@@ method1:method1.md\n", $content);
	}
}

(new MacroCleanIndexTest())->run();
