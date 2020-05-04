<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Tests\Markdown\Macro;

require_once __DIR__ . '/../../bootstrap.php';

use Mockery;
use Symfony\Component\Console\Output\OutputInterface;
use Tester\Assert;
use Tester\TestCase;
use Contributte\Anabelle\Console\Utils\Logger;
use Contributte\Anabelle\Http\AuthCredentials;
use Contributte\Anabelle\Markdown\DocuScope;
use Contributte\Anabelle\Markdown\Macro\MacroSection;

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

		$docuScope = new DocuScope(TEST_DOCU_DIR);

		$macro = new MacroSection(
			new Logger($logger),
			new AuthCredentials(null, null),
			$docuScope
		);
		$macro->runMacro(TEST_DOCU_DIR, TEST_DOCU_DIR . '/../testOutputDir', $content);

		Assert::same(TEST_DOCU_DIR, $docuScope->getOutputDirectory());

		Assert::same($expectedOutput, $content);
	}
}

(new MacroSectionTest())->run();
