<?php

declare(strict_types=1);

namespace Ublaboo\Anabelle\Tests\Markdown\Macros;

require_once __DIR__ . '/../../bootstrap.php';

use Tester\Assert;
use Tester\TestCase;
use Ublaboo\Anabelle\Markdown\DocuScope;
use Ublaboo\Anabelle\Markdown\Macros\MacroInlineFileLink;

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
