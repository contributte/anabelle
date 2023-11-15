<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Tests\Cases\Markdown\Macro;

use Contributte\Anabelle\Markdown\Macro\MacroIndex;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
class MacroIndexTest extends TestCase
{

	public function testTitle(): void
	{
		$content = <<<CONTENT
### nope
# Test title

# Foo

CONTENT;

		$macro = new MacroIndex;
		$macro->runMacro('', '', $content);

		Assert::same("# Test title\n\n", $content);
	}


	public function testSections(): void
	{
		$content = <<<CONTENT
# Test title
@ home:home.md
@ about:about.md

Nothing

## SubSitle

@@ method1:method1.md

Foo

@@ method1:method1.md
CONTENT;

		$macro = new MacroIndex;
		$macro->runMacro('', '', $content);

		Assert::same("# Test title\n\n@ home:home.md\n@ about:about.md\n## SubSitle\n@@ method1:method1.md\n@@ method1:method1.md\n", $content);
	}
}

(new MacroIndexTest())->run();
