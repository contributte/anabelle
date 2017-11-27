<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Tests\Markdown\Macros;

require_once __DIR__ . '/../../bootstrap.php';

use Mockery;
use Symfony\Component\Console\Output\OutputInterface;
use Tester\Assert;
use Tester\TestCase;
use Ublaboo\Anabelle\Console\Utils\Logger;
use Ublaboo\Anabelle\Http\AuthCredentials;
use Ublaboo\Anabelle\Markdown\DocuScope;
use Ublaboo\Anabelle\Markdown\Macros\MacroSection;

/**
 * @testCase
 */
class MacroSectionTest extends TestCase
{

	public function testMacro(): void
	{
		$content = "
# Awesome cookbook JSON-RPC API doc

#include variables.md

@ Home:home.md
@ About project:about.md

@@ user.login:methods/user.login.md
@@ user.logout:methods/user.logout.md

@ Foo:foo.md
@ Foo:foo.txt
@ Foobar.txt

";

		$expectedOutput = "
# Awesome cookbook JSON-RPC API doc

#include variables.md

@ Home:home.html
@ About project:about.html

@@ user.login:methods/user.login.html
@@ user.logout:methods/user.logout.html

@ Foo:foo.html
@ Foo:foo.txt
@ Foobar.txt

";

		$logger = Mockery::mock(OutputInterface::class);

		$logger->shouldReceive('writeln');

		$macro = new MacroSection(
			new Logger($logger),
			new AuthCredentials(null, null),
			new DocuScope
		);
		$macro->runMacro(TEST_DOCU_DIR, TEST_DOCU_DIR . '/../testOutputDir', $content);

		Assert::same($expectedOutput, $content);
	}
}

(new MacroSectionTest())->run();
