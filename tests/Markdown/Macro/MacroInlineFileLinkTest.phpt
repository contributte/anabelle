<?php

declare(strict_types=1);

namespace Contributte\Anabelle\Tests\Markdown\Macro;

require_once __DIR__ . '/../../bootstrap.php';

use Tester\Assert;
use Tester\TestCase;
use Contributte\Anabelle\Markdown\DocuScope;
use Contributte\Anabelle\Markdown\Macro\MacroInlineFileLink;

/**
 * @testCase
 */
class MacroInlineFileLinkTest extends TestCase
{

	public function testFileLink()
	{
		$content = file_get_contents(__DIR__ . '/files/input_MacroInlineFileLinkTest.md');
		$expected = file_get_contents(__DIR__ . '/files/expected_MacroInlineFileLinkTest.md');

		$macro = new MacroInlineFileLink(new DocuScope(__DIR__ . '/files'), function(string $path): string {
			return 'testhash';
		});
		$macro->runMacro(__DIR__ . '/files', __DIR__ . '/files', $content);

		Assert::same($expected, $content);
	}

}

(new MacroInlineFileLinkTest())->run();
